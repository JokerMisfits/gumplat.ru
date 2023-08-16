<?php

namespace app\controllers;

use app\models\Cities;
use app\models\Tickets;
use app\models\CitySearch;

/**
 * CitiyController implements the CRUD actions for Cities model.
 */
class CityController extends AppController{

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
     * Lists all Cities models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Cities model.
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
     * Creates a new Cities model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $model = new Cities();
        if(\Yii::$app->request->isPost){
            if($model->load(\yii::$app->request->post())){
                if($model->save()){
                    \Yii::$app->session->addFlash('success', 'Н. П. успешно добавлен.');
                    $updates['city'][$model->id]['name'] = $model->name;
                    $updates['city'][$model->id]['territory'] = $model->territory;
                    $updates['city'][$model->id]['event'] = 'create';
                    $cache = \Yii::$app->cache->get('updates');
                    if($cache === false){
                        \Yii::$app->cache->set('updates', $updates, null);
                    }
                    else{
                        $cache['city'][$model->id] = $updates['city'][$model->id];
                        \Yii::$app->cache->set('updates', $cache, null);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при добавлении Н. П.');
                }
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
     * Updates an existing Cities model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost && $model->load(\yii::$app->request->post())){
            if($model->save()){
                \Yii::$app->session->addFlash('success', 'Н. П. успешно обновлен.');
                $updates['city'][$id]['name'] = $model->name;
                $updates['city'][$model->id]['territory'] = $model->territory;
                $updates['city'][$id]['event'] = 'update';
                $cache = \Yii::$app->cache->get('updates');
                if($cache === false){
                    \Yii::$app->cache->set('updates', $updates, null);
                }
                else{
                    if(array_key_exists($model->id, $cache['city']) && $cache['city'][$model->id]['event'] === 'create'){
                        $updates['city'][$id]['event'] = 'create';
                    }
                    $cache['city'][$id] = $updates['city'][$id];
                    \Yii::$app->cache->set('updates', $cache, null);
                }
                return $this->redirect(['view', 'id' => $id]);
            }
            else{
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при обновлении Н. П.');
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Cities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        if(Tickets::find()->where(['city_id' => $id])->limit(1)->count() === 0){
            $model = $this->findModel($id);
            if($model->delete() !== false){
                \Yii::$app->session->addFlash('success', 'Н. П. ' . $model->name . ' успешно удален.');
                $updates['city'][$id]['event'] = 'delete';
                $cache = \Yii::$app->cache->get('updates');
                if($cache === false){
                    \Yii::$app->cache->set('updates', $updates, null);
                }
                else{
                    $cache['city'][$id] = $updates['city'][$id];
                    \Yii::$app->cache->set('updates', $cache, null);
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении Н. П. ' . $model->name . '.');  
            }
        }
        else{
            \Yii::$app->getSession()->addFlash('error', 'Данный Н. П. еще используется! '. \yii\helpers\Html::a(\yii\helpers\Html::encode('Перейти к данным обращениям'), \yii\helpers\Url::to(['tickets/', 'TicketSearch[city_id]' => $id]), ['class' => 'link-dark link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover', 'title' => 'Перейти']));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Cities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cities the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Cities{
        if (($model = Cities::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}