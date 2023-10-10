


<?php include('header.php');?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="<?php echo $base_url;?>/js/parsley.min.js"></script>
<body>

<?php include('nav.php');?>
<section class="home_cont">

<style>

</style>
	<div class="container">
		<div class="">
			<h1>Removal Request</h1>
		</div>

		<div class="row">
			<div class="col-md-6">
				<form data-parsley-validate method="POST" onsubmit="return verify(this);" name="removalRequestForm" id="removalRequestForm" action="removeRequestSubmit.php">
					<div class="form-group">
						<label  for="exampleInputEmail1">Phone Number</label>
						<input required type="text" class="form-control" name="phone" id="txtbox_phone" placeholder="Phone">
					</div>
					<div class="form-group">
						<label  for="inputPassword3" class="">Reason</label>
						<textarea required class="form-control" name="reason" id="txtbox_reason"></textarea>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="">Prove that you are a human</label>
						<div class="g-recaptcha" id="comment_captcha" data-sitekey="6LeFYQkTAAAAAMpzETg598KEYfFnbn8Xiq8choF3"></div>
						<p style="color: #A33" id="captcha-error"></p>
					</div>
					<div class="form-group">
						<div class="">
							<button type="submit" class="btn btn-success">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
</section>
<br/><hr/><br/>
<footer class="footer">
	<div class="container">Copyright &copy; 2015 All rights reserved.</div>
</footer>


</body>
</html>
<script>
function verify(form)
{
	var v = grecaptcha.getResponse();
	console.log(v);
    if(v.length == 0)
    {
    	//alert('hehe')
    	$('#captcha-error').text("You can't leave Captcha Code empty")
        return false;
    }
    else
    {
    	//alert('haha')
      	$('#captcha-error').text("")
        return true; 
    }
}
</script>