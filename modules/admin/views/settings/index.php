<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\admin\Module;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $model->renderFormFields($form) ?>

<?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
