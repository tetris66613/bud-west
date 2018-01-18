<?php

use app\assets\MaintanceAsset;

MaintanceAsset::register($this);

?>
<?= $this->beginPage(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Site under maintance</title>
    <?= $this->head() ?>
</head>
<body>
    <?= $this->beginBody() ?>
    <div class="header">
    </div>
    <div class="content">
        <?= $content ?>
    </div>
    <div class="footer">
    </div>
    <?= $this->endBody() ?>
</body>
</html>
<?= $this->endPage();
