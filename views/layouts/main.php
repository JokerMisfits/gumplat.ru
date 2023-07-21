<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Nav;
use yii\bootstrap5\Html;
use app\assets\AppAsset;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Yii::getAlias('@web/images/favicon.png')]);
$name = Yii::$app->name;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<style>
    .form-required::after {
        content: '*';
        color: red;
        margin-left: 4px;
    }
    .form-required:hover::after {
        content: 'Обязательное поле';
        color: red;
        margin-left: 4px;
    }
    .crudHeader{
        margin: 32px 0 10px 10px;
    }
</style>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '<span style="padding-left: 1.5rem;">' . $name . '</span>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark fixed-top bg-dark row']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav col-md-6 m-0 p-0 text-center'],
        'items' => [
            ['label' => 'Обращения', 'url' => ['ticket/index']],

        ]
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav d-flex justify-content-md-end col-md-6 text-center'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/login']]
                : '<li class="nav-item">'
                . Html::beginForm(['/logout'])
                . Html::submitButton((strlen(Yii::$app->user->identity->username) > 5) 
                ? 'Выйти<span class="d-none d-lg-inline">(' . Yii::$app->user->identity->username . ')</span>'
                : 'Выйти(' . Yii::$app->user->identity->username . ')',
                    ['class' => 'nav-link btn btn-link logout text-center']
                )
                . Html::endForm()
                . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0 col-12" role="main">
        <?php if(!empty($this->params['breadcrumbs'])):?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
</main>

<footer id="footer" class="mt-auto py-3 bg-light border-top">
    <div class="container">
        <div class="row text-dark">
            <div class="col-12 text-dark text-center">&copy; <?= date('Y') . ' Copyright: ' . $name ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
