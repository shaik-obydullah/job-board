<?php

namespace App\Livewire\Admin;

use App\Models\Skill;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manage Skills')]
class SkillManagement extends Component
{
    use WithPagination;

    public string $name = '';

    public string $search = '';

    public function create(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name'],
        ]);

        Skill::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        $this->name = '';
        session()->flash('success', 'Skill added.');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Skill::query()->withCount('jobs');

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $skills = $query->orderBy('name')->paginate(30);

        return view('livewire.admin.skill-management', ['skills' => $skills]);
    }
}
