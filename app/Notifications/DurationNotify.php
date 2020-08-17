<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use GuzzleHttp\Client;


class ProArtisanNotify extends Notification 
{
    use Queueable;
    public $cususer;
    public $prouser;
    public $project_call;
    public $bid;
    public $project_subject;
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cususer, $prouser, $bid, $project_call)
    {
        $this->project_call = $project_call;
        $this->bid = $bid;
        $this->prouser = $prouser;
        $this->cususer = $cususer;
        $this->project_subject = 'New Task Awarded '.$project_call->cus_name.' Just awarded you '.$project_call->sub_category_name.' task';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'slack'];
    }

   
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $client = new Client();
        $res = $client->request('POST', 'https://account.kudisms.net/api/?username=hello@ubuy.ng&password=ubuy&message=Hello, '.$this->project_call->cus_name.' just awarded you a  '.$this->project_call->sub_category_name.' task. login to https://ubuy.ng/dashboard/ for details &sender=Ubuy.ng&mobiles='.$this->prouser->number.'', [
        ]);

        $project_id = $this->project_call->project_id;
        // $url = url('/dashboard/bids/'.$project_id);
                    return (new MailMessage)
                    ->subject($this->project_subject)
                    ->view(
                        'emails.pros.notify_artisan_email',
                         ['prouser' => $this->prouser,
                        'project_call' => $this->project_call,
                        'cususer' => $this->cususer,
                        'bid' => $this->bid,
                       ]
                    );
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
            'pro_id' => $this->prouser->id,
            'project_id' => $this->project_call->id,
            'cus_name' => $this->project_call->cus_name,
            'project_name' => $this->project_call->sub_category_name,
        ];
    }

}
