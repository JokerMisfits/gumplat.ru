<?php
/** @var yii\web\View $this */
/** @var string $content */
app\assets\AppAsset::register($this);
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
<html lang="<?= Yii::$app->language; ?>" class="h-100">
<head>
    <title><?= yii\bootstrap5\Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    yii\bootstrap5\NavBar::begin([
        'brandLabel' => '<span style="padding-left: 1.5rem;">' . $name . '</span>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark fixed-top bg-dark row']
    ]);
    echo yii\bootstrap5\Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Авторизация', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . yii\bootstrap5\Html::beginForm(['/site/logout'])
                    . yii\bootstrap5\Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . yii\bootstrap5\Html::endForm()
                    . '</li>'
        ]
    ]);
    yii\bootstrap5\NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= app\widgets\Alert::widget(); ?>
        <?= $content; ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light border-top">
    <div class="container">
        <div class="row text-dark">
            <div class="col-12 text-dark text-center">&copy; <?= date('Y') . ' Copyright: ' . $name; ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>