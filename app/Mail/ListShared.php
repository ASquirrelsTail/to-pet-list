<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\User;

class ListShared extends Mailable
{
    use Queueable, SerializesModels;

    public $author;
    public $sharee;

    public function __construct(User $author, ?User $sharee)
    {
        $this->author = $author;
        $this->sharee = $sharee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You\'ve been invited to a To Pet List')
            ->view('emails.list-share')->with(['author'=>$this->author, 'sharee'=>$this->sharee]);
    }
}
