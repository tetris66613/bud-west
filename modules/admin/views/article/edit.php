<?php

?>

<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <?= \app\widgets\DeleteForm::widget(['model' => $modelDelete]); ?>
        </div>
    </div>
</div>

<?= $this->render('form', ['model' => $model]) ?>
