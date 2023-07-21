<?php

namespace app\controllers;

use yii;
use app\models\Tickets;
use app\models\TicketSearch;

/**
 * TicketController implements the CRUD actions for Tickets model.
 */
class TicketController extends AppController{

    /**
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex(){
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param int $id ID
     * @return string
     * @throws yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|yii\web\Response
     */
    public function actionCreate(){
        $model = new Tickets();
        if(Yii::$app->request->isPost){
            if($model->load($this->request->post()) && $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|yii\web\Response
     * @throws yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost && $model->load($this->request->post()) && $model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return yii\web\Response
     * @throws yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id){
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tickets the loaded model
     * @throws yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if(($model = Tickets::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new yii\web\NotFoundHttpException('The requested page does not exist.');
    }
}