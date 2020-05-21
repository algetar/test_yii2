<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dishes_ingredients".
 *
 * @property int $dish_id Ид блюда
 * @property int $ingredient_id Ид ингредиента
 *
 * @property Dishes $dish
 * @property Ingredients $ingredient
 */
class TblDishesIngredients extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'dishes_ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['dish_id', 'ingredient_id'], 'required'],
            [['dish_id', 'ingredient_id'], 'integer'],
            [['dish_id', 'ingredient_id'], 'unique', 'targetAttribute' => ['dish_id', 'ingredient_id']],
            [['dish_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dishes::class, 'targetAttribute' => ['dish_id' => 'id']],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredients::class, 'targetAttribute' => ['ingredient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'dish_id' => 'Ид блюда',
            'ingredient_id' => 'Ид ингредиента',
        ];
    }

    /**
     * Gets query for [[Dish]].
     *
     * @return ActiveQuery
     */
    public function getDish(): ActiveQuery
    {
        return $this->hasOne(Dishes::class, ['id' => 'dish_id']);
    }

    /**
     * Gets query for [[Ingredient]].
     *
     * @return ActiveQuery
     */
    public function getIngredient(): ActiveQuery
    {
        return $this->hasOne(Ingredients::class, ['id' => 'ingredient_id']);
    }
}
