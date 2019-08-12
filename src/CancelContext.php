<?php
namespace Payment;

use Payment\Cancel\AliCancel;
use Payment\Common\BaseStrategy;
use Payment\Common\PayException;

/**
 * 查询上下文
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 * Class CancelContext
 * @package Payment
 */
class CancelContext
{
    /**
     * 查询的渠道
     * @var BaseStrategy
     */
    protected $cancel;


    /**
     * 设置对应的查询渠道
     * @param string $channel 查询渠道
     *  - @see Config
     *
     * @param array $config 配置文件
     * @throws PayException
     * @author helei
     */
    public function initCancel($channel, array $config)
    {
        try {
            switch ($channel) {
                case Config::ALI_CANCEL:
                    $this->cancel = new AliCancel($config);
                    break;
                default:
                    throw new PayException('当前仅支持：ALI_CANCEL');
            }
        } catch (PayException $e) {
            throw $e;
        }
    }

    /**
     * 通过环境类调用支付异步通知
     *
     * @param array $data
     *      // 二者设置一个即可
     *      $data => [
     *          'transaction_id'    => '原付款支付宝交易号',
     *          'order_no' => '商户订单号',
     *      ];
     *
     * @return array
     * @throws PayException
     * @author helei
     */
    public function cancel(array $data)
    {
        if (! $this->cancel instanceof BaseStrategy) {
            throw new PayException('请检查初始化是否正确');
        }

        try {
            return $this->cancel->handle($data);
        } catch (PayException $e) {
            throw $e;
        }
    }
}
