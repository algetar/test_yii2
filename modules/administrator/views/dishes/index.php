<?php
declare(strict_types=1);

use yii\grid\ActionColumn;
use app\components\tools\yii\CTHtml;
use yii\grid\SerialColumn;
use yii\grid\GridView;
use app\modules\administrator\models\Dishes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $controller app\modules\system\controllers\UsersController */

$controller = $this->context;
$this->title =  $controller->activePage->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs();

?>
<div class="dishes-index">

    <p>
        <?= CTHtml::aBtn('@add', ['create']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class,
                'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
            ],
            
            [
                'attribute' => 'title',
                'contentOptions' =>['style'=>'width: 210px;'],
            ],
            'ingredientsString',
            [
                'attribute' => 'available',
                'content' => static function (Dishes $model) {
                    return CTHtml::bool((bool)$model->available);
                },
                'contentOptions' => ['style' => 'width: 90px;text-align: center;'],
            ],
            
            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 90px;text-align: center;'],
            ],
        ],
    ]) ?>

</div>
