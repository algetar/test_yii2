<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

use yii\web\NotFoundHttpException;

/**
 * Description of Ingredients
 *
 * @property string $titleStatus
 *
 * @author tga
 */
class Ingredients extends TblIngredients
{
    
    /**
     * @param int|null $id
     *
     * @return Ingredients
     * @throws NotFoundHttpException
     */
    public static function prepare($id = null): Ingredients
    {
        $model = null;
        if (null === $id) {
            $model = new static();
            $model->available = true;
        } else {
            $model = static::findOne($id);

            if ($model === null) {
                throw new NotFoundHttpException(sprintf('The requested Ingredient.%s does not exist.', $id));
            }
        }

        return $model;
    }

    /**
     * Наименование недоступного ингредиента
     * выделяет фигурными скобками.
     * @return string
     */
    public function getTitleStatus(): string
    {
        return $this->available ? $this->title : '{' . $this->title . '}';
    }
}
