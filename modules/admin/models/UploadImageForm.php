<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\UploadImage;
use app\modules\Admin\Module;

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
                /* TODO - check yii2 why not work this
                $uploadImage->setAttributes($this->getAttributes());
                 */
                $uploadImage->title = $this->title;
                $uploadImage->description = $this->description;
                $uploadImage->filename = $this->imageFile->name;
                $uploadImage->mimetype = $this->imageFile->type;
                $uploadImage->data = file_get_contents($this->imageFile->tempName);

                if ($uploadImage->save()) {
                    $path = 'uploads/' . $uploadImage->id . '.' . $this->imageFile->extension;
                    $uploadImage->url = '/' . $path;

                    $uploadImage->update();
                    $this->imageFile->saveAs($path);
                    return true;
                }
            }
        }

        return false;
    }

    public function attributeLabels()
    {
        return array_merge(UploadImage::attributeLabels(), [
            'imageFile' => Module::t('main', 'Image File'),
        ]);
    }
}
