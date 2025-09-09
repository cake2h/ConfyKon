<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AntiplagiatClient;

class AntiplagiatController extends Controller
{
    public function __construct(protected AntiplagiatClient $client) {}

    /**
     * Форма загрузки документа
     */
    public function uploadForm()
    {
        return view('antiplagiat.upload');
    }

    /**
     * Загрузка документа и запуск проверки
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // max 5MB
        ]);

        $file = $request->file('file');
        $content = base64_encode(file_get_contents($file->path()));

        // Загружаем документ
        $uploadResponse = $this->client->uploadDocument(
            $content,
            $file->getClientOriginalName(),
            '.' . $file->getClientOriginalExtension(),
            auth()->id() ?? uniqid('user_')
        );

        $docId = $uploadResponse->UploadDocumentResult->Uploaded->Id->Id ?? null;

        if (!$docId) {
            return back()->withErrors(['file' => 'Не удалось загрузить документ.']);
        }

        // Запускаем проверку
        $this->client->checkDocument($docId);

        return redirect()
            ->route('antiplagiat.status', ['docId' => $docId])
            ->with('success', 'Документ отправлен на проверку.');
    }

    /**
     * Статус проверки документа
     */
    public function status($docId)
    {
        $status = $this->client->getCheckStatus($docId);
        
        if ($status->GetCheckStatusResult->Status === 'Ready') {
            return redirect()->route('antiplagiat.report', ['docId' => $docId]);
        }

        return view('antiplagiat.status', [
            'docId' => $docId,
            'status' => $status,
        ]);
    }

    /**
     * Просмотр итогового отчета
     */
    public function report($docId)
    {
        $report = $this->client->getReportView($docId);

        return view('antiplagiat.report', [
            'docId' => $docId,
            'report' => $report,
        ]);
    }
}
