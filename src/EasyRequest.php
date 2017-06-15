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
    const CONTENT_TYPE_CUSTOMIZE = 0;
    const CONTENT_TYPE_FORM = 1;
    const CONTENT_TYPE_URLENCODED = 2;
    const CONTENT_TYPE_RAW = 3;
    const CONTENT_TYPE_JSON = 4;

    /** @var string $method */
    protected $method = 'GET';

    /** @var array $headers */
    protected $headers = [];

    /** @var array $query */
    protected $query = [];

    /** @var mixed $data */
    protected $data = null;

    /** @var null $contentType */
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
     * @param bool $keepCase
     * @return $this
     */
    public function setHeader($key, $value, $keepCase = true)
    {
        $key = trim($key);
        $value = trim($value);
        !$keepCase && $key = strtolower($key);
        $this->headers[strtoupper($key)] = "$key: $value";
        return $this;
    }

    /**
     * @param array $headers
     * @param bool $keepCase
     * @return $this
     */
    public function setHeaders(array $headers, $keepCase = true)
    {
        $this->headers = [];
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value, $keepCase);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        foreach ($this->headers as $key => $item) {
            $key = substr($item, 0, strlen($key));
            $headers[$key] = substr($item, strlen($key)+2-strlen($item));
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
        return $this->url;
    }

    /**
     * @return string
     */
    public function buildUrl()
    {
        $url = parse_url($this->url);
        $scheme   = isset($url['scheme']) ? $url['scheme'] . '://' : '';
        $host     = isset($url['host']) ? $url['host'] : '';
        $port     = isset($url['port']) ? ':' . $url['port'] : '';
        $user     = isset($url['user']) ? $url['user'] : '';
        $pass     = isset($url['pass']) ? ':' . $url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($url['path']) ? $url['path'] : '';
        $query    = isset($url['query']) ? '?' . $url['query'] : '';
        $fragment = isset($url['fragment']) ? '#' . $url['fragment'] : '';
        $queryStr = http_build_query($this->query);
        !empty($queryStr) && $query = '?' . $queryStr;
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