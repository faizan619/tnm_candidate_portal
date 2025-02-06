<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use App\Models\emailConfig;

class EmailHelper
{
    public static function setMailConfig()
    {
        // Retrieve email configuration settings from the database
       $emailSetting = emailConfig::where('status',1)->first();

config([
        'mail.mailers.smtp.host' => $emailSetting->smtp_server,
        'mail.mailers.smtp.port' => $emailSetting->port_no,
        'mail.mailers.smtp.encryption' => $emailSetting->ssl_enabled=='yes' ? 'ssl' : null,
        'mail.mailers.smtp.username' => $emailSetting->user_id,
        'mail.mailers.smtp.password' => $emailSetting->password,
        'mail.from.address' => $emailSetting->sender_id,
    ]);

    // Set the default mailer
    config(['mail.default' => 'smtp']);
   /* $configuredMailData = config('mail');
    dd($configuredMailData);*/
    }
}
