<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\modules\system\models\Domains;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title =  $controller->activePage->title;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs();
?>
<div class="domains-index">

    <p>
        <?= CTHtml::aBtn('@add', ['create']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '№',
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
            ],

            'title',
            'cid',
            'native_cid',
            'index',
            [
                'attribute' => 'available',
                'content' => static function (Domains $model) {
                    return CTHtml::bool($model->available);
                },
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
            ],

            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
            ],
        ],
    ]) ?>

</div>
