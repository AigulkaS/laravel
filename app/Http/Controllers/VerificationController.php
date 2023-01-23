<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (
            !hash_equals((string) $request->route('id'), (string) $user->getKey())
            || !hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))
        ) {

            return response()->json(['message' => 'Verification error ! Try again'], 500);
        }


        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'already verified !'], 200);
        }

        if ($user->markEmailAsVerified()) {
            return response()->json(['message' => 'email verified successfully !'], 200);
        }

        return response()->json(['verified' => true]);
    }

    public function resend(Request $request)
    {

        $this->validate($request, ['id' => 'required']);

        $user = User::find($request->id);

        if (!$user)  return response()->json(['message' => 'Verification error '], 500);


        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email уже подтвержден !'], 200);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Письмо для подтверждения email было повторно отправлено !', 200]);
    }
}
