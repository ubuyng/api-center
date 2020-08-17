<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use GuzzleHttp\Client;


class NewTask extends Notification 
{
    use Queueable;
    public $data;
    public $user;
    public $details;
    public $project;
    public $project_subject;
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $details, $project)
    {
        $this->details = $details;
        $this->project = $project;
        $this->user = $user;
        $this->project_subject = $project->cus_name .' needs '. $project->sub_category_name. ' in ' . $project->city.', '.$project->state;
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
        $res = $client->request('POST', 'https://account.kudisms.net/api/?username=hello@ubuy.ng&password=ubuy&message=Hello%20'.$this->user->first_name. ', '.$this->project->cus_name.' is looking for a  '.$this->project->sub_category_name.'. login to https://ubuy.ng/dashboard/ to send a bid &sender=Ubuy.ng&mobiles='.$this->user->number.'', [
        ]);

        $project_id = $this->project->project_id;
        // $url = url('/dashboard/bids/'.$project_id);
                    return (new MailMessage)
                    ->subject($this->project->cus_name.' Needs '.$this->project->sub_category_name.' Ubuy Nigeria')
                    ->view(
                        'emails.pros.project_email',
                         ['details' => $this->details,
                        'project' => $this->project,
                        'user' => $this->user,
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
            'cus_id' => $this->project->user_id,
            'pro_id' => $this->user->id,
            'project_id' => $this->project->id,
            'cus_name' => $this->project->cus_name,
            'project_name' => $this->project->sub_category_name,
        ];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A new Project  has been posted to your application' . $this->dataLocation->sub_category_name);
    }

}
