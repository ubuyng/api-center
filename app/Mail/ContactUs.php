<?php

namespace App\Dev\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->data = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = $this->data->email;
        $subject = $this->data->subject;
        $name = $this->data->name;
        $ubuy_email = 'hello@ubuy.ng';
        $ubuy_copy = 'basictechy@ubuy.ng';
        
        return $this->view('emails.contact_us')
                    ->from($address, $name)
                    ->to($ubuy_email)
                    ->cc($ubuy_copy, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with([ 'message' => $this->data['message'] ]);
    }
}
