<?php

namespace App\Livewire;

use App\Models\Audiobook;
use Livewire\Component;

class AudiobookCustomization extends Component
{
    public $audiobookId;
    public $voiceName;
    public $videoBackgroundType;
    public $videoBackgroundColor;
    public $subtitleStyle;
    public $subtitleFontSize;

    // Vozes disponíveis do Google TTS
    public $availableVoices = [
        'pt-BR-Standard-A' => 'Voz Feminina - Padrão A (Natural)',
        'pt-BR-Standard-B' => 'Voz Masculina - Padrão B (Natural)',
        'pt-BR-Standard-C' => 'Voz Feminina - Padrão C (Natural)',
        'pt-BR-Wavenet-A' => 'Voz Feminina - WaveNet A (Premium)',
        'pt-BR-Wavenet-B' => 'Voz Masculina - WaveNet B (Premium)',
        'pt-BR-Wavenet-C' => 'Voz Feminina - WaveNet C (Premium)',
        'pt-BR-Neural2-A' => 'Voz Feminina - Neural2 A (Melhor Qualidade)',
        'pt-BR-Neural2-B' => 'Voz Masculina - Neural2 B (Melhor Qualidade)',
        'pt-BR-Neural2-C' => 'Voz Feminina - Neural2 C (Melhor Qualidade)',
    ];

    public $backgroundColors = [
        '#1e3a8a' => 'Azul Escuro',
        '#1e40af' => 'Azul',
        '#7c3aed' => 'Roxo',
        '#db2777' => 'Rosa',
        '#059669' => 'Verde',
        '#dc2626' => 'Vermelho',
        '#ea580c' => 'Laranja',
        '#000000' => 'Preto',
        '#374151' => 'Cinza Escuro',
    ];

    public $subtitleStyles = [
        'default' => 'Padrão (Texto branco com sombra)',
        'bold' => 'Negrito (Texto mais forte)',
        'outline' => 'Contorno (Borda ao redor do texto)',
        'box' => 'Caixa (Fundo semi-transparente)',
    ];

    public function mount($audiobookId)
    {
        $audiobook = Audiobook::where('user_id', auth()->id())->findOrFail($audiobookId);

        $this->audiobookId = $audiobookId;
        $this->voiceName = $audiobook->voice_name ?? 'pt-BR-Standard-A';
        $this->videoBackgroundType = $audiobook->video_background_type ?? 'gradient';
        $this->videoBackgroundColor = $audiobook->video_background_color ?? '#1e3a8a';
        $this->subtitleStyle = $audiobook->subtitle_style ?? 'default';
        $this->subtitleFontSize = $audiobook->subtitle_font_size ?? 24;
    }

    public function save()
    {
        $this->validate([
            'voiceName' => 'required|string',
            'videoBackgroundType' => 'required|in:gradient,solid',
            'videoBackgroundColor' => 'required|string',
            'subtitleStyle' => 'required|in:default,bold,outline,box',
            'subtitleFontSize' => 'required|integer|min:16|max:48',
        ]);

        $audiobook = Audiobook::where('user_id', auth()->id())->findOrFail($this->audiobookId);

        $audiobook->update([
            'voice_name' => $this->voiceName,
            'video_background_type' => $this->videoBackgroundType,
            'video_background_color' => $this->videoBackgroundColor,
            'subtitle_style' => $this->subtitleStyle,
            'subtitle_font_size' => $this->subtitleFontSize,
        ]);

        session()->flash('success', 'Personalização salva com sucesso!');

        $this->dispatch('customization-saved');
    }

    public function render()
    {
        return view('livewire.audiobook-customization');
    }
}
