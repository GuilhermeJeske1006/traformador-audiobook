<?php

namespace App\Jobs;

use App\Models\Audiobook;
use App\Services\VideoGeneratorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessVideoJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 7200; // 2 horas - vídeos podem demorar mais

    public function __construct(
        public Audiobook $audiobook
    ) {
        ini_set('memory_limit', '1024M'); // Mais memória para processamento de vídeo
    }

    public function handle(VideoGeneratorService $videoService): void
    {
        try {
            // Verifica se o audiobook tem áudio gerado
            if (!$this->audiobook->audio_path) {
                throw new \RuntimeException('Audiobook não possui áudio gerado. Gere o áudio primeiro.');
            }

            // Verifica se o audiobook tem texto extraído
            if (!$this->audiobook->extracted_text) {
                throw new \RuntimeException('Audiobook não possui texto extraído para gerar legendas.');
            }

            $this->audiobook->update([
                'video_status' => 'processing',
                'video_progress' => 10,
            ]);

            Log::info('Iniciando geração de vídeo', [
                'audiobook_id' => $this->audiobook->id,
                'title' => $this->audiobook->title,
            ]);

            $this->audiobook->update(['video_progress' => 30]);

            // Gera o vídeo com legendas sincronizadas
            $videoFilename = 'video_' . $this->audiobook->id . '_' . time() . '.mp4';

            // Usa timecodes salvos para sincronização perfeita, ou texto se não disponível
            $timecodes = $this->audiobook->timecodes ?? null;

            Log::info('Gerando vídeo', [
                'audiobook_id' => $this->audiobook->id,
                'has_timecodes' => $timecodes !== null,
                'timecodes_count' => $timecodes ? count($timecodes) : 0,
            ]);

            // Prepara personalização
            $customization = [
                'background_type' => $this->audiobook->video_background_type ?? 'gradient',
                'background_color' => $this->audiobook->video_background_color ?? '#1e3a8a',
                'subtitle_style' => $this->audiobook->subtitle_style ?? 'default',
                'subtitle_font_size' => $this->audiobook->subtitle_font_size ?? 24,
            ];

            $videoPath = $videoService->generateVideoWithSubtitles(
                $this->audiobook->audio_path,
                $this->audiobook->extracted_text,
                $this->audiobook->title,
                $videoFilename,
                $timecodes,
                $customization
            );

            $this->audiobook->update([
                'video_path' => $videoPath,
                'video_status' => 'completed',
                'video_progress' => 100,
            ]);

            Log::info('Vídeo gerado com sucesso', [
                'audiobook_id' => $this->audiobook->id,
                'video_path' => $videoPath,
            ]);
        } catch (\Exception $e) {
            $this->audiobook->update([
                'video_status' => 'failed',
                'video_error_message' => $e->getMessage(),
            ]);

            Log::error('Falha ao gerar vídeo', [
                'audiobook_id' => $this->audiobook->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
