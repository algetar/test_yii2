<?php
declare(strict_types=1);

namespace app\components\tools\controller;

use app\components\tools\urls\CrumbsNodes;

/**
 * Interface KnotControllerInterface
 * Интерфейс для контроллеров участвующих в редактировании
 * данных в нескольких формах, и обеспечивают корректный возврат.
 */
interface KnotControllerInterface
{
    /**
     * Цепочка узлов текущего контроллера
     * @return CrumbsNodes
     */
    public function getCrumbNodes(): CrumbsNodes;

    /**
     * Текущее действие
     * @return ActivePage
     */
    public function getActivePage(): ActivePage;
}
