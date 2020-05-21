<?php
declare(strict_types=1);

use app\components\tools\urls\CrumbsNodes;
use app\components\tools\yii\CTHtml;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use app\modules\administrator\models\Ingredients;
use yii\web\YiiAsset as YiiAssetAlias;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\Dishes */
/* @var $controller app\modules\system\controllers\UsersController */

$controller = $this->context;
$this->title = $model->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);

$this->registerCssFile('@web/css/view.css');
YiiAssetAlias::register($this);

$adp = new ActiveDataProvider([
    'query' => $model->getIngredients()
]);
?>
<div class="dishes-view">

    <div class="btn-group" role="group" aria-label="...">
        <?php
        echo CTHtml::aBtn('@back', $controller->getUrl());
        if (!($controller->activePage->status & CrumbsNodes::PROVIDER)) {
            echo CTHtml::aBtn('@delete', ['delete', 'id' => $model->id]);
            echo CTHtml::aBtn('@update', ['update', 'id' => $model->id]);
        }
        ?>
    </div>

    <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'ingredientsString',
                [
                    'attribute' => 'available',
                    'format' => 'raw',
                    'value' => static function ($model) {
                        /* @var $model Ingredients */
                        return CTHtml::bool($model->available);
                    },
                ],
            ],
        ])
?>

    <h2>Ингредиенты</h2>
    <p>
        <?= CTHtml::aBtn('@add', ['add-ingredients', 'id' => $model->id, '_node' => 0]) ?>
    </p>

    <?=
        GridView::widget([
            'dataProvider' => $adp,
            'columns' => [
                ['class' => SerialColumn::class,
                    'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
                ],

                'title',
                [
                    'label' => 'Доступен',
                    'content' => static function ($model) {
                        /* @var $model Ingredients */
                        return CTHtml::bool((bool) $model->available);
                    },
                    'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                ],

                ['class' => ActionColumn::class,
                    'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                    'template' => '{update} {delete} {remove}',
                    'buttons' => [
                        'update' => static function ($url, Ingredients $ingredient, $key) {
                            $uri = ['ingredients/update', 'id' => $ingredient->id, '_node' => 0];
                            return CTHtml::aBtnGV('@update', $uri);
                        },
                        'delete' => static function ($url, Ingredients $ingredient, $key) {
                            $uri = ['ingredients/delete', 'id' => $ingredient->id, '_node' => 0];
                            return CTHtml::aBtnGV('@delete', $uri);
                        },
                        'remove' => static function ($url, Ingredients $ingredient, $key) use ($model) {
                            $uri = ['delete-ingredient', 'did' => $model->id, 'id' => $ingredient->id];
                            return CTHtml::aBtnGV('@remove', $uri);
                        },
                    ],
                ],
            ],
        ]) ?>

</div>
