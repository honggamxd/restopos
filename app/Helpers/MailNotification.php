<?php

namespace App\Helpers;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

class MailNotification
{
    public $send_to_address;
    public $send_to_name;
    public $subject;
    public $message;
    public $form_type;
    public $form_number;
    public $form_approval_url;
    public $can_approve;
    public $attachment_path;
    public $attachment_filename;

    public $mail_host;
    public $mail_username;
    public $mail_password;
    public $mail_from_name;
    public $mail_from_address;
    
    public function __construct() {
        // $this->send_to_address = '';
        // $this->send_to_name = '';
        // $this->subject = '';
        // $this->attachment_path = '';
        // $this->attachment_filename = '';
        $this->mail_host = config('mail.host');
        $this->mail_username = config('mail.username');
        $this->mail_password = config('mail.password');
        $this->mail_from_name = config('mail.from.name');
        $this->mail_from_address = config('mail.from.address');
    }

    public function send(){

        $this->message = 'Hello '.$this->send_to_name.',';
        $this->message .= '<br><br><a href="'.$this->attachment_path.'">'.$this->form_type." No. ".$this->form_number."</a> has been generated.";
        if($this->can_approve){
            $this->message .= '<br><br>To approve this '.strtolower($this->form_type).' form, please click on the link below:';
            $this->message .= '<br><br><a href="'.$this->form_approval_url.'">Click here to approve</a>.';
            $this->message .= '<br><br>Or copy and paste link below to your browser&#39;s address bar';
            $this->message .= '<br><br><a href="'.$this->form_approval_url.'">'.$this->form_approval_url.'</a>';
        }
        $this->message .="<br><br><br><i>* This is a system generated e-mail, please do not reply.</i>";
        // Create the Transport
        $transport = (new Swift_SmtpTransport($this->mail_host, 25))
        ->setUsername($this->mail_username)
        ->setPassword($this->mail_password);

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        
        // Create a message
        $message = (new Swift_Message($this->subject))
        ->setFrom([$this->mail_from_address => $this->mail_from_name])
        ->setTo([$this->send_to_address => $this->send_to_name])
        ->setBody($this->message,'text/html')
        ->setPriority(1);
                //attachments
        if($this->attachment_filename && $this->attachment_path){
            $attachments = Swift_Attachment::fromPath($this->attachment_path)
            ->setFilename($this->attachment_filename);

            $message->attach($attachments);
        }
        // return $message;

        // Send the message
        return $mailer->send($message);
    }
}
?>