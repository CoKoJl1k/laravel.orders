<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userOrder;
    public $subject;
    public $replyComment;
    public $view;

    public function __construct(string $userName,int $userOrder,string $subject,string $view,string $replyComment = '')
    {
        $this->userName = $userName;
        $this->userOrder = $userOrder;
        $this->subject = $subject;
        $this->view = $view;
        $this->replyComment = $replyComment;

    }

    public function build()
    {
        return $this->view($this->view)->subject($this->subject);
    }
}
