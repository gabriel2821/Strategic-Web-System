<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncompleteProgramNotification extends Notification
{
    use Queueable;
    public $program;
    public $incompleteRows; 
    /**
     * Create a new notification instance.
     */
    public function __construct($program, $incompleteRows)
    {
        $this->program = $program;
        $this->incompleteRows = $incompleteRows;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->line('Reminder: Incomplete Program Tasks.')
            ->line('Program anda "' . $this->program->title . '" mempunyai tugasan yang tidak lengkap berikut:')
            ->line('');

        foreach ($this->incompleteRows as $row) {
            $mail->line("- {$row->title} ({$row->completion}%)");
    }

    return $mail
            ->action('View Program', url('/programs/' . $this->program->id))
            ->line('Sila kemas kini program anda secepat mungkin.');
    } 
    
    public function toDatabase($notifiable)
    {
        return [
            'program_id' => $this->program->id,
            'program_title' => $this->program->title,
            'message' => 'Peringatan: Program anda "' . $this->program->title . '" mempunyai tugas yang tidak lengkap.',
            'url' => route('program_rows.index', ['program' => $this->program->id]),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
