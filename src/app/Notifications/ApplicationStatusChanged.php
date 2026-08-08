<?php

namespace App\Notifications;

use App\Models\Application;
use App\Support\JobMeta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = JobMeta::applicationStatuses()[$this->application->status] ?? $this->application->status;

        return (new MailMessage)
            ->subject('Application status update for '.$this->application->job->title)
            ->greeting('Hi '.$notifiable->name.'!')
            ->line('Your application for '.$this->application->job->title.' at '.$this->application->job->company->name.' has been updated.')
            ->line('New status: '.$status)
            ->when($this->application->employer_note, fn ($mail) => $mail->line('Note from employer: '.$this->application->employer_note))
            ->action('View application', route('applied-jobs'))
            ->line('Thank you for using '.config('app.name').'!');
    }

    public function toArray(object $notifiable): array
    {
        $status = JobMeta::applicationStatuses()[$this->application->status] ?? $this->application->status;

        return [
            'type' => 'application_status_changed',
            'title' => 'Application '.strtolower($status),
            'message' => 'Your application for '.$this->application->job->title.' is now '.$status,
            'url' => route('applied-jobs'),
            'application_id' => $this->application->id,
        ];
    }
}
