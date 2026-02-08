<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Tasks;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::findOne(1);
        return $this->render('index',[
            'tasks' => $tasks
        ]);
    }
}