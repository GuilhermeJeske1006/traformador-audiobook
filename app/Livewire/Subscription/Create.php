<?php

namespace App\Livewire\Subscription;

use Livewire\Component;

class Create extends Component
{
    public $paymentMethod;

    public function subscribe()
    {
        $user = auth()->user();

        $user->newSubscription('default', config('cashier.price_id'))
            ->create($this->paymentMethod);

        return redirect()->route('app');
    }

    public function render()
    {
        return view('livewire.subscription.create');
    }
}
