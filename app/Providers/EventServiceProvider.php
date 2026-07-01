<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \App\Support\AuditLogger::log('auth.login', $event->user, 'Pengguna berhasil masuk (Login).');
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                \App\Support\AuditLogger::log('auth.logout', $event->user, 'Pengguna keluar dari sistem (Logout).');
            }
        });

        Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            \App\Support\AuditLogger::log('auth.failed', null, 'Gagal masuk sistem menggunakan email: ' . ($event->credentials['email'] ?? 'unknown'));
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
