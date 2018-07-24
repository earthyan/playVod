<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/13
 * @time: 9:37
 */

namespace DianHun\Util\Http;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isError();

    /**
     * @return int
     */
    public function getErrorCode();

    /**
     * @return string
     */
    public function getErrorMsg();

    /**
     * @return int
     */
    public function getHttpStatusCode();

    /**
     * @return string
     */
    public function getBody();
}
