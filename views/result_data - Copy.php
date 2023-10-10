<div class="home_left">

<?php
if (isset($result->error) OR isset($error)) {
include("error.php");
}
else
{
	if (isset($result->phone)) {
	 if($result->phone=='database')
	 {
	   
	   foreach($result->location as $key => $val) {
			 $loc.=  '['.$val['latitude'].",". $val['longitude'] .","."'".$val['fulladdress']."'".']'.',';
			 $cityLoc.=!empty($val['standard_address_location'])?$val['standard_address_location'].',':'';
		}
	 }
	 if (!empty($result->phone)) {
		//echo '<pre>';print_r($result->location);
		$stmt = $dbh->prepare("INSERT INTO cf_user (userName,userPhone,userAddress,userStandardAddressLocation,userCountryCallingCode,carrier) VALUES ('".$result->name."', '".$result->phone['phone_number']."','".$loc."','".$cityLoc."','".$result->phone['country_calling_code'] ."','".$result->phone['carrier']."')");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':value', $value);
		
		// insert one row
		$name = 'one';
		$value = 1;
		$stmt->execute();
		foreach($result->location as $key => $val) {
			 $loc.=  '['.$val['latitude'].",". $val['longitude'] .","."'".$val['fulladdress']."'".']'.',';
			 $cityLoc.=!empty($val['standard_address_location'])?$val['standard_address_location'].',':'';
		}
		//print_r($cityLoc);
		 ?>
			
				
				<h2><?php echo $result->phone['country_calling_code'] ?>-<?php echo substr($result->phone['phone_number'], 0, 3) . "-" . substr($result->phone['phone_number'], 3, 3) . "-" . substr($result->phone['phone_number'], 6); ?></h2>
				<p>Choice one Cummunications Landline</p>
				<div class="gray_area">
					<h2>+<?php echo $result->phone['country_calling_code'] ?>-<?php echo substr($result->phone['phone_number'], 0, 3) . "-" . substr($result->phone['phone_number'], 3, 3) . "-" . substr($result->phone['phone_number'], 6); ?><?php echo !empty($val['name'])? $val['name']:''?> is available</h2>
					<a href="#">View owner's for free</a><br>
					<a href="#">Try WHitepages, No credit card required.</a>
				</div>
				<div id="map">
		   
		 <script type="text/javascript">       
		 var markers=[<?php echo $loc?>];
		// alert(markers)
		function load() {
			var map = new google.maps.Map(document.getElementById("map"), {
			center: new google.maps.LatLng(markers[0][0], markers[0][1]),
			
			zoom: 6,
			mapTypeId: ROADMAP,
		    styles: [{"featureType":"water","elementType":"all","stylers":[{"hue":"#252525"},{"saturation":-100},{"lightness":-81},{"visibility":"on"}]},{"featureType":"portrait","elementType":"all","stylers":[{"hue":"#666666"},{"saturation":-100},{"lightness":-55},{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"hue":"#555555"},{"saturation":-100},{"lightness":-57},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#777777"},{"saturation":-100},{"lightness":-6},{"visibility":"on"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#cc9900"},{"saturation":100},{"lightness":-22},{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#444444"},{"saturation":0},{"lightness":-64},{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"hue":"#555555"},{"saturation":-100},{"lightness":-57},{"visibility":"off"}]}]
		
		  });
		  var infoWindow = new google.maps.InfoWindow;
		  var marker, i;
		  for (var i = 0; i < markers.length; i++) 
		  {
			  if(markers[i][2]!='')
			  {
			  //alert(markers[i][2])
			  var point = new google.maps.LatLng(markers[i][0],markers[i][1]);
			  var html = "<b>" +markers[i][2];
		
			  var marker = new google.maps.Marker({
				map: map,
				position: point          
			  });
			  bindInfoWindow(marker, map, infoWindow, html);
			}
			}
		  
		}
	
		function bindInfoWindow(marker, map, infoWindow, html) {
		  google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		  });
		}
	
		function downloadUrl(url, callback) {
		  var request = window.ActiveXObject ?
			  new ActiveXObject('Microsoft.XMLHTTP') :
			  new XMLHttpRequest;
	
		  request.onreadystatechange = function() {
			if (request.readyState == 4) {
			  request.onreadystatechange = doNothing;
			  callback(request, request.status);
			}
		  };
	
		  request.open('GET', url, true);
		  request.send(null);
		}
	
		function doNothing() {}
	
		//]]>
	
	  </script>
				</div>
				<div class="home_left_dscp">
					<p><span><strong>Area Code:</strong> <?php echo $result->phone['country_calling_code'] ?> &nbsp; &nbsp; &nbsp;</span> <span><strong>Carrier:</strong> <?php echo isset($result->phone['carrier'])?$result->phone['carrier']:''; ?> &nbsp; &nbsp; &nbsp;</span></p>
					<p><span><strong>Full Number:</strong> +<?php echo $result->phone['country_calling_code'] ?>-<?php echo substr($result->phone['phone_number'], 0, 3) . "-" . substr($result->phone['phone_number'], 3, 3) . "-" . substr($result->phone['phone_number'], 6); ?>  &nbsp; &nbsp; &nbsp;</span> <span><strong>City/State:</strong> <?php echo $cityLoc?> &nbsp; &nbsp; &nbsp;</span></p>
				</div>
		   
		   
		<?php
		} else {
			include 'message.php';
		}
	}
}
?>
 </div>
