<?php if (isset($result->phone)) {?>

<div class="home_left">
<?php
if (isset($result->error) OR isset($error)) {
include("error.php");
}
else
{
	if (isset($result->phone)) 
	{
		 if($result->phone=='database')
		 {
			//print_r($stmt->fetchAll());
			$result->phone['line_type']='yes';
			foreach ($stmt->fetchAll() as $row) {
				//print_r($row);
				$fulladdress=$row['userAddress'];
				$latitude=$row['userLatitude'];
				$longitude=$row['userLongitude'];
				$standard_address_location=$row['userStandardAddressLocation'];
				$country_calling_code=$row['userCountryCallingCode'];
				$phone_number=$row['userPhnNo'];
				$name=$row['userName'];
				$carrier=$row['carrier'];
			}
		 }
		 else
		 {
			//echo '<pre>';print_r($result->location);
			//echo $result->phone['line_type'];die;
			 if($result->phone['line_type'])
			 {
				$fulladdress=$result->location[0]['fulladdress'];
				$latitude=$result->location[0]['latitude'];
				$longitude=$result->location[0]['longitude'];
				$standard_address_location=$result->location[0]['standard_address_location'];
				$country_calling_code=$result->phone['country_calling_code'];
				$phone_number=$result->phone['phone_number'];
				$name=$result->people[0]['name'];
				$carrier=$result->phone['carrier'];
				$stmt = $db->prepare("INSERT INTO cf_user (userName,userPhnNo,userAddress,userStandardAddressLocation,userCountryCallingCode,userCarrier,userlatitude,userLongitude) VALUES ('".$name."', '".$phone_number."','".$fulladdress."','".$standard_address_location."','".$country_calling_code ."','".$carrier."','".$latitude."','".$longitude."')");
				$stmt->execute();
			}
			else
			{
				$country_calling_code=0;
				$phone_number=$result->phone['phone_number'];
				$not='not';
			}
		} ?>
		<h2><?php echo $country_calling_code ?>-<?php echo substr($phone_number, 0, 3) . "-" . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?></h2>
				<p>Choice one Cummunications Landline</p>
				<div class="gray_area">
					<h2>+<?php echo $country_calling_code ?>-<?php echo substr($phone_number, 0, 3) . "-" . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?><?php echo !empty($name)?' '.$name:''?> is <?php echo $not; ?> available</h2>
					<a href="#">View owner's for free</a><br>
					<a href="#">Try WHitepages, No credit card required.</a>
				</div>
				<div class="map" >
		         <iframe width="488" height="250" frameborder="0" style="border:0" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="
http://maps.google.com/maps
?f=q
&amp;source=s_q
&amp;hl=en
&amp;geocode=
&amp;q=<?php echo $fulladdress; ?>
&amp;aq=0
&amp;ie=UTF8
&amp;hq=
&amp;hnear=<?php echo $fulladdress; ?>
&amp;t=m
&amp;ll=<?php echo $longitude; ?>,<?php echo $latitude; ?>
&amp;z=12
&amp;iwloc=
&amp;output=embed"></iframe>
    
		
				</div>
				<?php
				if($result->phone['line_type']){?>
				<div class="home_left_dscp">
					<p><span><strong>Area Code:</strong> <?php echo $country_calling_code ?> &nbsp; &nbsp; &nbsp;</span> <span><strong>Carrier:</strong> <?php echo isset($carrier)?$carrier:''; ?> &nbsp; &nbsp; &nbsp;</span></p>
					<p><span><strong>Full Number:</strong> +<?php echo $country_calling_code ?>-<?php echo substr($phone_number, 0, 3) . "-" . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?>  &nbsp; &nbsp; &nbsp;</span> <span><strong>City/State:</strong> <?php echo $standard_address_location?> &nbsp; &nbsp; &nbsp;</span></p>
				</div>
				<? }?>
<?	}	
	
}
?>
 </div>
 
 <? }?>
