<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]) ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>
<?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
