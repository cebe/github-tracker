<?php
/**
 *
 *
 * @author Carsten Brandt <mail@cebe.cc>
 */

namespace app\models;


use yii\base\Model;

class PrFilter extends Model
{
    public $milestone;
    public $author;
    public $assignee;
    public $type;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['milestone', 'author', 'assignee', 'type'], 'trim'],
            [['milestone', 'author', 'assignee', 'type'], 'default'],
            [['milestone'], 'string'],
            [['author'], 'string'],
            [['assignee'], 'string'],
            [['type'], 'string'],
        ];
    }

    /**
     * @param Pr[] $models
     * @return array
     */
    public function filter($models)
    {
        return array_filter($models, function($model) {
            /** @var $model Pr */
            $match = true;
            if ($this->milestone !== null) {
                $match = ($model['milestone']['title'] === $this->milestone);
                if (!$match) {
                    return false;
                }
            }
            if ($this->author !== null) {
                $match = ($model['user']['login'] === $this->author);
                if (!$match) {
                    return false;
                }
            }
            if ($this->assignee !== null) {
                $match = false;
                foreach($model['assignees'] as $assignee) {
                    if ($assignee['login'] === $this->assignee) {
                        $match = true;
                        break;
                    }
                }
                if (!$match) {
                    return false;
                }
            }
            if ($this->type !== null) {
                $match = false;
                foreach($model->getType() as $type) {
                    if ($type === $this->type) {
                        $match = true;
                        break;
                    }
                }
                if (!$match) {
                    return false;
                }
            }
            return $match;
        });
    }
}