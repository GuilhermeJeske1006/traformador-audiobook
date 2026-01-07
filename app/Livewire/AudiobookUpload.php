<?php

namespace App\Livewire;

use App\Jobs\ProcessAudiobookJob;
use App\Models\Audiobook;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AudiobookUpload extends Component
{
    use WithFileUploads;

    public $pdfFile;
    public $title = '';
    public $uploading = false;

    // Opções de personalização de áudio
    public $voiceName = 'pt-BR-Standard-A';

    protected $rules = [
        'title' => 'required|string|max:255',
        'pdfFile' => 'required|file|mimes:pdf|max:51200',
        'voiceName' => 'required|string',
    ];

    protected $messages = [
        'title.required' => 'O título é obrigatório.',
        'pdfFile.required' => 'Selecione um arquivo PDF.',
        'pdfFile.mimes' => 'O arquivo deve ser um PDF.',
        'pdfFile.max' => 'O arquivo não pode ser maior que 50MB.',
    ];

    public function updatedPdfFile()
    {
        $this->validate([
            'pdfFile' => 'required|file|mimes:pdf|max:51200',
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->uploading = true;

        try {
            $filename = time() . '_' . $this->pdfFile->getClientOriginalName();
            $path = $this->pdfFile->storeAs('pdfs', $filename, 'public');

            $audiobook = Audiobook::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'original_filename' => $this->pdfFile->getClientOriginalName(),
                'pdf_path' => $path,
                'status' => 'pending',
                'voice_name' => $this->voiceName,
            ]);

            ProcessAudiobookJob::dispatch($audiobook);

            session()->flash('success', 'PDF enviado com sucesso! O processamento foi iniciado.');

            $this->reset(['pdfFile', 'title']);

            $this->dispatch('audiobook-uploaded');
        } catch (\Exception $e) {
            Log::error('Erro ao enviar arquivo PDF: ' . $e->getMessage());
            session()->flash('error', 'Erro ao enviar arquivo: ' . $e->getMessage());
        } finally {
            $this->uploading = false;
        }
    }

    public function getAvailableVoicesProperty()
    {
        return [
            'pt-BR-Standard-A' => 'Voz Feminina - Padrão A',
            'pt-BR-Standard-B' => 'Voz Masculina - Padrão B',
            'pt-BR-Standard-C' => 'Voz Feminina - Padrão C',
            'pt-BR-Wavenet-A' => 'Voz Feminina - WaveNet A (Premium)',
            'pt-BR-Wavenet-B' => 'Voz Masculina - WaveNet B (Premium)',
            'pt-BR-Neural2-A' => 'Voz Feminina - Neural2 A (Melhor)',
            'pt-BR-Neural2-B' => 'Voz Masculina - Neural2 B (Melhor)',
        ];
    }

    public function render()
    {
        return view('livewire.audiobook-upload');
    }
}
