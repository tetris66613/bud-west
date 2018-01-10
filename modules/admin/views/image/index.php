<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use app\widgets\Glyphicon;
use app\modules\admin\Module;

?>

<?= GridView::widget([
    'dataProvider' => $imagesData,
    'columns' => [
        'title',
        'description',
        'url',
        [
            'class' => ActionColumn::className(),
            'template' => '',
            'header' => Html::a(Glyphicon::icon('plus') . ' ' . Module::t('main', 'Upload'), ['upload'], ['class' => 'btn btn-sm btn-success']),
        ],
    ],
]) ?>
