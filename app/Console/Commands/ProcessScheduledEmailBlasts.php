<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailBlastJob;
use App\Models\EmailBlast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessScheduledEmailBlasts extends Command
{
    protected $signature = 'email:process-scheduled';
    protected $description = 'Process scheduled email blasts';

    public function handle()
    {
        try {
            $this->info('Starting to process scheduled email blasts...');

            $scheduledEmails = EmailBlast::where('status', EmailBlast::STATUS_SCHEDULED)
                ->where('scheduled_at', '<=', now())
                ->get();

            if ($scheduledEmails->isEmpty()) {
                $this->info('No scheduled email blasts to process.');
                return;
            }

            foreach ($scheduledEmails as $emailBlast) {
                try {
                    $this->info("Processing email blast: {$emailBlast->subject}");

                    // Update status menjadi processing
                    $emailBlast->update(['status' => EmailBlast::STATUS_PROCESSING]);

                    // Dispatch jobs untuk setiap recipient
                    $recipients = $emailBlast->recipients;
                    if ($recipients->isEmpty()) {
                        $this->warn("No recipients found for email blast: {$emailBlast->subject}");
                        continue;
                    }

                    foreach ($recipients as $recipient) {
                        SendEmailBlastJob::dispatch($emailBlast, $recipient);
                    }

                    $this->info("Dispatched jobs for {$recipients->count()} recipients");
                } catch (Exception $e) {
                    Log::error("Error processing email blast {$emailBlast->id}: " . $e->getMessage());
                    $this->error("Error processing email blast {$emailBlast->id}: " . $e->getMessage());
                }
            }

            $this->info('Finished processing scheduled email blasts');
        } catch (Exception $e) {
            Log::error("Error in ProcessScheduledEmailBlasts command: " . $e->getMessage());
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
