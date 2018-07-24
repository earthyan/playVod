<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/15
 * @time: 15:41
 */

namespace DianHun\Ams;

/**
 * Class Ams
 * @package DianHun\Ams
 */
class Ams
{
    /**
     * 系统接口API host
     * @var string
     */
    const API_HOST = 'https://ams.om.dianhun.cn';

    /**
     * 系统登入uri
     * @var string
     */
    const LOGIN_URI = '/login';

    /**
     * 系统登出uri
     * @var string
     */
    const LOGOUT_URI = '/logout';

    /**
     * 系统校验及数据获取uri
     * @var string
     */
    const CHECK_URI = '/system/check';

    /**
     * 发起注册用户
     * @var string
     */
    const REGISTER_URI = '/system/register';

    /**
     * 应用审核ID
     * @var int
     */
    private $appId = 1006;

    /**
     * Ams constructor.
     * @param $appId
     */
    public function __construct($appId)
    {
        if (!is_int($appId)) {
            throw new \InvalidArgumentException('不合法的appId参数，只接受int类型参数');
        }
        $this->appId = $appId;
    }

    /**
     * 获取完整登录请求地址
     *
     * @param $redirect
     * @return string
     */
    public function getLoginUrl($redirect)
    {
        if (!filter_var($redirect, FILTER_VALIDATE_URL)) {
            throw new \UnexpectedValueException('不合法的登入成功跳转地址');
        }
        $url = self::API_HOST . self::LOGIN_URI . '?id=' . $this->appId;
        $url .= '&url=' . rawurlencode($redirect);
        return $url;
    }

    /**
     * 获取完整登出请求地址
     *
     * @param $redirect
     * @return string
     */
    public function getLogoutUrl($redirect)
    {
        if (!filter_var($redirect, FILTER_VALIDATE_URL)) {
            throw new \UnexpectedValueException('不合法的登出成功跳转地址');
        }
        $url = self::API_HOST . self::LOGOUT_URI . '?id=' . $this->appId;
        $url .= '&url=' . rawurlencode($redirect);
        return $url;
    }

    /**
     * 获取完整校验数据请求地址
     * @return string
     */
    public function getCheckUrl()
    {
        return self::API_HOST . self::CHECK_URI . '?id=' . $this->appId;
    }

    /**
     * 获取完整校验数据请求地址
     * @return string
     */
    public function getRegisterUrl()
    {
        return self::API_HOST . self::REGISTER_URI . '?id=' . $this->appId;
    }
}
