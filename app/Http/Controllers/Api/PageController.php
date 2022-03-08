<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\Response;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GrahamCampbell\ResultType\Success;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Requests\TransferFormValidate;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    public function index() 
    {
        return 'testing';
    }


    public function profile()
    {
        $user = auth()->user();

        $data = new ProfileResource($user);
        $res = new Response('success', $data);
        return $res->success();
    }

    public function transaction(Request $request)
    {
        $user = auth()->user();

        $transactions = Transaction::with('user', 'source')->where('user_id', $user->id)->orderBy('created_at', 'DESC');

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        $transactions = $transactions->paginate(5);

        $data = TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }

    public function transactionDetail($trx_id)
    {
        $authUser = auth()->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $authUser->id)->where('trx_id', $trx_id)->first();

        $data = new TransactionDetailResource($transaction);
        $res = new Response('success', $data);
        return $res->success();
    }

    public function notification()
    {
        $authUser = auth()->user();
        $notifications = $authUser->notifications()->paginate(5);

        $data = NotificationResource::collection($notifications)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }

    public function notificationDetail($id)
    {
        $authUser = auth()->user();
        $notification = $authUser->notifications()->where('id', $id)->first();
        $notification->markAsRead();

        $data = new NotificationDetailResource($notification);
        $res = new Response('success', $data);
        return $res->success();
    }

    public function toAccountVerify(Request $request)
    {
        $authUser = auth()->user();

        if ($authUser->phone != request('phone')) {
            $user = User::where('phone', request('phone'))->first();
            if ($user) {
                $data = new Response('success', ['name' => $user->name, 'phone' => $user->phone]);
                return $data->success();
            }
        }

        $data1 = new Response('fail', null);
        return $data1->fail();
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        $authUser = auth()->user();
        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if ($request->amount < 1000) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount must be at least 1000MMK.');
        }

        // if transfer same phone, return to error
        $authUser = auth()->user();
        if ($authUser->phone == request('to_phone')) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // my amount is greater than request->amount
        if (!$from_account->wallet || !$to_account->wallet) {
            $data1 = new Response('fail', null);
            return $data1->fail('Something went wrong!');
        }

        if ($from_account->wallet->amount < $amount) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount is insufficient.');
        }

        $data = new Response('success', [
            'from_account_name' => $from_account->name,
            'from_account_phone' => $from_account->phone,
            'to_account_name' => $to_account->name,
            'to_account_phone' => $to_account->phone,
            'amount' => $amount,
            'description' => $description,
        ]);

        return $data->success();
    }

    public function transferComplete(TransferFormValidate $request)
    {
        if (!$request->password) {
            $data1 = new Response('fail', 'Please fill your password!');
            return $data1->fail();
        }


        $authUser = auth()->user();
        if (!Hash::check($request->password, $authUser->password)) {
            $data1 = new Response('fail', 'Your password is incorrect!');
            return $data1->fail();
        }


        if ($request->amount < 1000) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount must be at least 1000MMK.');
        }

        // if transfer same phone, return to error
        $authUser = auth()->user();
        if ($authUser->phone == request('to_phone')) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }


        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if (!$from_account->wallet || !$to_account->wallet) {
            $data1 = new Response('fail', null);
            return $data1->fail('Something went wrong!');
        }

        if ($from_account->wallet->amount < $amount) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount is insufficient.');
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            // From noti
            $title = 'Transfer money!';
            $message = 'Your wallet transfered ' . number_format($amount, 2) . ' MMK to ' . $to_account->name . ' (' . $to_account->phone . ').';
            $sourceable_id = $from_account->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction_detail', $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'trx_id' => $from_account_transaction->trx_id,
                ],
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            // To noti
            $title = 'Received money!';
            $message = 'Your wallet received ' . number_format($amount, 2) . ' MMK from ' . $from_account->name . ' (' . $from_account->phone . ').';
            $sourceable_id = $to_account->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction_detail', $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'profile',
                'parameter' => [
                    'trx_id' => $to_account_transaction->trx_id,
                ],
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();
            $data = new Response('success', ['trx_id' => $from_account_transaction->trx_id]);
            return $data->success('Successfully transfered!');
        } catch (\Exception $e) {
            DB::rollBack();
            $data1 = new Response('fail', null);
            return $data1->fail('Error Again!');
        }
    }

    public function scanAndPayForm(Request $request)
    {
        $from_account = auth()->user();

        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            $data1 = new Response('fail', null);
            return $data1->fail('QR is invalid!');
        }

        $data = new Response('success', [
            'from_account_name' => $from_account->name,
            'from_account_phone' => $from_account->phone,
            'to_accont_name' => $to_account->name,
            'to_account_phone' => $to_account->phone,
        ]);
        return $data->success();
    }

    public function scanAndPayConfirm(TransferFormValidate $request)
    {
        $authUser = auth()->user();
        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if ($request->amount < 1000) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount must be at least 1000MMK.');
        }

        // if transfer same phone, return to error
        $authUser = auth()->user();
        if ($authUser->phone == request('to_phone')) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // my amount is greater than request->amount
        if (!$from_account->wallet || !$to_account->wallet) {
            $data1 = new Response('fail', null);
            return $data1->fail('Something went wrong!');
        }

        if ($from_account->wallet->amount < $amount) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount is insufficient.');
        }

        $data = new Response('success', [
            'from_account_name' => $from_account->name,
            'from_account_phone' => $from_account->phone,
            'to_account_name' => $to_account->name,
            'to_account_phone' => $to_account->phone,
            'amount' => $amount,
            'description' => $description,
        ]);
        return $data->success();
    }

    public function scanAndPayComplete(TransferFormValidate $request)
    {
        if (!$request->password) {
            $data1 = new Response('fail', 'Please fill your password!');
            return $data1->fail();
        }


        $authUser = auth()->user();
        if (!Hash::check($request->password, $authUser->password)) {
            $data1 = new Response('fail', 'Your password is incorrect!');
            return $data1->fail();
        }


        if ($request->amount < 1000) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount must be at least 1000MMK.');
        }

        // if transfer same phone, return to error
        $authUser = auth()->user();
        if ($authUser->phone == request('to_phone')) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            $data1 = new Response('fail', null);
            return $data1->fail('Phone number is invalid!');
        }


        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if (!$from_account->wallet || !$to_account->wallet) {
            $data1 = new Response('fail', null);
            return $data1->fail('Something went wrong!');
        }

        if ($from_account->wallet->amount < $amount) {
            $data1 = new Response('fail', null);
            return $data1->fail('The amount is insufficient.');
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            // From noti
            $title = 'Transfer money!';
            $message = 'Your wallet transfered ' . number_format($amount, 2) . ' MMK to ' . $to_account->name . ' (' . $to_account->phone . ').';
            $sourceable_id = $from_account->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction_detail', $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'trx_id' => $from_account_transaction->trx_id,
                ],
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            // To noti
            $title = 'Received money!';
            $message = 'Your wallet received ' . number_format($amount, 2) . ' MMK from ' . $from_account->name . ' (' . $from_account->phone . ').';
            $sourceable_id = $to_account->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction_detail', $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'profile',
                'parameter' => [
                    'trx_id' => $to_account_transaction->trx_id,
                ],
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();
            $data = new Response('success', ['trx_id' => $from_account_transaction->trx_id]);
            return $data->success('Successfully transfered!');
        } catch (\Exception $e) {
            DB::rollBack();
            $data1 = new Response('fail', null);
            return $data1->fail('Error Again!');
        }
    }
}
