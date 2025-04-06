<?php

namespace App\Jobs;

use App\Models\EmailBlast;
use App\Models\EmailBlastRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailBlast;
    protected $recipient;
    protected $delayInMinutes;

    /**
     * Create a new job instance.
     */
    public function __construct(EmailBlast $emailBlast, EmailBlastRecipient $recipient, $delayInMinutes = 60)
    {
        $this->emailBlast = $emailBlast;
        $this->recipient = $recipient;
        $this->delayInMinutes = $delayInMinutes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Kirim email
            Mail::send([], [], function ($message) {
                $message->to($this->recipient->recipient_email)
                    ->subject($this->emailBlast->subject)
                    ->setBody($this->emailBlast->body, 'text/html');
            });

            // Update status recipient menjadi sent
            $this->recipient->update(['status' => 'Sent']);

            // Cek apakah semua recipient sudah terkirim
            $this->checkAllRecipientsSent();
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$this->recipient->recipient_email}: {$e->getMessage()}");

            // Update status recipient menjadi failed
            $this->recipient->update(['status' => 'Failed']);

            // Update status email blast jika semua recipient gagal
            $this->checkAllRecipientsFailed();
        }
    }

    /**
     * Get the delay before the job should be processed.
     *
     * @return \DateTime
     */
    public function delay()
    {
        return now()->addMinutes($this->delayInMinutes);
    }

    /**
     * Cek apakah semua recipient sudah terkirim
     */
    private function checkAllRecipientsSent(): void
    {
        $pendingRecipients = $this->emailBlast->recipients()
            ->where('status', '!=', 'Sent')
            ->count();

        if ($pendingRecipients === 0) {
            $this->emailBlast->update([
                'status' => 'Sent',
                'sent_at' => now()
            ]);
        }
    }

    /**
     * Cek apakah semua recipient gagal
     */
    private function checkAllRecipientsFailed(): void
    {
        $failedRecipients = $this->emailBlast->recipients()
            ->where('status', 'Failed')
            ->count();

        $totalRecipients = $this->emailBlast->recipients()->count();

        if ($failedRecipients === $totalRecipients) {
            $this->emailBlast->update([
                'status' => 'Failed',
                'sent_at' => now()
            ]);
        }
    }
}
