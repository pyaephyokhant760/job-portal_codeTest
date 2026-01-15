<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
   protected $fillable = ['employer_id','title','description','category_id','location','status','expiry_date'];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
