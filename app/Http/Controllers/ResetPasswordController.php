<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller{

    public function forgot(Request $request){
        $exist = $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        if($exist){

            $token = Str::random(64);
            $insert = DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            if($insert){
                Mail::send('Email.passwordReset', ['token'=> $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Reset your password');
                });

                return response()->json([
                    'success' => 'we have emailed you with reset password link'
                ]);
            }
        }else{
            return response()->json([
                'Error' => 'Your email does not exist'
            ]);
        }
    }
    public function reset($token, Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        $validateToken = DB::table('password_reset_tokens')->where([
            'token' => $token,
            'email' => $request->email
        ])->first();

        if(!$validateToken){
            return response()->json([
                'Error' => 'Invalid Token of reset re reset your password'
            ]);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        if($user){
            DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
            return response()->json([
                'Success' => 'password updated successfully'
            ]);
        }
    }
}

