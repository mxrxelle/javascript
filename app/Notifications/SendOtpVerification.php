<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpVerification extends Notification
{
    use Queueable;

    protected $otp;

    public function __withNotificationDetails($otp)
    {
        $this->otp = $otp;
    }

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function toBatches($notifiable)
    {
        return ['mail'];
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Certly Account Verification Code')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Gamit ang code sa ibaba upang ma-verify ang iyong account sa Certly Platform:')
                    ->line('**' . $this->otp . '**') // Makapal na numero
                    ->line('Ang code na ito ay magagamit mo lamang sa kasalukuyang verification page.')
                    ->line('Kung hindi mo sinubukang mag-register, maaari mong balewalain ang email na ito.');
    }
}