<?php

use yii\helpers\Html;

/**
 * Update Order View
 */

$this->title = 'Edit Order';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="data-table-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!empty($data)): ?>
    <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data', 'id' => 'new_order']) ?>
    <div class="form-group">
        <?= Html::label('ORDER ID :', 'order_id') ?>
        <?= Html::input('text', 'order_id', $data[0]['order_id'], ['placeholder'=>'(order ID)']) ?>
        <?= Html::button('Save', [
            'class' => 'btn btn-primary', 
            'style'=>'margin: -5px 0 0 25px;', 
            'onclick'=>'updateData("'.\yii\helpers\Url::toRoute('/site/update').'")'
            ]) ?>
    </div>
    <div id="error-msg"></div>
    <table id="data-table" width="100%">
        <tbody>
            <tr>
                <th width="3%">№</th>
                <th width="8%">available</th>
                <th width="15%">price</th>
                <th>description</th>
            </tr>       
            <?php foreach ($data as $key => $orderItem): ?>
            <tr>
                <td><?=$key+1?></td>
                <td>
                   <?= Html::checkbox('agree_'.$orderItem['id'], $orderItem['availabel']); ?>
                </td>
                <td>
                    <?= Html::input('text', 'price_'.$orderItem['id'], $orderItem['price'], ['class' => 'price-input', 'placeholder'=>'(price)']) ?>
                </td>
                <td>
                    <?= Html::input('text', 'desc_'.$orderItem['id'], $orderItem['description'], ['class' => 'desc-input', 'placeholder'=>'(description)']) ?>
                </td>
            </tr>
            <?php endforeach; ?>          
        </tbody>
    </table>
   <?= Html::endForm() ?>
    <?php else: ?>
        <h3>Order not found.</h3>
    <?php endif; ?>
</div>