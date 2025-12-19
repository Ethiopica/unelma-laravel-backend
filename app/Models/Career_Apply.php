<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Career_Apply extends Model
{
    protected $table = 'career_applied';
    protected $guarded = [];

    /**
     * Get the career/job position this application is for
     */
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Get the user who submitted this application (if registered)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all replies sent to this applicant
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ApplicantReply::class, 'career_apply_id');
    }
}
