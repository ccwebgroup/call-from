<?php 
include_once("config.php");
$rating = isset($_POST['rating']) ? $_POST['rating'] : -1;
$phone = isset($_POST['phoneNo']) ? $_POST['phoneNo'] : false;

if($rating < 0 || $rating > 10 || !$phone)
{
	echo json_encode(array('result' => false, 'msg' => 'Invalid parameter'));
	return 0;
}

$ip = $_SERVER['REMOTE_ADDR'];
$sql = "SELECT * from phoneRating WHERE phone_id = :id and ip = :ip"; 
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $phone);
$stmt->bindParam(':ip', $ip);
$stmt->execute(); 
$ratingrec=$stmt->fetchAll();


if(!$ratingrec)
{
	$stmt = $db->prepare("INSERT INTO phoneRating(phone_id, ip, rating)
	VALUES(:id, :ip, :rating)");
	$stmt->bindParam(':id', $phone);
	$stmt->bindParam(':ip', $ip);
	$stmt->bindParam(':rating', $rating);
	$result = $stmt->execute();

	if($result)
	{
		echo json_encode(array('result' => true, 'msg' => 'OK!'));
		return 0;
	}
	else
	{
		echo json_encode(array('result' => false, 'msg' => 'An error occured while saving. Please try again!'));
		return 0;
	}

}
else
{
	echo json_encode(array('result' => false, 'msg' => 'Already voted!'));
	return 0;
}

?>