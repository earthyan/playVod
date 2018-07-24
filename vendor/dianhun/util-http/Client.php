<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/12
 * @time: 18:21
 */

namespace DianHun\Util\Http;

use Curl\Curl;

/**
 * Class Client
 * @package DianHun\Util\Http
 */
class Client implements ClientInterface
{
    const DEFAULT_USER_AGENT = 'PHP Curl (+http://www.icodemo.com)';
    const DEFAULT_REFERER = 'http://www.icodemo.com';
    const DEFAULT_CONNECT_TIME_OUT = 5;
    const DEFAULT_TIME_OUT = 5;

    /**
     * @var array
     */
    private static $defaults = array(
        'verify' => false
    );

    /**
     * @var array
     */
    private $config = array();

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var Response
     */
    private $response;

    /**
     * Client constructor.
     * @param array $option
     */
    public function __construct(array $option = array())
    {
        try {
            $this->curl = new Curl();
        } catch (\ErrorException $e) {
        }
        $this->configureDefaults($option);
        $this->initialize();
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @param bool $payload
     * @return ResponseInterface
     */
    public function request($method, $url, array $data = [], $payload = false)
    {
        switch (strtolower($method)) {
            case 'get':
                $this->curl->get($url, $data);
                break;
            case 'post':
                $this->curl->post($url, $data);
                break;
            case 'put':
                $this->curl->put($url, $data, $payload);
                break;
            case 'patch':
                $this->curl->patch($url, $data, $payload);
                break;
            case 'delete':
                $this->curl->delete($url, $data, $payload);
                break;
            default:
                $this->curl->get($url, $data);
                break;
        }
        $response = $this->transfer();
        $this->close();
        return $response;
    }

    /**
     * @return ResponseInterface
     */
    public function transfer()
    {
        $res = new Response();
        if ($this->curl->error) {
            $res->setError(true);
        }
        $res->setErrorCode($this->curl->error_code);
        $res->setErrorMsg($this->curl->error_message);
        $res->setHttpStatusCode($this->curl->http_status_code);
        $res->setCurlErrorCode($this->curl->curl_error_code);
        $res->setBody($this->curl->response);
        return $res;
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array_merge(self::$defaults, [
            'headers' => [
                'User-Agent' => self::DEFAULT_USER_AGENT,
                'Referer' => self::DEFAULT_REFERER
            ],
            'connectTimeOut' => self::DEFAULT_CONNECT_TIME_OUT,
            'timeOut' => self::DEFAULT_TIME_OUT
        ]);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value)
    {
        $this->curl->setHeader($key, $value);
    }

    /**
     * @param $headers
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function addCookie($key, $value)
    {
        $this->curl->setCookie($key, $value);
    }

    /**
     * @param $cookies
     */
    public function setCookies(array $cookies)
    {
        foreach ($cookies as $key => $value) {
            $this->addCookie($key, $value);
        }
    }

    /**
     * @param $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->curl->setUserAgent($userAgent);
    }

    /**
     * @param $referer
     */
    public function setReferer($referer)
    {
        $this->curl->setReferer($referer);
    }

    /**
     * @param $second
     * @return void
     */
    public function setConnectTimeOut($second)
    {
        $this->curl->setOpt(CURLOPT_CONNECTTIMEOUT, $second);
    }

    /**
     * @param $second
     * @return void
     */
    public function setTimeOut($second)
    {
        $this->curl->setOpt(CURLOPT_TIMEOUT, $second);
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->curl = $this->curl->reset();
        $this->initialize();
        return $this;
    }

    /**
     * @return $this
     */
    public function close()
    {
        $this->curl = $this->curl->close();
        return $this;
    }

    /**
     * Close the connection when the Client object will be destroyed.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     *  the Curl initialize
     */
    private function initialize()
    {
        $this->setHeaders($this->config['headers']);

        if (isset($this->config['cookies'])) {
            $this->setCookies($this->config['cookies']);
        }

        $verify = $this->config['verify'];
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, $verify);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, $verify ? 2 : 0);

        $this->setConnectTimeOut($this->config['connectTimeOut']);
        $this->setTimeOut($this->config['timeOut']);
    }

    /**
     * @param array $config
     */
    private function configureDefaults(array $config = array())
    {
        $this->config = $config + self::$defaults;

        if (!isset($this->config['headers'])) {
            $this->config['headers'] = [
                'User-Agent' => self::DEFAULT_USER_AGENT,
                'Referer' => self::DEFAULT_REFERER
            ];
        }

        if (!empty($config['cookies']) && is_array($config['cookies'])) {
            $this->config['cookies'] = $config['cookies'];
        }

        $this->config['connectTimeOut'] = !isset($this->config['connectTimeOut']) ?
            self::DEFAULT_CONNECT_TIME_OUT : $config['connectTimeOut'] ;

        $this->config['timeOut'] = !isset($this->config['timeOut']) ?
            self::DEFAULT_TIME_OUT : $config['timeOut'] ;
    }
}
