<?php

namespace App\Jobs;

use App\Models\Audiobook;
use App\Services\PdfTextExtractorService;
use App\Services\TextToSpeechService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessAudiobookJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600;

    public function __construct(
        public Audiobook $audiobook
    ) {
        ini_set('memory_limit', '512M');
    }

    public function handle(
        PdfTextExtractorService $pdfExtractor,
        TextToSpeechService $ttsService
    ): void {
        try {
            $this->audiobook->update([
                'status' => 'processing',
                'processing_progress' => 10,
            ]);

            // Obtém o conteúdo do PDF do storage (MinIO)
            $disk = config('filesystems.default');
            Log::info('Buscando PDF do storage', [
                'disk' => $disk,
                'path' => $this->audiobook->pdf_path,
            ]);

            $pdfContent = Storage::disk($disk)->get($this->audiobook->pdf_path);
            $pdfPath = sys_get_temp_dir() . '/' . uniqid('pdf_') . '.pdf';
            file_put_contents($pdfPath, $pdfContent);

            Log::info('PDF temporário criado', [
                'temp_path' => $pdfPath,
                'original_path' => $this->audiobook->pdf_path,
                'file_exists' => file_exists($pdfPath),
                'file_size' => filesize($pdfPath),
            ]);

            $text = $pdfExtractor->extractText($pdfPath);

            $this->audiobook->update([
                'extracted_text' => $text,
                'total_characters' => strlen($text),
                'processing_progress' => 40,
            ]);

            $audioFilename = 'audiobook_' . $this->audiobook->id . '_' . time() . '.mp3';

            // Gera áudio com timecodes para sincronização precisa de legendas
            $voiceName = $this->audiobook->voice_name ?? 'pt-BR-Standard-A';
            $result = $ttsService->convertTextToSpeechWithTimecodes($text, $audioFilename, $voiceName);

            $this->audiobook->update([
                'audio_path' => $result['audio_path'],
                'timecodes' => $result['timecodes'],
                'status' => 'completed',
                'processing_progress' => 100,
            ]);

            Log::info('Audiobook processed successfully', [
                'audiobook_id' => $this->audiobook->id,
            ]);

            // Limpa arquivo temporário
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        } catch (\Exception $e) {
            // Limpa arquivo temporário em caso de erro
            if (isset($pdfPath) && file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            $this->audiobook->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Failed to process audiobook', [
                'audiobook_id' => $this->audiobook->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
