<?php
include('config.php');
if(isset($_POST['commentForm']))
{
	$result = false;
	$data = $_POST['commentForm'];
	$phone = $data['phone'];
	//var_dump($data );
	unset($data['phone']);
	$cptcha = false;
	if(!isset($data['parentcomment_id']))
	{
		if(isset($_POST['g-recaptcha-response']))
		{
			$captcha=$_POST['g-recaptcha-response'];
		}
		if(!$captcha)
		{
			echo '<h2>Please check the the captcha form.</h2>';
			exit;
		}
		//verify captcha
		define('SECRET_KEY', '6LeFYQkTAAAAAAbhPVuuKVxzpwRd3iSiJV9hhzYC');
		$responseraw=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		$response = json_decode($responseraw);
		if($response->success==false)
		{
			echo 'You are a robot!';
			exit();
		}
	}
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$sql = "SELECT * FROM userIpCommentMonitor WHERE ip=:ip";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ip', $ip);
	$stmt->execute(); 
	$commentCount = $stmt->fetchAll();
	$totRows=$stmt->rowCount();

	//check for comment count in IP...
	if($totRows >= 3)
	{
		header("Location: http://call-from.com/".$phone."&status=2");
		exit();
	}
	else
	{
		$statement = $db->prepare("INSERT INTO userIpCommentMonitor (ip)
		VALUES(:ip)");
		$result = $statement->execute(array('ip' => $ip));

		if(isset($data['parentcomment_id'])) //means subcomment
		{
			$statement = $db->prepare("INSERT INTO usercomments(name, comment_body, phone_id, parentcomment_id)
			VALUES(:name, :comment_body, :phone_id, :parentcomment_id)");
			$result = $statement->execute($data);
		}
		else // main comment 
		{
			$statement = $db->prepare("INSERT INTO usercomments(name, comment_body, caller_company, caller_type, phone_id)
			VALUES(:name, :comment_body, :caller_company, :caller_type, :phone_id)");
			$result = $statement->execute($data);
		}

	}

	
	
	if($result)
	{
		header("Location: http://call-from.com/".$phone."&status=0");
	}
	else
	{
		header("Location: http://call-from.com/".$phone."/err&status=1");
	}
}
else
{
	header("Location: /");
}
?>
