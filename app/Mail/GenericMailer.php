<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;   // Dynamic subject
    public $content;   // Email content (can be text, HTML, etc.)
    public $fromEmail; // Optional: sender email

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $content
     * @param string|null $fromEmail
     */
    public function __construct($subject, $content, $fromEmail = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->fromEmail = $fromEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.template') // Reference a view for email content
                     ->subject($this->subject) // Set the unique subject
                     ->with(['body_content' => $this->content]);

        if ($this->fromEmail) {
            $email->from($this->fromEmail, 'Human-I-T');
        }

        return $email;
    }
}
