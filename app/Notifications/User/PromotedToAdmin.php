<?php

declare(strict_types=1);

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Translation\Translator;

class PromotedToAdmin extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $translator = app()->make(Translator::class);

        return (new MailMessage())
            ->subject($translator->get('Your account has been promoted to Admin status'))
            ->line($translator->get('You are receiving this email because your account has been promoted to admin status.'))
            ->action($translator->get('Open Admin Dashboard'), 'some-url');
    }
}
