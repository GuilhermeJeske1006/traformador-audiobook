<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audiobook extends Model
{
    protected $fillable = [
        'title',
        'original_filename',
        'pdf_path',
        'audio_path',
        'extracted_text',
        'status',
        'total_characters',
        'processing_progress',
        'error_message',
    ];

    protected $casts = [
        'total_characters' => 'integer',
        'processing_progress' => 'integer',
    ];

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }
}
