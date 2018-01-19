<?php

use yii\helpers\Html;
use app\assets\MaintanceAsset;

MaintanceAsset::register($this);

?>
<?= $this->beginPage(); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->head() ?>
</head>
<body>
    <?= $this->beginBody() ?>
    <div class="content">
        <?= $content ?>
    </div>
    <?= $this->endBody() ?>
</body>
</html>
<?= $this->endPage();
