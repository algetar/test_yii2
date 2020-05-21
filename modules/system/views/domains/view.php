<?php
declare(strict_types=1);

use app\components\tools\urls\CrumbsNodes;
use app\components\tools\yii\CTHtml;
use app\modules\system\models\Domains;
use app\modules\system\models\Sites;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Domains */
/* @var $controller app\modules\system\controllers\UsersController */
$controller = $this->context;

$this->title = 'Модуль: ' . $model->cid;
$this->params['breadcrumbs'] = $controller->crumbNodes->getCrumbs($this->title);

$this->registerCssFile('@web/css/view.css');

YiiAsset::register($this);

$dataProvider = new ActiveDataProvider([
    'query' => $model->getSites(),
]);

?>
<div class="domains-view">

    <div class="btn-group" role="group" aria-label="...">
        <?php
        echo CTHtml::aBtn('@back', $controller->getUrl());
        if (!($controller->activePage->status & CrumbsNodes::PROVIDER)) {
            echo CTHtml::aBtn('@delete', ['delete', 'id' => $model->id]);
            echo CTHtml::aBtn('@update', ['update', 'id' => $model->id]);
        }
        ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'title',
                'captionOptions' => ['style' => ['width' => '200px']],
            ],
            'cid',
            'native_cid',
            'index',
            [
                'label' => 'Главная страница',
                'format' => 'raw',
                'value' => static function (Domains $model) {
                    return CTHtml::a($model->homeSite->url, $model->homeSite->url);
                }
            ],
        ],
    ]) ?>

    <p>
        <?= CTHtml::aBtn('@add', ['sites/create', 'id' => $model->id, '_node' => 0]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class,
                'contentOptions' => ['style' => 'width: 30px; text-align: center;'],
            ],

            'title',
            'url:html',

            ['class' => ActionColumn::class,
                'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => static function ($url, Sites $site, $key) {
                        return CTHtml::aBtnGV(
                            '@update:',
                            ['sites/update', 'id' => $site->id, '_node' => 0],
                        );
                    },
                    'delete' => static function ($url, Sites $site, $key) {
                        return CTHtml::aBtnGV(
                            '@delete:',
                            ['sites/delete', 'id' => $site->id, '_node' => 0],
                        );
                    },
                ],
            ],
        ],
    ]) ?>

</div>
