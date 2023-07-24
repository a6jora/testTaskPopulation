<?php

namespace app\commands;

use app\services\DatabaseService;
use yii\console\Controller;


class ConsoleController extends Controller
{
    public function actionLoad(): void
    {
        $service = new DatabaseService();
        $service->setDataFromURL();
        echo 'Default cvs loaded' . PHP_EOL;
    }
}
