<?php

namespace app\controllers;

use Yii;
use app\models\DataTable;
use yii\web\Controller;

/**
 * SiteController
 */
class SiteController extends Controller
{
    public $defaultAction = 'create';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays order with order-item rows.
     * @param integer $item_id
     * @return mixed
     */
    public function actionView($item_id)
    {
        $data = DataTable::find()->where(['order_id'=>$item_id])->asArray()->all();
        
        return $this->render('view', [
            'data' => $data,
        ]);
    }

    /**
     * Creates a new Order.
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $data = $this->collectData();
            $error = $this->saveData($data);
            if (!$error) {
                echo json_encode([
                    'status' => 'success', 
                    'msg' => 'Order data has been saved.', 
                    'url'=> \yii\helpers\Url::toRoute(['/site/view', 'item_id'=>$data['order_id']])
                ]);
            } else {
                echo json_encode($error);
            } 
            Yii::$app->end();
        }
        return $this->render('create');
    }

    /**
     * Updates an existing Order data.
     */
    public function actionUpdate()
    {   
        if (Yii::$app->request->isAjax) {
            $data = $this->collectData();
            $error = $this->saveData($data, true);
            if (!$error) {
                echo json_encode([
                    'status' => 'success', 
                    'msg' => 'Order data has been saved.', 
                    'url'=> \yii\helpers\Url::toRoute(['/site/view', 'item_id'=>$data['order_id']])
                ]);
            } else {
                echo json_encode($error);
            } 
        }
        Yii::$app->end();
    }

    /**
     * Collects data from POST and structures it for further processing
     * @return array
     */
    private function collectData()
    {
        $orderData = array(
            'order_id' => '',  
            'items' => array() 
        );
        
        foreach ($_POST as $key => $val) {
            if ($key == 'order_id') {
                $orderData['order_id'] = $val;
            } else {
                $keyIndex = substr($key, strpos($key, '_')+1);
                $newKey = substr($key, 0, strpos($key, '_'));
                //'agree', 'price', 'desc' - the required values
                if (in_array($newKey, array('agree', 'price', 'desc'))) {
                    $orderData['items'][$keyIndex][$newKey] = $val;
                }
            }
        }  
        return $orderData;
    }
    
    /**
     * Save Order-data to DB
     * @param array $data
     * @param boolean $update
     * <ul>
     *  <li>true - New Order</li>
     *  <li>false - Update Order</li>
     * </ul>
     * @return array
     */
    private function saveData($data, $update = false) {
        try {
            //validation
            $errors = $this->validateData($data, $update);
            if (!$errors) {        
                foreach ($data['items'] as $key => $orderItem) {
                    if ($update) {
                        //update existing rows
                        $order = DataTable::find()->where(['id'=>$key])->one();
                    } else {
                        //new row
                        $order = new DataTable();
                        $order->order_id = $data['order_id'];
                    }
                    $order->price = $orderItem['price'];
                    $order->description = $orderItem['desc'];
                    $order->availabel = isset($orderItem['agree'])?$orderItem['agree']:0;
                    $order->save();
                } 
            }
        } catch (Exception $e) {
            return ['status' => 'failed', 'msg' => $e->getMessage()];
        }
        return $errors;
    }
    
    /**
     * Validate data before saving
     * @param array $data
     * @param boolean $update
     * <ul>
     *  <li>true - New Order</li>
     *  <li>false - Update Order</li>
     * </ul>
     * @return boolean
     */
    private function validateData($data, $update = false)
    {
        $errorStr = '';
        $exists = DataTable::find()->where(['order_id'=>$data['order_id']])->exists();
        
        if ($update) {
            //for update order must exist
            if (!$exists) {
                $errorStr .= '<li>Order ID'.$data['order_id'].' not availabel.</li>';
            }
        } else {    
            //required order ID
            if (empty($data['order_id'])) {
                $errorStr .= '<li>Field Order ID required.</li>';
            }
            if ($exists) {
                //on new record
                $errorStr .= '<li>Order ID '.$data['order_id'].' alredy exists.</li>';
            }
        }
        if (!is_numeric($data['order_id'])) {
            //order_id must be a number
            $errorStr .= '<li>Order ID must be a number.</li>';
        }
        
        foreach ($data['items'] as $key => $orderItem) {
            if (empty($orderItem['price']) || empty($orderItem['desc'])) {
                //price and description are required fields
                $errorStr .= '<li>Row #'.$key.': Fields "Price" and "Description" is required.</li>';
            }
            if (!is_numeric($orderItem['price'])) {
                //price must be a number
                $errorStr .= '<li>Row #'.$key.': Field Price has wrong value.</li>';
            }
        }
        
        if (!empty($errorStr)) {
            $errors = ['status' => 'failed', 'msg' => 'Error(s):<ul>'.$errorStr.'</ul>'];
        } else {
            $errors = false;
        }
        
        return $errors;
    }
    
}
