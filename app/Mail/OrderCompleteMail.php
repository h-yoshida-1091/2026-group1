<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $cartItems;
    public $products;
    public $total;

    /**
     * 注文情報をメールクラスに注入
     */
    public function __construct($user, $cartItems, $products, $total)
    {
        $this->user = $user;
        $this->cartItems = $cartItems;
        $this->products = $products;
        $this->total = $total;
    }

    /**
     * メールの設定（件名など）
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【真理文庫】ご購入手続き完了のお知らせ',
        );
    }

    /**
     * メール本文のビュー定義
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_complete', // メール用のBladeビュー
        );
    }
}