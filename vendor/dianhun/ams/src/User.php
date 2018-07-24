<?php
/**
 * Created by IntelliJ IDEA.
 * @author: WangGuochao
 * @date: 2018/4/14
 * @time: 23:10
 */

namespace DianHun\Ams;

use DianHun\Util\Http\Client;
use DianHun\Util\Http\ClientInterface;
use DianHun\Util\Http\ResponseInterface;

/**
 * Class User
 * @package DianHun\Ams
 */
class User
{
    /**
     * Current version
     * @var string
     */
    const VERSION = '0.2.0';

    const USER_LOGIN_REQUEST_TYPE = 'auth';
    const USER_INFO_REQUEST_TYPE = 'info';
    const USER_LIST_REQUEST_TYPE = 'list';

    const SUCCESS_CODE = 0;
    const ERROR_CODE = -10000;
    const REQUEST_TIMEOUT_CODE = -10001;
    const REQUEST_FAIL_CODE = -10002;

    /**
     * @var Ams
     */
    private $ams;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    /**
     * User constructor.
     * @param Ams $ams
     * @param $key
     * @param ClientInterface|null $client
     */
    public function __construct(Ams $ams, $key, ClientInterface $client = null)
    {
        if (!is_string($key) || empty($key)) {
            throw new \UnexpectedValueException('不合法的参数key');
        }

        $this->key = $key;
        $this->ams = $ams;
        $this->client = $client;

        if (!$this->client instanceof ClientInterface) {
            $this->client = new Client();
        }
    }

    /**
     * 检查用户是否认证成功
     * 获取用户信息
     *
     * @return array
     */
    public function login()
    {
        $response = $this->sendCheckRequest($this->key, self::USER_LOGIN_REQUEST_TYPE);
        $result = $this->handleResponse($response);
        return $result;
    }

    /**
     * 请求用户id和用户名对应的列表
     *
     * @return array
     */
    public function getList()
    {
        $response = $this->sendCheckRequest($this->key, self::USER_LIST_REQUEST_TYPE);
        $result = $this->handleResponse($response);
        if ($result['code'] == self::SUCCESS_CODE) {
            $result['data'] = $result['data']['list'];
        }
        return $result;
    }

    /**
     * 请求单个用户信息
     *
     * @param $account
     * @return array
     */
    public function getByAccount($account)
    {
        $response = $this->sendCheckRequest($this->key, self::USER_INFO_REQUEST_TYPE, $account);
        $result = $this->handleResponse($response);
        return $result;
    }

    /**
     * @param $account
     */
    public function register($account)
    {
        throw new \UnexpectedValueException('暂不支持向AMS系统注册用户');
    }

    /**
     * 向AMS校验并读取数据
     *
     * @param $key
     * @param $type
     * @param string $account
     * @return ResponseInterface
     */
    private function sendCheckRequest($key, $type, $account = '')
    {
        $data = array(
            'key' => $key,
            'request' => $type
        );
        if ($account != '') {
            $data['user'] = $account;
        }
        $response = $this->client->request('POST', $this->ams->getCheckUrl(), $data, false);
        return $response;
    }

    /**
     * 处理通用响应
     *
     * @param ResponseInterface $response
     * @return array
     */
    private function handleResponse(ResponseInterface $response)
    {
        if ($response->isError()) {
            $isTimeout = $response->getErrorCode() == CURLE_OPERATION_TIMEDOUT;
            $code = $isTimeout ? self::REQUEST_TIMEOUT_CODE : self::REQUEST_FAIL_CODE;
            return ['code' => $code, 'data' => null, 'msg' => $response->getErrorMsg()];
        }

        $body = $response->getBody();
        $data = json_decode($body, true);
        if (!$data) {
            return ['code' => self::REQUEST_FAIL_CODE, 'data' => $body, 'msg' => '响应数据解析异常'];
        }

        if (!array_key_exists('code', $data) || $data['code'] != 0) {
            $msg = empty($data['msg']) ? json_encode($response, JSON_UNESCAPED_UNICODE) : $data['msg'];
            return [
                'code' => self::ERROR_CODE,
                'data' => $data,
                'msg' => $msg
            ];
        }

        unset($data['code'], $data['success'], $data['msg']);
        return ['code' => self::SUCCESS_CODE, 'data' => $data, 'msg' => 'success'];
    }
}
