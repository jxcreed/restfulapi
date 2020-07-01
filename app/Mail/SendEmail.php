<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable 
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $page;

    public function __construct($page, $subject, $data) {
        $this->page = $page;
        $this->subject = $subject;
        $this->data = $data;

    }

    public function build() {
        return $this->subject($this->subject)
        ->view($this->page)->with(['data', $this->data]);
    }

}