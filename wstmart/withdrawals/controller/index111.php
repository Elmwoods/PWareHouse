<?php
/**
 * 2017-04-11
 * by 我是个导演
 */
require_once 'AopSdk.php';
$aop = new AopClient ();
$aop->appId = '2017011705151664';
$aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEAzknOfQFizD/6B1RkNcR3xQr5o8HtP74Lg5CaJqvXjEh2QRILvEhSHWhMxyJCLW38mmhmdQQ6l7TIRE1tkgU3pmcYgYmi593RuJ4V49NLRT4KTQNcU2q+bp/MvnbqYrOWoxVoIzYSnLHnVK0ymwHUmhhcg1PBdI5xzyk21pUbchTdFQ/VmqbnOghgOmLfe2PbJogfLfvu8/d8f1os70gRBYSGJzg5jIUdABCdRc5x+CikNbkDBxRJEI1xWdozLEceckyyGwSzN5otvJBm+HGTEd5Cf3SmF7zzvEi/H/2tN9tX7QE/KGjZlUxTNYZuGYJIJYa8iyJUh8tPfeE1MXaoVQIDAQABAoIBAQCMGJvfUX2jcR+AstOLoG4mp5l6mU1iqNJw+1d1Q+cTInMNJhBKQmNiDV93LdD7wWJ4CsbqWYDhXqlTmbH8JQbyP7no32x/Q6oWU2ZSX0ETOVsNima9UBUcU/Jct63eclCvWO5sW2CwgjG01Bs2IjwcmsbZeZw8aDDqm/beLE2DX2fOPPZrSqy4zRhaFQxUbMdYHX9/TQ//K201rS0m1GvcbFO/CngzvKc+xVleeQc+8CBpAMhsENB6SUMVYlIJaq3nvG0aXnwSH7Kmpj0bqIw+K997hfQMsKANSZOaBaHL5Dv2MalI4/qMs2VJUkgRr6yks5qoMIfaIpgQdZjvBmeBAoGBAOg/1CcweFF9ThXxzXLbbf/1mCJ3lHPR31XEiipn0NbG8R+dXIqEt66R0jgrzsIf6S6fOGpPDIOQGURCfTwR8IGSrnnDqR/f3JWYolfRymFXVunvGNz7TF60xvyU+2PC6jXHDr2dM7uJg6XTml2ZE0T2pMnTsfGEHExKnZoi9zm5AoGBAONiVM0gtslv62dYonSZjtrRYlWtJvM6maf0SyaO6TZUEDaMoRbHoXh88Rz/kqV3/WL93cpFcqjLSTDlLTSxxFZpcK8oJkbuwrKJ+/rZCmbEca/Pb40X50tlRkIqawQsdTfOlHqggTpjCeQW2jnYe7jScEv2WFgX/B6hUgvPk8F9AoGAKe4cJ1cg4dV1m5CkPvBO079LUC22p5Jkd9+b8jv8AEq6jbKjWn7LisDY6zs9gN6yArDMRqUu5THG3gQDCC1U9o+84E7q4c5QzNFZvfEqUJisIGACZSMZjp+krUVYfZJbJophpuoSxPD2y6GAZRWV3QWKisWlgq0PTtbJzWIysEECgYEAk1k0RN1PNggdzxHD7LVZunj3NTgIxpOR4SHQ1ULE49zjyMWm2iExhOfKQ5VmjW3NOKn0YNBSNgnN+y539e7AoZKgYBEvhMXSS2pZbLvbHq9sUJam3hLAYr5VIilkwgahSzHGTBTYyWJGlZUtg1DDFAjilocjxqp8SckWZurz/+0CgYEAxDlomIksx8Cg6H/aEXgfoPLgk7ISbXXd+HWaB/08m6Zb+AxOYDKl0sNCO0ObTfsJhr7rJetrXvki/Jm6BgCzV4vnJiEGsqUqvbA5b+pcfKVGkEqVRfKWtqNgV724ShZwrs3PfFl+G7AeArjbCNShVq4uP+rbu1uGtYqRbS3wG3U=';
$aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset='utf-8';
$aop->format='json';
//var_dump($_POST);die;
$request = new AlipayFundTransToaccountTransferRequest ();
$request->setBizContent("{" .
	"\"out_biz_no\":\"".$_POST['out_biz_no']."\"," .
	"\"payee_type\":\"ALIPAY_LOGONID\"," .
	"\"payee_account\":\"".$_POST['alipyname']."\"," .
	"\"amount\":\"".$_POST['amount']."\"," .
	"\"remark\":\"".$_POST['remark']."\"" .
	"}");
$result = $aop->execute ($request);
$fm = "<form name='aaa' action='/purse/predeposit/withdrawals_ok' method='post'>";
$fm .= "<input type='hidden' name='msg' value=".$result->alipay_fund_trans_toaccount_transfer_response->msg.">";
$fm .= "<input type='hidden' name='sub_msg' value=".@$result->alipay_fund_trans_toaccount_transfer_response->sub_msg.">";
$fm .= "<input type='hidden' name='amount' value=".$_POST['amount'].">";
$fm .= "<input type='hidden' name='sn' value=".$_POST['out_biz_no'].">";
$fm .= "<input type='hidden' name='type' value=".@$_POST['type'].">";
$fm .= "<input type='hidden' name='name' value=".$_POST['alipyname'].">";
$fm .= "<script>";
$fm .= "function sub() {";
$fm .= "console.log(document.aaa.submit());";
$fm .= "}";
$fm .= "setTimeout(sub,1);";
$fm .= "</script>";
echo $fm;
//var_dump($result);



	
