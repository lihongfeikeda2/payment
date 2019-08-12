<?php
namespace Payment\Client;

use Payment\Common\PayException;
use Payment\Config;
use Payment\CancelContext;

/**
 * @author: helei
 * @createTime: 2017-09-02 18:20
 * @description: 退款操作客户端接口
 * @link      https://www.gitbook.com/book/helei112g1/payment-sdk/details
 * @link      https://helei112g.github.io/
 *
 * Class Refund
 * @package Payment\Client
 */
class Cancel
{
    private static $supportChannel = [
        Config::ALI_CANCEL,// 支付宝
    ];

    /**
     * 退款实例
     * @var RefundContext
     */
    protected static $instance;

    protected static function getInstance($channel, $config)
    {
        /* 设置内部字符编码为 UTF-8 */
        mb_internal_encoding("UTF-8");

        if (is_null(self::$instance)) {
            static::$instance = new CancelContext();
        }

        try {
            static::$instance->initCancel($channel, $config);
        } catch (PayException $e) {
            throw $e;
        }

        return static::$instance;
    }

    public static function run($channel, $config, $orderData)
    {
        if (! in_array($channel, self::$supportChannel)) {
            throw new PayException('sdk当前不支持该撤销订单渠道，当前仅支持：' . implode(',', self::$supportChannel));
        }

        try {
            $instance = self::getInstance($channel, $config);

            $ret = $instance->cancel($orderData);
        } catch (PayException $e) {
            throw $e;
        }

        return $ret;
    }
}
