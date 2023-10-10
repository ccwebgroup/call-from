<?php
error_reporting(E_ERROR);
include_once("config.php");
$userPhone=isset($_REQUEST['number'])?$_REQUEST['number']:'';
//if(isset($_POST['submit']))
if(!empty($userPhone))
{
        //$userPhone=$_REQUEST['phone'];
	    $sql = "SELECT * FROM cf_user WHERE userPhnNo='$userPhone'";
        $result = mysqli_query($link, $sql);

		//display information
        $noRows=mysqli_num_rows($result);
		
		
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
$sqlSearch="SELECT *,count(searchPhoneId) as totSearch FROM cf_user inner join cf_search_details on (cf_user.userId=cf_search_details.searchPhoneId) group by searchPhoneId order by totSearch desc";
$stmtSearch = mysqli_query($link, $sqlSearch);
$totRows=mysqli_num_rows($stmtSearch);
$pageToDispaly = ceil($totRows / 10);
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
	<script language="JavaScript" type="text/JavaScript">
        function changeAction(frm)
        {
            //alert(123)
			document.getElementById("searchFrm").action =  document.getElementById("phone").value ;
			frm.submit();
        }
</script>
<script src="js/jquery.paginate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jpaginate.css" media="screen"/>
<script type="text/javascript">
		$(function() {
			$("#pagination").paginate({
				count 		: <?php echo $pageToDispaly?>,
				start 		: 1,
				display     : 7,
				border					: true,
				text_color  			: '#000',
				background_color    	: '#EEE',	
				text_hover_color  		: '#fff',
				background_hover_color	: '#B8D654', 
				images					: false,
				mouse					: 'press',
				onChange     			: function(page){
											$('._current','#paginationdiv').removeClass('_current').hide();
											$('#p'+page).addClass('_current').show();
										  }
			});
		});
		</script>
</head>

<body>
<header class="header">
	<div class="container">
    	<div class="logo">
    		<h3>Call-From.com</h3>
            </div>
            <div id="search">
            <form class="navbar-form navbar-right" action="index.php" method="GET" id="searchFrm" name="searchFrm">
			  
      <div class="form-group">
	  
        <input type="text" class="form-control" placeholder="Enter Phone Number" name="number" id="phone" value="<?php echo isset($userPhone)?$userPhone:''?>" autoComplete="OFF">
        <!--<button class="btn srch_Btn" type="button" name="btnSubmit" id="btnSubmit" onclick="changeAction(this.form)">Search</button>-->
		<button class="btn srch_Btn" type="submit">Search</button>
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
        <? //if (isset($result->phone)) {?>
        <div class="home_right">
		<h2>Frequently Searched Numbers</h2>
        <div id="paginationdiv">
	   <div class="col-sm">
       <strong>Full Number: </strong>
       </div>
       
       <div class="col-sm1">
        <strong>City/State: </strong>
        </div>
        
        <div class="col-sm">
        <strong>Frequently Search:</strong>
      
       </div>
        <?php
        if($totRows>0)
		{
		    //print_r($row);
            $i=0;$j=0;$page=1;
			//foreach ($stmtSearch->fetchAll() as $row) 
			while($row = mysqli_fetch_array($stmtSearch)) 
			{			
               
			   //print_r($row);
			   //die;
			   
			    if($j==0)
				{
					$class='_current';
					$style='';
                }
				else
                {
					$class='';
					$style='display:none;';
				}
				if($i==0)
				{
				  echo '<div class="pagedemo '.$class.'" id="p'.$page.'" style="'.$style.'">';
				}
				echo '<div class="home_right_dscp">
					<div class="col-sm">
					'.$row['userPhnNo'].' </div>	
					
					 <div class="col-sm1">
					 '.$row['userStandardAddressLocation'].' </div>	
					
					<div class="col-sm">
					'.$row['totSearch'].' </div>	
									
				</div>';
				
               
				$i++;$j++;
                if($i==9 || $j==$totRows)
                {
                   echo '</div>';
				   $i=0;$page++;
                }
			}		
		}
		?>
        
        <div id="pagination"></div>
        </div>
		<?php
		if($result->phone['line_type']){?>
		<script type="text/javascript">		 
		  $(document).ready(function() {
		  var html=$("#popup_box").html();
          //alert(123);
			$.fancybox(
				html,
				{
					'autoDimensions'    : false,
					'width'                 : 150,
					'height'                : 'auto',
					'transitionIn'      : 'none',
					'transitionOut'     : 'none',
					'closeClick':true,
					'helpers':{'overlay':{'closeClick':true}},
					'closeBtn':false,
				}
			);
		});
		
		
 
		 </script>
		   <script language="javascript">
		   function fancyboxClose(){
		
					  parent.jQuery.fancybox.close();
		}
		</script>
		 
		 <? }?>
		 
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
                 <p><a href="javascript:void(0)" id="loginlink" onclick="fancyboxClose();">search again</a></p>
            </div>
			</div>
        </div>
    </div>
    	<? //} else{?>
        
        <!--<div class="default_cnt">Seach a Number for information.</div>-->
        <? //}?>
</section>

<footer class="footer">
	<div class="container">Copyright &copy; 2015 All rights reserved.</div>
</footer>

</body>
</html>
