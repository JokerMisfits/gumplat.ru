<?php

namespace app\controllers;

use app\models\Documents;
use app\models\DocumentSearch;

/**
 * DocumentController implements the CRUD actions for Documents model.
 */
class DocumentController extends AppController{

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
     * Lists all Documents models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'folderSize' => self::getFolderSize(realpath(\Yii::getAlias('@web')) . '/uploads/')
        ]);
    }

    /**
     * Displays a single Documents model.
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
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $folderSize = self::getFolderSize(realpath(\Yii::getAlias('@web')) . '/uploads/');
        if(round(($folderSize*100 / \Yii::$app->params['maxFileStorageSize']), 2) > 99.5){
            exit('Файловое хранилище переполнено, для расширения функционала сервера обратитесь к разработчикам, либо удалите ненужные файлы.');
        }
        else{
            $model = new Documents(['scenario' => 'upload']);
            if(\Yii::$app->request->post()){
                if($model->load(\yii::$app->request->post())){
                    $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
                    if($model->file && $model->validate()){
                        if($model->upload()){
                            $model->base_name = $model->file->baseName;
                            $model->extension = $model->file->extension;
                            $model->file = null;
                            $model->path = realpath(\Yii::getAlias('@web')) . '/uploads/' . $model->name . '.' . $model->extension;
                            $transaction = $model->getDb()->beginTransaction();
                            if($model->save(false)){
                                $transaction->commit();
                                \Yii::$app->session->addFlash('success', 'Файл успешно добавлен.');
                            }
                            else{
                                $transaction->rollBack();
                                unlink($model->path);
                                \Yii::$app->session->addFlash('error', 'Ошибка загрузки файла.');
                            }
                        }
                    }
                    else{
                        \Yii::$app->session->addFlash('error', 'Ошибка загрузки файла.');
                    }
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
    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if(\Yii::$app->request->post() && $model->load(\yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        $model = $this->findModel($id);
        if(file_exists($model->path)){
            if(unlink($model->path)){
                if($model->delete() !== false){
                    \Yii::$app->session->addFlash('success', 'Документ ' . $model->base_name . '.' . $model->extension . ' успешно удален.');   
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении файла.');   
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении файла.');
            }
        }
        else{
            if($model->delete() !== false){
                \Yii::$app->session->addFlash('success', 'Документ ' . $model->base_name . '.' . $model->extension . ' успешно удален.');   
            }
            else{
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении файла.');   
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionDownloadFile(int $id) : \yii\web\Response{
    $model = $this->findModel($id);
    if(file_exists($model->path)){
        return \Yii::$app->response->sendFile($model->path, $model->base_name . '.' . $model->extension, ['inline' => false]);
    }
    else{
        throw new \yii\web\NotFoundHttpException('Файл не найден.');
    }
}

    /**
     * @param string $path path
     * @return string|\yii\web\Response
     */
    public function actionDownloadFileFromTg(string $path) : \yii\web\Response{
        $path = explode('-', $path);
        if($path[0] === 'main'){
            $token = $_SERVER['BOT_TOKEN'];
        }
        else{
            $token = $_SERVER['BOT_FILE_TOKEN'];
        }
        $realPath = 'https://api.telegram.org/file/bot' . $token . '/' . $path[1] . '/' . $path[2] . '.' . $path[3];

        \Yii::$app->response->headers->set('Content-Type', 'application/octet-stream');
        \Yii::$app->response->headers->set('Content-Disposition', 'attachment; filename="' . $path[2] . '.' . $path[3] . '"');

        return \Yii::$app->response->sendContentAsFile(
            file_get_contents($realPath),
            $path[2] . '.' . $path[3],
            ['inline' => true]
        );
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Documents the loaded model
     * @throws\yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Documents{
        if(($model = Documents::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }

    /**
     * @param string $folderPath Path
     * @return int size
     */
    private static function getFolderSize(string $folderPath) : int{
        $totalSize = 0;
        if(!is_dir($folderPath)){
            return $totalSize;
        }
        $directory = new \RecursiveDirectoryIterator($folderPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);
        foreach($iterator as $file){
            if($file->isFile()){
                $totalSize += $file->getSize();
            }
        }
        return $totalSize;
    }
}