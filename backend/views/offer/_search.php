<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="offer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'name') ?>

    <?php echo $form->field($model, 'redirectUrl') ?>

    <?php echo $form->field($model, 'payout') ?>

    <?php echo $form->field($model, 'networkID') ?>

    <?php // echo $form->field($model, 'clicks') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'income') ?>

    <?php // echo $form->field($model, 'conversion') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('tracking', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('tracking', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
