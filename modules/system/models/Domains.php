<?php
declare(strict_types=1);

namespace app\modules\system\models;

use app\components\tools\helpers\CTUrlHelper;
use app\components\tools\urls\FUrl;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Модули приложения
 * @property string $url
 * @property string $route
 * @property Sites $site
 * @property Sites $homeSite
 * @property bool $available
 * @property array $node
  */
class Domains extends TblDomains
{
    /**
     * Список отсутствующих в Domains Модулей в
     * формате [cid => cid]
     * @var array
     */
    private static array $_unregisteredDomainsList = [];

    /**
     * Коллекция контроллеров текущего модуля,
     * домашний сайт модуля - url самого контроллера
     * @var Sites[]
     */
    private ?array $_sites = null;

    /** @var string[] Коллекция имен сайтов в формате ['cid' => 'id] */
    private array $_siteCIds = [];

    /** Ид текущего действия */
    private ?int $_siteId = null;

    /** Ид домашней станицы */
    private ?int $_homeId = null;

    /**
     * cid текущего контроллера присутствует в списке
     * зарегистрированных в $app->config модулей
     * @var bool|null
     */
    private ?bool $_available = null;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'available' => 'Действующий',
            'url'       => 'Индекс',
        ]);
    }

    /**
     * @return bool
     */
    public function getAvailable(): bool
    {
        if (null === $this->_available) {
            $modules = Yii::$app->modules;
            $this->_available = array_key_exists($this->cid, $modules);
        }

        return $this->_available;
    }

    /**
     * Список для dropDownList
     * @return array
     */
    public static function domainsList(): array
    {
        $domains = [];
        /** @var Domains $domain */
        foreach (self::find()->all() as $domain) {
            $domains[$domain->id] = $domain->cid;
        }

        return $domains;
    }

    /**
     * Список модулей, отсутствующих в Domains
     * @return array
     */
    public static function unregisteredDomainsList(): array
    {
        $domains = ArrayHelper::getColumn(self::find()->asArray()->all(), 'cid');
        foreach (Yii::$app->modules as $cid => $module) {
            if (!in_array($cid, $domains, true)) {
                self::$_unregisteredDomainsList[$cid] = $cid;
            }
        }

        return self::$_unregisteredDomainsList;
    }

    /**
     * @param int|null $id
     *
     * @return Domains
     * @throws NotFoundHttpException
     */
    public static function prepare($id = null): Domains
    {
        if (null === $id) {
            $model             = new static();
            $model->cid        = '';
            $model->native_cid = 'default';
            $model->index      = 'index';
        } else {
            $model =  self::findOne($id);
            if (null === $model) {
                throw new NotFoundHttpException(sprintf('The requested Domain %s does not exist.', $id));
            }
        }
        self::$_unregisteredDomainsList[$model->cid] = $model->cid;

        return $model;
    }

    /**
     * Запись с указанным модулем
     * @param int|string $id
     *
     * @param bool|null $throw
     * @return Domains|null
     * @throws NotFoundHttpException
     */
    public static function getDomain($id, ?bool $throw = true): ?Domains
    {
        $model = null;

        if (is_numeric($id)) {
            $model =  self::findOne((int) $id);
        } elseif (is_string($id)) {
            $model = self::findOne(['cid' => $id]);
        }

        if (null !== $model) {
            return $model;
        }

        if ($throw) {
            throw new NotFoundHttpException(sprintf('The requested Domain %s does not exist.', $id));
        }

        return null;
    }

    /**
     * Контроллеры текущего модуля
     *
     * @return Sites[]
     */
    public function sites(): array
    {
        if (null === $this->_sites) {
            $this->_sites = [];
            $this->_siteCIds = [];

            foreach ($this->getSites()->all() as $site) {
                $this->addSite($site);
            }

            if (null === $this->_homeId) {
                $this->_homeId = $this->addHomeSite();
            }
        }

        return $this->_sites;
    }


    /**
     * @param string|int|null $id
     * @param bool|null $throw
     *
     * @return Sites|null
     * @throws NotFoundHttpException
     */
    public function getSite($id = null, ?bool $throw = true): ?Sites
    {
        $sites = $this->sites();
        if (null === $id) {
            return $sites[$this->_siteId];
        }

        if (is_numeric($id)) {
            return $sites[(int) $id];
        }

        if (is_string($id)) {
            return $sites[$this->_siteCIds[$id]];
        }

        if ($throw) {
            throw new NotFoundHttpException(sprintf('The requested Site %s in Domain %s does not exist.', $id, $this->cid));
        }

        return null;
    }

    /**
     * Домашний сайт модуля.
     *
     * @return Sites
     */
    public function getHomeSite(): Sites
    {
        $sites = $this->sites();
        return $sites[$this->_homeId];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return FUrl::createRoute($this->cid, $this->native_cid, $this->index);
    }

    /**
     * @return array
     */
    public function getNode(): array
    {
        return [
            'domain' => $this->cid,
            'site'   => $this->native_cid,
            'page'   => $this->index,
            'title'  => $this->title,
            'route'  => $this->route,
            'url'    => $this->url,
        ];
    }

    /**
     * @return Module|null
     */
    public function getModule(): ?Module
    {
        return Yii::$app->getModule($this->cid);
    }

    /**
     * @return int
     */
    private function addHomeSite(): int
    {
        return $this->addSite(new Sites([
            'id'        => 0,
            'domain_id' => $this->id,
            'cid'       => $this->native_cid,
            'index'     => $this->index,
            'title'     => 'Начальная страница',
            'is_root'   => true
        ]));
    }

    /**
     * @param Sites $site
     * @return int
     */
    private function addSite(Sites $site): int
    {
        $this->_sites[$site->id] = $site;
        $this->_siteCIds[$site->cid] = $site->id;

        if (Yii::$app->controller->id === $site->cid) {
            $this->_siteId = (int) $site->id;
        }

        if ($site->route === $this->route) {
            $this->_homeId = $site->id;
        }

        return $site->id;
    }
}
