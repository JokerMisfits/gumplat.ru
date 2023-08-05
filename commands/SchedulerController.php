<?php

namespace app\commands; // just test

use Yii;
use app\jobs\SendNotificationJob;

class SchedulerController extends \yii\console\Controller{
    public function actionStart() : void{
        try{
            $id = Yii::$app->queue->push(new SendNotificationJob());
            Yii::$app->queue->isWaiting($id);
            Yii::$app->queue->isReserved($id);
            Yii::$app->queue->isDone($id);
        }
        catch(\Exception|\Throwable $e){
            echo $e->getMessage();
        }
    }
}

?>