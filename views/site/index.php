<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
\yii\web\YiiAsset::register($this);
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <div class="form-group">
            <?php $form = ActiveForm::begin(['id' => 'url-form']); ?>

                <div class="input-group">
                    <?= $form->field($model, 'original_url')
                        ->textInput(['placeholder' => 'Введите URL', 'style' => 'min-width: 500px;'])
                        ->label(false); ?>

                    <span class="input-group-btn">
                        <?= Html::submitButton('OK', ['class' => 'btn btn-primary', 'id' => 'submit']); ?>
                    </span>
                </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div id="result"></div>
        <div id="error" class="text-danger"></div>

    </div>
</div>

<?php
$script = <<< JS
    $(document).on('submit', '#url-form', function(e) {
        e.preventDefault();
        const url = $('#link-original_url').val();

        $.ajax({
            url: '/link/validate-url',
            type: 'POST',
            data: { url: url },
            success: function(response) {
                if (response.error) {
                    $('#error').html(response.error);
                    $('#result').html('');
                } else if (response.success) {
                    $('#error').html('');
                    $('#result').html('<img src="' + response.qrCode + '" alt="QR Code"/><br><a href="' + response.shortUrl + '">' + response.shortUrl + '</a>');
                } else {
                    $('#error').html('else');
                }
            },
            error: function(xhr) {
                $('#error').html('Произошла ошибка, попробуйте позже.');
            }
        });
    });
JS;
$this->registerJs($script);
?>