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

    protected $rules = [
        'title' => 'required|string|max:255',
        'pdfFile' => 'required|file|mimes:pdf|max:51200',
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

    public function render()
    {
        return view('livewire.audiobook-upload');
    }
}
