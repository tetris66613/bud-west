<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\admin\Module;

?>

<?php $form = ActiveForm::begin(); ?>

<?= $this->render('fields', [
    'form' => $form,
    'model' => $model,
]) ?>

<div class="form-group">
    <?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
