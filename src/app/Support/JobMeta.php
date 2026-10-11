<?php

namespace App\Support;

class JobMeta
{
    public static function jobTypes(): array
    {
        return [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
            'internship' => 'Internship',
            'freelance' => 'Freelance',
            'remote' => 'Remote',
        ];
    }

    public static function locationTypes(): array
    {
        return [
            'on_site' => 'On-site',
            'remote' => 'Remote',
            'hybrid' => 'Hybrid',
        ];
    }

    public static function experienceLevels(): array
    {
        return [
            'entry' => 'Entry Level',
            'junior' => 'Junior',
            'mid' => 'Mid Level',
            'senior' => 'Senior',
            'lead' => 'Lead',
            'executive' => 'Executive',
        ];
    }

    public static function applicationStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'reviewed' => 'Reviewed',
            'shortlisted' => 'Shortlisted',
            'rejected' => 'Rejected',
            'hired' => 'Hired',
            'withdrawn' => 'Withdrawn',
        ];
    }

    public static function jobStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'closed' => 'Closed',
            'expired' => 'Expired',
        ];
    }

    public static function jobStatusColor(string $status): string
    {
        return match ($status) {
            'published' => 'green',
            'draft' => 'gray',
            'closed' => 'red',
            'expired' => 'amber',
            default => 'gray',
        };
    }

    public static function applicationStatusColor(string $status): string
    {
        return match ($status) {
            'pending' => 'amber',
            'reviewed' => 'blue',
            'shortlisted' => 'primary',
            'rejected' => 'red',
            'hired' => 'green',
            'withdrawn' => 'gray',
            default => 'gray',
        };
    }

    public static function roleColor(string $role): string
    {
        return match ($role) {
            'admin' => 'red',
            'employer' => 'blue',
            default => 'primary',
        };
    }
}
