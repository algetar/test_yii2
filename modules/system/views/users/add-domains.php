<?php
declare(strict_types=1);

use app\components\tools\yii\CTHtml;
use app\modules\system\models\Domains;
use app\modules\system\models\Users;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model Users */
/* @var $selector app\components\tools\models\selector\Selector */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $controller app\modules\system\controllers\UsersController */

$controller = $this->context;
$this->title = 'Выбор модулей пользователя: ' . $model->name;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);

$dataProvider = new ActiveDataProvider([
    'query' => $model->getNotUserDomains(),
    'pagination' => ['pageSize' => 50],
]);

YiiAsset::register($this);
?>

<div class="users-add-modules">

    <p>
        <?= CTHtml::aBtn('@add', ['domains/create', '_node' => 0]) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'grid',
        'columns' => [
            ['class' => SerialColumn::class,
                'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
            ],

            ['class' => CheckboxColumn::class,
                'contentOptions' => ['style' => ['width' => '42px', 'text-align' => 'center']],
                'checkboxOptions' => ['class' => 'selector'],
            ],

            'cid',
            'url:html',

            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                'buttons' => [
                    'view' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/view', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@view', $uri);
                    },
                    'update' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/update', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@update', $uri);
                    },
                    'delete' => static function ($url, Domains $domains, $key) {
                        $uri = ['domains/delete', 'id' => $domains->id, '_node' => 0];
                        return CTHtml::aBtnGV('@delete', $uri);
                    },
                ],
            ],
        ]
    ]) ?>

    <?= $this->render(Yii::$app->params['selector'] . '/selector-view', [
        'selector' => $selector,
    ]) ?>

</div>
