<?php 
	$msg = "";
	$title = "";
	$hasError = false;
	$phone = isset($_POST['phone']) ? $_POST['phone'] : false;
	$reason = isset($_POST['reason']) ? $_POST['reason'] : false;
	$state = false;
	if(!$phone || !$reason)
	{
		$title = "Error!";
		$msg = 'Invalid request!';
		$hasError  = true;
	}
	else
	{
		if(isset($_POST['g-recaptcha-response']))
		{
			$captcha=$_POST['g-recaptcha-response'];
		}
		if(!$captcha)
		{
			$title = "Error!";
			$msg =  '<h2>Please check the the captcha form.</h2>';
			$hasError  = true;
		}
		//verify captcha
		define('SECRET_KEY', '6LeFYQkTAAAAAAbhPVuuKVxzpwRd3iSiJV9hhzYC');
		$responseraw=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		$response = json_decode($responseraw);
		if($response->success==false)
		{
			$title = "Error!";
			$msg = 'You are a robot!';
			$hasError  = true;
		}

		require_once('lib/swiftmailer/lib/swift_required.php');
		$str = "<p>Phone: " . $phone ."</p>";
		$str .= "<p>Reason: " . $reason ."</p><br/>";
		
		// Create the Transport
		$transport = Swift_SmtpTransport::newInstance('mail.call-from.com', 25)
		->setUsername('support@call-from.com')
		->setPassword('J&*bhjdd3')
		;

		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);

		// Create a message
		$message = Swift_Message::newInstance('REMOVAL REQUEST CALL-FROM.COM')
		->setFrom(array('support@call-from.com' => 'Call From'))
		->setTo(array('support@call-from.com'))
		->setBody($str)
		;
		$message->setContentType("text/html");
		// Send the message
		if (!$mailer->send($message, $errors))
		{
			$hasError  = true;
			$title = "Error!";
			$msg = "An Error occured while sending your request! Please try again";
		}
		//$result = $mailer->send($message);
	}

?>

<!doctype html>
<title>Removal Request</title>
<style>
  body { text-align: center; padding: 150px; }
  h1 { font-size: 50px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
</style>
 
<?php if($hasError):?>
	<article>
		<h1><?php echo $title?></h1>
		<div>
			<p><?php echo $msg; ?></p>
			<a href="/" class="btn btn-default" style="float: right;">Go back</a>
		</div>
	</article>
<?php else:?>
	<article>
		<h1>Thank you</h1>
		<div>
			<p>Your response was received. Your request may take 5-7 days for processing.</p>
			<p>&mdash; Call-From.com</p>
			<a href="/" class="btn btn-default" style="float: right;">Go back</a>
		</div>
	</article>
<?php endif;?>
 