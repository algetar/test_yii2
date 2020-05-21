<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

use app\components\tools\models\selector\Selector;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Description of Dishes
 *
 * @property string $ingredientsString список ингредиентов
 * @property bool $available Один или несколько из ингредиентов не доступны
 * @property array $ingredientsIds ключи ингредиентов
 * @author tga
 */
class Dishes extends TblDishes
{
    /**
     * Один или несколько из ингредиентов не доступны
     *
     * @var bool
     */
    private ?bool $_available = null;
    
    /**
     * Список ингредиентов виде строки
     *
     * @var string
     */
    private ?string $_ingredientsString = null;
    
    /**
     * Массив ключей ингредиентов блюда
     *
     * @var array
     */
    private array $_ids = [];

    /**
     * Ключи ингредиентов блюда
     *
     * @return array
     */
    public function getIngredientsIds(): array
    {
        if ($this->_ingredientsString === null) {
            $this->getIngredientsString();
        }
        return $this->_ids;
    }

    /**
     * Дополнительные аттрибуты
     *
     * @return array
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'ingredientsString' => 'Ингредиенты',
            'available' => 'Доступно'
        ]);
    }
    /**
     * Сохраняет выбранные модули
     *
     * @param Selector $selector
     * @return bool
     */
    public function applyChoice(Selector $selector): bool
    {
        $ids = explode(',', $selector->choiceIds);
        foreach ($ids as $id) {
            DishesIngredients::add($this->id, $id);
        }

        return true;
    }

    /**
     * Все ингредиенты, за исключением ингредиентов текущего блюда
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getNotDishIngredients(): ActiveQuery
    {
        $ingredients = $this->getIngredients()->asArray()->all();
        $ids = ArrayHelper::getColumn($ingredients, 'id');

        if ([] === $ids) {
            return Ingredients::find();
        }

        return Ingredients::find()
            ->where('NOT `id` IN (' . implode(',', $ids) . ')');
    }

    /**
     * @param int|string|null $id
     *
     * @return Dishes
     * @throws NotFoundHttpException
     */
    public static function prepare($id = null): Dishes
    {
        $model = null;
        if (null === $id) {
            $model = new static();
        } else {
            $model = static::findOne($id);

            if ($model === null) {
                throw new NotFoundHttpException(sprintf('The requested Dish.%s does not exist.', $id));
            }
        }

        return $model;
    }


    /**
     * список ингредиентов
     *
     * @return string
     */
    public function getIngredientsString(): ?string
    {
        if ($this->_ingredientsString === null) {
            $this->_ingredientsString = '';
            $this->_available = true;
            $this->_ids = [];
            foreach ($this->ingredients as $ingredient) {
                $this->_available = $this->_available && $ingredient->available;
                $this->_ids[] = $ingredient->id;
                $this->_ingredientsString .=
                        ($this->_ingredientsString ? ', ': '') . $ingredient->titleStatus;
            }
        }
        
        return $this->_ingredientsString;
    }
    
    /**
     * Один или несколько из ингредиентов не доступны
     *
     * @return bool
     */
    public function getAvailable(): ?bool
    {
        if ($this->_available === null) {
            $this->_ingredientsString = null;
            $this->getIngredientsString();
        }
        
        return $this->_available;
    }

    /**
     * @param $id
     *
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteIngredient($id): void
    {
        foreach ($this->dishesIngredients as $dishesIngredient) {
            if ($dishesIngredient->ingredient_id === $id) {
                $dishesIngredient->delete();
            }
        }
    }
}
