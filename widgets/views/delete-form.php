<?php

use yii\helpers\Html;

?>

<?php $form = $widget->formClass::begin([
    'action' => $widget->action,
]); ?>

<?= $form->field($widget->model, 'id')->hiddenInput()->label(false) ?>

<?= Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-danger']) ?>

<?php $widget->formClass::end(); ?>
