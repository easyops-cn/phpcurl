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
 * Class EasyRequest
 * @package easyops\curl
 */
class EasyRequest
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    const CONTENT_TYPE_CUSTOMIZE = 0;
    const CONTENT_TYPE_FORM = 1;
    const CONTENT_TYPE_URLENCODED = 2;
    const CONTENT_TYPE_JSON = 3;

    /** @var string $method */
    protected $method = 'GET';

    /** @var array $headers */
    protected $headers = [];

    /** @var array $query */
    protected $query = [];

    /** @var mixed $data */
    protected $data = null;

    /** @var int $contentType */
    protected $contentType = self::CONTENT_TYPE_CUSTOMIZE;

    protected $url = "";

    /**
     * EasyRequest constructor.
     * @param string $url
     */
    public function __construct($url = "")
    {
        $this->url = $url;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader($key, $value)
    {
        $key = trim($key);
        $value = trim($value);
        $this->headers[strtolower($key)] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = [];
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = $this->headers;
        if ($this->contentType == self::CONTENT_TYPE_FORM) {
            unset($headers['content-type']);
        }
        elseif ($this->contentType == self::CONTENT_TYPE_URLENCODED) {
            $headers['content-type'] = 'application/x-www-form-urlencoded';
        }
        elseif ($this->contentType == self::CONTENT_TYPE_JSON) {
            $headers['content-type'] = 'application/json';
        }
        return $headers;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function setQuery(array $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setJson(array $data)
    {
        $this->data = json_encode($data, JSON_FORCE_OBJECT);
        $this->contentType = self::CONTENT_TYPE_JSON;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setForm(array $data)
    {
        $this->data = $data;
        $this->contentType = self::CONTENT_TYPE_FORM;
        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        $this->contentType = self::CONTENT_TYPE_CUSTOMIZE;
        return $this;
    }

    /**
     * @param string $key
     * @param string $filename
     * @param string $mime
     * @param string $postname
     * @return $this
     */
    public function attachFile($key, $filename, $mime = 'application/octet-stream', $postname = null)
    {
        $filename = realpath($filename);
        is_null($postname) && $postname = basename($filename);
        !is_array($this->data) && $this->data = [];
        $this->data[$key] = new \CURLFile($filename, $mime, $postname);
        $this->contentType = self::CONTENT_TYPE_FORM;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        $url = parse_url($this->url);
        $scheme   = isset($url['scheme']) ? $url['scheme'] . '://' : '';
        $host     = isset($url['host']) ? $url['host'] : '';
        $port     = isset($url['port']) ? ':' . $url['port'] : '';
        $user     = isset($url['user']) ? $url['user'] : '';
        $pass     = isset($url['pass']) ? ':' . $url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($url['path']) ? $url['path'] : '';
        $fragment = isset($url['fragment']) ? '#' . $url['fragment'] : '';
        parse_str(isset($url['query']) ? $url['query'] : '', $query);
        $query = array_merge($query, $this->query);
        $query = empty($query) ? '' : '?'.http_build_query($query);
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * @param string $url
     * @return EasyResponse|null
     */
    public function send($url = null)
    {
        !is_null($url) && $this->url = $url;
        return EasyCurl::sendRequest($this);
    }


}