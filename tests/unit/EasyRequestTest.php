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

class EasyRequestTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $request = new \easyops\curl\EasyRequest('http://www.google.com/?q=abc&page=2');
        $this->assertEquals('http://www.google.com/?q=abc&page=2', $request->getURL());

        $request->setHeader('host', 'www.Google.com');
        $this->assertEquals('www.Google.com', $request->getHeaders()['host']);

        $request->setHeaders(['Content-Type' => 'application/json']);
        $this->assertEquals('application/json', $request->getHeaders()['content-type']);

        $request->setMethod('post');
        $this->assertEquals('post', $request->getMethod());

        $request->setQuery(['page' => 1, 'pageSize' => 30]);
        $this->assertEquals(1, $request->getQuery()['page']);
        $this->assertEquals(30, $request->getQuery()['pageSize']);
        $this->assertEquals('http://www.google.com/?q=abc&page=1&pageSize=30', $request->getURL());

        $request->setData('abc');
        $this->assertEquals('abc', $request->getData());
        $request->setForm(['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertArrayNotHasKey('content-type', $request->getHeaders());
        $this->assertArraySubset(['a' => 1, 'b' => 2, 'c' => 3], $request->getData());
        $request->setJson(['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertArrayHasKey('content-type', $request->getHeaders());
        $this->assertEquals('application/json', $request->getHeaders()['content-type']);
        $this->assertEquals('{"a":1,"b":2,"c":3}', $request->getData());
        $request->attachFile('upload', './temp.txt');
        $this->assertArrayNotHasKey('content-type', $request->getHeaders());
        $this->assertInstanceOf('CURLFile', $request->getData()['upload']);
    }

}
