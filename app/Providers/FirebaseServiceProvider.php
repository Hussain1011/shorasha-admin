<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\ServiceAccount;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            return (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        });
    }
}
