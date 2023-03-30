<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(){
        $user=User::find(Auth::id());
        return response()->json($user);
    }
    public function editProfile(Request $req)
    {
        $user=User::find(Auth::id());
        if(!Hash::check($req->password,$user->password)){
            return response()->json(['message' => 'incorrect password'], 404);
        }
        $user->name=$req->name;
        $user->email=$req->email;
        $user->password= Hash::make($req->newPassword);
        $user->save();
        return response()->json([
            'status' => true,
            'message' => "Profile Updated successfully!",
            'article' => $user
        ], 200);
    }
}
