<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/13
 * @time: 9:27
 */

namespace DianHun\Util\Http;

interface ClientInterface
{
    const VERSION = '0.1.0';

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @param $payload
     * @return ResponseInterface
     */
    public function request($method, $url, array $data, $payload);

    /**
     * @return array
     */
    public function getDefaultOptions();

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function addHeader($key, $value);

    /**
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function addCookie($key, $value);

    /**
     * @param array $cookies
     * @return void
     */
    public function setCookies(array $cookies);

    /**
     * @param $userAgent
     * @return void
     */
    public function setUserAgent($userAgent);

    /**
     * @param $referer
     * @return void
     */
    public function setReferer($referer);

    /**
     * @param $second
     * @return void
     */
    public function setConnectTimeOut($second);

    /**
     * @param $second
     * @return void
     */
    public function setTimeOut($second);

    /**
     * @return void
     */
    public function reset();

    /**
     * @return void
     */
    public function close();
}
