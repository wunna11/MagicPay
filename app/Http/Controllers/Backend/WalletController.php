<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }

    public function ssd()
    {
        $data = Wallet::with('user');           //user = database relationship with wallet and user, egaloaing

        return DataTables::of($data)
            ->addColumn('account_person', function ($each) {
                $user = $each->user;
                if ($user) {
                    return '<p>Name: ' . $user->name . ' </p>
                            <p>Email: ' . $user->email . ' </p>
                            <p>Phone: ' . $user->phone . ' </p>';
                }

                return '-';
            })
            ->editColumn('amount', function ($each) {
                return number_format($each->amount, 2);             //number_format($each->amount, 2) 2 = decimal 2 points
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
            })
            ->rawColumns(['account_person'])
            ->make(true);
    }

    public function addAmount()
    {
        // $users = DB::table('users')->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('backend.wallet.add_amount', compact('users'));
    }

    public function addAmountStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            DB::commit();
            return redirect()->route('admin.wallet.index')->with('create', 'Successfully added amount!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function reduceAmount()
    {
        // $users = DB::table('users')->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('backend.wallet.reduce_amount', compact('users'));
    }

    public function reduceAmountStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;
            if ($to_account_wallet->amount < $request->amount) {
                throw new Exception("The amount is greater than the wallet's balance");
            }
            $to_account_wallet->decrement('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 2;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            DB::commit();
            return redirect()->route('admin.wallet.index')->with('create', 'Successfully reduced amount!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
