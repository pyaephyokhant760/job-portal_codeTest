<?php

namespace App\Pipelines\Interview;

use App\Notifications\InterviewInvitation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Closure;

class SendInterviewNotification
{
    public function handle($interview, Closure $next)
    {
        // Log::info('Before sending email', ['interview' => $interview->toArray()]);

        // Send the notification
        if ($interview->candidate_email) {
            \Illuminate\Support\Facades\Notification::route('mail', $interview->candidate_email)
                ->notify(new \App\Notifications\InterviewInvitation($interview));
        }

        // Pass the interview to the next pipe (or the 'then' closure)
        return $next($interview);
    }
}
