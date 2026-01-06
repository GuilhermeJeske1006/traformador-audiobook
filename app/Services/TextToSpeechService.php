<?php

namespace App\Services;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Illuminate\Support\Facades\Storage;

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
        $path = 'audiobooks/' . $outputFilename;
        $fullPath = Storage::disk('public')->path($path);

        // Cria o diretório se não existir
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Abre o arquivo para escrita
        $fileHandle = fopen($fullPath, 'wb');
        if ($fileHandle === false) {
            throw new \RuntimeException("Falha ao abrir arquivo para escrita: {$fullPath}");
        }

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

                // Escreve o chunk diretamente no arquivo e libera memória
                fwrite($fileHandle, $response->getAudioContent());
                unset($response);
                gc_collect_cycles();
            }
        } finally {
            fclose($fileHandle);
        }

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

    public function __destruct()
    {
        $this->client->close();
    }
}
