<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Models\smsConfig;


class SMSHelper
{
    public static function setSMSConfig($mobile_number, $message)
    {
        // Retrieve email configuration settings from the database
        $smsSetting = smsConfig::firstOrFail();
         // Construct the API URL
        $apiUrl = "http://mobicomm.dove-sms.com//submitsms.jsp";
        $params = [
            'user' => $smsSetting->user_id,
            'key' => $smsSetting->password,
            'mobile' => '+91' . $mobile_number, // Adjust this based on your SMS configuration
            'message' => $message, // Adjust this based on the message you want to send
            'senderid' => $smsSetting->sender_id,
            'accusage' => 1
        ];
        
        // Make the HTTP request to the API
        $response = Http::get($apiUrl, $params);

        // Check if the request was successful
        if ($response->successful()) {
            // Handle successful response
            $responseData = $response->json(); 
        } else {
            // Handle failed request
            $errorMessage = $response->body(); 
        }
    }
}
