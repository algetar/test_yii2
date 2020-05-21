<?php
declare(strict_types = 1);

namespace app\modules\system\controllers;

use app\components\tools\controller\KnotControllerInterface;
use app\components\tools\controller\KnotControllerTrait;
use app\components\tools\models\selector\Selector;
use app\components\tools\urls\CrumbsNodes;
use app\modules\system\models\Users;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UsersController extends Controller implements KnotControllerInterface
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
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
        $this->_statusOfActions['create'] = CrumbsNodes::CUSTOMER;
        $this->_statusOfActions['update'] = CrumbsNodes::CUSTOMER;
        $this->_statusOfActions['add-domains'] = CrumbsNodes::CUSTOMER;
    }

    /**
     * Lists all Users models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Domains model.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Users::getUser($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $model = Users::getUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Users::getUser($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = Users::getUser($id);
        $user->delete();

        return $this->redirect('index');
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionAddDomains($id)
    {
        $model = Users::getUser($id);
        $selector = new Selector();

        if ($selector->load(Yii::$app->request->post()) && $model->applyChoice($selector)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('add-domains', [
            'model' => $model, 'selector' => $selector
        ]);
    }

    /**
     * @param $uid
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteDomain($uid, $id): Response
    {
        $model      = Users::getUser($uid);
        $userDomain = $model->getUsersDomain($id);
        if (null === $userDomain) {
            throw new NotFoundHttpException(sprintf('The requested User.%s Domain.%s does not exist.', $uid, $id));
        }

        $userDomain->delete();

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
