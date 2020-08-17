<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PassChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'info@ubuy.ng';
        $subject = 'Password Reset Request';
        $name = 'Ubuy Nigeria';
        $user_email = $this->user->email;
        
        return $this->view('emails.passreset')
                    ->from($address, $name)
                    ->to($user_email)
                    ->replyTo($address, $name)
                    ->subject($subject);
                    // ->with([ 'message' => $this->data['message'] ]);
    }
}
