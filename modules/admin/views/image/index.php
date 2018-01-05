<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use app\widgets\Glyphicon;

?>

<?= GridView::widget([
    'dataProvider' => $imagesData,
    'columns' => [
        'id',
        'title',
        'description',
        'url',
        [
            'class' => ActionColumn::className(),
            'template' => '',
            'header' => Html::a(Glyphicon::icon('plus') . ' Upload', ['upload'], ['class' => 'btn btn-sm btn-success']),
        ],
    ],
]) ?>
