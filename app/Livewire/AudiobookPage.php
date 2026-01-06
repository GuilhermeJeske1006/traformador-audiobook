<?php

namespace App\Livewire;

use Livewire\Component;

class AudiobookPage extends Component
{
    public function render()
    {
        return view('livewire.audiobook-page')->with('layout', 'components.layouts.app');
    }
}
