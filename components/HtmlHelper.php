<?php
/**
 *
 *
 * @author Carsten Brandt <mail@cebe.cc>
 */

namespace app\components;


use yii\bootstrap\Progress;
use yii\helpers\Html;

class HtmlHelper
{
    public static function statusLabel($status, $label = null, $title = null, $encodeLabel = true)
    {
        if ($label === null) {
            $label = $status;
        }
        switch($status)
        {
            case 'open':
            case 'success':
                $color = 'success';
                break;
            case 'closed':
            case 'error':
            case 'failure':
                $color = 'danger';
                break;
            case 'pending':
                $color = 'warning';
                break;
            default:
                $color = 'default';
        }
        if ($title !== null) {
            $title = ' title="'.Html::encode($title).'" data-toggle="tooltip" data-placement="top"';
        } else {
            $title = '';
        }
        return '<span class="label label-'.$color.'"'.$title.'>'. ($encodeLabel ? Html::encode($label) : $label) . '</span>';
    }

    public static function githubUser($user)
    {
        return Html::a(
            Html::img($user['avatar_url'], ['style' => 'width: 24px; height: 24px;']) . '&nbsp;' . Html::encode($user['login']),
            $user['html_url']
        );
    }

    public static function milestone($milestone)
    {
//        $issueCount = $milestone['open_issues'] + $milestone['closed_issues'];
        return Html::a(Html::encode($milestone['title']), $milestone['html_url']);
            //Progress::widget(['percent' => ($issueCount > 0) ? ($milestone['closed_issues'] / $issueCount) * 100 : 0]);
    }
}