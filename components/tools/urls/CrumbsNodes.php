<?php
declare(strict_types=1);

namespace app\components\tools\urls;

use app\components\tools\controller\ActivePage;

/**
 * Class CrumbsNodes
 */
class CrumbsNodes extends CrumbsNodesObject
{
    /* статусы узлов  */
    public const DEFAULT  = 0; //Цепочка по умолчанию
    public const PROVIDER = 1; //Цепочка читается из сессии
    public const CUSTOMER = 2; //Цепочка сохраняется в сессии

    /* текущее действие */
    private ActivePage $_action;

    /**
     * CrumbsNodes constructor.
     * @param ActivePage $action
     */
    public function __construct(ActivePage $action)
    {
        parent::__construct();

        $this->_action = $action;
        if ($action->status & self::PROVIDER) {
            $this->read();

            $id = $this->find($action->getRoute());
            if ($id >= 0) {
                //Уже в цепочке, обрезаем цепочку до найденного узла.
                $this->splice($id);
            }
        } else {
            if ($action->isDomain) {
                return;
            }
            $this->setNode($action->getNode('domain'));

            if ($action->isSite) {
                return;
            }
            $this->setNode($action->getNode('site'));
        }
    }

    /**
     * @param array| string $url
     * @param string|null $title
     *
     * @return $this
     */
    public function addNode($url, ?string $title = null): CrumbsNodes
    {
        $this->setNode(FUrl::url($url, $title)->node);

        return $this;
    }

    /**
     * Создает массив в виде params['breadcrumbs'].
     * Если текущий статус действия содержит self::CUSTOMER,
     * то вся цепочка сохраняется в текущей сессии.
     * @param string|null $title
     *
     * @return array
     */
    public function getCrumbs(?string $title = null): array
    {
        if (null !== $title) {
            $this->_action->title = $title;
        }

        if ($this->_action->status & self::CUSTOMER) {
            $this->write($this->_action->getNode());
        }

        $crumbs = $this->build();
        $crumbs[] = $this->_action->title;

        return $crumbs;
    }

    /**
     * @return array|string
     */
    public function getUrl()
    {
        return parent::getUrl();
    }

}
