<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = ['application_id', 'recruiter_id', 'candidate_email', 'date_time', 'type', 'outcome', 'link'];

    protected $casts = [
        'date_time' => 'datetime',
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
