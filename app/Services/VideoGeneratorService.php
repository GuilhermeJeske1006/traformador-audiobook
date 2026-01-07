<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoGeneratorService
{
    /**
     * Gera um vídeo MP4 a partir de um arquivo de áudio com legendas fixas
     *
     * @param string $audioPath Caminho relativo do áudio (ex: audiobooks/file.mp3)
     * @param string $text Texto completo para gerar legendas (usado se timecodes não fornecidos)
     * @param string $title Título do audiobook
     * @param string $outputFilename Nome do arquivo de saída
     * @param array|null $timecodes Array de timecodes com sincronização exata
     * @return string Caminho relativo do vídeo gerado
     */
    public function generateVideoWithSubtitles(
        string $audioPath,
        string $text,
        string $title,
        string $outputFilename,
        ?array $timecodes = null,
        array $customization = []
    ): string {
        // Verifica se FFmpeg está disponível
        $this->checkFFmpegAvailable();

        // Caminhos completos
        $audioFullPath = Storage::disk('public')->path($audioPath);
        $videoPath = 'videos/' . $outputFilename;
        $videoFullPath = Storage::disk('public')->path($videoPath);

        // Gera SRT com timecodes reais ou calculados
        if ($timecodes) {
            $srtPath = $this->generateSrtFileFromTimecodes($timecodes);
            Log::info('Gerando vídeo com timecodes sincronizados', [
                'total_subtitles' => count($timecodes),
            ]);
        } else {
            $srtPath = $this->generateSrtFile($text, $audioFullPath);
            Log::info('Gerando vídeo com timecodes estimados');
        }

        // Cria o diretório se não existir
        $directory = dirname($videoFullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        try {
            // Gera vídeo com legendas fixas usando FFmpeg
            $this->generateVideo($audioFullPath, $srtPath, $videoFullPath, $title, $customization);

            return $videoPath;
        } finally {
            // Remove arquivo SRT temporário
            if (file_exists($srtPath)) {
                unlink($srtPath);
            }
        }
    }

    /**
     * Gera arquivo SRT a partir de timecodes sincronizados
     */
    private function generateSrtFileFromTimecodes(array $timecodes): string
    {
        $srt = '';
        $index = 1;

        foreach ($timecodes as $timecode) {
            $text = $timecode['text'] ?? '';
            $startTime = $timecode['start_time'] ?? 0;
            $endTime = $timecode['end_time'] ?? $startTime + 5;

            // Limita o tamanho da legenda para melhor visualização
            $lines = $this->splitSubtitleLine($text, 42);

            $srt .= "{$index}\n";
            $srt .= $this->formatSrtTime($startTime) . " --> " . $this->formatSrtTime($endTime) . "\n";
            $srt .= implode("\n", $lines) . "\n\n";

            $index++;
        }

        // Gera arquivo SRT temporário
        $srtPath = sys_get_temp_dir() . '/' . uniqid('subtitle_sync_') . '.srt';
        file_put_contents($srtPath, $srt);

        Log::info('Arquivo SRT gerado com timecodes', [
            'path' => $srtPath,
            'total_entries' => count($timecodes),
        ]);

        return $srtPath;
    }

    /**
     * Verifica se o FFmpeg está instalado
     */
    private function checkFFmpegAvailable(): void
    {
        $output = [];
        $returnVar = 0;
        exec('ffmpeg -version 2>&1', $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \RuntimeException(
                'FFmpeg não está instalado ou não está disponível no PATH. ' .
                'Por favor, instale o FFmpeg para gerar vídeos.'
            );
        }
    }

    /**
     * Gera arquivo SRT (legendas) a partir do texto
     */
    private function generateSrtFile(string $text, string $audioPath): string
    {
        // Calcula duração do áudio usando FFprobe
        $duration = $this->getAudioDuration($audioPath);

        // Divide o texto em legendas com timing
        $subtitles = $this->createSubtitles($text, $duration);

        // Gera arquivo SRT temporário
        $srtPath = sys_get_temp_dir() . '/' . uniqid('subtitle_') . '.srt';
        file_put_contents($srtPath, $subtitles);

        return $srtPath;
    }

    /**
     * Obtém a duração do áudio em segundos usando FFprobe
     */
    private function getAudioDuration(string $audioPath): float
    {
        $command = sprintf(
            'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s',
            escapeshellarg($audioPath)
        );

        $output = [];
        exec($command, $output);

        return (float) ($output[0] ?? 0);
    }

    /**
     * Cria conteúdo do arquivo SRT com base no texto e duração
     */
    private function createSubtitles(string $text, float $totalDuration): string
    {
        // Divide o texto em sentenças
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        if (empty($sentences)) {
            return '';
        }

        // Calcula tempo por sentença
        $timePerSentence = $totalDuration / count($sentences);

        $srt = '';
        $index = 1;
        $currentTime = 0;

        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (empty($sentence)) {
                continue;
            }

            // Limita o tamanho da legenda para melhor visualização
            $lines = $this->splitSubtitleLine($sentence, 42);

            $startTime = $this->formatSrtTime($currentTime);
            $currentTime += $timePerSentence;
            $endTime = $this->formatSrtTime($currentTime);

            $srt .= "{$index}\n";
            $srt .= "{$startTime} --> {$endTime}\n";
            $srt .= implode("\n", $lines) . "\n\n";

            $index++;
        }

        return $srt;
    }

    /**
     * Divide uma linha longa em múltiplas linhas para legenda
     */
    private function splitSubtitleLine(string $text, int $maxChars): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if (strlen($currentLine) + strlen($word) + 1 <= $maxChars) {
                $currentLine .= ($currentLine ? ' ' : '') . $word;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
        }

        // Limita a 2 linhas por legenda
        if (count($lines) > 2) {
            return [
                implode(' ', array_slice($lines, 0, ceil(count($lines) / 2))),
                implode(' ', array_slice($lines, ceil(count($lines) / 2)))
            ];
        }

        return $lines;
    }

    /**
     * Formata tempo em formato SRT (00:00:00,000)
     */
    private function formatSrtTime(float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        $millis = round(($seconds - floor($seconds)) * 1000);

        return sprintf('%02d:%02d:%02d,%03d', $hours, $minutes, $secs, $millis);
    }

    /**
     * Gera o vídeo usando FFmpeg
     */
    private function generateVideo(string $audioPath, string $srtPath, string $outputPath, string $title, array $customization = []): void
    {
        // Personalizações
        $bgType = $customization['background_type'] ?? 'gradient';
        $bgColor = $customization['background_color'] ?? '#1e3a8a';
        $subtitleStyleType = $customization['subtitle_style'] ?? 'default';
        $fontSize = $customization['subtitle_font_size'] ?? 24;

        // Define estilo da legenda baseado na seleção
        $subtitleStyle = $this->getSubtitleStyle($subtitleStyleType, $fontSize);

        // Define background baseado no tipo
        $duration = $this->getAudioDuration($audioPath);
        $backgroundFilter = $this->getBackgroundFilter($bgType, $bgColor, $duration);

        // Comando FFmpeg personalizado
        $command = sprintf(
            'ffmpeg -f lavfi -i %s -i %s -filter_complex "[0:v]drawtext=fontfile=/System/Library/Fonts/Supplemental/Arial.ttf:text=%s:fontcolor=white:fontsize=36:x=(w-text_w)/2:y=30:shadowcolor=black:shadowx=2:shadowy=2,subtitles=%s:force_style=\'%s\'" -c:v libx264 -c:a aac -b:a 192k -shortest -pix_fmt yuv420p -movflags +faststart %s 2>&1',
            $backgroundFilter,
            escapeshellarg($audioPath),
            escapeshellarg(addslashes($title)),
            escapeshellarg($srtPath),
            $subtitleStyle,
            escapeshellarg($outputPath)
        );

        Log::info('Executando comando FFmpeg', ['command' => $command]);

        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            Log::error('Erro ao gerar vídeo', [
                'command' => $command,
                'output' => implode("\n", $output),
                'return_code' => $returnVar
            ]);

            throw new \RuntimeException(
                'Erro ao gerar vídeo com FFmpeg: ' . implode("\n", $output)
            );
        }

        if (!file_exists($outputPath)) {
            throw new \RuntimeException('Vídeo não foi gerado: arquivo de saída não encontrado');
        }

        Log::info('Vídeo gerado com sucesso', ['path' => $outputPath]);
    }

    /**
     * Retorna o filtro de background baseado no tipo
     */
    private function getBackgroundFilter(string $type, string $color, float $duration): string
    {
        // Converte cor de # para 0x
        $ffmpegColor = '0x' . str_replace('#', '', $color);

        if ($type === 'gradient') {
            // Gradiente simples
            $color1 = $ffmpegColor;
            $color2 = $this->darkenColor($color, 30);
            return sprintf('color=c=%s:s=1280x720:d=%s', $color1, $duration);
        }

        // Cor sólida
        return sprintf('color=c=%s:s=1280x720:d=%s', $ffmpegColor, $duration);
    }

    /**
     * Escurece uma cor hexadecimal
     */
    private function darkenColor(string $hex, int $percent): string
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, $r - ($r * $percent / 100));
        $g = max(0, $g - ($g * $percent / 100));
        $b = max(0, $b - ($b * $percent / 100));

        return sprintf('0x%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Retorna o estilo de legenda baseado na seleção
     */
    private function getSubtitleStyle(string $style, int $fontSize): string
    {
        $baseStyle = "FontName=Arial,FontSize={$fontSize},PrimaryColour=&H00FFFFFF,MarginV=20";

        switch ($style) {
            case 'bold':
                return $baseStyle . ",Bold=1,Outline=2,Shadow=1";

            case 'outline':
                return $baseStyle . ",OutlineColour=&H00000000,Outline=3,Shadow=0";

            case 'box':
                return $baseStyle . ",BackColour=&HA0000000,BorderStyle=4,Outline=1,Shadow=0";

            case 'default':
            default:
                return $baseStyle . ",OutlineColour=&H00000000,BackColour=&H80000000,BorderStyle=4,Outline=2,Shadow=0";
        }
    }
}
