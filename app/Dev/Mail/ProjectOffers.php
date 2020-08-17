<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\User;
use App\SubCategory;
use App\Project;
use App\Response;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectOffers extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $user;
    public $details;
    public $project;
    public $project_subject;
    public function __construct($user, $details, $project)
    {
        $this->details = $details;
        $this->project = $project;
        $this->user = $user;
        $this->project_subject = $project->cus_name .' needs '. $project->sub_category_name. ' in ' . $project->city.', '.$project->state;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'info@ubuy.ng';
        $subject = $this->project_subject;
        $name = 'Ubuy Nigeria';
        $user_email = $this->user->email;
       
        
        return $this->view('emails.pros.project_email')
                    ->from($address, $name)
                    ->to($user_email)
                    ->replyTo($address, $name)
                    ->subject($subject);
                    // ->with([ 'message' => $this->data['message'] ]);
    }
}
