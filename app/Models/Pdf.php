<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'stored_name',
        'version',
        'parent_pdf_id',
        'is_current',
        'token',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentPdf()
    {
        return $this->belongsTo(Pdf::class, 'parent_pdf_id');
    }

    public function versions()
    {
        return $this->hasMany(Pdf::class, 'parent_pdf_id')->orderBy('version', 'desc');
    }

    public function currentVersion()
    {
        if ($this->parent_pdf_id) {
            return $this->parentPdf->versions()->where('is_current', true)->first();
        }
        return $this->versions()->where('is_current', true)->first() ?? $this;
    }

    public function allVersions()
    {
        if ($this->parent_pdf_id) {
            return $this->parentPdf->versions()->orderBy('version', 'desc')->get();
        }
        return $this->versions()->orderBy('version', 'desc')->get()->prepend($this);
    }

    public function getRootPdf()
    {
        if ($this->parent_pdf_id) {
            return $this->parentPdf;
        }
        return $this;
    }

    public function feedbacks()
    {
        return $this->hasMany(\App\Models\Feedback::class, 'pdf_id');
    }

    public function latestFeedback()
    {
        return $this->hasOne(\App\Models\Feedback::class, 'pdf_id')->latestOfMany();
    }
}
