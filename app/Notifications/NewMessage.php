<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use GuzzleHttp\Client;

class NewMessage extends Notification
{
    use Queueable;
    public $request;
    public $project;
    public $receiver_user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request, $receiver_user, $project)
    {
        $this->receiver_user = $receiver_user;
        $this->request = $request;
        $this->project = $project;
        $this->project_subject = 'New message for '.$this->project->sub_category_name.' task';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // :TODO: ADD ONESIGNAL
        $client = new Client();
        $res = $client->request('POST', 'https://account.kudisms.net/api/?username=hello@ubuy.ng&password=ubuy&message=Hello, '.$this->receiver_user->first_name.' you have a new message for  '.$this->project->sub_category_name.'  task. login to https://ubuy.ng/dashboard/ for details &sender=Ubuy.ng&mobiles='.$this->receiver_user->number.'', [
        ]);

        $project_id = $this->project->id;
                    return (new MailMessage)
                    ->subject($this->project_subject)
                    ->view(
                        'emails.notify_chat_all',
                         [
                        'project' => $this->project,
                        'receiver_user' => $this->receiver_user,
                        'request' => $this->request,
                       ]
                    );
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
