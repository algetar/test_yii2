<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

/**
 * Description of DishesIngredients
 *
 * @author tga
 */
class DishesIngredients extends TblDishesIngredients
{
    
    /**
     * Добавляет запись
     * @param int $dishId
     * @param int $ingredientId
     */
    public static function add($dishId, $ingredientId): void
    {
        $model = new static();
        $model->dish_id       = $dishId;
        $model->ingredient_id = $ingredientId;
        
        $model->save();
    }
}
