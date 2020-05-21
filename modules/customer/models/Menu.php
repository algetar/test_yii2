<?php
declare(strict_types=1);

namespace app\modules\customer\models;

use app\components\tools\models\selector\Selector;
use app\modules\administrator\models\Dishes;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Description of Menu
 *
 * @property array $ingredientsIds
 *
 * @author tga
 */
class Menu extends Model
{
    /* выбранные ингредиенты */
    public Selector $selector;

    /* Блюда с искомыми ингредиентами в формате [$dishId => count] */
    private array $_dishes = [];

    /* выбранные значения в виде массива */
    private array $_selected = [];

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
        $this->selector = new Selector();
        $this->selector->choiceLimit = 5;
    }

    /**
     * Поиск подходящих блюд
     */
    public function findDishes(): void
    {
        $ids = explode(',', $this->selector->choiceIds);
        $count = count($ids);
        if ($count < 2) {
            $this->_dishes[] = 'Выберите больше ингредиентов';
            return;
        }
        
        $this->_dishes = [];
        $dishes = ArrayHelper::index(Dishes::find()->all(), 'id');
        foreach ($ids as $id) {
            $this->scan($dishes, $id);
        }
        
        if ($this->_dishes === []) {
            $this->_dishes[] = 'Ничего не найдено';
            return;
        }
        
        arsort($this->_dishes);
        $this->_dishes = $this->reorderDishes($dishes);
    }

    /**
     * @param $id
     * @return bool
     */
    public function selected($id): bool
    {
        if ('' === $this->selector->choiceIds) {
            return false;
        }

        if ([] === $this->_selected) {
            $this->_selected = explode(',', $this->selector->choiceIds);
        }

        return in_array($id, $this->_selected, false);
    }

    /**
     * Преобразуем результат выборки
     * @param Dishes[] $dishes
     *
     * @return array
     */
    private function reorderDishes($dishes): array
    {
        $max    = false;
        $result = [];
        foreach ($this->_dishes as $id => $count) {
            if ($count === $this->selector->choiceLimit) {
                $max = true;
                $result[] = $dishes[$id]->title . '(' . $dishes[$id]->ingredientsString . ')';
                continue;
            }
            
            if ($max && $count === $this->selector->choiceLimit) {
                $result[] = $dishes[$id]->title . '(' . $dishes[$id]->ingredientsString . ')';
                continue;
            }
            
            if ($count < 2 || ($max && $count < $this->selector->choiceLimit)) {
                break;
            }
            
            $result[] = $dishes[$id]->title . '(' . $dishes[$id]->ingredientsString . ')';
        }
        
        return $result;
    }

        /**
     * Определяет все блюда, содержащие ингредиент
     * с id равным $id
     * @param Dishes[] $dishes
     * @param int      $id
     */
    private function scan($dishes, $id): void
    {
        foreach ($dishes as $dish) {
            if (!$dish->available) {
                continue;
            }
            
            if (!in_array($id, $dish->getIngredientsIds(), false)) {
                continue;
            }
            
            if (array_key_exists($dish->id, $this->_dishes)) {
                $this->_dishes[$dish->id]++;
            } else {
                $this->_dishes[$dish->id] = 1;
            }
        }
    }
    
    /**
     * Список подходящих блюд
     * @return array
     */
    public function getDishesList(): array
    {
        return $this->_dishes;
    }
}
