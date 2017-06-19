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

namespace easyops\curl;

/**
 * Class EasyResponse
 * @package easyops\curl
 */
class EasyResponse
{
    /** @var int $code */
    protected $code;

    /** @var string $message */
    protected $message;

    /** @var array $headers */
    protected $headers;

    /** @var string $contentType */
    protected $contentType;

    /** @var string $body */
    protected $body;

    /**
     * EasyResponse constructor.
     * @param int $code
     * @param string $message
     * @param array $headers
     * @param string $contentType
     * @param string $body
     */
    public function __construct($code, $message, $headers, $contentType, $body)
    {
        $this->code = $code;
        $this->message = $message;
        $this->headers = $headers;
        $this->contentType = $contentType;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'http_code' => $this->code,
            'http_message' => $this->message,
            'headers' => $this->headers,
            'content_type' => $this->contentType,
            'body' => $this->body,
        ];
    }

    /**
     * @param bool $toArray
     * @return mixed
     */
    public function getBody($toArray = false)
    {
        if ($toArray) {
            if (strpos($this->contentType, 'application/json') === 0) {
                return json_decode($this->body);
            }
        }
        return $this->body;
    }
}