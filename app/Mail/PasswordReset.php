<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The recipient's first_name.
     *
     * @var string
     */
    public $first_name;
    /**
     * The recipient's last_name.
     *
     * @var string
     */
    public $last_name;

    /**
     * Create a new message instance.
     *
     * @param string $name  - recipient name.
     * @param string $token - email reset token.
     */
    public function __construct(string $first_name, string $last_name, string $token)
    {
        $this->first_name = $first_name;
        $this->last_name  = $last_name;
        $this->token      = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password-reset')
                    ->with([
                        'first_name' => $this->first_name,
                        'last_name'  => $this->last_name,
                        'token'      => $this->token,
                    ]);
    }
}
