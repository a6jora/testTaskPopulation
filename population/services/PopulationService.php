<?php

namespace app\services;

use app\models\Populations;

class PopulationService
{
    public function getPopulation(array $params): int
    {
        $population = Populations::findOne([
            'prefecture_id' => $params['prefecture'],
            'year_id' => $params['year'],
            ]);

        return $population->population ?? 0;
    }
}
