<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailsForSend;
use Illuminate\Support\Facades\File;
use App\Jobs\SendEmailWithAttachments;
use Illuminate\Support\Facades\Storage;
use App\Models\Conference;
use App\Models\Application;

class EmailController extends Controller
{
    public function emailsPage()
    {
        $conferences = Conference::where('user_id', auth()->id())->get();
        return view('emails.email_page', compact('conferences'));
    }

    public function saveMail(Request $request)
    {
        $text = $request->input('mail_text');
        $path = resource_path('views/emails/mail.blade.php');
        File::put($path, $text);

        return redirect()->back()->with('message', 'Письмо успешно сохранено.');
    }

    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('temp/email-attachments');

        return response()->json([
            'success' => true,
            'attachment' => [
                'id' => uniqid(),
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]
        ]);
    }

    public function removeAttachment(Request $request)
    {
        $path = $request->input('path');
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }

        return response()->json(['success' => true]);
    }

    public function sendEmails(Request $request)
    {
        $emailList = request('emails');
        $emails = explode("\n", $emailList);
        $attachments = $request->input('attachments', []);

        foreach ($emails as $email) {
            SendEmailWithAttachments::dispatch(
                trim($email),
                'ПОМЕНЯТЬ НА ЗАГОЛОВОК',
                file_get_contents(resource_path('views/emails/mail.blade.php')),
                $attachments
            );
        }

        // Очищаем временные файлы после отправки
        foreach ($attachments as $attachment) {
            if (Storage::exists($attachment['file_path'])) {
                Storage::delete($attachment['file_path']);
            }
        }

        return redirect()->back()->with('success', 'Приглашения поставлены в очередь на отправку');
    }

    public function getEmails(Request $request)
    {
        try {
            $type = $request->input('type');
            $conferenceId = $request->input('conference_id');

            if (!$type) {
                return response()->json(['error' => 'Type parameter is required'], 400);
            }

            if (!$conferenceId && $type !== 'custom') {
                return response()->json(['error' => 'Conference ID is required'], 400);
            }

            switch ($type) {
                case 'conference':
                    if ($conferenceId) {
                        try {
                            $users = User::whereHas('applications', function($query) use ($conferenceId) {
                                $query->whereHas('section', function($q) use ($conferenceId) {
                                    $q->where('conference_id', $conferenceId);
                                })
                                ->where('application_status_id', 1);
                            })->get();
                        } catch (\Exception $e) {
                            throw $e;
                        }
                    } else {
                        $users = collect();
                    }
                    break;
                case 'moderators':
                    if ($conferenceId) {
                        try {
                            $users = User::whereHas('sections', function($query) use ($conferenceId) {
                                $query->where('conference_id', $conferenceId);
                            })->get();
                        } catch (\Exception $e) {
                            throw $e;
                        }
                    } else {
                        $users = collect();
                    }
                    break;
                case 'custom':
                    $users = collect();
                    break;
                default:
                    return response()->json(['error' => 'Invalid type specified'], 400);
            }

            $userData = $users->map(function($user) {
                return [
                    'name' => trim($user->surname . ' ' . $user->name . ' ' . $user->patronymic),
                    'email' => $user->email
                ];
            });

            return response()->json($userData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}


