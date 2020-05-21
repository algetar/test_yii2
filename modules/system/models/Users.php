<?php
declare(strict_types = 1);

namespace app\modules\system\models;

use app\components\tools\models\selector\Selector;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * Пользователи.
 * Права пользователей совпадают с их ролями, поэтому они же являются и участниками.
 * Для упрощения у каждого участника есть своя домашняя страница, которая соответствует
 * индексу модуля. Связывание модуля и участника происходит через их роли.
 *
 * @property bool $hasMenu
 * @property array|string $home
 *
 * @property Domains $homeDomain
 * @author tga
 */
class Users extends TblUsers implements IdentityInterface
{
    /* @var Domains[] Коллекция модулей пользователя,
     * домашний с индексом 0.                         */
    private ?array $_domains = null;

    /* Массив соответствий ['cid' => 'id'] */
    private array $_domainCis = [];

    /* Ид текущего модуля */
    private int $_domainId = 0;

    /**
     * Сохраняет выбранные модули
     *
     * @param Selector $selector
     * @return bool
     * @throws NotFoundHttpException
     */
    public function applyChoice(Selector $selector): bool
    {
        $ids = explode(',', $selector->choiceIds);
        foreach ($ids as $id) {
             $userDomain = new UsersDomains();
             $userDomain->user_id = $this->id;
             $userDomain->domain_id = $id;

            if (!$userDomain->save()) {
                throw new NotFoundHttpException(sprintf('The requested UsersDomains.%s does not exist.', $id));
            }
        }

        return true;
    }

    /**
     * Все Модули, не входящие в список модулей пользователя
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getNotUserDomains(): ActiveQuery
    {
        $domains = $this->getDomains()->asArray()->all();
        $ids = ArrayHelper::getColumn($domains, 'id');
        $ids[] = $this->homeDomain->id;

        return Domains::find()
            ->where('NOT `id` IN (' . implode(',', $ids) . ')');
    }

    /**
     * @param int|string|null $id
     *
     * @return Users
     * @throws NotFoundHttpException
     */
    public static function getUser($id = null): Users
    {
        $user = null;
        if (null === $id) {
            $user = new static();
            $user->password = password_hash('1234567', PASSWORD_DEFAULT);
        } elseif (is_numeric($id)) {
            $user = static::findOne((int) $id);
        } elseif (is_string($id)) {
            $user = static::findOne(['name' => $id]);
        }

        if ($user !== null) {
            return $user;
        }

        throw new NotFoundHttpException(sprintf('The requested User.%s does not exist.', $id));
    }

    /**
     * @param $id
     *
     * @return UsersDomains|array|ActiveRecord|null
     */
    public function getUsersDomain($id)
    {
        return $this->getUsersDomains()->where(['domain_id' => $id])->one();
    }

    /**
     * Список для dropDownList
     * @return array
     */
    public static function usersList(): array
    {
        $users = [];
        /** @var Users $user */
        foreach (self::find()->all() as $user) {
            $users[$user->id] = $user->name;
        }

        return $users;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?Users
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?Users
    {
        return self::findByUsername($token);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return Users|null
     */
    public static function findByUsername($username): ?Users
    {
        return self::findOne(['name' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Имя пользователя
     * @return string
     */
    public function getUsername(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return 100;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return true;
    }

    /**
     * Validates password
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Модули пользователя
     *
     * @return Domains[]
     * @throws InvalidConfigException
     */
    public function domains(): array
    {
        if ($this->_domains === null) {
            //первым всегда домашний
            $this->_domains[0] = $this->role->domain;

            foreach ($this->getDomains()->all() as $domain) {
                /** @var $domain Domains */
                $this->_domains[$domain->id] = $domain;
                $this->_domainCis[$domain->cid] = $domain->id;

                if (Yii::$app->controller->module->id === $domain->cid) {
                    $this->_domainId = (int) $domain->id;
                }
            }
        }

        return $this->_domains;
    }

    /**
     * Роли пользователя доступно боковое меню
     *
     * @return bool
     */
    public function getHasMenu(): bool
    {
        return $this->role_id < 3;
    }

    /**
     * Домашний модуль пользователя
     *
     * @return Domains
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function getHomeDomain(): Domains
    {
        return $this->getDomain(0);
    }

    /**
     * Указанный модуль.
     * Если $id опущено, то текущий модуль пользователя
     * @param int|string|null $id
     *
     * @return Domains
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function getDomain($id = null): Domains
    {
        $domains = $this->domains();
        if (null === $id) {
            return $domains[$this->_domainId];
        }

        if (is_numeric($id)) {
            return $domains[(int) $id];
        }

        if (is_string($id)) {
            return $domains[$this->_domainCis[$id]];
        }

        throw new NotFoundHttpException(sprintf('The requested Domain %s does not exist.', $id));
    }

    /**
     * Url домашней страницы пользователя
     *
     * @return array|string
     */
    public function getHome()
    {
        return $this->homeDomain->url;
    }

    /**
     * Опция текущего пользователя,
     * используется для хранения своих данных объектами.
     * @param string $owner имя объекта
     * @param string $name имя значения
     *
     * @return UserOptions
     */
    public function getOption(string $owner, string $name): UserOptions
    {
        $key = sprintf('%s.%s', $owner, $name);
        /** @var UserOptions $option */
        $option = $this->getUserOptions()->where(['key' => $key])->one();
        if (null === $option) {
            $option = new UserOptions();
            $option->user_id = $this->id;
            $option->key = $key;
        }

        return $option;
    }
}
