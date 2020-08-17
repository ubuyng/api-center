<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $user;
    public function __construct()
    {
        // $this->data = $request;
        $this->user = auth()->user();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'info@ubuy.ng';
        $subject = 'Welcome to Ubuy Nigeria';
        $name = 'Ubuy Nigeria';
        $user_email = $this->user->email;
        
        return $this->view('emails.pros.welcome')
                    ->from($address, $name)
                    ->to($user_email)
                    ->replyTo($address, $name)
                    ->subject($subject);
                    // ->with([ 'message' => $this->data['message'] ]);
    }
}
