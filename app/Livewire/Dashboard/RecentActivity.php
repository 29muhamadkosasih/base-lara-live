<?php

namespace App\Livewire\Dashboard;

use App\Models\AuditLog;
use Livewire\Component;

class RecentActivity extends Component
{
    /**
     * Refresh setiap 30 detik via wire:poll
     */
    public function render()
    {
        $recentActivities = AuditLog::with('user')->latest()->limit(8)->get();

        return view('livewire.dashboard.recent-activity', compact('recentActivities'));
    }
}
