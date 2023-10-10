<?php
error_reporting(E_ERROR);
include_once("config.php");

if(isset($_POST['submit']))
{
        $userPhone=$_POST['phone'];
	    $sql = "SELECT * FROM cf_user WHERE userPhnNo=:userPhone";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':userPhone', $userPhone);
		$stmt->execute(); 
        $noRows=$stmt->rowCount();
		if ($noRows >0)   
        {  
			$result->phone='database';			
		}
		else
		{
			include 'lib/phone_lookup.php';	
		} 		
}
else 
{
        $error='0';		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Call from</title>
<link href="css/carfrom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
	
</head>

<body>
<header class="header">
	<div class="container">
    	<div class="logo">
    		<h3>Call-
From.com</h3>
            </div>
            <div id="search">
            <form class="navbar-form navbar-right" action="index.php" method="post" >
			  
      <div class="form-group"> 
        <input type="text" class="form-control" placeholder="Enter Phone Number" name="phone" value="<?php echo isset($userPhone)?$userPhone:''?>" autoComplete="OFF">
        <button class="btn srch_Btn" type="submit" name="submit">Search</button>
      </div>
          </form>
            </div>
    </div>
</header>
<section class="home_cont">
<div class="ad_space">
<img src="http://xcstechnologies.com/carfrom/images/ad.png" />
</div>
	<div class="container">
    	<?php include 'views/result_data.php';?>
        <div class="home_right">
		<?php if($result->phone['line_type']){?>
		<script type="text/javascript">		 
		  jQuery(document).ready(function() {
		  var html=$("#popup_box").html();
			$.fancybox(
				html,
				{
					'autoDimensions'    : false,
					'width'                 : 150,
					'height'                : 'auto',
					'transitionIn'      : 'none',
					'transitionOut'     : 'none'
				}
			);
		});
		 </script><? }?>
		 <div id="popup_box" style="display:none;">
        	<div class="popup" >
            	<h1>Phone Number Found</h1>
                <p>Please install the new free EverydayLookup for Google CHrome to get latest activity on <?php echo '('.substr($phone_number, 0, 3).') '  . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?></p>
                <div class="popup_btn"><a class="btn btn-block btn-action" href="javascript:clickDownload();">	Install Now  </a></div>
                <ul>
                	<li>
                    	<h2>Recent Comments</h2>
                        <p>Review submitted comments from Whitepages.com</p>
                    </li>
                    
                    <li>
                    	<h2>White and Yellowpage Lookup</h2>
                        <p>Review submitted comments from </p>
                    </li>
                    
                    <li>
                    	<h2>Reserve Lookup</h2>
                        <p>Fast and easy reserve lookup tool straight from your browser.</p>
                    </li>
                </ul>
            </div>
			</div>
        </div>
    </div>
</section>

<footer class="footer">
	<div class="container">Copyright &copy; 2015 All rights reserved.</div>
</footer>

</body>
</html>
