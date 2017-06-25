<?php

use yii\helpers\Html;

/** @var $model array */

?>

<div class="panel panel-default">
    <h2>#<?= Html::encode($model['id']) ?>, <?= Html::encode($model['subject']['type']) ?>: <?= Html::encode($model['subject']['title']) ?> (<?= Html::encode($model['updated_at']) ?>)</h2>

    <pre>
        <?php unset($model['repository']) ?>
        <?= print_r($model, true); ?>
    </pre>
</div>
