<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailWithAttachments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $subject;
    protected $content;
    protected $attachments;

    public function __construct($email, $subject, $content, $attachments = [])
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
        $this->attachments = $attachments;
    }

    public function handle()
    {
        Mail::send(['text' => 'emails/mail'], ['content' => $this->content], function($message) {
            $message->to($this->email)
                ->subject($this->subject)
                ->from('stud0000264064@utmn.ru', 'Организатор конференции МИМ-2024');

            foreach ($this->attachments as $attachment) {
                if (Storage::exists($attachment['file_path'])) {
                    $message->attach(storage_path('app/' . $attachment['file_path']), [
                        'as' => $attachment['original_name'],
                        'mime' => $attachment['mime_type'],
                    ]);
                }
            }
        });
    }
} 