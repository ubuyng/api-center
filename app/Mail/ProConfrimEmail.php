<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $user;
    public function __construct($request)
    {
        $this->data = $request;
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
        $subject = 'Confirm your Ubuy Email';
        $name = 'Ubuy Nigeria';
        $user_email = $this->user->email;
        $confirm_code = rand(1452323252,954852323236);
        
        return $this->view('emails.pros.confirmation')
                    ->from($address, $name)
                    ->to($user_email)
                    ->replyTo($address, $name)
                    ->subject($subject);
                    // ->with([ 'message' => $this->data['message'] ]);
    }
}
