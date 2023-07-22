<?php

namespace app\services;

use GuzzleHttp\Client;
use League\Csv\InvalidArgument;
use League\Csv\Reader;

class DatabaseService
{
    public function setDataFromURL(string $url = 'https://www.e-stat.go.jp/en/stat-search/file-download?&statInfId=000031288682&fileKind=1')
    {
        $client = new Client();

        try {
            // Отправляем GET-запрос и получаем ответ
            $response = $client->get($url);

            // Проверяем статус ответа
            if ($response->getStatusCode() === 200) {
                // Получаем содержимое CSV-файла из ответа
                $csvContent = (string) $response->getBody();

                // Создаем экземпляр объекта Reader для чтения CSV
                $csv = Reader::createFromString($csvContent);
//                var_dump($csvContent); die;
                // Устанавливаем разделитель данных (по умолчанию - запятая)
                $csv->setDelimiter(',');

                // Читаем все строки из CSV-файла в виде ассоциативного массива
                $data = $csv->getRecords();
                echo '<pre>';
                foreach ($data as $row) {
                    print_r($row);
                }
                echo '</pre>';
                die;
            } else {
                echo 'Ошибка при загрузке файла: ' . $response->getStatusCode();
            }
        } catch (\Exception $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
