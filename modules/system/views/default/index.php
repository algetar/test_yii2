<?php
declare(strict_types=1);

use app\modules\system\models\Domains;

$this->title = Domains::getDomain(Yii::$app->controller->module->id)->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-default-index">
    <h1>Системный модуль</h1>
    <code>app\modules\system\system</code>
    Управление учетными записями.
    <p style="margin-top: 25px">
        Каждому пользователю {@see Users} соответствует роль - role,
    </p>
    <lu>
        <li>0 => sa,</li>
        <li>1 => Администратор,</li>
        <li>2 => Заказчик.</li>
    </lu>
    <p style="margin-top: 25px">
        Каждой роли соответствует модуль {@see Domains},<br>
        url этого модуля считается домашней страницей пользователя.
    </p>
    <lu>
        <li>0 => <code>app\modules\system\system</code>,</li>
        <li>1 => <code>app\modules\administrator\administrator</code>,</li>
        <li>2 => <code>app\modules\customer\customer</code>.</li>
    </lu>
    
</div>
