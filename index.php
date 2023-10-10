<?php
error_reporting(E_ERROR);
include_once("config.php");
$userPhone = isset($_REQUEST['number']) ? $_REQUEST['number'] : '';
//if(isset($_POST['submit']))
if (!empty($userPhone)) {
	$uri = $_SERVER['REQUEST_URI'];
	$userPhone = str_replace('-', '', $userPhone); // Replaces all spaces with hyphens.
	$userPhone = preg_replace('/[^A-Za-z0-9\-]/', '', $userPhone); // Removes special chars.
	if (strpos($uri, '?number') !== false) {
		header("Location: http://call-from.com/" . $userPhone);
	}

	//$userPhone=$_REQUEST['phone'];
	$sql = "SELECT * FROM cf_user WHERE userPhnNo=:userPhone";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':userPhone', $userPhone);
	$stmt->execute();
	$phoneData2 = $stmt->fetchAll();
	$noRows = count($phoneData2);

	if ($noRows > 0) {
		//rating average...
		$sql = "SELECT AVG(rating) as rating FROM phoneRating WHERE phone_id = :pid";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':pid', $phoneData2[0]['userId']);
		$stmt->execute();
		$ratingTemp = $stmt->fetch();

		$rating = isset($ratingTemp['rating']) ? $ratingTemp['rating'] : 0;


		$result->phone = 'database';
?>
		<script>
			showPopup = true;
			var notfound = false;
			var invalidPhone = false;
			var notfound = false;
		</script>
	<?php
	} else {
	?>
		<script>
			var invalidPhone = false;
			var notfound = false;
		</script>
<?php
		include 'lib/phone_lookup.php';
	}
} else {
	$error = '0';
}
$sqlSearch = "SELECT searchPhoneId, COUNT(searchPhoneId) AS totSearch 
              FROM cf_user 
              INNER JOIN cf_search_details ON cf_user.userId = cf_search_details.searchPhoneId 
              GROUP BY searchPhoneId 
              ORDER BY totSearch DESC";
$stmtSearch = $db->prepare($sqlSearch);
$stmtSearch->execute();
$results = $stmtSearch->fetchAll(PDO::FETCH_ASSOC);
$totRows = count($results);
$pageToDisplay = ceil($totRows / 10);

