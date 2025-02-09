<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $request->validate([
            'device_token' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        $firebase = app('firebase');
        $messaging = $firebase->getMessaging();

        $notification = Notification::create($request->title, $request->body);

        $message = CloudMessage::withTarget('token', $request->device_token)
            ->withNotification($notification);

        $response = $messaging->send($message);

        return response()->json(['message' => 'Notification sent!', 'response' => $response]);
    }
}
