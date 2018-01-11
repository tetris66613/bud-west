<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\admin\Module;
?>


<?php $form = ActiveForm::begin() ?>

<?= $model->renderFormFields($form) ?>

<div class="form-group">
    <?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end() ?>

