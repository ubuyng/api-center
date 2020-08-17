<?php

namespace App\Dev\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CusConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $user;
    public $confirm_code;
    public function __construct()
    {
        // $this->data = $request;
        $this->user = auth()->user();
        $this->confirm_code = rand(1452323252,954852323236);
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
       
        
        return $this->view('emails.customers.confirmation')
                    ->from($address, $name)
                    ->to($user_email)
                    ->replyTo($address, $name)
                    ->subject($subject);
                    // ->with([ 'message' => $this->data['message'] ]);
    }
}
