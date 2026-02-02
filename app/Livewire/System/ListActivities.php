<?php

namespace App\Livewire\System;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
class ListActivities extends Component
{
    use WithPagination;

    public string $search = '';
    public string $eventFilter = '';
    public bool $showDetailsModal = false;
    public ?Activity $selectedActivity = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedEventFilter()
    {
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->selectedActivity = Activity::findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedActivity = null;
    }

    public function render()
    {
        $activities = Activity::query()
            ->with('causer', 'subject')
            ->latest()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('event', 'like', '%' . $this->search . '%')
                    ->orWhereHas('causer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->eventFilter, function ($query) {
                $query->where('event', $this->eventFilter);
            })
            ->paginate(20);

        return view('livewire.system.list-activities', [
            'activities' => $activities,
        ]);
    }
}
