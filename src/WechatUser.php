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

use yii\base\Component;

/**
 * Class WechatUser
 * @package oueng\wechat
 */
class WechatUser extends Component
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $avatar;
    /**
     * @var array
     */
    public $original;
    /**
     * @var \Overtrue\Socialite\AccessToken
     */
    public $token;
    /**
     * @return string
     */
    public function getOpenId()
    {
        return isset($this->original['openid']) ? $this->original['openid'] : '';
    }
}