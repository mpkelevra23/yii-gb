<?php
/**
 * @var $activity Activity
 */

use app\models\Activity;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;

?>
<div class="row">
    <div class="col-md-6">
        <h2>Создать новую активность</h2>
        <?php $form = ActiveForm::begin([
            // Можно настроить ajax валидацию на всю форму, на каждое поле
//            'fieldConfig' => ['enableAjaxValidation' => true],
            'options' => ['enctype' => 'multipart/form-data'],
            'method' => 'post',
            'id' => 'activity'
        ]) ?>
        <!--        Задать адрес обработки формы-->
        <!--        --><?php //= $form->action = Url::to(['activity/create']) ?>
        <?= $form->errorSummary($activity) ?>
        <?= $form->field($activity, 'title') ?>
        <?= $form->field($activity, 'description')->textarea(['rows' => 5]) ?>
        <?= $form->field($activity, 'use_notification')->checkbox() ?>
        <?= $form->field($activity, 'email',
            [
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ])
        ?>
        <?= $form->field($activity, 'email_confirm',
            [
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ])
        ?>
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
        ]) ?>
        <?= $form->field($activity, 'end_day')->widget(DatePicker::class, [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' =>
                [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
        ]) ?>
        <?= $form->field($activity, 'images[]')->fileInput(['multiple' => true]) ?>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>