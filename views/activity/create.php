<?php
/**
 * @var $activity Activity
 */

use app\models\Activity;
use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;

?>
<div class="row">
    <div class="col-md-6">
        <h2>Create new activity</h2>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'id' => 'activity'
        ]) ?>
        <?= $form->field($activity, 'title') ?>
        <?= $form->field($activity, 'description')->textarea(['rows' => 5]) ?>
        <?= $form->field($activity, 'is_blocked')->checkbox() ?>
        <?= $form->field($activity, 'date_start')->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control'],
        ]); ?>
        <button type="submit" class="btn btn-primary">Submit</button>
        <?php ActiveForm::end() ?>
    </div>
</div>