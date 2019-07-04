<?php

return [
	'app_id' => 'wx99da6db81a980880',
	'app_secret' => '90286c6352026e169667fbdb7f7aefab',
	'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',

	// 微信获取access_token的url地址
	'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s'
];