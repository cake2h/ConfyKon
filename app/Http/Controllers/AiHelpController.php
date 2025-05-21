<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\UploadedFile;

class TextExtractionException extends \Exception {}

class AiHelpController extends Controller
{
    public function index()
    {
        return view('ai.help');
    }

    private function _extractTextFromFile(UploadedFile $file): string
    {
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

            if (empty($extractedText)) {
                throw new TextExtractionException('Документ пустой или не удалось извлечь из него текст после успешной обработки файла.');
            }
            return $extractedText;

        } catch (\PhpOffice\PhpWord\Exception\ExceptionInterface $e) {
            throw new TextExtractionException('Ошибка при обработке файла документа библиотекой PhpWord: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) { 
            throw new TextExtractionException('Общая ошибка при извлечении текста из файла: ' . $e->getMessage(), 0, $e);
        }
    }

    public function analyze(Request $request)
    {
        ini_set('max_execution_time', 180);
        $apiTimeoutSeconds = 150;

        $request->validate([
            'document' => 'required|file|mimes:doc,docx|max:10240',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            
            try {
                $extractedText = $this->_extractTextFromFile($file);
                
                $maxTextLength = 50000;
                if (mb_strlen($extractedText) > $maxTextLength) {
                    $extractedTextForApi = mb_substr($extractedText, 0, $maxTextLength);
                } else {
                    $extractedTextForApi = $extractedText;
                }

                $annotationPrompt = "Пожалуйста, создай аннотацию для следующего научного доклада:\n\n" . $extractedTextForApi;

                $annotationResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.deepseek.api_key'),
                    'Content-Type' => 'application/json',
                ])->timeout($apiTimeoutSeconds)
                  ->post(config('services.deepseek.url') . '/chat/completions', [
                    'model' => 'deepseek-chat', 
                    'messages' => [
                        ['role' => 'system', 'content' => 'Проанализируй текст научного доклада, представленный в загруженном файле, и напиши аннотацию в соответствии с ГОСТ 7.9–95 (СИБИД).
                                Требования к аннотации:
                                язык: русский;
                                объем: 400–600 знаков без пробелов;
                                стиль: научный, без вводных слов и оценочных суждений;
                                структура: укажи предмет, цель работы, методы, полученные результаты, область применения, научную новизну (если есть);
                                не дублируй заголовок и не используй аббревиатуры без расшифровки;
                                не добавляй информацию, не содержащуюся в докладе.
                            Аннотация должна соответствовать требованиям к публикации в сборнике научных трудов.'],
                        ['role' => 'user', 'content' => $annotationPrompt]
                    ],
                    'stream' => false,
                ]);

                $annotationResult = null;
                if ($annotationResponse->successful()) {
                    $annotationResult = trim($annotationResponse->json('choices.0.message.content'));
                    if (empty($annotationResult)) {
                        $annotationResult = 'Не удалось получить аннотацию из ответа API.';
                    }
                } else {
                    $annotationResult = 'Ошибка при получении аннотации: API вернул статус ' . $annotationResponse->status();
                    if (!$annotationResponse->serverError() && !$annotationResponse->clientError()) {
                         return response()->json([
                            'success' => false,
                            'message' => 'Ошибка сети при запросе аннотации: ' . $annotationResponse->toException()->getMessage(),
                            'annotation' => $annotationResult,
                            'bibliography' => 'Запрос на анализ библиографии не выполнялся из-за ошибки на предыдущем шаге.'
                        ], 500);
                    }
                }
                
                $bibliographyPrompt = "Пожалуйста, оформи библиографию для следующего научного доклада:" . $extractedTextForApi;

                $bibliographyResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.deepseek.api_key'),
                    'Content-Type' => 'application/json',
                ])->timeout($apiTimeoutSeconds)
                  ->post(config('services.deepseek.url') . '/chat/completions', [
                    'model' => 'deepseek-chat', 
                    'messages' => [
                        ['role' => 'system', 'content' => 'Проанализируй следующий текст научного документа и выполни действия в соответствии с ГОСТ Р 7.0.5–2008:\n\n"
                                    . "1. **Найди и извлеки ТОЛЬКО ЯВНЫЕ внутритекстовые библиографические ссылки.** Примерами таких ссылок являются: номера в квадратных скобках (например, [1], [2-5], [1, с. 5]) или фамилия автора с годом (например, Петров А.Н., 2021). КАТЕГОРИЧЕСКИ ЗАПРЕЩАЕТСЯ выводить: пустые квадратные скобки `[]`; скобки, содержащие только одиночные буквы (как `[n]`, если это не часть многотомной ссылки типа `[5, т. 1, с. 12]`), пробелы или случайные символы, не являющиеся частью корректной ссылки; числа или слова в скобках, если нет полной уверенности, что это библиографическая ссылка; любые сноски, не являющиеся ссылками на библиографические источники. Не пытайся угадывать или генерировать ссылки, если их нет в явном виде.\n"
                                    . "2. **Проанализируй раздел \"Список литературы\" (или \"Библиография\") в документе, если он есть.** Если его нет, создай его заново на основе найденных в тексте ссылок.\n"
                                    . "3. **Для каждой найденной ссылки или для каждого элемента существующего списка литературы сформируй корректное библиографическое описание по ГОСТ Р 7.0.5–2008.** Учитывай тип источника (книга, статья, электронный ресурс и т.д.). Обязательно включай все необходимые элементы: ФИО авторов, заголовок, место издания, издательство, год, страницы. **Для электронных ресурсов ОБЯЗАТЕЛЬНО указывай URL (если он есть в исходном тексте или списке литературы) и дату обращения.** Соблюдай пунктуацию и стиль ГОСТ.\n"
                                    . "4. **Выведи результат в следующем формате:**\n\n"
                                    . "**Перечень найденных ссылок в тексте (с контекстом):**\n"
                                    . "[Нумерованный список. Каждый пункт должен содержать: саму найденную ссылку в том виде, как она есть в тексте, и краткий контекст, в котором она была найдена (например, 5-7 слов до и после ссылки). Пример: 1. [1] - ...текст немного до [1] текст немного после... ]\n\n"
                                    . "**Список литературы (оформленный по ГОСТ Р 7.0.5–2008):**\n"
                                    . "[Нумерованный список литературы, оформленный по ГОСТ. Каждый элемент с новой строки. Для электронных ресурсов обязательно должен быть указан URL, если он был в исходном документе.]\n\n"
                                    . "**Текст документа для анализа:**\n\n'],
                        ['role' => 'user', 'content' => $bibliographyPrompt]
                    ],
                    'stream' => false,
                ]);

                $bibliographyResult = null;
                if ($bibliographyResponse->successful()) {
                    $bibliographyResult = trim($bibliographyResponse->json('choices.0.message.content'));
                    if (empty($bibliographyResult)) {
                        $bibliographyResult = 'Не удалось получить результат анализа библиографии из ответа API.';
                    }
                } else {
                    $bibliographyResult = 'Ошибка при анализе библиографии: API вернул статус ' . $bibliographyResponse->status();
                }

                return response()->json([
                    'success' => true,
                    'annotation' => $annotationResult,
                    'bibliography' => $bibliographyResult
                ]);

            } catch (TextExtractionException $e) {
                Log::error('Ошибка извлечения текста из документа', [
                    'error' => $e->getMessage(),
                    'file' => $file ? $file->getClientOriginalName() : 'N/A',
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось обработать файл документа: ' . $e->getMessage() 
                ], 400);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error('Ошибка соединения с API DeepSeek', [
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось соединиться с сервисом анализа документов (ошибка сети): ' . $e->getMessage()
                ], 504);
            } catch (\Exception $e) {
                Log::error('Внутренняя ошибка сервера при обработке документа или вызове API', [
                    'error' => $e->getMessage(),
                ]);
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