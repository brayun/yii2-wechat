<?php
/*                            _ooOoo_
 *                           o8888888o
 *                           88" . "88
 *                           (| -_- |)
 *                            O\ = /O
 *                        ____/`---'\____
 *                      .   ' \\| |// `.
 *                       / \\||| : |||// \
 *                     / _||||| -:- |||||- \
 *                       | | \\\ - /// | |
 *                     | \_| ''\---/'' | |
 *                      \ .-\__ `-` ___/-. /
 *                   ___`. .' /--.--\ `. . __
 *                ."" '< `.___\_<|>_/___.' >'"".
 *               | | : `- \`.;`\ _ /`;.`/ - ` : | |
 *                 \ \ `-. \_ __\ /__ _/ .-` / /
 *         ======`-.____`-.___\_____/___.-`____.-'======
 *                            `=---='
 *
 *         .............................................
 *                  佛祖保佑             永无BUG
 *
 * ======================================================
 * @author: Ethan Lu <ethan.lu@qq.com>
 *
 */

namespace oueng\wechat;

use Yii;
use yii\base\Component;
use yii\web\Response;
use EasyWeChat\Foundation\Application;
use EasyWeChat\OpenPlatform\OpenPlatform;
use Overtrue\Socialite\User;

/**
 * Class OpenWx
 * @property \EasyWeChat\Foundation\Application $app
 * @property \EasyWeChat\OpenPlatform\OpenPlatform $openPlatform
 * @package oueng\wechat
 */
class Wechat extends Component
{

    public $wechatOptions = [];

    public $sessionParam = '_wechatUser';

    public $returnUrlParam = '_wechatReturnUrl';

    private static $_app;

    private static $_user;

    private static $_openPlatform;

    /**
     * 用户授权请求
     * @return Response
     */
    public function authorizeRequired()
    {
        if(Yii::$app->request->get('code')) {
            // callback and authorize
            return $this->authorize($this->app->oauth->user());
        }else{
            $this->setReturnUrl(Yii::$app->request->getUrl());
            return Yii::$app->response->redirect($this->app->oauth->redirect()->getTargetUrl());
        }
    }

    /**
     * 用户授权跳转
     * @param \Overtrue\Socialite\User $user
     * @return Response
     */
    public function authorize(User $user)
    {
        unset($user['provider']);
        Yii::$app->session->set($this->sessionParam, $user->toJSON());
        return Yii::$app->response->redirect($this->getReturnUrl());
    }

    /**
     * 检测当前用户是否授权
     * @return bool
     */
    public function isAuthorized()
    {
        $hasSession = Yii::$app->session->has($this->sessionParam);
        $sessionVal = Yii::$app->session->get($this->sessionParam);
        return ($hasSession && !empty($sessionVal));
    }

    /**
     * 获取跳转链接
     * @param null $defaultUrl
     * @return mixed|null|string
     */
    public function getReturnUrl($defaultUrl = null)
    {
        $url = Yii::$app->getSession()->get($this->returnUrlParam, $defaultUrl);
        if (is_array($url)) {
            if (isset($url[0])) {
                return Yii::$app->getUrlManager()->createUrl($url);
            } else {
                $url = null;
            }
        }
        return $url === null ? Yii::$app->getHomeUrl() : $url;
    }

    /**
     * 设置跳转链接
     * @param $url
     */
    public function setReturnUrl($url)
    {
        Yii::$app->session->set($this->returnUrlParam, $url);
    }

    /**
     * 获取用户信息
     * @return WechatUser
     */
    public function getUser()
    {
        if(!$this->isAuthorized()) {
            return new WechatUser();
        }
        if(! self::$_user instanceof WechatUser) {
            $userInfo = Yii::$app->session->get($this->sessionParam);
            $config = $userInfo ? json_decode($userInfo, true) : [];
            self::$_user = new WechatUser($config);
        }
        return self::$_user;
    }

    /**
     * 判断是否为微信浏览器
     * @return bool
     */
    public function getIsWechat()
    {
        return strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
    }

    /**
     * 获取OpenPlatform对象
     * @return \EasyWeChat\OpenPlatform\OpenPlatform
     */
    public function getOpenPlatform()
    {
        if (! self::$_openPlatform instanceof OpenPlatform) {
            self::$_openPlatform = $openPlatform = $this->app->open_platform;
        }
        return self::$_openPlatform;
    }

    /**
     * 获取Application
     * @return Application
     */
    public function getApp()
    {
        if (! self::$_app instanceof Application) {
            self::$_app = new Application( $this->wechatOptions ?  : Yii::$app->params['WECHAT']);
        }
        return self::$_app;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        }catch (\Exception $e) {
            if($this->getApp()->$name) {
                return $this->app->$name;
            }else{
                throw $e->getPrevious();
            }
        }
    }

}