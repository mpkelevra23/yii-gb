<?php

use yii\db\DataReader;
use yii\helpers\ArrayHelper;

/** @var array|DataReader $users */
/** @var array|DataReader $user_activity */
/** @var false|int|string|DataReader|null $count_notification_activity */
/** @var array $all_user_activity */
/** @var DataReader $activity_reader */
?>

<div class="row">
    <div class="col-md-6">
        <pre>
            <?= print_r($users, true) ?>
        </pre>
    </div>
    <div class="col-md-6">
        <pre>
            <?= print_r($user_activity, true) ?>
        </pre>
    </div>
    <div class="col-md-6">
        <pre>
            <?= print_r($count_notification_activity, true) ?>
        </pre>
    </div>
    <div class="col-md-6">
        <pre>
            <?= print_r($all_user_activity, true) ?>
        </pre>
    </div>
    <div class="col-md-6">
        <pre>
            <?php
            while (($row = $activity_reader->read()) !== false) {
                echo ArrayHelper::getValue($row, 'title') . '<br>';
            }
            $activity_reader->close();
            ?>
        </pre>
    </div>
</div>
