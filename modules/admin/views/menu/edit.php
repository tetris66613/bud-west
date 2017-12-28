<?php

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-md-12 pull-right">
        <?= \app\widgets\DeleteForm::widget(['model' => $modelDelete]); ?>
    </div>
</div>

<?= $this->render('form', [
    'model' => $model,
]) ?>
