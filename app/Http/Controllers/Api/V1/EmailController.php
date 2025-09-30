<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{

    private function sendPopularityNotification(Person $person, $recipientEmail)
    {
        $subject = "[Popular] {$person->name} from {$person->location} — {$person->total_likes} likes";
        
        $message = "
            POPULARITY ALERT!
            
            Someone is getting very popular on our app!
            
            Person Details:
            - Name: {$person->name}
            - Age: {$person->age}
            - Location: {$person->location}
            - Total Likes: {$person->total_likes}
            
            This person has reached the popularity threshold and might be trending!
            
            Best regards,
            Tinder App System
        ";

        Mail::raw($message, function ($mail) use ($recipientEmail, $subject) {
            $mail->to($recipientEmail)
                 ->subject($subject)
                 ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }
}
