<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $replyMessage;
    public function __construct($replyMessage)
    {
        $this->replyMessage = $replyMessage;
    }

    /**
     * メールの設定（件名など）
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【真理文庫】お問い合わせへのご回答',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_reply', // あとで作成するメール用HTMLビュー
        );
    }
}
