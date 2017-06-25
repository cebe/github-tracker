<?php

namespace app\models;

use Yii;
use yii\base\InvalidCallException;
use yii\base\Object;

class Pr extends Object implements \ArrayAccess
{
    private $_attributes;

    public function __construct($data, $config = [])
    {
        $this->_attributes = $data;
        Yii::trace($this->_attributes);
        parent::__construct($config);
    }

    public function __get($name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        return parent::__get($name);
    }

    public function offsetExists($offset)
    {
        return isset($this->_attributes[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->_attributes[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new InvalidCallException('Pr is read-only.');
    }

    public function offsetUnset($offset)
    {
        throw new InvalidCallException('Pr is read-only.');
    }

    private $_statuses;
    public function getStatuses()
    {
        if ($this->_statuses !== null) {
            return $this->_statuses;
        }
        $key = "pr-{$this->_attributes['id']}-statuses-{$this->_attributes['head']['sha']}";
        $statuses = Yii::$app->cache->get($key);
        if ($statuses === false) {
            /** @var $client \Github\Client */
            $client = Yii::$app->github->client();
            /** @var $api \Github\Api\Repository\Statuses */
            $api = $client->repo()->statuses();
            $paginator = new \Github\ResultPager($client);
            $statuses = $paginator->fetchAll($api, 'show', [$this->_attributes['base']['user']['login'], $this->_attributes['base']['repo']['name'], $this->_attributes['head']['sha']]);
            Yii::$app->cache->set($key, $statuses, 0); // set cache forever, key depends on head commit sha
        }
        \yii\helpers\ArrayHelper::multisort($statuses, 'updated_at');
        return  $this->_statuses = $statuses;
    }

    public function getCurrentStatuses()
    {
        $statuses = $this->getStatuses();
        $currentStatus = [];
        foreach($statuses as $status) {
            $currentStatus[$status['context']] = $status;
        }
        return $currentStatus;
    }

    private $_files;
    public function getFiles()
    {
        if ($this->_files !== null) {
            return $this->_files;
        }
        $key = "pr-{$this->_attributes['id']}-files-{$this->_attributes['head']['sha']}";
        $files = Yii::$app->cache->get($key);
        if ($files === false) {
            // https://developer.github.com/v3/pulls/#list-pull-requests-files
            /** @var $client \Github\Client */
            $client = Yii::$app->github->client();
            /** @var $api \Github\Api\PullRequest */
            $api = $client->pullRequests();
            $paginator = new \Github\ResultPager($client);
            $files = $paginator->fetchAll($api, 'files', [$this->_attributes['base']['user']['login'],  $this->_attributes['base']['repo']['name'], $this->_attributes['number']]);
            Yii::$app->cache->set($key, $files, 0); // set cache forever, key depends on head commit sha
        }
        \yii\helpers\ArrayHelper::multisort($files, 'filename');
        return  $this->_files = $files;
    }

    public function getType()
    {
        $result = [];
        foreach($this->getFiles() as $file) {

            if (strpos($file['filename'], 'tests/') === 0) {
                $result['has tests'] = true;
            }
            if (strpos($file['filename'], 'docs/') === 0) {
                $result['has docs'] = true;
            }
            if (strpos($file['filename'], 'framework/CHANGELOG.md') === 0) {
                $result['has changelog'] = true;
                continue;
            }
            if (strpos($file['filename'], 'framework/messages') === 0) {
                $result['has message translation'] = true;
                continue;
            }
            if (strpos($file['filename'], 'framework/') === 0) {
                $result['fw'] = true;
            }

        }
        if (isset($result['has tests']) && count($result) == 1) {
            unset($result['has tests']);
            $result['tests only'] = true;
        }
        if (isset($result['has docs']) && count($result) == 1) {
            unset($result['has docs']);
            $result['docs only'] = true;
        }
        if (isset($result['has message translation']) && count($result) == 1) {
            unset($result['has message translation']);
            $result['message translation only'] = true;
        }
        return array_keys($result);
    }
}