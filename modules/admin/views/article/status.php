<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>
    <h1> Выберите статус для вышей статьи:</h1>
    <?= Html::dropDownList('status', $selectCategory, $categories, ['class'=>'form-control']) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Okey', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>