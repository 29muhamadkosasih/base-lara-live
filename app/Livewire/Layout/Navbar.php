<?php

namespace App\Livewire\Layout;

use App\Models\SettingApp;
use App\Models\User;
use Livewire\Component;

class Navbar extends Component
{
    public function render()
    {
        $setting = SettingApp::first();
        $logoUrl = $setting?->logo
            ? asset('storage/uploads/logos/' . $setting->logo)
            : asset('assets/img/favicon/favicon.ico');

        /** @var User|null $user */
        $user = auth()->user();
        $brandName = $setting->brand ?? 'Base App Template';
        $userName = $user?->name ?? 'User';
        $userRole = $user?->getRoleNames()->first() ?? 'User';
        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0d6efd&color=ffffff';

        return view('livewire.layout.navbar', compact(
            'logoUrl',
            'brandName',
            'userName',
            'userRole',
            'avatarUrl'
        ));
    }
}
