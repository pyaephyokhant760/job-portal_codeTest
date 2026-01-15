<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = ['application_id','recruiter_id','date_time','type','outcome','link'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
