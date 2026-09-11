<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail; 
use Illuminate\Http\Request;

class GuestGameController extends Controller
{
    // Show a simple page with a "Send Email" button
    public function show()
    {
        return view('guest_gamedetails');
    }

    // Send email with token and button
    public function send_static_email()
    {
        $to_name = "NICK";
        $to_email = "nicksolanki615@gmail.com";

        // Generate a unique token
        $token = uniqid();

        // Data to pass to the email view
        $data = [
            "name" => "SOLANKI",
            "token" => $token,
            "email" => $to_email
        ];

        // Send the email
        Mail::send("mail_content", $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject("Laravel Test Mail");
            $message->from("nicksolanki615@gmail.com");
        });

        return "Mail sent successfully!";
    }
}
