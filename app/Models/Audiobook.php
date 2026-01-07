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
        'timecodes',
        'voice_name',
        'video_background_type',
        'video_background_color',
        'video_background_image',
        'subtitle_style',
        'subtitle_font_size',
        'status',
        'total_characters',
        'processing_progress',
        'error_message',
        'video_path',
        'video_status',
        'video_progress',
        'video_error_message',
    ];

    protected $casts = [
        'total_characters' => 'integer',
        'processing_progress' => 'integer',
        'video_progress' => 'integer',
        'subtitle_font_size' => 'integer',
        'timecodes' => 'array',
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

    public function hasVideo(): bool
    {
        return $this->video_status === 'completed' && $this->video_path !== null;
    }

    public function isVideoProcessing(): bool
    {
        return $this->video_status === 'processing';
    }

    public function isVideoFailed(): bool
    {
        return $this->video_status === 'failed';
    }

    public function canGenerateVideo(): bool
    {
        return $this->isCompleted() && !$this->isVideoProcessing();
    }

    public function generateVideo(): void
    {
        if (!$this->canGenerateVideo()) {
            throw new \RuntimeException('Não é possível gerar vídeo para este audiobook no momento.');
        }

        $this->update([
            'video_status' => 'pending',
            'video_progress' => 0,
            'video_error_message' => null,
        ]);

        \App\Jobs\ProcessVideoJob::dispatch($this);
    }
}
