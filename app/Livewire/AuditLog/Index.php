<?php

namespace App\Livewire\AuditLog;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Audit Logs')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = '';
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';

    /**
     * When filter changes, reset pagination
     */
    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function applyFilter()
    {
        $this->filter = $this->search;
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['search', 'filter']);
        $this->resetPage();
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value === 'all' ? PHP_INT_MAX : (int) $value;
        $this->resetPage();
    }

    public function render()
    {
        $datas = AuditLog::with('user')
            ->when($this->filter, fn ($q) => $q->where(function ($q2) {
                $q2->where('action', 'like', '%' . $this->filter . '%')
                    ->orWhere('description', 'like', '%' . $this->filter . '%')
                    ->orWhere('subject_type', 'like', '%' . $this->filter . '%')
                    ->orWhere('subject_id', 'like', '%' . $this->filter . '%');
            }))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.audit-log.index', compact('datas'));
    }
}
