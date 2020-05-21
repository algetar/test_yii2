<?php
declare(strict_types=1);

use app\modules\customer\models\Menu;
use yii\helpers\Html;
use app\modules\administrator\models\Ingredients;

/* @var $this yii\web\View */
/* @var $model Menu */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/menu.css', ['depends' => yii\bootstrap\BootstrapAsset::class]);
?>
<div class="customer-default-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Необходимо выбрать не менее 2 ингредиентов
    </p>
    <table>
        <tbody>
        <tr>
            <th class="ingredients">Ингредиенты</th>
            <th class="dishes">Блюда</th>
        </tr>

        <tr>
            <td class='ingredients'>
                <ul class="list-group">
                    <?php
                    /* @var $ingredient Ingredients */
                    foreach (Ingredients::find()->all() as $ingredient) {
                        $chkBox = Html::checkbox(
                            'title',
                            $model->selected($ingredient->id),
                            ['class' => 'selector', 'value' => $ingredient->id]
                        );
                        echo Html::tag('li', $chkBox . ' ' . $ingredient->title, ['class' => 'list-group-item']);
                    }
                    ?>
                </ul>
            </td>
            <td class="dishes">
                <ul>
                    <?php
                    foreach ($model->getDishesList() as $dish) {
                        echo Html::tag('p', $dish) ;
                    }
                    ?>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>

    <?= $this->render(Yii::$app->params['selector'] . '/selector-view', [
        'selector' => $model->selector,
    ]) ?>

</div>
