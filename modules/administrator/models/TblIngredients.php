<?php
declare(strict_types=1);

namespace app\modules\administrator\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ingredients".
 *
 * @property int $id Ид записи
 * @property string $title Наименование блюда
 * @property bool $available Доступен
 *
 * @property DishesIngredients[] $dishesIngredients
 * @property Dishes[] $dishes
 */
class TblIngredients extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['available'], 'boolean'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'Ид записи',
            'title' => 'Наименование блюда',
            'available' => 'Доступен',
        ];
    }

    /**
     * Gets query for [[DishesIngredients]].
     *
     * @return ActiveQuery
     */
    public function getDishesIngredients(): ActiveQuery
    {
        return $this->hasMany(DishesIngredients::class, ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getDishes(): ActiveQuery
    {
        return $this->hasMany(Dishes::class, ['id' => 'dish_id'])->viaTable('dishes_ingredients', ['ingredient_id' => 'id']);
    }
}
