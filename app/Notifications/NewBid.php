<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Auth\User;
use GuzzleHttp\Client;


class NewBid extends Notification 
{
    use Queueable;
    protected $request;
    public $data;
    public $cus_user;
    public $pro_user;
    public $project;
    public $bid;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request, $cus_user, $pro_user, $project, $bid)
    {
        // 
        $this->request = $request;
        $this->cus_user = $cus_user;
        $this->pro_user = $pro_user;
        $this->project = $project;
        $this->bid = $bid;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
        $res = $client->request('POST', 'https://account.kudisms.net/api/?username=hello@ubuy.ng&password=ubuy&message=Hello%20'.$this->cus_user->first_name.', '.$this->pro_user->business_name.' has sent an offer for your task '.$this->project->sub_category_name.'. login to https://ubuy.ng/dashboard/my-projects to view pros bids &sender=Ubuy.ng&mobiles='.$this->cus_user->number.'', [
        ]);

        $project_id = $this->request->project_id;
        $url = url('/dashboard/bids/'.$project_id);
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

                    return (new MailMessage)
                    ->subject('New Bid for '.$this->project->sub_category_name.' Ubuy Nigeria')
                    ->view(
                        'emails.pros.sendbid_email',
                         ['request' => $this->request,
                        'project' => $this->project,
                        'cus_user' => $this->cus_user,
                        'bid' => $this->bid,
                        'pro_user'=> $this->pro_user]
                        
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
            'cus_id' => $this->cus_user->id,
            'pro_id' => $this->pro_user->user_id,
            'bid_id' => $this->bid->id,
            'project_id' => $this->project->id,
            'pro_name' => $this->pro_user->business_name,
            'project_name' => $this->project->sub_category_name,
        ];
    }
} 
