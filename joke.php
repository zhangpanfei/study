<?php
	/*ab8d7f42efc8e94460662295860b969c
	curl  --get --include  'http://apis.baidu.com/showapi_open_bus/showapi_joke/joke_text?page=1'  -H 'apikey:ab8d7f42efc8e94460662295860b969c'*/
	class joke{
		public $apiKey='apikey:ab8d7f42efc8e94460662295860b969c';
		public $url='http://apis.baidu.com/showapi_open_bus/showapi_joke/joke_text';
		public $error='';
		public function __construct($apiKey='',$url=''){
			$this->apiKey=empty($apiKey)?$this->apiKey:$apikey;
			$this->url=empty($url)?$this->url:$url;
		}
		public function getJoke($page=1){
			$data=['page'=>$page];
			$res=$this->C('get',$data);
			if($res){
				$data=json_decode($res,true);
				$data=$data['showapi_res_body']['contentlist'];
				return $data;
			}else{
				return $this->error;
			}
		}
		private function C($method='get',$data=array()){
			$ch=curl_init();
			if($method=='get'){
				$data=http_build_query($data);
				$config=array(
					CURLOPT_URL=>$this->url.'?'.$data,
					CURLOPT_HTTPHEADER=>array($this->apiKey),
				);
			}elseif($method=='post'){
				$config=array(
					CURLOPT_URL=>$this->url,
					CURLOPT_HTTPHEADER=>array($this->apiKey),
					CURLOPT_POST=>1,
					CURLOPT_POSTFIELDS=>$data,
				);
			}
			$config[CURLOPT_AUTOREFERER]=$this->url;
			$config[CURLOPT_SSL_VERIFYPEER]=false;
			$config[CURLOPT_SSL_VERIFYHOST]=false;
			curl_setopt_array($ch,$config);
			ob_start();
			if(curl_exec($ch)===false){
				$this->error=curl_error($ch);
				return false;
			}else{
				return ob_get_clean();
			}
		}

	}
	include_once './Mail/class.phpmailer.php';
	class Mail{
		static function send($FromName,$Subject,$MsgHTML,$AddAddress,$AddAttachment=''){
			$mail=new PHPMailer();
			/*服务器相关信息*/
			$mail->IsSMTP();                        //设置使用SMTP服务器发送
			$mail->SMTPAuth   = true;               //开启SMTP认证
			$mail->Host       = 'smtp.qq.com';   	    //设置 SMTP 服务器,自己注册邮箱服务器地址
			$mail->Username   = '2712504486@qq.com';  		//发信人的邮箱名称
			$mail->Password   = 'ytq123';          //发信人的邮箱密码
			/*内容信息*/
			$mail->IsHTML(true); 			         //指定邮件格式为：html
			$mail->CharSet    ="UTF-8";			     //编码
			$mail->From       = '2712504486@qq.com';	 		 //发件人完整的邮箱名称
			$mail->FromName   = $FromName;			 //发信人署名
			$mail->Subject    = $Subject;  			 //信的标题
			$mail->MsgHTML($MsgHTML);  				 //发信内容
			$mail->AddAttachment($AddAttachment);	     //附件
			/*发送邮件*/
			$mail->AddAddress($AddAddress);  			 //收件人地址
			//使用send函数进行发送
			if($mail->Send()) {
				return true;
			} else {
				global $error;
				$error=$mail->ErrorInfo;
				return false;
			}

		}
	}
	$page=2;
go:	$error='';
	$joke=new joke();
	$data=$joke->getJoke($page);
	$FromName='呵呵';
	$Subject='笑话数据';
	ob_start();
	require './joke.html';
	$MsgHTML=ob_get_clean();
	$AddAddress='1009928990@qq.com';
	$res=Mail::send($FromName,$Subject,$MsgHTML,$AddAddress);
	if(!$res){
		echo $error;
	}
	$page++;
	sleep(60);
	goto go;
?>