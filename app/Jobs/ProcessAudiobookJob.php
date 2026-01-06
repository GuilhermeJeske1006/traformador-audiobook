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

            $pdfPath = Storage::disk('public')->path($this->audiobook->pdf_path);

            $text = $pdfExtractor->extractText($pdfPath);

            $this->audiobook->update([
                'extracted_text' => $text,
                'total_characters' => strlen($text),
                'processing_progress' => 40,
            ]);

            $audioFilename = 'audiobook_' . $this->audiobook->id . '_' . time() . '.mp3';
            $audioPath = $ttsService->convertTextToSpeech($text, $audioFilename);

            $this->audiobook->update([
                'audio_path' => $audioPath,
                'status' => 'completed',
                'processing_progress' => 100,
            ]);

            Log::info('Audiobook processed successfully', [
                'audiobook_id' => $this->audiobook->id,
            ]);
        } catch (\Exception $e) {
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
