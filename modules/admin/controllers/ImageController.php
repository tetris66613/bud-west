<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\UploadImage;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\UploadImageForm;

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
}
