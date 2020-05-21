<?php
declare(strict_types=1);

namespace app\modules\system\controllers;

use app\components\tools\controller\KnotControllerInterface;
use app\components\tools\controller\KnotControllerTrait;
use app\modules\system\models\Sites;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SitesController implements the CRUD actions for Sites model.
 */
class SitesController extends Controller implements KnotControllerInterface
{
    use KnotControllerTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sites models.
     * @param int|null $id
     *
     * @return string
     */
    public function actionIndex(int $id = null): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sites::find()->where(['domain_id' => $id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Sites model.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $id)
    {
        $model = Sites::prepare($id, true);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Sites::prepare($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        Sites::prepare($id)->delete();

        return $this->redirect('index');
    }
}
