<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobPosted extends Notification implements ShouldQueue
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
            ->subject('New job from '.$this->job->company->name)
            ->greeting('Hi '.$notifiable->name.'!')
            ->line($this->job->company->name.' just posted a new position: '.$this->job->title.'.')
            ->line($this->job->salary_label)
            ->action('View job', route('jobs.show', $this->job))
            ->line('Check it out before it expires!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_job_posted',
            'title' => 'New job from '.$this->job->company->name,
            'message' => $this->job->title,
            'url' => route('jobs.show', $this->job),
            'job_id' => $this->job->id,
        ];
    }
}
