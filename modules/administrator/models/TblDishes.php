<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dishes".
 *
 * @property int $id Ид блюда
 * @property string $title Наименование блюда
 *
 * @property DishesIngredients[] $dishesIngredients
 * @property Ingredients[] $ingredients
 */
class TblDishes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'dishes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'Ид блюда',
            'title' => 'Наименование блюда',
        ];
    }

    /**
     * Gets query for [[DishesIngredients]].
     *
     * @return ActiveQuery
     */
    public function getDishesIngredients(): ActiveQuery
    {
        return $this->hasMany(DishesIngredients::class, ['dish_id' => 'id']);
    }

    /**
     * Gets query for [[Ingredients]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getIngredients(): ActiveQuery
    {
        return $this->hasMany(Ingredients::class, ['id' => 'ingredient_id'])->viaTable('dishes_ingredients', ['dish_id' => 'id']);
    }
}
