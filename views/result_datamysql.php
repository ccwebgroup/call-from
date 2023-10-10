<div class="home_left">
<?php 
function getOS() { 
    global $user_agent;
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   
    return $os_platform;
}
function getBrowser() {
    $user_agent= $_SERVER['HTTP_USER_AGENT'];
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}
function insertSearch($phoneId)
{
		global $db;
		$userOs        =   getOS();
		$userBrowser   =   getBrowser();
		$stmt="insert into cf_search_details set searchPhoneId='".$phoneId."', searchIp='".$_SERVER['REMOTE_ADDR']."',searchReferrer='".$_SERVER['GATEWAY_INTERFACE']."',searchOs='".$userOs."',searchBrowser='".$userBrowser."',searchDateTime=now()";
mysqli_query($link,$stmt);
}
if (isset($result->phone)) {?>


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
			$result->phone['line_type']='yes';
			
                          while($row = mysqli_fetch_array($result))
			 {
				//print_r($row);
				$fulladdress=$row['userAddress'];
				$latitude=$row['userLatitude'];
				$longitude=$row['userLongitude'];
				$standard_address_location=$row['userStandardAddressLocation'];
				$country_calling_code=$row['userCountryCallingCode'];
				$phone_number=$row['userPhnNo'];
				$name=$row['userName'];
				$carrier=$row['carrier'];
				insertSearch($row['userId']);
			  }
		 }
		 else
		 {
			
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
				$stmt = "INSERT INTO cf_user (userName,userPhnNo,userAddress,userStandardAddressLocation,userCountryCallingCode,userCarrier,userlatitude,userLongitude) VALUES ('".$name."', '".$phone_number."','".$fulladdress."','".$standard_address_location."','".$country_calling_code ."','".$carrier."','".$latitude."','".$longitude."')";
                                $insertQry= mysqli_query($link,$stmt);
                                $phoneId=mysqli_insert_id();
				insertSearch($phoneId);
			}
			else
			{
				$country_calling_code=0;
				$phone_number=$result->phone['phone_number'];
				$not='not';
			}
		} ?>
		<h2>Phone number <?php echo $not ?> found </h2>
				<p>Carrier owner of phone number.Choice one Cummunications Landline</p>
				<div class="gray_area">
					<h2><span style="color:#41C0F9;">+<?php echo $country_calling_code ?>-<?php echo substr($phone_number, 0, 3) . "-" . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?><?php echo !empty($name)?' '.$name:''?></span> is <?php echo $not; ?> available</h2>
					<?php
						if($result->phone['line_type']){?>
						<div class="home_left_dscp">
							<p><span><strong>Area Code:</strong> <?php echo $country_calling_code ?> &nbsp; &nbsp; &nbsp;</span> <span><strong>Carrier:</strong> <?php echo isset($carrier)?$carrier:''; ?> &nbsp; &nbsp; &nbsp;</span></p>
							<p><span><strong>Full Number:</strong> +<?php echo $country_calling_code ?>-<?php echo substr($phone_number, 0, 3) . "-" . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?>  &nbsp; &nbsp; &nbsp;</span> </p>
						    <p><span><strong>City/State:</strong> <?php echo $standard_address_location?> &nbsp; &nbsp; &nbsp;</span></p>
						</div>
					<? }?>
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
				
<?	}	
	
}
}
else
{
?>
<h2>Search a number for information</h2>
            <p>Carrier owner of phone number</p>
			 <div class="gray_area">
					<h2>Telepone Number <span style="color:#41C0F9;">(###) ##-####</span></h2>
					
						<div class="home_left_dscp">
							<p><span><strong>Area Code:</strong>&nbsp; &nbsp; &nbsp;</span> <span><strong>Carrier:</strong> &nbsp; &nbsp; &nbsp;</span></p>
							<p><span><strong>Full Number:</strong>  &nbsp; &nbsp; &nbsp;</span> </p>
						    <p><span><strong>City/State:</strong>  &nbsp; &nbsp; &nbsp;</span></p>
						</div>					
				</div>
            <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d94711.97653743453!2d-72.54634144999999!3d42.11284045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x89e6e62c08e4b81f%3A0x1d1808da41b9aabd!2sSpringfield%2C+MA%2C+USA!3m2!1d42.101483099999996!2d-72.589811!5e0!3m2!1sen!2sin!4v1432107888321" width="488" height="250" frameborder="0" style="border:0"></iframe>
            </div>
           
<?php
}
?>
 </div>