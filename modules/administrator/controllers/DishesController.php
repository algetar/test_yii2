<?php
declare(strict_types=1);

namespace app\modules\administrator\controllers;

use app\components\tools\controller\KnotControllerInterface;
use app\components\tools\controller\KnotControllerTrait;
use app\components\tools\models\selector\Selector;
use app\modules\administrator\models\Dishes;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DishesController implements the CRUD actions for Dishes model.
 */
class DishesController extends Controller implements KnotControllerInterface
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
     * Lists all Dishes models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Dishes::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dishes model.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Dishes::prepare($id),
        ]);
    }

    /**
     * Creates a new Dishes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $model = Dishes::prepare();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dishes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Dishes::prepare($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dishes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException|Throwable
     */
    public function actionDelete($id)
    {
        Dishes::prepare($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Предлагает список ингредиентов для выбора
     * @param int $id ид блюда
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddIngredients($id)
    {
        $model = Dishes::prepare($id);
        $selector = new Selector();

        if ($selector->load(Yii::$app->request->post()) && $model->applyChoice($selector)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('add-ingredients', [
            'model' => $model, 'selector' => $selector
        ]);
    }
    
    /**
     * Удаляет ингредиент из блюда
     * @param int $did
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteIngredient($did, $id)
    {
        $model = Dishes::prepare($did);
        $model->deleteIngredient((int) $id);

        return $this->redirect(['view', 'id' => $did]);
    }
}
