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
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>" class="h-100">
<head>
    <title><?= yii\bootstrap5\Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody(); ?>

<style>
    .form-required::after{
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
    .form-required:hover::after{
        content: 'Обязательное поле';
        color: #dc3545;
        margin-left: 4px;
    }
    .form-question-ticket-create-tg-user-id::after{
        content: '?';
        color: #0d6efd;
        margin-left: 4px;
    }
    .form-question-ticket-create-tg-user-id:hover::after{
        content: 'Необходимо для отправки и просмотра сообщений с пользователем в telegram';
        color: #dc3545;
        margin-left: 4px;
    }
    .form-question-city-create-coordinate::after{
        content: '?';
        color: #dc3545;
        margin-left: 4px;
    }
    .form-question-city-create-coordinate:hover::after{
        content: 'Обязательно поле. Необходимо для интеграции яндекс карты';
        color: #dc3545;
        margin-left: 4px;
    }
</style>

<header id="header">
    <?php
    yii\bootstrap5\NavBar::begin([
        'brandLabel' => '<span style="padding-left: 1.5rem;">' . $name . '</span>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark fixed-top bg-dark row']
    ]);
    $navItems = [
            ['label' => 'Обращения', 'url' => ['ticket/index']],
            ['label' => 'Категории', 'url' => ['category/index']],
            ['label' => 'Города', 'url' => ['city/index']],
            ['label' => 'Документы', 'url' => ['document/index']]
    ];
    if(Yii::$app->user->can('admin')){
        $navItems[] = ['label' => 'Сотрудники', 'url' => ['user/index']];
    }
    echo yii\bootstrap5\Nav::widget([
        'options' => ['class' => 'navbar-nav col-md-9 m-0 p-0 text-center'],
        'items' => $navItems
    ]);
    echo yii\bootstrap5\Nav::widget([
        'options' => ['class' => 'navbar-nav d-flex justify-content-md-end col-md-3 text-center'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/login']]
                : '<li class="nav-item">'
                . yii\bootstrap5\Html::beginForm(['/logout'])
                . yii\bootstrap5\Html::submitButton((strlen(Yii::$app->user->identity->snm) > 10)
                ? 'Выйти<span class="d-none d-xl-inline">(' . Yii::$app->user->identity->snm . ')</span>'
                : 'Выйти(' . Yii::$app->user->identity->snm . ')',
                    ['class' => 'nav-link btn btn-link logout text-center']
                )
                . yii\bootstrap5\Html::endForm()
                . '</li>'
        ]
    ]);
    yii\bootstrap5\NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0 col-12" role="main" style="margin-top: 60px;">
        <?php if(!empty($this->params['breadcrumbs'])):?>
            <?= '<div class="m-1 m-md-2">' . yii\bootstrap5\Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) . '</div>'; ?>
        <?php endif; ?>
        <?= app\widgets\Alert::widget(); ?>
        <?= $content; ?>
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