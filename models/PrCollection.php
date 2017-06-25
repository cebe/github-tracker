<?php

namespace app\models;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class PrCollection extends Component
{
    /**
     * @var Pr[]
     */
    private $_data;

    public function __construct($data, $config = [])
    {
        $this->_data = $data;
        parent::__construct($config);
    }

    /**
     * @param $repo
     * @param $user
     * @return PrCollection
     */
    public static function fromRepo($repo, $user)
    {
        // TODO https://github.com/KnpLabs/php-github-api#cache-usage
        $key = "prs-$repo/$user";
        $prData = Yii::$app->cache->get($key);
        if ($prData === false) {
            // https://developer.github.com/v3/pulls/
            /** @var $client \Github\Client */
            $client = Yii::$app->github->client();
            $api = $client->pullRequests();
            $paginator = new \Github\ResultPager($client);
            $prData = $paginator->fetchAll($api, 'all', [$repo, $user, ['state' => 'open']]);
            Yii::$app->cache->set($key, $prData, 60 * 5);
        }
        $prs = [];
        foreach($prData as $data) {
            $prs[] = new Pr($data);
        }
        return new static($prs);
    }

    public function getMilestones()
    {
        $milestones = array_unique(array_map(function($pr) { return $pr['milestone']['title']; }, $this->_data));
        asort($milestones, SORT_NATURAL);
        return array_combine($milestones, $milestones);
    }

    public function getAuthors()
    {
        $authors = array_unique(array_map(function($pr) { return $pr['user']['login']; }, $this->_data));
        ArrayHelper::multisort($authors, function($a) { return mb_strtolower($a); });
        return array_combine($authors, $authors);
    }

    public function getAssignees()
    {
        $assignees = [];
        foreach($this->_data as $pr) {
            foreach($pr['assignees'] as $assignee) {
                $assignees[$assignee['login']] = $assignee['login'];
            }
        }
        array_unique($assignees);
        ArrayHelper::multisort($assignees, function($a) { return mb_strtolower($a); });
        return $assignees;
    }

    public function getTypes()
    {
        $types = [];
        foreach($this->_data as $pr) {
            foreach($pr->getType() as $type) {
                $types[$type] = $type;
            }
        }
        array_unique($types);
        ArrayHelper::multisort($types, function($a) { return mb_strtolower($a); });
        return $types;
    }

    public function getData()
    {
        return $this->_data;
    }
}