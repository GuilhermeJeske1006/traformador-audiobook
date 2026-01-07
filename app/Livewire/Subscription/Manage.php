<?php

namespace App\Livewire\Subscription;

use Livewire\Component;

class Manage extends Component
{
    public function cancel()
    {
        $user = auth()->user();

        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
            session()->flash('message', 'Sua assinatura foi cancelada e permanecerá ativa até o final do período atual.');
        }
    }

    public function resume()
    {
        $user = auth()->user();

        if ($user->subscription('default')->onGracePeriod()) {
            $user->subscription('default')->resume();
            session()->flash('message', 'Sua assinatura foi reativada com sucesso!');
        }
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.subscription.manage', [
            'subscription' => $user->subscription('default'),
            'isSubscribed' => $user->subscribed('default'),
            'onGracePeriod' => $user->subscription('default') ? $user->subscription('default')->onGracePeriod() : false,
        ]);
    }
}
