<?php
namespace App\Pipelines\Job;

use Closure;
use Illuminate\Http\Request;

class FilterByTitle
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Closure $next
     * @return mixed
     */
    public function handle($query, Closure $next)
    {
        // when() သည် ပထမ parameter 'true' ဖြစ်မှသာ ဒုတိယ parameter (callback) ကို အလုပ်လုပ်စေပါသည်
        $query->when(request('title'), function ($q, $title) {
            $q->where('title', 'like', "%{$title}%");
        });

        return $next($query);
    }
}