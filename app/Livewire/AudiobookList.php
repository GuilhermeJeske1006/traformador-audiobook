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
    public $hasProcessingAudiobooks = false;

    // Modal de personalização de vídeo
    public $showVideoCustomizationModal = false;
    public $selectedAudiobookId = null;
    public $videoBackgroundType = 'gradient';
    public $videoBackgroundColor = '#1e3a8a';
    public $subtitleStyle = 'default';
    public $subtitleFontSize = 24;

    protected $queryString = ['search'];

    #[On('audiobook-uploaded')]
    public function refreshList()
    {
        $this->resetPage();
        $this->checkProcessingStatus();
    }

    public function mount()
    {
        $this->checkProcessingStatus();
    }

    public function checkProcessingStatus()
    {
        $this->hasProcessingAudiobooks = Audiobook::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('status', 'processing')
                    ->orWhere('status', 'pending')
                    ->orWhere('video_status', 'processing')
                    ->orWhere('video_status', 'pending');
            })
            ->exists();
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

        if ($audiobook->video_path && \Storage::disk('public')->exists($audiobook->video_path)) {
            \Storage::disk('public')->delete($audiobook->video_path);
        }

        $audiobook->delete();

        session()->flash('success', 'Audiobook excluído com sucesso!');
    }

    public function openVideoCustomizationModal($id)
    {
        $this->selectedAudiobookId = $id;
        $audiobook = Audiobook::where('user_id', auth()->id())->findOrFail($id);

        // Carrega valores existentes ou usa padrões
        $this->videoBackgroundType = $audiobook->video_background_type ?? 'gradient';
        $this->videoBackgroundColor = $audiobook->video_background_color ?? '#1e3a8a';
        $this->subtitleStyle = $audiobook->subtitle_style ?? 'default';
        $this->subtitleFontSize = $audiobook->subtitle_font_size ?? 24;

        $this->showVideoCustomizationModal = true;
    }

    public function closeVideoCustomizationModal()
    {
        $this->showVideoCustomizationModal = false;
        $this->selectedAudiobookId = null;
    }

    public function generateVideoWithCustomization()
    {
        $this->validate([
            'videoBackgroundType' => 'required|in:gradient,solid',
            'videoBackgroundColor' => 'required|string',
            'subtitleStyle' => 'required|in:default,bold,outline,box',
            'subtitleFontSize' => 'required|integer|min:16|max:48',
        ]);

        $audiobook = Audiobook::where('user_id', auth()->id())->findOrFail($this->selectedAudiobookId);

        try {
            // Salva as personalizações no banco
            $audiobook->update([
                'video_background_type' => $this->videoBackgroundType,
                'video_background_color' => $this->videoBackgroundColor,
                'subtitle_style' => $this->subtitleStyle,
                'subtitle_font_size' => $this->subtitleFontSize,
            ]);

            $audiobook->generateVideo();

            session()->flash('success', 'Vídeo sendo gerado com suas personalizações! Isso pode levar alguns minutos.');
            $this->closeVideoCustomizationModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getBackgroundColorsProperty()
    {
        return [
            '#1e3a8a' => 'Azul Escuro',
            '#7c3aed' => 'Roxo',
            '#db2777' => 'Rosa',
            '#059669' => 'Verde',
            '#ea580c' => 'Laranja',
        ];
    }

    #[On('video-generated')]
    public function refreshVideoStatus()
    {
        // Força refresh da página
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
