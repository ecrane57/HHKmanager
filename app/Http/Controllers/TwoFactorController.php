<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

class TwoFactorController extends Controller
{
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            '2fa' => 'required',
        ]);

        if($request->input('2fa') == Auth::user()->token_2fa){            
            $user = Auth::user();
            $user->token_2fa_expiry = \Carbon\Carbon::now()->addMinutes(config('session.lifetime'));
            $user->save();       
            return redirect('/sites/live');
        } else {
            Session::flash('error', "Incorrect Code");
            return redirect()->back();
        }
    }
    
    
    public function showTwoFactorForm()
    {
        return view('auth.2fa');
    }
}
