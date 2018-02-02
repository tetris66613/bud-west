<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\models\Menu;
use app\models\Settings;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('logo.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    $clientMenuItems = [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
    ];
    $clientMenuItems = array_merge($clientMenuItems, Menu::buildNavItems(Menu::TYPE_CLIENT_NAVBAR));
    if (User::checkIsAdmin()) {
        $clientMenuItems[] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin']];
        if (Yii::$app->user->isGuest) {
            $clientMenuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
        } else {
            $clientMenuItems[] = ''
                . '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    Yii::t('app', 'Logout') .  ' (' . Yii::$app->user->identity->email . ')',
                        ['class' => 'btn btn-link logout']
                    )
                . Html::endForm()
                . '</li>';
        }
    }

    echo Nav::widget([
        'options' => ['class' => 'nav_client navbar-nav navbar-right'],
        'items' => $clientMenuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="row">
            <div class="col-md-10">
                <?= $content ?>
            </div>
            <div class="col-md-2" style="margin-top:10px">
                <?= Nav::widget([
                    'options' => ['class' => 'list-group'],
                    'items' => Menu::buildNavItems(Menu::TYPE_SIDEBAR, ['linkOptions' => ['class' => 'list-group-item']]),
                ]) ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Settings::findValueByName('companyName', Settings::DEFAULT_COMPANY_NAME), ' ', date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
