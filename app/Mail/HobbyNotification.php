<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HobbyNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $firstname, $hobby)
    {
        $this->type = $type;
        $this->firstname = $firstname;
        $this->hobby = $hobby;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'New Hobby Created';
        $view = 'mails.add_hobby';

        switch ($this->type) {
            case 'create':
                $subject = 'New Hobby Created';
                $view = 'mails.add_hobby';
                break;
            case 'update':
                $subject = 'You Updated An Hobby';
                $view = 'mails.update_hobby';
                break;
            case 'delete':
                $subject = 'You Deleted an hobby';
                $view = 'mails.delete_hobby';
                break;
            default:
                break;
        }

        return $this->from('on-reply@hobby.com')
            ->subject($subject)
            ->markdown($view)
            ->with([
                'first_name' => $this->firstname,
                'hobby' => $this->hobby
            ]);
    }
}
