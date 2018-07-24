<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/12
 * @time: 19:15
 */

namespace DianHun\Util\Http;

/**
 * Class Response
 * @package DianHun\Util\Http
 */
class Response implements ResponseInterface
{
    /**
     * @var bool
     */
    private $error = false;

    /**
     * @var int
     */
    private $errorCode = 0;

    /**
     * @var null
     */
    private $errorMsg = null;

    /**
     * @var int
     */
    private $httpStatusCode = 0;

    /**
     * @var int
     */
    private $curlErrorCode = 0;

    /**
     * @var null
     */
    private $body = null;

    /**
     * Response constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return null|string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param $errorMsg
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * @return int
     */
    public function getCurlErrorCode()
    {
        return $this->curlErrorCode;
    }

    /**
     * @param $curlErrorCode
     */
    public function setCurlErrorCode($curlErrorCode)
    {
        $this->curlErrorCode = $curlErrorCode;
    }

    /**
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
