<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\UploadImage;

class UploadImageForm extends Model
{
    public $imageFile;
    public $title;
    public $description;

    public function rules()
    {
        return [
            ['imageFile', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['title', 'description'], 'default'],
            [['title', 'description'], 'string'],
        ];
    }

    public function requestUpload()
    {
        if ($this->load(Yii::$app->request->post())) {
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

            if ($this->validate()) {
                $uploadImage = new UploadImage();
                $uploadImage->setAttributes($this->getAttributes());

                if ($uploadImage->save()) {
                    $path = 'uploads/' . $uploadImage->id . '.' . $this->imageFile->extension;
                    $uploadImage->url = '/' . $path;
                    $uploadImage->update();
                    $this->imageFile->saveAs($path);
                    return true;
                }
                return true;
            }
        }

        return false;
    }
}
