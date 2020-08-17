<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;


class NewTaskslack extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project->sub_category_name;
        $this->cus_name = $project->cus_name;
        $this->cus_number = $project->phone_number;
        $this->cus_city = $project->city;
        $this->cus_state = $project->state;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSlack($notifiable)
    {
        $message = "Famous Hello World!";
        
        return (new SlackMessage)
                ->from('UbuyBot', ':champagne:')
                ->to('#newtasks')
                ->content("New Project posted by ".$this->cus_name." \n Task category ".$this->project." \n Number is ".$this->cus_number." \n city and state is ".$this->cus_city.' '.$this->cus_state );
    }
 

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
