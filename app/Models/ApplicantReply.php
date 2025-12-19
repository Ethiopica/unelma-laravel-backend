<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantReply extends Model
{
    protected $fillable = [
        'career_apply_id',
        'user_id',
        'sent_to_email',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the applicant this reply was sent to
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Career_Apply::class, 'career_apply_id');
    }

    /**
     * Get the admin user who sent this reply
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
