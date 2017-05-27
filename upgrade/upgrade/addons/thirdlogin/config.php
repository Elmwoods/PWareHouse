<?php

return array(
	
	'thirdTypes'=>array(
		'title'=>"第三方登录方式",
		'type'=>'checkbox',
		'options'=>array(
			'qq'=>'QQ登录',	
			'weixin'=>'微信登录',
			'weibo'=>'微博登录',
		),
		'value'=>'',
	),
	'group'=> array(
		'type'=>'group',
		'options'=>array(
			'qq'=>array(
				'title'=>'QQ登录',
				'options'=>array(
					'appId_qq'=>array(
						'title'=>'QQ AppID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'appKey_qq'=>array(
						'title'=>'QQ AppKey:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'img_qq'=>array(
						'type'=>'hidden',
						'value'=>'/addons/thirdlogin/view/images/qq.png',
						'tip'=>''
					)
				)
			),
			'weixin'=>array(
				'title'=>'微信登录',
				'options'=>array(
					'appId_weixin'=>array(
						'title'=>'微信 AppID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'appKey_weixin'=>array(
						'title'=>'微信 AppKey:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'img_weixin'=>array(
						'type'=>'hidden',
						'value'=>'/addons/thirdlogin/view/images/weixin.png',
						'tip'=>''
					)
				)
			),
			'weibo'=>array(
				'title'=>'微博登录',
				'options'=>array(
					'appId_weibo'=>array(
						'title'=>'微博 AppID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'appKey_weibo'=>array(
						'title'=>'微博 AppKey:',
						'type'=>'text',
						'value'=>'',
						'tip'=>''
					),
					'img_weibo'=>array(
						'type'=>'hidden',
						'value'=>'/addons/thirdlogin/view/images/weibo.png',
						'tip'=>''
					)
				)
			)
		)
	)
);
