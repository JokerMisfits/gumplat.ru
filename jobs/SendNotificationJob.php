<?php

namespace app\jobs;// just test

/**
 * Class SendNotificationJob.
*/
class SendNotificationJob extends \yii\base\BaseObject implements \yii\queue\JobInterface{    

    /**
     * @inheritdoc
     * @return int
     */
    public function execute($queue) : void{
        $users = \app\models\Users::find()->all();
        foreach($users as $user){
            echo 'user-id: ' . $user->id . PHP_EOL;
        }
        echo 'Done ' . time() . PHP_EOL;
        \Yii::$app->queue->delay(1 * 60)->push(new SendNotificationJob());
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getTtr() : int{
        return 60;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function canRetry($attempt, $error) : bool{
        return $attempt < 3;
    }
}

?>