?>
<?php include('header.php'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<body>

	<?php include('nav.php'); ?>
	<section class="home_cont">

		<style>
			td th {
				font-size: 12px;
			}
		</style>

		<div class="container">
			<div class="alert alert-danger hide invalidPhone">Invalid phone number. No such area code</div>
			<?php $status = isset($_GET['status']) ? $_GET['status'] : false;;
			if ($status == 1) : ?>
				<div class="alert alert-danger">An error occured while posting posting the comment</div>
			<?php elseif ($status == 2) : ?>
				<div class="alert alert-danger">Number of allowable comment per day was reached.</div>
			<?php endif; ?>
			<div class="ad_space">
				<!--<img src="http://xcstechnologies.com/carfrom/images/ad.png" /> -->
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Call-From.com -->
				<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3181355197170352" data-ad-slot="3613275729" data-ad-format="auto">
				</ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-7">
							<?php
							include 'views/result_data.php';
							?>
						</div>
						<div class="col-md-5">
							<h3>Frequently Searched Numbers</h3>
							<div class="">
								<table class="table table-hover table-condensed">
									<thead>
										<tr>
											<th style="width: 25%">Phone Number</th>
											<th style="width: 44%">City/State</th>
											<th style="width: 30%">Freq Search</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($stmtSearch->fetchAll() as $row) : ?>
											<tr class="clickable">
												<td><a href="/<?php echo htmlspecialchars($row['userPhnNo'], ENT_QUOTES) ?>"><?php echo htmlspecialchars($row['userPhnNo'], ENT_QUOTES) ?></a></td>
												<td><?php echo htmlspecialchars($row['userStandardAddressLocation'], ENT_QUOTES) ?></td>
												<td style="text-align: center;"><?php echo htmlspecialchars($row['totSearch'], ENT_QUOTES) ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php if (!empty($userPhone)) : ?>
						<hr />
						<div class="row">
							<div class="col-md-12">
								<?php include('views/phoneReview.php'); ?>
							</div>
						</div>

					<?php endif; ?>

				</div>

				<div class="col-md-2">
					<div class="row">
						<script async src="//"></script>
						<!-- Call-from Sidebar -->
						<ins class="adsbygoogle col-md-12" style="display:inline-block;width:300px;height:600px" data-ad-client="ca-pub-3181355197170352" data-ad-slot="5238538924"></ins>
						<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
					<br />
					<div class="row">
						<script async src="//"></script>
						<!-- Call-from Sidebar -->
						<ins class="adsbygoogle col-md-12" style="display:inline-block;width:300px;height:600px" data-ad-client="ca-pub-3181355197170352" data-ad-slot="5238538924"></ins>
						<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
				</div>
			</div>
		</div>
	</section>
	<br />
	<hr /><br />
	<footer class="footer">
		<div class="container">Copyright &copy; 2015 All rights reserved.</div>
	</footer>

	<!--ads -->

	<script>
		showPopup = false;
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

		ga('create', 'UA-3979678-39', 'auto');
		ga('send', 'pageview');

		$(document).ready(function() {
			$('.table').dataTable({
				searching: true,
				"bInfo": false,
				"iDisplayLength": 10,
				"bLengthChange": false,

			});
			if (showPopup) {
				$('#phoneNumberFound').modal('show');
			}
			if (notfound) {
				$('#searchedPhone').text(searchedPhone);
				$('#userLatitude').val(lat);
				$('#userLongitude').val(lng);
				$('#userLongitude').val(lng);
				$('#userPhnNo').val(searchedPhone);
				$('#userAreaCode').val(searchedPhone.substring(0, 3));
				$('#phoneNumberNotFound').modal('show');
			}
			if (invalidPhone) {
				$('.invalidPhone').removeClass('hide');
			}
		});

		function redirectToNumber(number) {
			window.location = "/" + number;
		}
	</script>
</body>

</html>

<div class="modal fade" id="phoneNumberFound">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title">Phone Number Found!</h2>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					Please install the new free EverydayLookup for Google CHrome to get latest activity on <?php echo '(' . substr($phone_number, 0, 3) . ') '  . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6); ?>
				</div>
				<div class="popup_btn"><a class="btn btn-block btn-primary " href="javascript:clickDownload();"> Install Now </a></div>
				<ul class="popupList">
					<li>
						<h2><span class="glyphicon glyphicon-ok"> </span> &nbsp;&nbsp;Recent Comments</h2>
						<p style="margin-left: 30px;">Review submitted comments from Whitepages.com</p>
					</li>

					<li>
						<h2><span class="glyphicon glyphicon-ok"> </span> &nbsp;&nbsp;White and Yellowpage Lookup</h2>
						<p style="margin-left: 30px;">Review submitted comments from </p>
					</li>

					<li>
						<h2><span class="glyphicon glyphicon-ok"> </span> &nbsp;&nbsp;Reserve Lookup</h2>
						<p style="margin-left: 30px;">Fast and easy reserve lookup tool straight from your browser.</p>
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Search Again</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="phoneNumberNotFound">
	<div class="modal-dialog">
		<form class="form-horizontal" id="newPhoneForm" name="newPhoneForm" method="POST" action="/saveNewPhone.php">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title">Phone Number Not Found!</h2>
				</div>
				<div class="modal-body">
					<div class="alert alert-info" style="text-align: center;">
						Please enter the phone number details. for <span id="searchedPhone"></span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="Phone[userName]" id="inputName" value="Unknown" placeholder="Phone number owner">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Address</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="Phone[userAddress]" id="inputAddress" value="Unknown" placeholder="Phone number address">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Carrier</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="Phone[userCarrier]" id="inputPassword3" placeholder="Phone carrier">
						</div>
					</div>
				</div>
				<div class="modal-footer">

					<input type="hidden" class="form-control" name="Phone[userAreaCode]" id="userAreaCode" value="1">
					<input type="hidden" class="form-control" name="Phone[userLatitude]" id="userLatitude" value="1">
					<input type="hidden" class="form-control" name="Phone[userLongitude]" id="userLongitude" value="1">
					<input type="hidden" class="form-control" name="Phone[userPhnNo]" id="userPhnNo" value="1">
					<button type="submit" class="btn btn-default pull-left">Submit</button>
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Search Again</button>

				</div>
			</div><!-- /.modal-content -->
		</form>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->