<?php

/**
 * @author index
 *   ┏┓   ┏┓+ +
 *  ┏┛┻━━━┛┻┓ + +
 *  ┃       ┃
 *  ┃  ━    ┃ ++ + + +
 * ████━████┃+
 *  ┃       ┃ +
 *  ┃  ┻    ┃
 *  ┃       ┃ + +
 *  ┗━┓   ┏━┛
 *    ┃   ┃
 *    ┃   ┃ + + + +
 *    ┃   ┃     Codes are far away from bugs with the animal protecting
 *    ┃   ┃ +         神兽保佑,代码无bug
 *    ┃   ┃
 *    ┃   ┃   +
 *    ┃   ┗━━━┓ + +
 *    ┃       ┣┓
 *    ┃       ┏┛
 *    ┗┓┓┏━┳┓┏┛ + + + +
 *     ┃┫┫ ┃┫┫
 *     ┗┻┛ ┗┻┛+ + + +
 */

namespace easyops\curl\Exception;

use easyops\curl\EasyRequest;
use RuntimeException;

/**
 * Class EasyCurlException
 * @package easyops\curl
 */
class EasyCurlException extends RuntimeException
{
    /** @var EasyRequest $request */
    protected $request;

    /**
     * EasyCurlException constructor.
     * @param EasyRequest $request
     * @param string $message
     * @param int $code
     */
    public function __construct(EasyRequest $request, $message = "", $code = 0)
    {
        $this->request = $request;
        parent::__construct($message, $code);
    }

    /**
     * @return EasyRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}