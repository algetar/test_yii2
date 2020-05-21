<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m200501_061003_testnsign
 */
class m200501_061003_testnsign extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        /*
        -----------------------------------------------------------------------
        -- Дамп данных таблицы ingredients
        -- Ингредиенты блюд
        -----------------------------------------------------------------------
        */
        $this->createTable('ingredients', [
            'id'        => $this->primaryKey()->comment('Ид записи'),
            'title'     => $this->string()->notNull()->comment('Наименование ингредиента'),
            'available' => $this->boolean()->defaultValue(true)->notNull()->comment('Доступен'),
        ]);
        $this->addCommentOnTable('ingredients', 'Ингредиенты блюд');
        /* записи */
        $this->insert('ingredients', ['title' => 'говядина']);      //1
        $this->insert('ingredients', ['title' => 'свинина']);       //2
        $this->insert('ingredients', ['title' => 'курица']);        //3
        $this->insert('ingredients', ['title' => 'рыба']);          //4
        $this->insert('ingredients', ['title' => 'картофель фри', 'available' => false]);
        $this->insert('ingredients', ['title' => 'картофель пюре']);//6
        $this->insert('ingredients', ['title' => 'рис']);           //7
        $this->insert('ingredients', ['title' => 'лук маринованный']);
        $this->insert('ingredients', ['title' => 'капуста']);       //9
        $this->insert('ingredients', ['title' => 'соус']);          //10
        $this->insert('ingredients', ['title' => 'сыр']);           //11
        $this->insert('ingredients', ['title' => 'салат']);         //12
        $this->insert('ingredients', ['title' => 'масло']);         //13

        /*
        -----------------------------------------------------------------------
        -- Дамп данных таблицы dishes
        -- Блюда
        -----------------------------------------------------------------------
        */
        $this->createTable('dishes', [
            'id'    => $this->primaryKey()->comment('Ид записи'),
            'title' => $this->string()->notNull()->comment('Наименование блюда')
        ]);
        $this->addCommentOnTable('dishes', 'Блюда');
        /* записи */
        $this->insert('dishes', ['title' => 'стэйк говядина']);
        $this->insert('dishes', ['title' => 'стэйк свинина']);
        $this->insert('dishes', ['title' => 'стэйк курица']);
        $this->insert('dishes', ['title' => 'стэйк рыба']);
        $this->insert('dishes', ['title' => 'стэйк']);
        $this->insert('dishes', ['title' => 'отбивная говядина']);
        $this->insert('dishes', ['title' => 'отбивная свинина']);
        $this->insert('dishes', ['title' => 'отбивная курица']);
        $this->insert('dishes', ['title' => 'отбивная рыба']);
        $this->insert('dishes', ['title' => 'салат']);
        $this->insert('dishes', ['title' => 'салат']);
        $this->insert('dishes', ['title' => 'салат']);
        $this->insert('dishes', ['title' => 'салат']);

        /*
        -----------------------------------------------------------------------
        -- Дамп данных таблицы dishes_ingredients
        -- Ингредиенты блюд
        -----------------------------------------------------------------------
        */
        $this->createTable('dishes_ingredients', [
            'dish_id'       => $this->integer()->notNull()->comment('Ид блюда'),
            'ingredient_id' => $this->integer()->notNull()->comment('Ид ингредиента'),
        ]);
        $this->addCommentOnTable('dishes_ingredients', 'Ингредиенты блюд');
        $this->addPrimaryKey(
            'dishes_ingredients_dish_id_ingredient_id_idx',
            'dishes_ingredients',
            ['dish_id', 'ingredient_id']
        );
        $this->addForeignKey(
            'dishes_ingredients_ibfk_1',
            'dishes_ingredients',
            'dish_id',
            'dishes',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'dishes_ingredients_ibfk_2',
            'dishes_ingredients',
            'ingredient_id',
            'ingredients',
            'id',
            'CASCADE',
            'CASCADE'
        );
        /* записи */
        $this->insert('dishes_ingredients', ['dish_id' => 1, 'ingredient_id' => 1]);
        $this->insert('dishes_ingredients', ['dish_id' => 1, 'ingredient_id' => 6]);
        $this->insert('dishes_ingredients', ['dish_id' => 1, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 1, 'ingredient_id' => 10]);
        $this->insert('dishes_ingredients', ['dish_id' => 1, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 2, 'ingredient_id' => 2]);
        $this->insert('dishes_ingredients', ['dish_id' => 2, 'ingredient_id' => 6]);
        $this->insert('dishes_ingredients', ['dish_id' => 2, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 2, 'ingredient_id' => 10]);
        $this->insert('dishes_ingredients', ['dish_id' => 2, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 3, 'ingredient_id' => 3]);
        $this->insert('dishes_ingredients', ['dish_id' => 3, 'ingredient_id' => 6]);
        $this->insert('dishes_ingredients', ['dish_id' => 3, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 3, 'ingredient_id' => 10]);
        $this->insert('dishes_ingredients', ['dish_id' => 3, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 4, 'ingredient_id' => 4]);
        $this->insert('dishes_ingredients', ['dish_id' => 4, 'ingredient_id' => 6]);
        $this->insert('dishes_ingredients', ['dish_id' => 4, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 4, 'ingredient_id' => 10]);
        $this->insert('dishes_ingredients', ['dish_id' => 4, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 5, 'ingredient_id' => 1]);
        $this->insert('dishes_ingredients', ['dish_id' => 5, 'ingredient_id' => 5]);
        $this->insert('dishes_ingredients', ['dish_id' => 5, 'ingredient_id' => 10]);
        $this->insert('dishes_ingredients', ['dish_id' => 5, 'ingredient_id' => 12]);
        $this->insert('dishes_ingredients', ['dish_id' => 5, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 6, 'ingredient_id' => 1]);
        $this->insert('dishes_ingredients', ['dish_id' => 6, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 6, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 6, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 7, 'ingredient_id' => 2]);
        $this->insert('dishes_ingredients', ['dish_id' => 7, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 7, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 7, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 8, 'ingredient_id' => 3]);
        $this->insert('dishes_ingredients', ['dish_id' => 8, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 8, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 8, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 9, 'ingredient_id' => 4]);
        $this->insert('dishes_ingredients', ['dish_id' => 9, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 9, 'ingredient_id' => 8]);
        $this->insert('dishes_ingredients', ['dish_id' => 9, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 1]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 11]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 12]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 10, 'ingredient_id' => 2]);
        $this->insert('dishes_ingredients', ['dish_id' => 11, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 11, 'ingredient_id' => 11]);
        $this->insert('dishes_ingredients', ['dish_id' => 11, 'ingredient_id' => 12]);
        $this->insert('dishes_ingredients', ['dish_id' => 11, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 12, 'ingredient_id' => 3]);
        $this->insert('dishes_ingredients', ['dish_id' => 12, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 12, 'ingredient_id' => 11]);
        $this->insert('dishes_ingredients', ['dish_id' => 12, 'ingredient_id' => 12]);
        $this->insert('dishes_ingredients', ['dish_id' => 12, 'ingredient_id' => 13]);
        $this->insert('dishes_ingredients', ['dish_id' => 13, 'ingredient_id' => 4]);
        $this->insert('dishes_ingredients', ['dish_id' => 13, 'ingredient_id' => 7]);
        $this->insert('dishes_ingredients', ['dish_id' => 13, 'ingredient_id' => 11]);
        $this->insert('dishes_ingredients', ['dish_id' => 13, 'ingredient_id' => 12]);
        $this->insert('dishes_ingredients', ['dish_id' => 13, 'ingredient_id' => 13]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        /*
        -----------------------------------------------------------------------
        -- Удаление таблицы dishes_ingredients
        -----------------------------------------------------------------------
        */
        $this->dropForeignKey('dishes_ingredients_ibfk_2', 'dishes_ingredients');
        $this->dropForeignKey('dishes_ingredients_ibfk_1', 'dishes_ingredients');
        $this->dropPrimaryKey('dishes_ingredients_dish_id_ingredient_id_idx', 'dishes_ingredients');
        $this->dropTable('dishes_ingredients');

        /*
        -----------------------------------------------------------------------
        -- Удаление таблицы dishes
        -----------------------------------------------------------------------
        */
        $this->dropTable('dishes');

        /*
        -----------------------------------------------------------------------
        -- Удаление таблицы ingredients
        -----------------------------------------------------------------------
        */
        $this->dropTable('ingredients');

        /*
        -----------------------------------------------------------------------
        -- Удаление таблицы menu
        -----------------------------------------------------------------------
        */
        $this->dropTable('menu');

        return true;
    }
}
