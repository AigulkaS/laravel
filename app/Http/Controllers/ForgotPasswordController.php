<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgot_password(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        Password::broker()->sendResetLink($request->only('email'));
        return response()->json([
            "msg" => "Письмо для сброса пароля отправлено.Проверьте Вашу почту",
        ], 200);
    }
}
