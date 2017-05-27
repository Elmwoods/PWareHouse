<?php
namespace wstmart\wechat\behavior;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 初始化微信消息模板
 */
class InitWechatMessges
{
    public function run(&$params){
        $tpl = WSTMsgTemplates($params['CODE']);
        if(!$tpl)return;
        $userId = $params['userId'];
        $user = model('common/users')->where('userId',$userId)->field('wxOpenId')->find();
        if($user['wxOpenId']=='')return;
        //数据封装
        $data = [];
        $data['touser'] = $user['wxOpenId'];
        $data['template_id'] = $tpl['tplExternaId'];
        if(isset($params['URL']) && $params['URL'] !='')$data['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".WSTConf("CONF.wxAppId")."&redirect_uri=".rawurlencode( $params['URL'] )."&response_type=code&scope=snsapi_userinfo&state=".WSTConf("CONF.wxAppCode")."&connect_redirect=1#wechat_redirect";
        $data['data'] = [];
        if(!empty($tpl['params'])){
            foreach($tpl['params'] as $key =>$v){
                foreach($params['params'] as $pkey =>$pv){
                   $v['fieldVal'] = str_replace('${'.$pkey.'}',$pv,$v['fieldVal']);
                }
                $tpl['params'][$key] = $v;
            }
        }
        foreach($tpl['params'] as $key =>$v){
            $data['data'][$v['fieldCode']] = array('value'=>urlencode($v['fieldVal']));
        }

        $we = WSTWechat();
        $rs = $we->sendTemplateMessage(urldecode(json_encode($data)));
    }
}