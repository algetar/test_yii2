<?php
declare(strict_types=1);

namespace app\modules\system\models;

use app\components\tools\common\Utils;
use app\components\tools\urls\FUrl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Sites контроллеры модуля.
 *
 * @property string $url
 * @property string $route
 * @property string $domainCid
 * @property array $node
 * @author tga
 */
class Sites extends TblSites
{
    /**
     * Контроллеры текущего модуля, отсутствующие в Sites
     * в формате [cid => cid].
     * @var array
     */
    private static array $_controllersList = [];

    /**
     * @param $id
     * @param bool $new
     *
     * @return Sites
     * @throws NotFoundHttpException
     */
    public static function prepare($id, $new = false): Sites
    {
        if ($new) {
            $site = new static();
            $site->domain_id = $id;
            $site->index     = 'index';
            $site->is_root   = true;
            $domain = Domains::getDomain($id);
        } else {
            $site = self::getSite($id);
            $domain = Domains::getDomain($site->domain_id);
        }
        self::$_controllersList = [$site->cid => $site->cid];
        self::getControllersList($domain);

        return $site;
    }

    /**
     * @param Domains|null $domain
     * @return array
     */
    public static function getControllersList(?Domains $domain = null): array
    {
        if (null !== $domain) {
            $sites = ArrayHelper::getColumn(
                self::find()->where(['domain_id' => $domain->id])->asArray()->all(),
                'cid'
            );
            foreach (Utils::getModuleControllersList($domain->getModule()) as $cid) {
                if (!in_array($cid, $sites, true)) {
                    self::$_controllersList[$cid] = $cid;
                }
            }
        }

        return self::$_controllersList;
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'domainCid' => 'Имя модуля',
            'url'       => 'Индекс',
        ]);
    }

    /**
     * @return string
     */
    public function getDomainCid(): string
    {
        return $this->domain->cid;
    }

    /**
     * Запись с указанным контроллером
     * @param int|string $id
     *
     * @return Sites
     * @throws NotFoundHttpException
     */
    public static function getSite($id): Sites
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

        throw new NotFoundHttpException(sprintf('The requested Site %s does not exist.', $id));
    }

    /**
     * Url действия index текущего контроллера.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getRoute();
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return FUrl::createRoute($this->domain->cid, $this->cid, $this->index);
    }

    /**
     * @return array
     */
    public function getNode(): array
    {
        return [
            'domain'   => $this->domain->cid,
            'site'     => $this->cid,
            'page'     => $this->index,
            'title'    => $this->title,
            'route'    => $this->route,
            'url'      => $this->url,
        ];
    }
}
