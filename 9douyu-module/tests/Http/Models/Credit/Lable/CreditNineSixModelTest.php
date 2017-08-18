<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午3:26
 */
namespace Tests\Http\Models\Credit\Lable;

use App\Http\Models\Credit\Lable\CreditNineSixModel;

use App\Http\Models\Credit\Lable\CreditLableModel;
/**
 * 九省心六月期债权按标签筛选数据
 *
 * Class CreditNineSixModelTest
 */
class CreditNineSixModelTest extends \TestCase
{

    /**
     * 获取未使用的债权集合
     * @return mixed
     */
    public function testGetUnusedCreditList(){
        $obj  = new CreditNineSixModel;
        $data = $obj->getUnusedCreditList();
        $this->assertTrue(is_array($data));
        return $data;
    }

}