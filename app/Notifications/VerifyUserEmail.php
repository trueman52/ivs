<?php

namespace App\Notifications;

use App\Mail\VerifyUser as VerifyUserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class VerifyUserEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * User submitted form data.
     *
     * @var array
     */
    public $form;

    /**
     * Create a new notification instance.
     *
     * @param array $form -  User submitted form data.
     */
    public function __construct(array $form)
    {
        $this->form = $form;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \App\Mail\PasswordReset
     */
    public function toMail($notifiable)
    {
        return (new VerifyUserMail($this->form))
            ->to($this->form['email'])
            ->subject('Verify Email Address');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
}
