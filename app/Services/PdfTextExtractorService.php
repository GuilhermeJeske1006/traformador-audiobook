<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfTextExtractorService
{
    public function extractText(string $pdfPath): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        $text = $this->cleanText($text);
        $text = $this->removePreContent($text);

        return $text;
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }

    private function removePreContent(string $text): string
    {
        $markers = [
            'introdução',
            'introducao',
            'capítulo 1',
            'capitulo 1',
            'capítulo i',
            'capitulo i',
            'cap. 1',
            'cap. i',
            'parte i',
            'parte 1',
            'chapter 1',
            'chapter i',
        ];

        $textLower = mb_strtolower($text);

        foreach ($markers as $marker) {
            $position = mb_stripos($textLower, $marker);

            if ($position !== false) {
                $foundMarker = mb_substr($text, $position, mb_strlen($marker));

                $startPosition = $position;
                while ($startPosition > 0 && mb_substr($text, $startPosition - 1, 1) !== "\n") {
                    $startPosition--;
                }

                return mb_substr($text, $startPosition);
            }
        }

        return $text;
    }
}
