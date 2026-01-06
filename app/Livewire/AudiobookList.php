<?php

namespace App\Livewire;

use App\Models\Audiobook;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AudiobookList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    #[On('audiobook-uploaded')]
    public function refreshList()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $audiobook = Audiobook::where('user_id', auth()->id())->findOrFail($id);

        if ($audiobook->pdf_path && \Storage::disk('public')->exists($audiobook->pdf_path)) {
            \Storage::disk('public')->delete($audiobook->pdf_path);
        }

        if ($audiobook->audio_path && \Storage::disk('public')->exists($audiobook->audio_path)) {
            \Storage::disk('public')->delete($audiobook->audio_path);
        }

        $audiobook->delete();

        session()->flash('success', 'Audiobook excluído com sucesso!');
    }

    public function render()
    {
        $audiobooks = Audiobook::query()
            ->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('original_filename', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.audiobook-list', [
            'audiobooks' => $audiobooks,
        ]);
    }
}
