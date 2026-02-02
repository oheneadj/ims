<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Request;

class LogAuthentication
{
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
        ];
    }

    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ])
            ->log('User Logged In');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties([
                    'ip' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                ])
                ->log('User Logged Out');
        }
    }
}
