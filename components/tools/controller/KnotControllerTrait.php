<?php
declare(strict_types=1);

namespace app\components\tools\controller;

use app\components\tools\urls\CrumbsNodes;
use app\components\tools\urls\FUrl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Trait KnotControllerTrait
 *  Реализует механизм создание цепочки {@see Breadcrumbs}, а также дает возможность
 * возвращаться к форме редактировании при добавлении в нее данных из другого
 * контроллера. Управление цепочкой осуществляется в классе {@see CrumbsNodes}
 *
 * Реализация:
 *  Включает текущее действие текущего контроллера в цепочку узлов, в
 * зависимости от его статуса: $status.
 *  Если в списке параметров текущего действия есть параметр '_node',
 * то к статусу текущего действия добавляется статус {@see CrumbsNodes::PROVIDER}.
 *  Массив _statusOfActions содержит список действий со специальным статусом.
 *  Если статус действия содержит {@see CrumbsNodes::PROVIDER}, то цепочка будет считана
 * из сессии, иначе будет создана цепочка: 'индекс модуля'->'индекс контроллера'.
 * Далее, к ним будет добавлено текущее действие в виде узла.
 * В "рендере" при построении цепочки Breadcrumbs задается наименование действия,
 * и, если действие имеет статус {@see CrumbsNodes::CUSTOMER}, вся цепочка сохраняется в сессии.
 *
 * @property CrumbsNodes $crumbNodes
 * @property ActivePage $activePage
 */
trait KnotControllerTrait
{
    /* цепочка узлов операций текущего app */
    private ?CrumbsNodes $_nodes = null;

    /* текущее действие */
    private ?ActivePage $_activePage = null;

    /* список действий со специальным статусом  */
    private array $_statusOfActions = [
        'view' => CrumbsNodes::CUSTOMER,
    ];

    /**
     * @return CrumbsNodes
     */
    public function getCrumbNodes(): CrumbsNodes
    {
        if (null === $this->_nodes) {
            $this->_nodes = new CrumbsNodes($this->activePage);
        }

        return $this->_nodes;
    }

    /**
     * @return ActivePage
     * @throws NotFoundHttpException
     */
    public function getActivePage(): ActivePage
    {
        if (null === $this->_activePage) {
            $url = FUrl::url();

            if (array_key_exists($url->page, $this->_statusOfActions)) {
                $url->status |= $this->_statusOfActions[$url->page];
            }

            if (array_key_exists('_node', $url->params)) {
                $url->status |= CrumbsNodes::PROVIDER;
            }

            $this->_activePage = new ActivePage($url);
        }

        return $this->_activePage;
    }

    /**
     * Урл возврата
     * @param array|string|null $default
     *
     * @return array|string
     */
    public function getUrl($default = null)
    {
        if ($this->activePage->status & CrumbsNodes::PROVIDER) {
            return $this->crumbNodes->getUrl();
        }

        return $default ?? 'index';
    }

    /**
     * {@inheritDoc}
     */
    public function redirect($url, $statusCode = 302): Response
    {
        return parent::redirect($this->getUrl($url), $statusCode);
    }
}
