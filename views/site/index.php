<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <div class="form-group">
            <?php $form = ActiveForm::begin(['id' => 'url-form']); ?>

                <?= $form->field($model, 'original_url')->textInput(['placeholder' => 'Введите URL'])->label(false); ?>

                <div class="form-group">
                    <?= Html::submitButton('OK', ['class' => 'brn btn-primary', 'id' => 'submit']); ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>