<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Audiobook extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
