<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Call-From.com</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<form class="navbar-form navbar-right" action="<?php echo $base_url;?>" method="GET" id="searchFrm" name="searchFrm">
						<div class="form-group">
							<input required type="text" class="form-control phone" placeholder="Enter Phone Number" name="number" id="phone" value="<?php echo isset($userPhone)?$userPhone:''?>" autoComplete="OFF">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
					<ul class="nav navbar-nav">
						<li><a href="#">Top Searched Phone Numbers</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="/doNotCall.php">Do Not Call List Info</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="/removalRequest.php">Removal Request</a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</div>
</header>


