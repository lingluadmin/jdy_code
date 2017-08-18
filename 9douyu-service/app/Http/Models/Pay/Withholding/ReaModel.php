<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:59
 * Desc: 融宝代扣
 */

namespace App\Http\Models\Pay\Withholding;

use App\Http\Models\Pay\PayModel;
use App\Http\Models\Common\ValidateModel;
use App\Lang\LangModel;
use App\Services\Pay\Withholding\Rea\Reapay;
use App\Tools\ToolMoney;

class ReaModel extends PayModel{

    private $service;

    public function __construct()
    {
        parent::__construct('REAPAY_CONFIG');

        $this->service = new Reapay($this->config);
    }

    /**
     * @param array $params
     * @return array
     */
    public function submit(array $params){


        //组装数据
        $config   = $this->config;
        $order_id = $params['order_id'];
        $data = array(
            'merchant_id' => $config['merchant_id'],
            'order_no'    => $order_id,
            'check_code'  => $params['sms_code']
        );

        //解析结果
        $return = $this->service->submit($data);
        $this->submitReturn['order_id']       = $order_id;    //订单号

        if($return['result_code']=='0000'){

            $this->submitReturn['status'] = self::TRADE_SUCCESS;
        }

        $this->submitReturn['msg']    = $return['result_msg'];

        return $this->submitReturn;
    }



    /**
     * @param array $params
     * @return array
     * 解密接口
     */
    public function decrypt(array $params)
    {
        // TODO: Implement decrypt() method.
        $vo = $this->service->deCodeNotice($params);

        //签名正确
        if(is_array($vo)){

            $this->decryptReturn['verify_status']  = true;      //签名状态
            $this->decryptReturn['trade_no']       = $vo['trade_no'];  //交易流水号
            $this->decryptReturn['order_id']       = $vo['order_no'];     //订单号
            $this->decryptReturn['amount']         = ToolMoney::formatDbCashDelete($vo['total_fee']);//订单金额
            if($vo['status']=='TRADE_FINISHED'){
                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;
            }
        }else{
            $this->decryptReturn['msg']  = self::TRADE_SIGN_ERROR;
        }

        return $this->decryptReturn;
    }

    /**
     * @param array $params
     * 签约接口
     */
    public function signed(array $params)
    {
        // TODO: Change the autogenerated stub
        $orderId = $params['order_id'];

        $this->signedReturn['order_id'] = $orderId;
        
        //参数数组
        $data = array(
            'card_no' => $params['card_no'],
            'owner' => $params['name'],
            'cert_no' => $params['id_card'],
            'order_no' => $orderId,
            'phone'=> $params['phone'],
            'transtime' => date('Y-m-d H:i:s'),
            'total_fee' => $params['cash']*100,
            'title' => '充值',
            'body' => '九斗鱼充值',
            'notify_url'    => $params['notify_url'],
            'terminal_type'=>'web',
            'terminal_info' => 'a4',//$this->getLogic()->getMacAddress(),//'a4:5e:60:f3:33:db',
            'member_ip' => $params['user_ip'],
            'member_id' => $params['user_id'],
        );
        $result = $this->service->signed($data);
        if($result['result_code'] == '0000'){

            $this->signedReturn['sign']      = '';
            $this->signedReturn['status']    = self::TRADE_SUCCESS;
        }

        $this->signedReturn['msg'] = $result['result_msg'];


        return $this->signedReturn;
    }

    /**
     * @param array $params
     * @return array
     * 发送验证码接口
     */
    public function sendCode(array $params)
    {
         // TODO: Change the autogenerated stub
        $orderId = $params['order_id'];

        $return = [
            'status' => self::TRADE_FAIL,
            'msg'    => self::TRADE_FAIL_MSG,
            'order_id'  => $orderId
        ];

        $result = $this->service->sendCode($orderId);

        if($result['result_code'] == '0000'){
           $return['status'] = self::TRADE_SUCCESS;
        }

        $return['msg'] = $result['result_msg'];

        return $return;
    }

    /**
     * @param array $params
     * @return array
     * 订动查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.
        $orderId = $params['order_id'];
        $this->searchReturn['order_id'] = $orderId;
        $res = $this->service->search($orderId);

        $this->searchReturn['trade_no'] = isset($res['trade_no']) ? $res['trade_no'] : '';
        $this->format($res);

        return $this->searchReturn;

    }

    /**
     * @param array $returnData
     * 查单接口数据格式化
     */
    public function format(array $returnData)
    {

        if(isset($returnData['result_code'])
            && $returnData['result_code'] == '0000'
            && $returnData['status'] == 'completed'){
            $msg = self::TRADE_SUCCESS_MSG;
            $tradeStatus = self::TRADE_SUCCESS;

        }else{
            $tradeStatus = self::TRADE_FAIL;
            $msg = isset($returnData['result_msg']) ? $returnData['result_msg'] : self::TRADE_FAIL_MSG;
        }
        $cash   = isset($returnData["total_fee"])?intval($returnData["total_fee"] / 100):"-1";
        $this->searchReturn['status']   = $tradeStatus;
        $this->searchReturn['msg']      = $msg;
        $this->searchReturn['cash']     = $cash;
    }


    /**
     * @param $phone
     * @param $name
     * @param $idCard
     * @param $cardNo
     * 融宝储蓄卡鉴权
     */
    public function checkDepositCard($phone,$name,$idCard,$cardNo){

        $result = $this->service->checkDepositCard($phone,$name,$idCard,$cardNo);
        return $this->checkCardFormat($result);
    }


    /**
     * @param $phone
     * @param $name
     * @param $idCard
     * @param $cardNo
     * @param $cvv2
     * @param $validthru
     * 融宝信用卡鉴权
     */
    public function checkCreditCard($phone,$name,$idCard,$cardNo,$cvv2,$validthru){

        $result = $this->service->checkCreditCard($phone,$name,$idCard,$cardNo,$cvv2,$validthru);
        return $this->checkCardFormat($result);
    }

    /**
     * @param $result
     * @return mixed
     * 验卡接口返回值格式化
     */
    private function checkCardFormat($result){

        if($result['result_code'] == '0000'){
            $return['status'] = self::TRADE_SUCCESS;
        }else{
            $return['status'] = self::TRADE_FAIL;
        }
        $return['msg'] = $result['result_msg'];
        $return['result_code'] = $result['result_code'];
        $return['bank_card_type'] = isset($result['bank_card_type']) ? $result['bank_card_type'] : '';
        return $return;
    }


}