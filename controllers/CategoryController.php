<?php

namespace app\controllers;

use app\models\Tickets;
use app\models\Documents;
use app\models\Categories;
use app\models\CategorySearch;

/**
 * CategoryController implements the CRUD actions for Categories model.
 */
class CategoryController extends AppController{
    
    /**
     * @inheritDoc
     * @return array
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
     * Lists all Categories models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param int $id ID
     * @return string
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id) : string{
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $model = new Categories();
        if (\Yii::$app->request->isPost){
            if ($model->load(\yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost && $model->load(\yii::$app->request->post()) && $model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        if(Tickets::find()->where(['category_id' => $id])->limit(1)->count() === 0){
            if(Documents::find()->where(['category_id' => $id])->limit(1)->count() === 0){
                $model = $this->findModel($id);
                if($model->delete() !== false){
                    \Yii::$app->session->addFlash('success', 'Категория ' . $model->name . ' успешно удалена.');  
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении категории ' . $model->name . '.');  
                }
            }
            else{
                \Yii::$app->getSession()->addFlash('error', 'Данная категория еще используется! '. \yii\helpers\Html::a(\yii\helpers\Html::encode('Перейти к данным документам'), \yii\helpers\Url::to(['documents/', 'DocumentSearch[category_id]' => $id]), ['class' => 'link-dark link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover', 'title' => 'Перейти']));
            }
        }
        else{
            \Yii::$app->getSession()->addFlash('error', 'Данная категория еще используется! '. \yii\helpers\Html::a(\yii\helpers\Html::encode('Перейти к данным обращениям'), \yii\helpers\Url::to(['tickets/', 'TicketSearch[category_id]' => $id]), ['class' => 'link-dark link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover', 'title' => 'Перейти']));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Categories the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Categories{
        if(($model = Categories::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}