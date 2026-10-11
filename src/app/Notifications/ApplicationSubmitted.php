<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Job $job) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Application submitted: '.$this->job->title)
            ->greeting('Hi '.$notifiable->name.'!')
            ->line('Your application for '.$this->job->title.' at '.$this->job->company->name.' has been submitted successfully.')
            ->line('The employer will review your application and get back to you.')
            ->action('View job', route('jobs.show', $this->job))
            ->line('Good luck!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'title' => 'Application submitted',
            'message' => 'You applied for '.$this->job->title.' at '.$this->job->company->name,
            'url' => route('applied-jobs'),
            'job_id' => $this->job->id,
        ];
    }
}
