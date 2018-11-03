<?php
/**
 *
 * token认证类
 * @author whoru.S.Q <whoru@sqiang.net>
 * @created 2018/11/02 22:35:03
 */
class Token
{
    /**
     * 盐值生成符号集
     */
    private $saltSet = [
        "letters" => "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234569078",
        "symbol" => "?/<>{}[]=+_)(!,.\\|:;%$#@*^",
    ];
    /**
     * 生成盐值字母个数
     *
     */
    private $letterNumber = 10;
    /**
     * 生成盐值特殊符号个数
     */
    private $symbolNumber = 4;
    /**
     * 生成的Token  
     */
    private $token = '';
    /**
     * 签名信息
     */
    private $info = '';
    /**
     * 生成的salt
     */
    private $salt = '';
    /**
     * 签名集
     * @param array
     */
    private $sign = [];


    /**
     * Token constructor.
     * @param $info
     * @param $salt
     */
    public function __construct($info = '', $salt = '')
    {

        if (!$info) {
            return;
        }
        //设置info签名信息
        $this->setInfo($info);
        //设置盐值
        !$salt OR $this->salt = $salt;

        //生成token
        $this->generateToken();
    }
    /**
     *
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    /**
     * @param mixed $info 签名信息
     * @return void
     */
    public function setInfo($info)
    {
        if (is_array($info)) {
            $info = implode('',$info);
        }

        $this->info = $info;
    }
    /**
     * 获取token
     * @return string token
     */
    public function getToken()
    {
        if (empty($this->token)) {
            $this->generateToken();
        }
        return $this->token;
    }
    /**
     * 获取sign
     */
    public function getSign()
    {
        if(count($this->sign) != 2) {
            $this->generateToken();
        }
        return $this->sign;
    }
    /**
     * token 签名
     * @param array|string info 签名信息
     * @param string $token
     * @param string $salt
     * @return bool true 匹配 | false 不匹配
     */
    public function validate($info, $token, $salt)
    {
        //赋值
        $this->setInfo($info);
        $this->setSalt($salt);
        //生成的验证信息
        $this->generateToken();

        $result = ($this->token === $token);
        return $result;
    }
    /**
     *
     * 根据MD5生成token
     */
    private function generateToken()
    {
        //处理签名信息
        try {
            $info = $this->getInfo();
        } catch (Exception $e) {
            die($e->getMessage());
        }

        //获取salt
        $this->salt OR $this->generateSalt();
        // 生成token
        $this->token = md5($info . $this->salt);
        $this->sign = ['salt' => $this->salt, 'token' => $this->token];
    }

    /**
     *
     * @return string $info
     * @throws Exception
     */
    public function getInfo()
    {
        $info =  $this->info;

        if (!$info) {
            throw new Exception('没有设置签名信息');
        } else {
            return $info;
        }
    }
    /**
     * 生成盐值，为了生成复杂度
     * @return void
     */
    private function generateSalt()
    {
        if ($this->salt) return;
        //拼接初始字符串
        $salt = $this->getLetter() . $this->getSymbol();
        // 生成salt值并返回
        $this->salt = str_shuffle($salt) . time();
    }

    /**
     * 从字母集中随机获取
     * @param int $len
     * @return string
     */
    private function getLetter($len = null)
    {
        //打乱顺序
        $letters = str_shuffle($this->saltSet['letters']);
        //截取长度
        $len = !is_null($len) ? $len : $this->letterNumber;

        return substr($letters, rand(0, strlen($letters) - $len - 1), $len);
    }
    /**
     *
     *
     * @param int $len
     * @return string
     */
    private function getSymbol($len = null)
    {
        $symbol = str_shuffle($this->saltSet['symbol']);
        $len = !is_null($len) ? $len : $this->symbolNumber;

        return substr($symbol, rand(0, strlen($symbol) - 1 - $len), $len);
    }
}
