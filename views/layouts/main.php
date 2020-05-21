<?php
declare(strict_types=1);

/* @var $this View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\tools\yii\CTHtml;
use app\modules\system\models\Users;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    /* @var $user Users */
    $user = Yii::$app->user->identity ?? null;
    NavBar::begin([
        'brandLabel' => 'Главная',
        'brandUrl' => '/site/index',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    try {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Домой', 'url' => $user ? $user->home : ['/site/index']],
                ['label' => 'О программе', 'url' => ['/site/about']],
                ['label' => 'Контакты', 'url' => ['/site/contact']],
                Yii::$app->user->isGuest ? (
                    ['label' => 'Вход', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Выход (' . $user->name . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
    } catch (Exception $e) {
    }
    NavBar::end();
    ?>

    <div class="container">
    <?php
        $content =
            Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) .
            Alert::widget() .
            $content;

        if ($user && $user->hasMenu) {
            $content = CTHtml::table(CTHtml::tr(
                CTHtml::td($content, ['class' => 'content-block']) .
                    CTHtml::td(CTHtml::userMenu($user), ['class' => 'menu-block'])
            ));
        }
        ?>

        <?= $content ?>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
