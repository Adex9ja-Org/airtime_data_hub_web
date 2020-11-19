<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $view;
    public $subject;
    public $data;
    public function __construct($view, $subject, $data)
    {
        $this->view = $view;
        $this->subject = $subject;
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.username'), config('app.name'))
            ->subject($this->subject)
            ->view($this->view, $this->data);
    }
}
