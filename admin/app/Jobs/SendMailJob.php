<?php

namespace App\Jobs;

use App\Mail\EmailForQueuing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @param $view
     * @param $to
     * @param $subject
     * @param array $cc
     * @param array $data
     */

    private $view;
    private $to;
    private $subject;
    private $cc;
    private $data;
    public function __construct($view, $to, $subject, $cc, $data)
    {
        $this->view = $view;
        $this->to = $to;
        $this->subject = $subject;
        $this->cc = $cc;
        $this->data = $data;
    }


    public function handle()
    {
        $mail = new EmailForQueuing($this->view, $this->subject, $this->data);
        Mail::to($this->to)->cc($this->cc)->send($mail);
    }
}
