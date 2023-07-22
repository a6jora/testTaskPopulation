<?php

namespace app\services;

use app\models\Populations;
use app\models\Prefectures;
use app\models\Years;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use SplFileObject;

class DatabaseService
{
    public function setDataFromURL(string $url = 'https://www.e-stat.go.jp/en/stat-search/file-download?&statInfId=000031288682&fileKind=1'): void
    {
        $client = new Client();

        try {
            $response = $client->get($url);
            if ($response->getStatusCode() === 200) {
                $this->clearTables();
                $data = $this->convertCsvStringToArray((string)$response->getBody());
                $yearIds = $this->saveYears($data['years']);
                $this->savePrefectureAndPopulation($data['populationInfo'], $yearIds);
            } else {
                throw new \Exception('Url loading failed');
            }
        } catch (Exception|GuzzleException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function setDataFromFile($fileObject) {
        try {
            $this->clearTables();
            $fileContent = file_get_contents($fileObject->tempName);
            $data = $this->convertCsvStringToArray($fileContent);
            $yearIds = $this->saveYears($data['years']);
            $this->savePrefectureAndPopulation($data['populationInfo'], $yearIds);
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    private function saveYears(array $years): array
    {
        $yearsIds = [];
        foreach ($years as $year) {
            if (empty($year)) continue;
            $yearModel = new Years();
            $yearModel->year = $year;
            if (!$yearModel->save())
                $this->throwDbException();
            $yearsIds[] = $yearModel->year_id;
        }
        return $yearsIds;
    }

    /**
     * @throws Exception
     */
    private function savePrefectureAndPopulation(array $populationInfo, array $yearIds): void
    {
        foreach ($populationInfo as $prefecturePopulation) {
            $prefecture = new Prefectures();
            $prefecture->prefecture_name = $prefecturePopulation[0];

            if (!$prefecture->save())
                $this->throwDbException();

            unset($prefecturePopulation[0]);

            foreach (array_values($prefecturePopulation) as $key => $value) {
                $population = new Populations();
                $population->prefecture_id = $prefecture->prefecture_id;
                $population->year_id = $yearIds[$key];
                $population->population = is_numeric($value) ? $value : 0;
                if (!$population->save())
                    $this->throwDbException();
            }
        }
    }

    /**
     * @throws Exception
     */
    private function throwDbException(): void
    {
        throw new Exception('Wrong database insert');
    }

    /**
     * @throws InvalidArgument
     */
    private function convertCsvStringToArray(string $content): array
    {
        $csv = Reader::createFromString($content);
        $csv->setDelimiter(',');

        $data = iterator_to_array($csv->getRecords());

        $years = $data[2];
        for ($i = 0; $i <= 2; $i++) {
            unset($data[$i]);
        }
        $populationInfo = array_values($data);

        return ['years' => $years, 'populationInfo' => $populationInfo];
    }

    private function clearTables(): void
    {
        Populations::deleteAll();
        Prefectures::deleteAll();
        Years::deleteAll();
    }
}
