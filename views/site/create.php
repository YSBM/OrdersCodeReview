<?php

use yii\helpers\Html;

/**
 * Create Order View
 */

$this->title = 'Create Order';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="data-table-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="data-table-form">
        <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data', 'id' => 'new_order']) ?>
        <div class="form-group">
            <?= Html::label('ORDER ID :', 'order_id') ?>
            <?= Html::input('text', 'order_id', '', ['placeholder'=>'(order ID)']) ?>
            <?= Html::button('Save', [
                'class' => 'btn btn-primary', 
                'style'=>'margin: -5px 0 0 25px;', 
                'onclick'=>'saveData("'.\yii\helpers\Url::toRoute('/site/create').'")'
                ]) ?>
        </div>
        <div id="error-msg"></div>
        <h3>Add Row</h3>
        <table id="data-table" width="100%">
            <tbody>
                <tr>
                    <th width="3%">№</th>
                    <th width="8%">available</th>
                    <th width="15%">price</th>
                    <th>description</th>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>
                       <?= Html::checkbox('agree_1', true) ?>
                    </td>
                    <td>
                        <?= Html::input('text', 'price_1', '', ['class' => 'price-input', 'placeholder'=>'(price)']) ?>
                    </td>
                    <td>
                        <?= Html::input('text', 'desc_1', '', ['class' => 'desc-input', 'placeholder'=>'(description)']) ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group" style="margin-top:25px;">
            <?= Html::button('<span class="glyphicon glyphicon-plus"></span>', ['class' => 'btn btn-success', 'onclick'=>'addTr()']) ?>
            <?= Html::button('<span class="glyphicon glyphicon-minus"></span>', ['class' => 'btn btn-danger', 'onclick'=>'delTr()']) ?>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>