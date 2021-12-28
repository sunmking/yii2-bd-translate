<?php

/**
 * Created by PhpStorm.
 * User: saviorlv
 * Date: 2018/10/30
 * Time: 13:16
 * @author saviorlv <1042080686@qq.com>
 */

namespace Saviorlv\Baidu;

use Yii;
use GuzzleHttp\Client;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Translate
 * @package Saviorlv\Baidu
 */
class Translate extends Component
{
    /**
     * @var string
     */
    public $app_id;

    /**
     * @var string
     */
    public $sec_key;

    /**
     * @var string
     */
    public $apiUrl = 'https://api.fanyi.baidu.com/api/trans/vip/translate';

    /**
     * @var array
     */
    public $guzzleOptions = [];
    /**
     * @var array
     */
    public $langue = ['zh', 'en', 'yue', 'wyw', 'jp', 'kor', 'fra', 'spa', 'th', 'ara', 'ru', 'pt', 'de', 'it', 'el', 'nl', 'pl', 'bul', 'est', 'dan', 'fin', 'cs', 'rom', 'slo', 'swe', 'hu', 'cht', 'vie'];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->app_id === null) {
            throw new InvalidConfigException('The "app_id" property must be set.');
        }
        if ($this->sec_key === null) {
            throw new InvalidConfigException('The "sce_key" property must be set.');
        }
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * @param array $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * 翻译入口
     * @param $query
     * @param $from
     * @param $to
     * @return mixed
     */
    public function translate($query, $from, $to)
    {

        if (!\in_array($from, $this->langue)) {
            throw new InvalidParamException('Invalid response langue from: ' . $from);
        }
        if (!\in_array($to, $this->langue)) {
            throw new InvalidParamException('Invalid response langue to: ' . $to);
        }

        $args = array(
            'q' => $query,
            'appid' => $this->app_id,
            'salt' => rand(10000, 99999),
            'from' => $from,
            'to' => $to,

        );
        $args['sign'] = self::buildSign($query, $args['salt']);

        try {
            $response = $this->getHttpClient()->get($this->apiUrl, [
                'query' => $args,
            ])->getBody()->getContents();

            $res = \json_decode($response, true);
            return self::returnMsg($res);
        } catch (\Exception $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 签名
     * @param $query
     * @param $salt
     * @return string
     */
    protected function buildSign($query, $salt)
    {
        $str = $this->app_id . $query . $salt . $this->sec_key;
        $ret = md5($str);
        return $ret;
    }

    /**
     * @return array
     */
    protected function getCodeMsg()
    {
        return [
            52000 => "成功",
            52001 => "请求超时",
            52002 => "系统错误",
            52003 => "未授权用户",
            54000 => "必填参数为空",
            54001 => "签名错误",
            54003 => "访问频率受限",
            54004 => "账户余额不足",
            54005 => "长query请求频繁",
            58000 => "客户端IP非法",
            58001 => "译文语言方向不支持"
        ];
    }

    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function returnMsg($res)
    {
        $arrayMsg = self::getCodeMsg();
        if (isset($res['error_code'])) {
            $code = $res['error_code'];
            $msg = $arrayMsg[$code];
            return ['code' => $code, 'msg' => $msg, 'data' => $res['data']];
        } else {
            return ['code' => "0", 'msg' => "请求成功", 'data' => $res];
        }
    }
}
