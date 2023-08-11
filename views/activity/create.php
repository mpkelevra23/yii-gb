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
            // Можно настроить ajax валидацию на всю форму, на каждое поле
//            'fieldConfig' => ['enableAjaxValidation' => true],
            'method' => 'post',
            'id' => 'activity'
        ]) ?>
        <?= $form->errorSummary($activity) ?>
        <?= $form->field($activity, 'title') ?>
        <?= $form->field($activity, 'description')->textarea(['rows' => 5]) ?>
        <?= $form->field($activity, 'use_notification')->checkbox() ?>
        <?= $form->field($activity, 'email',
            [
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]) ?>
        <?= $form->field($activity, 'email_confirm',
            [
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]) ?>
        <?= $form->field($activity, 'is_blocked')->checkbox() ?>
        <?= $form->field($activity, 'is_repeat')->checkbox() ?>
        <?= $form->field($activity, 'start_day')->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' =>
                [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
        ]); ?>
        <?= $form->field($activity, 'end_day')->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' =>
                [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
        ]); ?>
        <?= $form->field($activity, 'image')->fileInput() ?>
        <button type="submit" class="btn btn-primary">Отправить</button>
        <?php ActiveForm::end() ?>
    </div>
</div>