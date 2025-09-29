<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShiftNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shift;
    public $messageText;

    public function __construct($shift, $messageText)
    {
        $this->shift = $shift;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('Shift Update Notification')
                    ->view('emails.shift-notification')
                    ->with([
                        'shift' => $this->shift,
                        'messageText' => $this->messageText,
                    ]);
    }
}
