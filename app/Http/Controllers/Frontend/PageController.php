<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Hashids\Hashids;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TransferFormValidate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PageController extends Controller
{
    public function home()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.home', compact('user'));
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.profile', compact('user'));
    }

    public function updatePassword()
    {
        return view('frontend.update_password');
    }

    public function updatePasswordStore(UpdatePassword $request)
    {
        $old_password = request('old_password');
        $new_password = request('new_password');
        $user = Auth::guard('web')->user();

        if (Hash::check($old_password, $user->password)) {
            // The passwords match...
            $user->password = Hash::make($new_password);
            $user->update();

            return redirect()->route('profile')->with('update', 'Successfully password updated!');
        }

        return back()->withErrors(['old_password' => 'The old password is not incorrect'])->withInput();
    }

    // Wallet
    public function wallet()
    {
        $authUser = Auth::guard('web')->user();
        return view('frontend.wallet', compact('authUser'));
    }

    // Transfer
    public function transfer()
    {
        $authUser = Auth::guard('web')->user();
        return view('frontend.transfer', compact('authUser'));
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        $authUser = Auth::guard('web')->user();
        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be at least 1000MMK.'])->withInput();
        }

        // if transfer same phone, return to error
        $authUser = Auth::guard('web')->user();
        if ($authUser->phone == request('to_phone')) {
            return back()->withErrors(['to_phone' => 'Phone number is invalid!'])->withInput();
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'Phone number is invalid!'])->withInput();
        }

        // my amount is greater than request->amount
        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['transfer_message' => 'Something went wrong!'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is insufficient.'])->withInput();
        }

        return view('frontend.transfer_confirm', compact('from_account', 'to_account', 'to_phone', 'amount', 'description'));
    }

    public function transferComplete(TransferFormValidate $request)
    {
        // return $request->all();
        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be at least 1000MMK.'])->withInput();
        }

        // if transfer same phone, return to error
        $authUser = Auth::guard('web')->user();
        if ($authUser->phone == request('to_phone')) {
            return back()->withErrors(['to_phone' => 'Phone number is invalid!'])->withInput();
        }

        // Do not transfer money to no ph number data in database
        $to_account = User::where('phone', request('to_phone'))->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'Phone number is invalid!'])->withInput();
        }


        $from_account = $authUser;
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');

        if (!$from_account->wallet || !$to_account->wallet) {
            return back()->withErrors(['transfer_message' => 'Something went wrong!'])->withInput();
        }

        if ($from_account->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'The amount is insufficient.'])->withInput();
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

            DB::commit();
            return redirect()->route('transaction_detail', $from_account_transaction->trx_id)->with('transfer_success', 'Successfully transfered!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    // Transaction
    public function transaction(Request $request)
    {
        $authUser = Auth::guard('web')->user();
        // eagerloading -> with('users', 'soucrce') => database relationship user and transaction
        $transactions = Transaction::with('user', 'source')->where('user_id', $authUser->id)->orderBy('created_at', 'DESC');

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        $transactions = $transactions->paginate(3);
        return view('frontend.transaction', compact('transactions'));
    }

    public function transactionDetail($trx_id)
    {
        $authUser = Auth::guard('web')->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $authUser->id)->where('trx_id', $trx_id)->first();
        // $transaction = Transaction::findOrFail($trx_id);
        return view('frontend.transaction_detail', compact('transaction'));
    }

    public function toAccountVerify(Request $request)
    {
        $authUser = Auth::guard('web')->user();

        // check no transfer money from my phone number
        if ($authUser->phone != request('phone')) {
            $user = User::where('phone', request('phone'))->first();

            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $user,
                ]);
            }
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid data',
        ]);
    }

    public function passwordCheck(Request $request)
    {
        $authUser = Auth::guard('web')->user();

        if (Hash::check($request->password, $authUser->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Your password is correct',
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Your password is incorrect!',
        ]);
    }

    public function receiveQR()
    {
        $authUser = auth()->guard('web')->user();
        return view('frontend.receive_qr', compact('authUser'));
    }

    public function scanAndPay()
    {
        return view('frontend.scan_and_pay');
    }
}
