<?php

namespace App\Livewire;

use App\Models\Audiobook;
use Livewire\Component;

class AudiobookPage extends Component
{
    public $qtdAudiobooks = 0;

    public function mount()
    {
        $this->qtdAudiobooks = Audiobook::where('user_id', auth()->id())->count();
    }


    public function render()
    {
        return view('livewire.audiobook-page')->with('layout', 'components.layouts.app');
    }
}
