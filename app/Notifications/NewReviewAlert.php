<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Review $review) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Book Review: ' . $this->review->book->title)
            ->greeting('Hello Admin!')
            ->line('A customer has just submitted a new review.')
            ->line('**Book:** ' . $this->review->book->title)
            ->line('**Rating:** ' . $this->review->rating . ' / 5 Stars')
            ->line('**Comment:** "' . $this->review->comment . '"')
            ->line('**User:** ' . $this->review->user->first_name)
            ->line('**Email:** ' . $this->review->user->email)
            ->action('Manage Books', route('books.index'));
    }
}