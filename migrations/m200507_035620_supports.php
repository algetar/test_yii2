<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m200507_035620_supports
 */
class m200507_035620_supports extends Migration
{

    /**
     * Initializes the migration.
     * This method will set [[db]] to be the 'db' application component, if it is `null`.
     */
    public function init(): void
    {
        $this->db = 'dbsu';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        /*
        -----------------------------------------------------------------------
        -- Таблица domains
        -----------------------------------------------------------------------
        */
        $this->createTable('domains', [
            'id'         => $this->primaryKey()->comment('Ид записи'),
            'cid'        => $this->string(20)->notNull()->comment('Модуль'),
            'title'      => $this->string()->notNull()->comment('Наименование'),
            'native_cid' => $this->string()->notNull()->defaultValue('default')->comment('Контроллер'),
            'index'      => $this->string()->notNull()->defaultValue('index')->comment('Действие'),
        ]);
        $this->addCommentOnTable('domains', 'Модули приложения');
        $this->createIndex('cid', 'domains', 'cid', true);
        /* записи */
        $this->insert('domains', [
            'id'    => 1,
            'cid'   => 'system',
            'title' => 'Настройки',
        ]);
        $this->insert('domains', [
            'id'    => 2,
            'cid'   => 'administrator',
            'title' => 'Администратор',
        ]);
        $this->insert('domains', [
            'id'    => 3,
            'cid'   => 'customer',
            'title' => 'Заказчик',
        ]);

        /*
        -----------------------------------------------------------------------
        -- Таблица sites
        -----------------------------------------------------------------------
        */
        $this->createTable('sites', [
            'id'        => $this->primaryKey()->comment('Ид записи'),
            'domain_id' => $this->integer()->notNull()->comment('Модуль'),
            'cid'       => $this->string(20)->notNull()->comment('Контроллер'),
            'index'     => $this->string()->notNull()->defaultValue('index')->comment('Действие'),
            'title'     => $this->string()->notNull()->comment('Наименование'),
            'is_root'   => $this->boolean()->defaultValue(true)->notNull()->comment('Корневой процесс')
        ]);
        $this->addCommentOnTable('sites', 'Контроллеры модулей');
        $this->addForeignKey(
            'sites_ibfk_1',
            'sites',
            'domain_id',
            'domains',
            'id',
            'CASCADE',
            'CASCADE'
        );

        /* записи */
        $this->insert('sites', [
            'domain_id' => 1,
            'cid'       => 'default',
            'title'     => 'Начальная страница',
        ]);
        $this->insert('sites', [
            'domain_id' => 1,
            'cid'       => 'users',
            'title'     => 'Пользователи',
        ]);
        $this->insert('sites', [
            'domain_id' => 1,
            'cid'       => 'roles',
            'title'     => 'Роли',
        ]);
        /* записи */
        $this->insert('sites', [
            'domain_id' => 1,
            'cid'       => 'domains',
            'title'     => 'Модули',
        ]);
        $this->insert('sites', [
            'domain_id' => 1,
            'cid'       => 'sites',
            'title'     => 'Контроллеры',
            'is_root'   => false,
        ]);
        $this->insert('sites', [
            'domain_id' => 2,
            'cid'       => 'default',
            'title'     => 'Начальная страница',
        ]);
        $this->insert('sites', [
            'domain_id' => 2,
            'cid'       => 'ingredients',
            'title'     => 'Ингредиенты',
        ]);
        $this->insert('sites', [
            'domain_id' => 2,
            'cid'       => 'dishes',
            'title'     => 'Блюда',
        ]);
        $this->insert('sites', [
            'domain_id' => 3,
            'cid'       => 'default',
            'title'     => 'Заказы',
        ]);

        /*
        -----------------------------------------------------------------------
        -- Таблица roles
        -----------------------------------------------------------------------
        */
        $this->createTable('roles', [
            'id'        => $this->primaryKey()->comment('Ид записи'),
            'title'     => $this->string()->notNull()->comment('Имя'),
            'domain_id' => $this->integer()->notNull()->comment('Модуль роли'),
        ]);
        $this->addCommentOnTable('roles', 'Роли пользователей');
        $this->addForeignKey(
            'roles_ibfk_1',
            'roles',
            'domain_id',
            'domains',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        /* записи */
        $this->insert('roles', [
            'id'        => 1,
            'title'     => 'sa',
            'domain_id' => 1,
        ]);
        $this->insert('roles', [
            'id'        => 2,
            'title'     => 'Администратор',
            'domain_id' => 2,
        ]);
        $this->insert('roles', [
            'id'        => 3,
            'title'     => 'Посетитель',
            'domain_id' => 3,
        ]);

        /*
        -----------------------------------------------------------------------
        -- Таблица users
        -----------------------------------------------------------------------
        */
        $this->createTable('users', [
            'id'       => $this->primaryKey()->comment('Ид записи'),
            'name'     => $this->string()->notNull()->comment('Логин'),
            'password' => $this->string()->notNull()->comment('Пароль'),
            'role_id'  => $this->integer()->notNull()->comment('Роль'),
        ]);
        $this->addCommentOnTable('users', 'Роли пользователей');
        $this->addForeignKey(
            'users_ibfk_1',
            'users',
            'role_id',
            'roles',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        /* записи */
        $this->insert('users', [
            'id'       => 1,
            'name'     => 'sa',
            'password' => password_hash('1234567', PASSWORD_DEFAULT),
            'role_id'  => 1,
        ]);
        $this->insert('users', [
            'id'       => 2,
            'name'     => 'Администратор',
            'password' => password_hash('1234567', PASSWORD_DEFAULT),
            'role_id'  => 2,
        ]);
        $this->insert('users', [
            'id'       => 3,
            'name'     => 'Посетитель',
            'password' => password_hash('1234567', PASSWORD_DEFAULT),
            'role_id'  => 3,
        ]);

        /*
        -----------------------------------------------------------------------
        -- Таблица users_domains
           Доступ пользователя к модулям других ролей
        -----------------------------------------------------------------------
        */
        $this->createTable('users_domains', [
            'user_id'   => $this->integer()->notNull()->comment('Пользователь'),
            'domain_id' => $this->integer()->notNull()->comment('Модуль'),
        ]);
        $this->addCommentOnTable('users_domains', 'Модули пользователей');
        $this->addPrimaryKey('users_domains_pk', 'users_domains', ['user_id', 'domain_id']);
        $this->addForeignKey(
            'users_domains_ibfk_1',
            'users_domains',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'users_domains_ibfk_2',
            'users_domains',
            'domain_id',
            'domains',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->insert('users_domains', [
           'user_id'   => 1,
           'domain_id' => 2,
        ]);
        $this->insert('users_domains', [
            'user_id'   => 1,
            'domain_id' => 3,
        ]);

        /*
        -----------------------------------------------------------------------
        -- Таблица domains
        -----------------------------------------------------------------------
        */
        $this->createTable('user_options', [
            'user_id' => $this->integer()->notNull()->comment('Пользователь'),
            'key'     => $this->string()->notNull()->comment('Ключ'),
            'value'   => $this->string()->notNull()->comment('Значение'),
        ]);
        $this->addCommentOnTable('user_options', 'Хранимые опции пользователя');
        $this->addPrimaryKey('user_id_key_idx', 'user_options', ['user_id', 'key']);
        $this->addForeignKey(
            'user_options_ibfk_1',
            'user_options',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        $this->dropForeignKey('user_options_ibfk_1', 'user_options');
        $this->dropTable('user_options');

        $this->dropForeignKey('users_domains_ibfk_1', 'users_domains');
        $this->dropForeignKey('users_domains_ibfk_2', 'users_domains');
        $this->dropTable('users_domains');

        $this->dropForeignKey('users_ibfk_1', 'users');
        $this->dropTable('users');

        $this->dropForeignKey('roles_ibfk_1', 'roles');
        $this->dropTable('roles');

        $this->dropForeignKey('sites_ibfk_1', 'sites');
        $this->dropTable('sites');

        return true;
    }
}
