<?php
declare(strict_types=1);

use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use app\components\tools\yii\CTHtml;
use yii\grid\GridView;
use app\modules\administrator\models\Ingredients;
use yii\web\YiiAsset;
use app\modules\administrator\models\Dishes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Dishes */
/* @var $selector app\components\tools\models\selector\Selector */
/* @var $controller app\modules\system\controllers\UsersController */

$controller = $this->context;
$this->title = 'Выбор ингредиентов блюда: ' . $model->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);

$dataProvider = new ActiveDataProvider([
    'query' => $model->getNotDishIngredients(),
    'pagination' => ['pageSize' => 50],
]);

YiiAsset::register($this);
?>

<div class="clients-index">

    <p>
        <?= CTHtml::aBtn('@add', ['ingredients/create', '_node' => 0]) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'grid',
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 42px;text-align: center;']
            ],
            ['class' => CheckboxColumn::class,
                'contentOptions' => ['style' => 'width: 42px;text-align: center;'],
                'checkboxOptions' => ['class' => 'selector'],
            ],
            'title',
            [
                'attribute' => 'available',
                'content' => static function (Ingredients $model) {
                    return CTHtml::bool((bool) $model->available);
                },
                'contentOptions' => ['style' => 'width: 90px;text-align: center;'],
            ],
            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => static function ($url, Ingredients $ingredient, $key) {
                        $uri = ['ingredients/update', 'id' => $ingredient->id, '_node' => 0];
                        return CTHtml::aBtnGV('@update', $uri);
                    },
                    'delete' => static function ($url, Ingredients $ingredient, $key) {
                        $uri = ['ingredients/delete', 'id' => $ingredient->id, '_node' => 0];
                        return CTHtml::aBtnGV('@delete', $uri);
                    },
                ],
            ],
        ]
    ])?>

    <?= $this->render(Yii::$app->params['selector'] . '/selector-view', [
        'selector' => $selector,
    ]) ?>

</div>
