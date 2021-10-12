<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\TransferFormValidate;

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

        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be at least 1000MMK.'])->withInput();
        }

        // Do not transfer money to no ph number data in database
        $to_check = User::where('phone', request('to_phone'))->first();
        if (!$to_check) {
            return back()->withErrors(['to_phone' => 'Phone number is invalid!'])->withInput();
        }

        $authUser = Auth::guard('web')->user();
        $to_phone = request('to_phone');
        $amount = request('amount');
        $description = request('description');
        return view('frontend.transfer_confirm', compact('authUser', 'to_phone', 'amount', 'description'));
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
}
