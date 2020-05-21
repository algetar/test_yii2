<?php
declare(strict_types=1);

use yii\bootstrap\Html;

$this->title = 'Стартовая страница';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= $this->title ?></h1>
    </div>
        <?php
        if (Yii::$app->user->isGuest) {
            echo '<p>для продолжения работы необходима ' . Html::a('авторизация', ['/site/login']) . '</p>';
            echo '<p><bold>sa</bold> - администратор системы</p>';
            echo '<p><bold>Администратор</bold> - роль администратора</p>';
            echo '<p><bold>Заказчик</bold> - роль заказчика</p>';
        }
        ?>
    
    <div class="body-content">
        
    </div>
</div>
