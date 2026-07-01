<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\AuditLog;
use App\Support\UserAgentParser;

#[Layout('layouts.app')]
#[Title('Keamanan Akun')]
class Security extends Component
{
    public $confirmLogoutId = null;

    /**
     * Get active sessions for the authenticated user
     */
    private function getActiveSessions()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get();

        return $sessions->map(function ($session) {
            $parsed = UserAgentParser::parse($session->user_agent);

            return [
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === Session::getId(),
                'browser' => $parsed['browser'],
                'platform' => $parsed['platform'],
                'device_type' => $parsed['device_type'],
                'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
            ];
        });
    }

    /**
     * Get activity timeline for the authenticated user
     */
    private function getUserActivityTimeline()
    {
        return AuditLog::where('user_id', auth()->id())
            ->latest()
            ->take(15) // ambil 15 aktivitas terakhir
            ->get();
    }

    /**
     * Logout a specific session
     */
    public function logoutSession($sessionId)
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        session()->flash('success', 'Perangkat berhasil dikeluarkan.');
        $this->dispatch('notify', type: 'success', message: 'Sesi perangkat berhasil diakhiri.');
        $this->confirmLogoutId = null;
    }

    /**
     * Logout all other sessions
     */
    public function logoutOtherDevices()
    {
        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', Session::getId())
            ->delete();

        session()->flash('success', 'Semua perangkat lain berhasil dikeluarkan.');
        $this->dispatch('notify', type: 'success', message: 'Semua perangkat lain telah dikeluarkan.');
    }

    public function render()
    {
        return view('livewire.auth.security', [
            'sessions' => $this->getActiveSessions(),
            'activities' => $this->getUserActivityTimeline(),
        ]);
    }
}
