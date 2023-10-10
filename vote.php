<?php
include('config.php');
if(isset($_POST['refNo']) && isset($_POST['state']))
{
	$comment_id = $_POST['refNo'];
	$state = $_POST['state']; // 0 means down 1 means up
	$ip = $_SERVER['REMOTE_ADDR'];
	
	
	//check if the IP already voted...
	$sql = "SELECT * FROM comment_vote WHERE ip=:ip AND comment_id = :comment_id"; 
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ip', $ip);
	$stmt->bindParam(':comment_id', $comment_id);
	$stmt->execute(); 
	$noOfComments=$stmt->rowCount();

	if($noOfComments > 0)
	{
		echo json_encode(array('result' => false, 'msg' => 'You are already voted in this comment!'));
		return true;
	}
	else //insert new vote
	{
		if($state == 0)
		{
			$statement = $db->prepare("INSERT INTO comment_vote(comment_id, is_dislike, ip)
			VALUES(:comment_id, :is_dislike, :ip)");
			$result = $statement->execute(array(':comment_id' => $comment_id, ':is_dislike' => 1, ':ip' => $ip));
		}
		else if($state = 1)
		{
			$statement = $db->prepare("INSERT INTO comment_vote(comment_id, is_like, ip)
			VALUES(:comment_id, :is_like, :ip)");
			$result = $statement->execute(array(':comment_id' => $comment_id, ':is_like' => 1, ':ip' => $ip));
		}
		else
		{
			echo json_encode(array('result' => false, 'msg' => 'Unknown state!'));
			return true;
		}

		if($result) //saved into db. let's count the result
		{
			$sql = "SELECT count(is_like) - count(is_dislike) like_diff FROM comment_vote WHERE comment_id=:comment_id"; 
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comment_id', $comment_id);
			$stmt->execute(); 
			$votes=$stmt->fetch();
			//var_dump($noOfComments);
			echo json_encode(array('result' => true, 'msg' => 'Success', 'like_diff' => $votes['like_diff']));
			return true;
		}

		else
		{
			echo json_encode(array('result' => false, 'msg' => 'DB error'));
			return true;
		}
		
	}

	/*if(isset($data['parentcomment_id'])) //means subcomment
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
	
	if($result)
	{
		header("Location: http://call-from.com/phonenumberclicked/".$phone."&status=0");
	}
	else
	{
		header("Location: http://call-from.com/phonenumberclicked/".$phone."/err&status=1");
	}*/
}
else
{
	header("Location: /");
}
?>
