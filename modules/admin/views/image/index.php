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
            'template' => '{download}',
            'header' => Html::a(Glyphicon::icon('upload') . ' ' . Module::t('main', 'Upload'), ['upload'], ['class' => 'btn btn-sm btn-success']),
            'buttons' => [
                'download' => function($url, $model) {
                    return Html::a(Glyphicon::icon('download-alt') . ' ' . Module::t('main', 'Download'), ['download', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
                }
            ],
        ],
    ],
]) ?>
