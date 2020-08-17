<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use GuzzleHttp\Client;


class CusArtisanNotify extends Notification 
{
    use Queueable;
    public $cususer;
    public $proprofile;
    public $project_call;
    public $bid;
    public $project_subject;
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cususer, $proprofile, $project_call, $bid)
    {
        $this->project_call = $project_call;
        $this->bid = $bid;
        $this->proprofile = $proprofile;
        $this->cususer = $cususer;
        $this->project_subject = 'Your '.$this->project_call->sub_category_name.' Task has been awarded to '.$this->proprofile->business_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'database', 'slack'];
    }

   
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
                    // return (new MailMessage)
                    // ->subject($this->project_subject)
                    // ->view(
                    //     'emails.customers.notify_artisan_email',
                    //      ['proprofile' => $this->proprofile,
                    //     'project_call' => $this->project_call,
                    //     'cususer' => $this->cususer,
                    //     'bid' => $this->bid,
                    //    ]
                    // );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
      return [
            'cus_id' => $this->project_call->user_id,
            'pro_id' => $this->proprofile->user_id,
            'project_id' => $this->project_call->id,
            'cus_name' => $this->project_call->cus_name,
            'project_name' => $this->project_call->sub_category_name,
        ];
    }

}
