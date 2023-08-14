<?php

/**
 * @var $message string
 * @var $activity Activity
 */

use app\models\Activity;
use yii\helpers\Html;

?>
<div><?= Html::a('Перейти к списку активностей', ['@web/activity/index'], ['class' => 'btn btn-primary']) ?></div>
<div><h1><?= Html::encode($message) ?></h1>
    <div>
        <p class="text-start fw-bold">
            <?= Html::encode($activity->getAttributeLabel('title')) ?>:
            <span class="fw-normal"><?= Html::encode($activity->title) ?></span>
        </p>
    </div>
    <?php if ($activity->email): ?>
        <p class="text-start fw-bold">
            <?= Html::encode($activity->getAttributeLabel('email')) ?>:
            <span class="fw-normal"><?= Html::encode($activity->email) ?></span>
        </p>
    <?php endif ?>
    <!--    --><?php //= $activity->email ?? Html::tag('div', 'Email: ' . Html::encode($activity->email), ['class' => 'test']) ?>
</div>
<div><?= Html::a('Создать новую активность', ['@web/activity/create'], ['class' => 'btn btn-primary']) ?></div>
<div><?= Html::a('Редактировать активность', ['@web/activity/update'], ['class' => 'btn btn-primary']) ?></div>

