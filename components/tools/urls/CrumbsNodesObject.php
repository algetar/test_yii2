<?php
declare(strict_types=1);

namespace app\components\tools\urls;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidCallException;

/**
 * Class CrumbsNodesComponent
 *
 * @property array $node
 * @property string $title
 */
class CrumbsNodesObject extends BaseObject
{
    public const SESSION_NAME = 'crumbs-nodes';

    /* Список узлов */
    private array $_nodes = [];

    /* Ид текущего узла, по умолчанию указывает на последний */
    private int $_id = -1;

    /**
     * Сохраняет данные в сессии
     * @param $node
     */
    protected function write($node): void
    {
        $nodes = $this->_nodes;
        $nodes[] = $node;
        Yii::$app->session->set(self::SESSION_NAME, $nodes);
    }

    /**
     * Строит BreadCrumbs массив
     * @return array
     */
    protected function build(): array
    {
        $result = [];
        for ($this->_id = 0, $loopsMax = count($this->_nodes); $this->_id < $loopsMax; $this->_id++) {
            $result[] = ['label' => $this->title, 'url' => $this->getUrl()];
        }
        $this->_id = $this->getLastId();

        return $result;
    }

    /**
     * Читает данные из сессии
     */
    protected function read(): void
    {
        $this->_nodes = (array) Yii::$app->session->get(self::SESSION_NAME);
        $this->_id = $this->getLastId();
    }

    /**
     * @param string $route
     *
     * @return int
     */
    protected function find(string $route): int
    {
        $id = -1;
        for ($this->_id = 0, $loopsMax = count($this->_nodes); $this->_id < $loopsMax; $this->_id++) {
            if ($this->getRoute() === $route) {
                $id = $this->_id;
                break;
            }
        }
        $this->_id = $this->getLastId();

        return $id;
    }

    /**
     * Укорачивает цепочку до указанного индекса.
     * Последний узел становится текущим.
     * @param $id
     */
    protected function splice($id): void
    {
        if ($id <= $this->getLastId()) {
            array_splice($this->_nodes, $id);
            $this->_id = $this->getLastId();
        }
    }

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return $this->node['title'];
    }

    /**
     * @param stri $title
     */
    protected function setTitle(stri $title): void
    {
        $this->node['title'] = $title;
    }

    /**
     * Текущий узел
     * @return array
     */
    protected function getNode(): array
    {
        if ($this->_id >= 0 && array_key_exists($this->_id, $this->_nodes)) {
            return $this->_nodes[$this->_id];
        }

        throw new InvalidCallException(sprintf('Список узлов не определен: %s::node', get_class($this)));
    }

    /**
     * Добавляет узел в конец цепочки, делает его текущим.
     * @param array $value
     *
     * @return int
     */
    protected function setNode(array $value): int
    {
        $this->_id = count($this->_nodes);
        $this->_nodes[] = $value;

        return $this->_id;
    }

    /**
     * @return array|string
     */
    protected function getUrl()
    {
        if ($this->_id < 0) {
            return '';
        }

        $url = $this->getRoute();
        if (array_key_exists('params', $this->node) && ([] !== $this->node['params'])) {
            $url = array_merge([$url], $this->node['params']);
        }

        return $url;
    }

    /**
     * @return string
     */
    private function getRoute(): string
    {
        return FUrl::createRoute($this->node['domain'], $this->node['site'], $this->node['page']);
    }

    /**
     * @return int
     */
    private function getLastId(): int
    {
        return count($this->_nodes) - 1;
    }
}
