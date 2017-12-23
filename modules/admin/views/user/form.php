<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\admin\Module;
?>


<?php $form = ActiveForm::begin() ?>
<?php if ($model->isAttributeActive('id')) : ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
<?php endif ?>
<?php if ($model->isAttributeActive('email')) : ?>
    <?= $form->field($model, 'email') ?>
<?php endif ?>
<?php if ($model->isAttributeActive('username')) : ?>
    <?= $form->field($model, 'username') ?>
<?php endif ?>
<?php if ($model->isAttributeActive('role')) : ?>
    <?= $form->field($model, 'role')->dropDownList($model->rolesItems()) ?>
<?php endif ?>
<?php if ($model->isAttributeActive('password')) : ?>
    <?= $form->field($model, 'password') ?>
<?php endif ?>
<?php if ($model->isAttributeActive('changePassword')) : ?>
    <?= $form->field($model, 'changePassword')->passwordInput() ?>
<?php endif ?>
<?php if ($model->isAttributeActive('changePasswordConfirm')) : ?>
    <?= $form->field($model, 'changePasswordConfirm')->passwordInput() ?>
<?php endif ?>

<div class="form-group">
    <?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end() ?>

