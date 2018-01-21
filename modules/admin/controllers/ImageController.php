<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\UploadImage;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\UploadImageForm;
use app\modules\admin\Module;

class ImageController extends AdminController
{
    public function actionIndex()
    {
        $imagesData = UploadImage::gridData();

        return $this->render('index', [
            'imagesData' => $imagesData,
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadImageForm();

        if ($model->requestUpload()) {
            return $this->redirect(['index']);
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    public function actionDownload($id)
    {
        $uploadImage = UploadImage::findOne($id);
        if (!$uploadImage) {
            throw new \yii\web\NotFoundHttpException(Module::t('main', 'Image not found'));
        }
        $fileContent = $uploadImage->data;

        Yii::$app->response->sendContentAsFile($uploadImage->data, $uploadImage->filename, [
            'mimeType' => $uploadImage->mimetype,
        ]);
    }
}
