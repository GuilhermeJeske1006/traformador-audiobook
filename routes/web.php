<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', App\Livewire\LandingPage::class)->name('home');
Route::get('/app', App\Livewire\AudiobookPage::class)->name('app')->middleware(['auth', 'verified', 'subscribed']);
Route::get('/subscription/create', App\Livewire\Subscription\Create::class)->name('subscription.create')->middleware(['auth']);
Route::get('/subscription/manage', App\Livewire\Subscription\Manage::class)->name('subscription.manage')->middleware(['auth']);


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
