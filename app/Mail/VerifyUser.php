<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;    

    /**
     * The user submitted form values.
     *
     * @var array
     */
    public $form;

    /**
     * Create a new message instance.
     *
     * @param string $name  - recipient name.
     * @param string $token - email reset token.
     */
    public function __construct(array $form)
    {
        $this->form = $form;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verify-email')
                    ->with([
                        'form'      => $this->form,
                    ]);
    }
}
