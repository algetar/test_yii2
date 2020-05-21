<?php
declare(strict_types=1);

use app\modules\system\models\Domains;

$this->title = Domains::getDomain(Yii::$app->controller->module->id)->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="administrator-default-index">
    <h1>Модуль администратора</h1>
    <code>app\modules\administrator\administrator</code>
        Ввод ингредиентов, приготовление блюд,<br>
    <p style="margin-top: 25px">
        Заполнение списка ингредиентов {@see app\modules\administrator\models\Ingredients} <br>
    </p>
    <p style="margin-top: 25px">
        Заполнение списка блюд {@see app\modules\administrator\models\Dishes} <br>
    </p>
    <p style="margin-top: 25px">
        Приготовление блюд в действии <code>view</code> контроллера {@see app\modules\administrator\Controllers\DishesController} <br>
    </p>
</div>
