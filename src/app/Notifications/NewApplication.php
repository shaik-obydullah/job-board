<?php

namespace App\Notifications;

use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplication extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Job $job, public User $applicant) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New application received for '.$this->job->title)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line($this->applicant->name.' has applied for the position of '.$this->job->title.'.')
            ->line('Email: '.$this->applicant->email)
            ->action('View applicants', route('company.jobs.applicants', $this->job))
            ->line('Thank you for using '.config('app.name').'!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application',
            'title' => 'New application received',
            'message' => $this->applicant->name.' applied for '.$this->job->title,
            'url' => route('company.jobs.applicants', $this->job),
            'job_id' => $this->job->id,
        ];
    }
}
