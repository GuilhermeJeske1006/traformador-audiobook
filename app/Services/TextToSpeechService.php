<?php

namespace App\Services;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\TimePointType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TextToSpeechService
{
    private TextToSpeechClient $client;

    public function __construct()
    {
        $this->client = new TextToSpeechClient([
            'credentials' => config('services.google.credentials_path'),
        ]);
    }

    public function convertTextToSpeech(string $text, string $outputFilename): string
    {
        $chunks = $this->splitTextIntoChunks($text, 4500);
        $path = 'audios/' . $outputFilename;

        // Coleta todos os chunks de áudio em memória temporária
        $audioContent = '';

        try {
            foreach ($chunks as $chunk) {
                $synthesisInput = new SynthesisInput();
                $synthesisInput->setText($chunk);

                $voice = new VoiceSelectionParams();
                $voice->setLanguageCode('pt-BR');
                $voice->setSsmlGender(SsmlVoiceGender::NEUTRAL);

                $audioConfig = new AudioConfig();
                $audioConfig->setAudioEncoding(AudioEncoding::MP3);
                $audioConfig->setSpeakingRate(1.0);
                $audioConfig->setPitch(0.0);

                $request = new SynthesizeSpeechRequest();
                $request->setInput($synthesisInput);
                $request->setVoice($voice);
                $request->setAudioConfig($audioConfig);

                $response = $this->client->synthesizeSpeech($request);

                // Acumula o conteúdo de áudio
                $audioContent .= $response->getAudioContent();
                unset($response);
                gc_collect_cycles();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Falha ao gerar áudio: " . $e->getMessage());
        }

        // Salva o arquivo usando o Storage padrão (suporta local ou S3/MinIO)
        Storage::put($path, $audioContent);

        return $path;
    }

    private function splitTextIntoChunks(string $text, int $maxChars): array
    {
        $chunks = [];
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk) + strlen($sentence) > $maxChars) {
                if ($currentChunk !== '') {
                    $chunks[] = $currentChunk;
                    $currentChunk = '';
                }

                if (strlen($sentence) > $maxChars) {
                    $words = explode(' ', $sentence);
                    foreach ($words as $word) {
                        if (strlen($currentChunk) + strlen($word) + 1 > $maxChars) {
                            $chunks[] = $currentChunk;
                            $currentChunk = $word;
                        } else {
                            $currentChunk .= ($currentChunk ? ' ' : '') . $word;
                        }
                    }
                } else {
                    $currentChunk = $sentence;
                }
            } else {
                $currentChunk .= ($currentChunk ? ' ' : '') . $sentence;
            }
        }

        if ($currentChunk !== '') {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }

    /**
     * Converte texto para fala e retorna timecodes para sincronização de legendas
     *
     * @param string $text Texto completo
     * @param string $outputFilename Nome do arquivo de saída
     * @param string $voiceName Nome da voz do Google TTS
     * @return array ['audio_path' => string, 'timecodes' => array]
     */
    public function convertTextToSpeechWithTimecodes(string $text, string $outputFilename, string $voiceName = 'pt-BR-Standard-A'): array
    {
        // Divide em sentenças individuais para timecodes precisos
        $sentences = $this->extractSentences($text);
        $path = 'audios/' . $outputFilename;

        $allTimecodes = [];
        $cumulativeTime = 0.0;
        $audioContent = '';

        try {
            // Agrupa sentenças em chunks para evitar muitas chamadas à API
            $chunks = $this->groupSentencesIntoChunks($sentences, 4500);

            foreach ($chunks as $chunkData) {
                $chunkText = $chunkData['text'];
                $chunkSentences = $chunkData['sentences'];

                $synthesisInput = new SynthesisInput();
                $synthesisInput->setText($chunkText);

                $voice = new VoiceSelectionParams();
                $voice->setLanguageCode('pt-BR');
                $voice->setName($voiceName);

                $audioConfig = new AudioConfig();
                $audioConfig->setAudioEncoding(AudioEncoding::MP3);
                $audioConfig->setSpeakingRate(1.0);
                $audioConfig->setPitch(0.0);

                $request = new SynthesizeSpeechRequest();
                $request->setInput($synthesisInput);
                $request->setVoice($voice);
                $request->setAudioConfig($audioConfig);

                $response = $this->client->synthesizeSpeech($request);

                $chunkAudio = $response->getAudioContent();

                // Acumula áudio
                $audioContent .= $chunkAudio;

                // Calcula duração real do chunk
                $tempFile = tempnam(sys_get_temp_dir(), 'audio_chunk_');
                file_put_contents($tempFile, $chunkAudio);
                $chunkDuration = $this->getAudioDurationFromFile($tempFile);
                unlink($tempFile);

                // Distribui tempo proporcionalmente entre as sentenças do chunk
                $totalChunkChars = strlen($chunkText);
                $chunkStartTime = $cumulativeTime;

                foreach ($chunkSentences as $index => $sentence) {
                    $sentenceChars = strlen($sentence);
                    $sentenceDuration = ($sentenceChars / $totalChunkChars) * $chunkDuration;

                    // Adiciona margem de 0.3s para sincronização mais natural
                    // A legenda aparece ligeiramente depois e dura um pouco mais
                    $startDelay = 0.3;
                    $endExtension = 0.5;

                    $allTimecodes[] = [
                        'text' => $sentence,
                        'start_time' => $cumulativeTime + $startDelay,
                        'end_time' => $cumulativeTime + $sentenceDuration + $endExtension,
                    ];

                    $cumulativeTime += $sentenceDuration;
                }

                unset($response, $chunkAudio);
                gc_collect_cycles();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Falha ao gerar áudio com timecodes: " . $e->getMessage());
        }

        // Salva o arquivo usando o Storage padrão
        Storage::put($path, $audioContent);

        // Calcula duração total real do arquivo final
        $tempFile = tempnam(sys_get_temp_dir(), 'audio_final_');
        file_put_contents($tempFile, $audioContent);
        $totalDuration = $this->getAudioDurationFromFile($tempFile);
        unlink($tempFile);

        // Ajusta último timecode para duração total real
        if (count($allTimecodes) > 0) {
            $allTimecodes[count($allTimecodes) - 1]['end_time'] = $totalDuration;
        }

        Log::info('Áudio gerado com timecodes sincronizados', [
            'total_sentences' => count($allTimecodes),
            'total_duration' => $totalDuration,
            'avg_sentence_duration' => $totalDuration / max(1, count($allTimecodes)),
        ]);

        return [
            'audio_path' => $path,
            'timecodes' => $allTimecodes,
        ];
    }

    /**
     * Extrai sentenças individuais do texto
     */
    private function extractSentences(string $text): array
    {
        // Divide por pontuação mantendo as sentenças completas
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Limpa e filtra sentenças vazias
        return array_values(array_filter(array_map('trim', $sentences)));
    }

    /**
     * Agrupa sentenças em chunks para otimizar chamadas à API
     */
    private function groupSentencesIntoChunks(array $sentences, int $maxChars): array
    {
        $chunks = [];
        $currentChunk = '';
        $currentSentences = [];

        foreach ($sentences as $sentence) {
            // Se adicionar esta sentença ultrapassar o limite
            if (strlen($currentChunk) + strlen($sentence) + 1 > $maxChars && $currentChunk !== '') {
                $chunks[] = [
                    'text' => trim($currentChunk),
                    'sentences' => $currentSentences,
                ];
                $currentChunk = '';
                $currentSentences = [];
            }

            $currentChunk .= ($currentChunk ? ' ' : '') . $sentence;
            $currentSentences[] = $sentence;
        }

        // Adiciona último chunk
        if ($currentChunk !== '') {
            $chunks[] = [
                'text' => trim($currentChunk),
                'sentences' => $currentSentences,
            ];
        }

        return $chunks;
    }

    /**
     * Divide texto em sentenças preservando pontuação
     */
    private function splitTextIntoSentences(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Agrupa sentenças em chunks de até 5000 chars
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk) + strlen($sentence) > 5000) {
                if ($currentChunk !== '') {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                }

                // Sentença muito longa - divide por palavras
                if (strlen($sentence) > 5000) {
                    $words = explode(' ', $sentence);
                    foreach ($words as $word) {
                        if (strlen($currentChunk) + strlen($word) + 1 > 5000) {
                            $chunks[] = trim($currentChunk);
                            $currentChunk = $word;
                        } else {
                            $currentChunk .= ($currentChunk ? ' ' : '') . $word;
                        }
                    }
                } else {
                    $currentChunk = $sentence;
                }
            } else {
                $currentChunk .= ($currentChunk ? ' ' : '') . $sentence;
            }
        }

        if ($currentChunk !== '') {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    /**
     * Obtém duração de arquivo de áudio usando FFprobe
     */
    private function getAudioDurationFromFile(string $filePath): float
    {
        $command = sprintf(
            'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s',
            escapeshellarg($filePath)
        );

        $output = [];
        exec($command, $output);

        return (float) ($output[0] ?? 0);
    }

    public function __destruct()
    {
        $this->client->close();
    }
}
