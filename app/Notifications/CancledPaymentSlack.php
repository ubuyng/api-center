<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;


class CancledPaymentSlack extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project, $cus_name, $pro_name, $amount, $user)
    {
        $this->project = $project;
        $this->cus_name = $cus_name;
        $this->cus_number = $user->number;
        $this->amount = $amount;
        $this->pro_name = $pro_name;
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
        return (new SlackMessage)
                ->from('UbuyEventBot', ':sad:')
                ->to('#newtasks')
                ->content($this->cus_name." \n Just stopped the payment for ".$this->project."\n paying ₦".number_format($this->amount)."\n Pro name is ".$this->pro_name." \n his number is ".$this->cus_number);
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
