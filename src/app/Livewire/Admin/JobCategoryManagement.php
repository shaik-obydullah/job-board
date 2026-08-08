<?php

namespace App\Livewire\Admin;

use App\Models\JobCategory;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manage Categories')]
class JobCategoryManagement extends Component
{
    use WithPagination;

    public string $name = '';

    public string $icon = '';

    public string $description = '';

    public bool $is_active = true;

    public ?int $editingId = null;

    public function create(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:job_categories,name'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        JobCategory::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'icon' => $this->icon ?: null,
            'description' => $this->description ?: null,
            'is_active' => $this->is_active,
        ]);

        foreach (CacheKeys::categoryKeys() as $key) {
            Cache::forget($key);
        }

        $this->reset(['name', 'icon', 'description']);
        $this->is_active = true;

        session()->flash('success', 'Category created.');
    }

    public function edit(int $id): void
    {
        $category = JobCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon ?? '';
        $this->description = $category->description ?? '';
        $this->is_active = $category->is_active;
    }

    public function update(): void
    {
        $category = JobCategory::findOrFail($this->editingId);

        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:job_categories,name,'.$category->id],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'icon' => $this->icon ?: null,
            'description' => $this->description ?: null,
            'is_active' => $this->is_active,
        ]);

        foreach (CacheKeys::categoryKeys() as $key) {
            Cache::forget($key);
        }

        $this->cancelEdit();
        session()->flash('success', 'Category updated.');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset(['name', 'icon', 'description']);
        $this->is_active = true;
    }

    public function toggleActive(int $id): void
    {
        $category = JobCategory::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);
        foreach (CacheKeys::categoryKeys() as $key) {
            Cache::forget($key);
        }

        session()->flash('success', $category->is_active ? 'Category enabled.' : 'Category disabled.');
    }

    public function render()
    {
        $categories = JobCategory::withCount('jobs')->latest()->paginate(20);

        return view('livewire.admin.job-category-management', ['categories' => $categories]);
    }
}
