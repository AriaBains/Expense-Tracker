<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function createUser(Request $request) {
        $inputFields = $request->validate([
            "name" => ['bail', 'required', 'min:4', 'regex:/^[a-z ]+$/i', 'max:30'],
            "email" => ['bail', 'required', 'email', 'unique:users'],
            "currency" => ['required', Rule::in(array_keys(config('currencies')))],
            "password" => ['bail', 'required', 'min:3', 'max:25', 'confirmed:password_confirmation']
        ], [
            "name.required" => "Name is Required",
            "name.min" => "Name should be 4 or more digits",
            "name.regex" => "Name can only contain letters",
            "name.max" => "Name length should be between 4 and 30",
            "email.required" => "Email is Required",
            "email.unique" => "This email is already registered",
            "email.email" => "Enter a Valid email",
            "currency.required" => "Select a Currency",
            "currency.regex" => "Choose a valid currency",
            "password.required" => "Password is Required",
            "password.min" => "Password must be between 3 and 25 characters long",
            "password.max" => "Password must be between 3 and 25 characters long",
            "password.confirmed" => "Passowords didn't match"
        ]);

        $inputFields['password'] = bcrypt($inputFields['password']);
        $user = User::create($inputFields);
        
        return redirect('/login');
    }

    public function loginUser(Request $request) {
        $credentials = $request->validate([
            "email" => ['required', 'email'],
            "password" => ['required']
        ], [
            "email.required" => "Enter your Email",
            "email.email" => "Enter a valid Email",
            "password.required" => "Enter your Password"
        ]);

        if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect('/')->with('showLoggedInModal', 'show');
        }
        
        return back()->with('wrongCredentialsError', 'Invalid Email or Password');
        
    }

    public function logoutUser() {
        Auth::logout();
        return redirect('/login');
    }

}
