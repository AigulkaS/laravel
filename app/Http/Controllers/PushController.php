<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
//use Illuminate\Notifications\Notification;
//use Illuminate\Notifications\Notification;
use Notification;
use App\Notifications\PushDemo;
//use Illuminate\Support\Facades\Notification;

class PushController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
//            'publicKey'   => 'required',
//            'authToken' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $publicKey = $request->publicKey;
        $authToken = $request->authToken;
        $contentEncoding = $request->contentEncoding;
        $user = auth('sanctum')->user();
        $user->updatePushSubscription($endpoint, $publicKey, $authToken, $contentEncoding);

        $user->notify(new PushDemo());
        return response()->json(['success' => true],200);
    }

    public function storePush(Request $request)
    {
        $user = auth('sanctum')->user();
        $user->notify(new PushDemo);
//        $request->user()->notify(new HelloNotification);

        return response()->json('Notification sent.', 201);
    }

    public function push(){
        $user = auth('sanctum')->user();
        $user->notify(new PushDemo);
//        Notification::send(User::all(), new PushDemo);
        return response()->json(['success' => true],200);
    }
}
