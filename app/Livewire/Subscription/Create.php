<?php

namespace App\Livewire\Subscription;

use Livewire\Component;
use Laravel\Cashier\Exceptions\IncompletePayment;

class Create extends Component
{
    public $paymentMethod;

    public function subscribe()
    {
        $user = auth()->user();

        try {
            $checkout = $user->newSubscription('default', config('cashier.price_id'))
                ->checkout([
                    'success_url' => route('app'),
                    'cancel_url' => route('subscription.create'),
                ]);

            return redirect($checkout->url);
        } catch (\Exception $exception) {
            session()->flash('error', 'Erro ao processar o pagamento: ' . $exception->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.subscription.create');
    }
}
