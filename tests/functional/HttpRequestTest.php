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
class HttpRequestTest extends PHPUnit_Framework_TestCase
{
    const URL = '127.0.0.1:8080';

    public function testGET()
    {
        $request = new \easyops\curl\EasyRequest();
        $response = $request->send(static::URL.'/success.php');
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getBody());

        $response = $request->send(static::URL.'/failure.php');
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals('Failure', $response->getBody());

        $response = $request->send(static::URL.'/404page.php');
        $this->assertEquals(404, $response->getCode());
    }

    public function testJsonRequest()
    {
        $request = new \easyops\curl\EasyRequest();
        $response = $request->setJson(['a' => 1, 'b' => 2, 'c' => 3])
            ->setMethod('post')->send(static::URL.'/echo.php');
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('{"a":1,"b":2,"c":3}', $response->getBody());
    }

    public function testUpload()
    {
        $file = __FILE__;
        $request = new \easyops\curl\EasyRequest();
        $response = $request->setForm(['a' => 1, 'b' => 2, 'c' => 3])
            ->attachFile('upload', $file)
            ->setMethod('post')
            ->send(static::URL.'/upload.php');
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(basename($file)."\t".filesize($file)."\n", $response->getBody());
    }
}
