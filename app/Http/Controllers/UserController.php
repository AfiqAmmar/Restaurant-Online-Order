<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('account.edit', [
            'user' => $user,
        ]);
    }

    public function editUser(Request $request)
    {
        $user = Auth::user();
        if($request->password == null){
            // validate credentials
            $validatedData = $request->validate([
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:1000'],
                'phone' => ['required', 'string', 'max:255'],
                'gender' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id),],
            ]);

            $user->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'address' => $request->address,
                'phone_number' => $request->phone,
                'gender' => $request->gender,
                'email' => $request->email,
            ]);
        }
        else{
            // validate credentials
            $validatedData = $request->validate([
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:1000'],
                'phone' => ['required', 'string', 'max:255'],
                'gender' => ['required'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'address' => $request->address,
                'phone_number' => $request->phone,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        }
        
        Session::flash('success','Account updated successfully');
        return response()->json(['success'=>'Account updated successfully']);
    }
}
