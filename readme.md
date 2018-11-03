##这是个生成和验证Token的类
###生成token
方法一
```
$demo = new Token($info[,$salt]);//$info{array|string}
$token = $demo->getToken();
$salt = $demo->getSalt();
$sign = $demo->getSign();//返回token 和 salt的关联数组;
```
方法二
```$xslt
$demo = new Token;
$demo->setInfo($info);
$demo->setSalt($salt);
$token = $demo->getToken();
$salt = $demo->getSalt();
$sign = $demo->getSign();//返回token 和 salt的关联数组;
```
###验证token
```
$demo = new Token;
$demo->validate($info, $token, $salt); //true : 正确， false : 错误
```