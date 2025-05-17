<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;

class AiHelpController extends Controller
{
    public function index()
    {
        return view('ai.help');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:doc,docx|max:10240',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            
            try {
                $extractedText = '';
                try {
                    $phpWord = IOFactory::load($file->getRealPath());
                    foreach ($phpWord->getSections() as $section) {
                        foreach ($section->getElements() as $element) {
                            if (method_exists($element, 'getText')) {
                                $extractedText .= $element->getText() . ' ';
                            } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                foreach ($element->getElements() as $textRunElement) {
                                    if (method_exists($textRunElement, 'getText')) {
                                        $extractedText .= $textRunElement->getText() . ' ';
                                    }
                                }
                            }
                        }
                    }
                    $extractedText = trim($extractedText);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Не удалось обработать файл документа (ошибка извлечения текста): ' . $e->getMessage()
                    ], 400);
                }
                
                if (empty($extractedText)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Документ пустой или не удалось извлечь из него текст.'
                    ], 400);
                }

                $maxTextLength = 30000;
                if (mb_strlen($extractedText) > $maxTextLength) {
                    $extractedText = mb_substr($extractedText, 0, $maxTextLength);
                }
                
                $prompt = "Пожалуйста, создай аннотацию для следующего научного доклада:\n\n" . $extractedText;

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.deepseek.api_key'),
                    'Content-Type' => 'application/json',
                ])->post(config('services.deepseek.url') . '/chat/completions', [
                    'model' => 'deepseek-chat', 
                    'messages' => [
                        ['role' => 'system', 
                        'content' => 'Проанализируй текст научного доклада, представленный в загруженном файле, и напиши аннотацию в соответствии с ГОСТ 7.9–95 (СИБИД).
                            Требования к аннотации:
                            – язык: русский;
                            – объем: 400-600 знаков без пробелов;
                            – стиль: научный, без вводных слов и оценочных суждений;
                            – структура: укажи предмет, цель работы, методы, полученные результаты, область применения, научную новизну (если есть);
                            – не дублируй заголовок и не используй аббревиатуры без расшифровки;
                            – не добавляй информацию, не содержащуюся в докладе.
                            Аннотация должна соответствовать требованиям к публикации в сборнике научных трудов.
                            Аннотация должна включать характеристику основной темы, проблемы научного доклада, цели работы и ее результаты. В аннотации указывают, какие аспекты темы докладчик планирует раскрыть в своем выступлении.
                            '],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    $annotation = $response->json('choices.0.message.content');
                    if ($annotation) {
                        return response()->json([
                            'success' => true,
                            'annotation' => trim($annotation)
                        ]);
                    } else {
                         return response()->json([
                            'success' => false,
                            'message' => 'Не удалось получить аннотацию из ответа API. Ответ не содержит ожидаемых данных.',
                            'error_details' => [
                                'status' => $response->status(),
                                'response' => $response->json(),
                            ]
                        ], 500);
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при анализе документа (API Error)',
                    'error_details' => [
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]
                ], 500);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Внутренняя ошибка сервера: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Документ не был предоставлен'
        ], 400);
    }
} 