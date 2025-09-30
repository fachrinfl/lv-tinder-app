<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Person;
use App\Models\Interaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckPopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'popularity:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for people who have reached popularity threshold (>50 likes) and send notification emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking popularity threshold...');
        
        $people = Person::all();
        $popularPeople = collect();
        
        foreach ($people as $person) {
            $totalLikes = $person->likes()->count();
            
            if ($totalLikes >= 51 && 
                (is_null($person->popularity_notified_at) || $person->last_notified_like_count < $totalLikes)) {
                
                $person->total_likes = $totalLikes;
                $popularPeople->push($person);
            }
        }

        $notifiedCount = 0;

        foreach ($popularPeople as $person) {
            try {
                $this->sendPopularityNotification($person);
                
                Person::where('id', $person->id)->update([
                    'popularity_notified_at' => now(),
                    'last_notified_like_count' => $person->total_likes
                ]);
                
                $notifiedCount++;
                
                $this->info("Notified about {$person->name} ({$person->location}) - {$person->total_likes} likes");
                
            } catch (\Exception $e) {
                Log::error("Failed to send popularity notification for person {$person->id}: " . $e->getMessage());
                $this->error("Failed to notify about {$person->name}: " . $e->getMessage());
            }
        }

        $this->info("Popularity check completed. Notified about {$notifiedCount} people.");
        
        return Command::SUCCESS;
    }

    private function sendPopularityNotification(Person $person)
    {
        $adminEmail = config('app.admin_email');
        
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

        try {
                   Mail::raw($message, function ($mail) use ($adminEmail, $subject) {
                       $mail->to($adminEmail)
                            ->subject($subject)
                            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                   });
            
            Log::info("Popularity notification email sent", [
                'to' => $adminEmail,
                'subject' => $subject,
                'person_id' => $person->id,
                'person_name' => $person->name,
                'total_likes' => $person->total_likes,
                'from' => env('MAIL_FROM_ADDRESS')
            ]);
            
            $this->info("✅ Email sent to: {$adminEmail}");
            
        } catch (\Exception $e) {
            Log::error("Failed to send popularity notification email", [
                'to' => $adminEmail,
                'person_id' => $person->id,
                'error' => $e->getMessage()
            ]);
            
            $this->error("❌ Failed to send email: " . $e->getMessage());
            throw $e;
        }
    }
}