<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('System Settings')]
class SystemSettings extends Component
{
    public string $site_name = '';

    public string $site_tagline = '';

    public string $support_email = '';

    public string $jobs_per_page = '12';

    public string $maintenance_mode = '';

    public function mount(): void
    {
        $this->site_name = Setting::get('site_name', config('app.name'));
        $this->site_tagline = Setting::get('site_tagline', 'Find your next career move');
        $this->support_email = Setting::get('support_email', '');
        $this->jobs_per_page = Setting::get('jobs_per_page', '12');
        $this->maintenance_mode = Setting::get('maintenance_mode', 'off');
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
        ]);

        Setting::set('site_name', $this->site_name);
        Setting::set('site_tagline', $this->site_tagline);
        Setting::set('support_email', $this->support_email);

        config(['app.name' => $this->site_name]);

        ActivityLog::record(auth()->user(), 'settings_updated', 'Updated general site settings.');

        session()->flash('success', 'General settings saved.');
    }

    public function saveListing(): void
    {
        $this->validate(['jobs_per_page' => ['required', 'integer', 'min:6', 'max:60']]);

        Setting::set('jobs_per_page', $this->jobs_per_page);

        session()->flash('success', 'Listing settings saved.');
    }

    public function saveMaintenance(): void
    {
        Setting::set('maintenance_mode', $this->maintenance_mode);

        ActivityLog::record(auth()->user(), 'maintenance_updated', 'Updated maintenance mode to '.$this->maintenance_mode.'.');

        session()->flash('success', $this->maintenance_mode === 'on'
            ? 'Maintenance mode enabled. Run php artisan down to activate.'
            : 'Maintenance mode disabled.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}
