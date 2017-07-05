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

use easyops\curl\Exception\EasyCurlException;


/**
 * Class EasyCurl
 * @package easyops\curl
 */
class EasyCurl
{
    /**
     * @param EasyRequest $request 请求对象
     * @return resource curl句柄
     */
    private static function parseRequest($request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $request->getURL());

        $method = strtoupper($request->getMethod());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $header = [];
        foreach ($request->getHeaders() as $key => $value) $header[] = "$key: $value";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $data = $request->getData();
        !is_null($data) && curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getData());

        return $ch;
    }

    /**
     * @param resource $ch
     * @param string $response
     * @return EasyResponse
     */
    private static function parseResponse($ch, $response)
    {
        $info = curl_getinfo($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $headerString = trim(substr($response, 0, $headerSize));
        $headers = explode("\r\n", trim($headerString));
        $message = array_shift($headers);
        $header = [];
        foreach ($headers as $item) {
            $pos = strpos($item, ':');
            $key = trim(substr($item, 0, $pos));
            $value = trim(substr($item, $pos+1));
            $header[$key] = $value;
        }
        $body = substr($response, $headerSize);

        return new EasyResponse($message, $header, $body, $info);
    }

    /**
     * EasyCurl sendRequest.
     * @param EasyRequest $request 请求对象
     * @return EasyResponse|null 返回对象
     */
    public static function sendRequest(EasyRequest $request)
    {
        $ch = self::parseRequest($request);
        $result = curl_exec($ch);

        // 判断请求异常
        if ($result === false) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);
            throw new EasyCurlException($request, $error, $errno);
        }
        // 解析curl结果
        $response = self::parseResponse($ch, $result);
        curl_close($ch);
        return $response;
    }
}
