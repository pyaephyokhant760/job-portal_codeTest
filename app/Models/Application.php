<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id','user_id','cv_path','status','score'];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
}
