<?php

namespace app\controllers;

use app\models\Users;
use app\models\Cities;
use app\models\Tickets;
use app\models\Categories;
use app\models\TicketSearch;

/**
 * TicketController implements the CRUD actions for Tickets model.
 */
class TicketController extends AppController{

    /**
    * @inheritDoc
    */
   public function behaviors() : array{
       return array_merge(
           parent::behaviors(),
           [
               'verbs' => [
                   'class' => \yii\filters\VerbFilter::class,
                   'actions' => [
                       'delete' => ['POST']
                   ]
               ]
           ]
       );
   }

    /**
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param int $id ID
     * @return string
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id) : string{
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $model = new Tickets();
        $model->user_id = \Yii::$app->params['systemUserId'];
        if(\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                if($model->save()){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при создании обращения');                  
                    return $this->render('create', [
                        'model' => $model,
                        'categories' => new Categories()
                    ]);
                }
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                    'categories' => new Categories()
                ]);
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'cities' => new Cities(),
            'users' => new Users(),
            'categories' => new Categories()
        ]);
    }

    /**
     * Updates an existing Tickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                if($model->status == 2 || $model->status == 3){
                    if($model->comment == '' || !isset($model->comment) || strlen($model->comment) < 5){
                        \Yii::$app->session->addFlash('error', 'Необходимо заполнить результаты рассмотрения, перед закрытием обращения.');     
                    }
                }
                else{
                    if($model->save()){
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'cities' => new Cities(),
                    'users' => new Users(),
                    'categories' => new Categories()
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'cities' => new Cities(),
            'users' => new Users(),
            'categories' => new Categories()
        ]);
    }

    /**
     * Deletes an existing Tickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        if(\Yii::$app->user->can('admin')){
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
        else{
            throw new \yii\web\ForbiddenHttpException('Доступ только у администраторов');
        }
    }

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tickets the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Tickets{
        if(($model = Tickets::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}