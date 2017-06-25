<?php

use app\components\HtmlHelper;
use yii\helpers\Html;

/** @var $model \app\models\Pr */

?>

<div class="panel panel-default">
    <div class="panel-heading">
    <h3>
        <?= Html::a('#' . Html::encode($model['number']), $model['html_url']) ?>
        <small><?= Html::a(Html::encode($model['title']), $model['html_url']) ?></a></small>
        <div class="pull-right"><small>

            <?php foreach($model->getType() as $type) {
                echo HtmlHelper::statusLabel($type) . ' ';
            } ?>

            <?php foreach($model->getCurrentStatuses() as $status) {
                echo HtmlHelper::statusLabel(
                        $status['state'],
                        Html::a(str_replace('continuous-integration/travis-ci', 'travis-ci', $status['context']), $status['target_url']),
                        $status['state'] . ': ' . $status['description'],
                        false
                ) . ' ';
            } ?>

            <?= HtmlHelper::statusLabel($model['state']) ?>
        </small></div>
    </h3>
    </div>

    <div class="panel-body">

        <div class="pull-right">
            Milestone: <?= HtmlHelper::milestone($model['milestone']) ?><br>
            Created <?= Yii::$app->formatter->asRelativeTime($model['created_at']) ?><br>
            Updated <?= Yii::$app->formatter->asRelativeTime($model['updated_at']) ?>
        </div>

        Author: <?= HtmlHelper::githubUser($model['user']) ?>
        <br>
        Assignees:
        <?php foreach($model['assignees'] as $assignee): ?>
            <?= HtmlHelper::githubUser($assignee) ?>
        <?php endforeach; ?>
        <br>
        Requested Reviews:
        <?php foreach($model['requested_reviewers'] as $reviewer): ?>
            <?= HtmlHelper::githubUser($reviewer) ?>
        <?php endforeach; ?>

    </div>
        <hr>
    <div class="panel-body">
        <?php /*
        <div class="pull-right">
            Statuses:
            <?php

            echo '<ul>';
            $currentStatus = $model->getCurrentStatuses();
            foreach($currentStatus as $status) {
                echo Html::tag('li',
                    'State: ' . $status['state'] . ' '
                    . ', ' . str_replace('continuous-integration/travis-ci', 'travis-ci', $status['context']) . ', '
                    . Html::a(Html::encode($status['description']), $status['target_url'])
                );
                // echo '<pre>' . print_r($status, true) . '</pre>';
            }
            echo '</ul>';

            ?>
        </div>*/
        ?>

        Files:
        <ul>
        <?php
        $files = $model->getFiles();
        foreach($files as $file) {
            echo Html::tag('li',
                $file['status'] . ' ' .
                Html::a(
                    Html::encode($file['filename']),
                    "#pr-{$model['number']}-{$file['sha']}",
                    [
                        'data-toggle' => 'collapse',
                        'aria-expanded' => 'false',
                        'aria-controls' =>  "pr-{$model['number']}-{$file['sha']}"
                    ]
                )
                . ' <span class="text-success">+' . (int) $file['additions'] . '</span>'
                . ' <span class="text-danger">-' . (int) $file['deletions'] . '</span>'
                . '<pre id="' . "pr-{$model['number']}-{$file['sha']}" . '" class="collapse">'.(isset($file['patch']) ? Html::encode($file['patch']) : '').'</pre>');
            // echo '<pre>' . print_r($file, true) . '</pre>';
        } ?>
        </ul>

        <?php /*
        <pre>
            <?= print_r($model); ?>
        </pre>
        */ ?>
    </div>

</div>
<?php // http://getbootstrap.com/javascript/#tooltips
$this->registerJs('$(\'[data-toggle="tooltip"]\').tooltip();');
?>