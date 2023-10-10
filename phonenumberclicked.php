<?php
error_reporting(E_ERROR);
include_once("config.php");
?>
<?php include('header.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/css/comment.css" />
<script type="text/javascript" src="<?php echo $base_url;?>/js/parsley.min.js"></script>
<body>

<?php include('nav.php');?>
<?php
	$status = isset($_GET['status']) ? $_GET['status'] : false;
	$phone =  htmlspecialchars( $_GET['phone'], ENT_QUOTES, 'utf-8');
	$sql = "SELECT * FROM cf_user WHERE userPhnNo=:userPhone";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':userPhone', $phone);
	$stmt->execute(); 
	$phoneData = $stmt->fetch();

	$noRows=$stmt->rowCount();

	$sql = "SELECT * FROM usercomments WHERE phone_id=:pid AND parentcomment_id is null";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':pid', $phoneData['userId']);
	$stmt->execute(); 
	$comments = $stmt->fetchAll();

	//for the purpose of counting...
	$sql = "SELECT * FROM usercomments WHERE phone_id=:pid";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':pid', $phoneData['userId']);
	$stmt->execute(); 
	$noOfComments=$stmt->rowCount();
	/*$phone = mb_convert_encoding($phone, 'UTF-8', 'UTF-8');
	$phone = htmlentities($phone, ENT_QUOTES, 'UTF-8');*/

	function getVote($id, $db)
	{
		$sql = "SELECT count(is_like) - count(is_dislike) like_diff FROM comment_vote WHERE comment_id=:comment_id"; 
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':comment_id', $id);
		$stmt->execute(); 
		$votes=$stmt->fetch();

		return $votes['like_diff'];
	}
?>


<div class="container">
	<?php if($status == 1):?>
		<div class="alert alert-danger">An error occured while posting posting the comment</div>
	<?php endif;?>
	<div class="col-md-8">
		<h2>Call from <?php echo substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6); ?></h2>
		<hr/>
		<p>Did you get a call from <?php echo $phone;?>? Read the comments below to find out details about this number. Also <a href="javascript:void(0)">report unwanted calls</a> to help identify who is using this phone number</p>
		<h3>Telephone: <?php echo substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6); ?></h3>
		<h3>Country: USA</h3>
		<h3>City State: Bossier Lousiana</h3>
		
		<div class="ads">
			ADS HERE...
		</div>
	
		<!--Comments section... --> 
		<div class="row">
			<div class="col-md-12">
				<h2 class="page-header"><?php echo $noOfComments?> Review(s) for this phone</h2>
				<section class="comment-list">
					<?php foreach($comments as $comment):?>
						<article class="row" id="comment_<?php echo $comment['comment_id']?>" commentid="<?php echo $comment['comment_id']?>">
							<div class="col-md-2 col-sm-2 hidden-xs">
								<figure class="thumbnail">
									<img class="img-responsive" src="/images/default-avatar.jpg" />
									<figcaption class="text-center"><?php echo htmlspecialchars( $comment['name'], ENT_QUOTES, 'utf-8'); ?></figcaption>
								</figure>
							</div>
							<div class="col-md-10 col-sm-10">
								<div class="panel panel-default arrow left">
									<div class="panel-body">
										<header class="text-left">
											<!-- <div class="comment-user"><i class="glyphicon glyphicon-user"></i> That Guy</div> -->
											<time class="comment-date" datetime="06-19-2015 01:05"><i class="glyphicon glyphicon-time"></i> <?php echo htmlspecialchars( $comment['date_posted'], ENT_QUOTES, 'utf-8'); ?></time>
										</header>
										<div class="comment-post">
											<p>
												<?php echo htmlspecialchars( $comment['comment_body'], ENT_QUOTES, 'utf-8'); ?>			
											</p>
										</div>
										<div class="ajaxload" id="loader_<?php echo $comment['comment_id']?>"></div>
										<div class="vote" id="vote_<?php echo $comment['comment_id']?>">
											<button title="vote up" class="voteup btn btn-default btn-circle"><i class="glyphicon glyphicon-thumbs-up"> </i> </button>
											&nbsp; <span id="like_diff_<?php echo $comment['comment_id']?>"><?php echo getVote($comment['comment_id'], $db);?></span> &nbsp;
											<button  title="vote down" class="votedown btn btn-default btn-circle"><i class="glyphicon glyphicon-thumbs-down"> </i> </button>
											<span class="pull-right text-right"><a href="javascript:void(0)" class="btn_reply_comment btn btn-default btn-sm"><i class="glyphicon glyphicon-share-alt"></i> reply</a></span>
										</div>
										<p class="voteResponse" id="vote_result_<?php echo $comment['comment_id']?>"></p>
									</div>
								</div>
							</div>
						</article>
						<?php 
						$sql = "SELECT * FROM usercomments WHERE phone_id=:pid AND parentcomment_id = :parentcomment_id";
						$stmt = $db->prepare($sql);
						$stmt->bindParam(':pid' ,$phoneData['userId']);
						$stmt->bindParam(':parentcomment_id', $comment['comment_id']);
						$stmt->execute(); 
						$subcomments = $stmt->fetchAll(); 
						foreach($subcomments as $subcomment):
						?>
							<article class="row" id="comment_<?php echo $subcomment['comment_id']?>" commentid="<?php echo $subcomment['comment_id']?>">
								<div class="col-md-2 col-sm-2 col-md-offset-1 col-sm-offset-0 hidden-xs">
									<figure class="thumbnail">
										<img class="img-responsive" src="/images/default-avatar.jpg" />
									<figcaption class="text-center"><?php echo htmlspecialchars( $subcomment['name'], ENT_QUOTES, 'utf-8'); ?></figcaption>
									</figure>
								</div>
								<div class="col-md-9 col-sm-9">
									<div class="panel panel-default arrow left">
										<div class="panel-heading right">Reply</div>
										<div class="panel-body">
											<header class="text-left">
												<!-- <div class="comment-user"><i class="glyphicon glyphicon-user"></i> That Guy</div> -->
												<time class="comment-date" datetime="06-19-2015 01:05"><i class="glyphicon glyphicon-time"></i> <?php echo htmlspecialchars( $subcomment['date_posted'], ENT_QUOTES, 'utf-8'); ?></time>
											</header>
											<div class="comment-post">
												<p>
													<?php echo htmlspecialchars( $subcomment['comment_body'], ENT_QUOTES, 'utf-8'); ?>	
												</p>
											</div>
											<div class="ajaxload" id="loader_<?php echo $subcomment['comment_id']?>"></div>
											<div class="vote" id="vote_<?php echo $subcomment['comment_id']?>">
												<button title="vote up" class="voteup btn btn-default btn-circle"><i class="glyphicon glyphicon-thumbs-up"> </i> </button>
												&nbsp; <span id="like_diff_<?php echo $subcomment['comment_id']?>"><?php echo getVote($subcomment['comment_id'], $db);?></span> &nbsp;
												<button  title="vote down" class="votedown btn btn-default btn-circle"><i class="glyphicon glyphicon-thumbs-down"> </i> </button>
											</div>
											<p class="voteResponse" id="vote_result_<?php echo $subcomment['comment_id']?>"></p>
										</div>
									</div>
								</div>
							</article>
						<?php endforeach;?>

					<?php endforeach;?>
					<!-- First Comment -->
				
				</section>
			</div>
		</div>
		<!--End Comments section... -->

		<hr/>
		<div class="row">
			<h3>Report a call from <?php echo substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6); ?></h3>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="widget-area no-padding blank">
					<div class="">
						<form data-parsley-validate method="POST" action="<?php echo $base_url?>/phonenosubmitreview.php" id="commentform" name="commentform">
							<div class="form-group">
								<label for="exampleInputEmail1">Your Name * </label>
								<input name="commentForm[name]"required type="text" class="form-control" id="txtboxCommentName" placeholder="">
								<br/>
								<p class="help-block">Your name as you would like it to appear in the title of your post.</p>
							</div>
							<div style="clear: both"></div>
							<div class="form-group">
								<label for="exampleInputEmail1">Your Comment * </label>
								<p class="help-block">Describe your experience with the calls from this number. Anything you know about the caller of the number that might help others especially welcome</p>
								<textarea rows="8"  name="commentForm[comment_body]" required placeholder="" class="form-control"></textarea>
							</div>
							<div style="clear: both"></div>
							<br/>
							<div class="form-group">
								<label for="exampleInputEmail1">Caller Company </label>
								<input name="commentForm[caller_company]" type="text" class="form-control" id="txtboxCommentName" placeholder="">
							</div>
							<div style="clear: both"></div>
							<div class="form-group">
								<label for="exampleInputEmail1">Call Type </label>
								<select name="commentForm[caller_type]" class="form-control">
									<option value="">Unknown</option>
									<option value="Debt Collector">Debt Collector</option>
									<option value="Fax machine">Fax machine</option>
									<option value="Harassment">Harassment</option>
									<option value="Non-profit agency">Non-profit agency</option>
									<option value="Telemarketing/sales">Telemarketing/sales</option>
									<option value="Prank call">Prank call</option>
									<option value="Political call">Political call </option>
									<option value="Survey">Survey</option>
								</select>
							</div>
							<input name="commentForm[phone_id]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phoneData['userId']?>">
							<input name="commentForm[phone]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phone?>">
							<button type="submit" class="btn btn-success green"><i class="glyphicon glyphicon-share"></i> Submit</button>
							<div style="clear: both"></div>
							<br/>
							<p class="help-block">Thank you for your contribution! Please watch your language and post only truthful information.</p>
						</form>
					</div><!-- Status Upload  -->
				</div><!-- Widget Area -->
			</div>
		</div>




	</div> <!--md8-->
</div> <!--content-->

 
 <br/><hr/><br/>
<footer class="footer">
	<div class="container">Copyright &copy; 2015 All rights reserved.</div>
</footer>

<script>
$(document).ready(function()
{
	$('.btn_reply_comment').click(function()
	{
		var domComment = $(this).parent().parent().parent().parent().parent().parent();
		var parentId = domComment.attr('commentid');
		$('.replyForm').remove();
		var formHtml = '<div class="col-md-10 pull-right replyForm"> \
<div class="widget-area no-padding blank">\
<div class="">\
<form data-parsley-validate method="POST" action="<?php echo $base_url?>/phonenosubmitreview.php" id="commentform" name="commentform">\
<div class="form-group">\
<label for="exampleInputEmail1">Your Name * </label>\
<input name="commentForm[name]"required type="text" class="form-control" id="txtboxCommentName" placeholder="">\
<br/>\
<p class="help-block">Your name as you would like it to appear in the title of your post.</p>\
</div>\
<div style="clear: both"></div>\
<div class="form-group">\
<label for="exampleInputEmail1">Your Comment * </label>\
<p class="help-block">Describe your experience with the calls from this number. Anything you know about the caller of the number that might help others especially welcome</p>\
<textarea rows="8" name="commentForm[comment_body]" required placeholder="" class="form-control"></textarea>\
</div>\
<input name="commentForm[phone_id]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phoneData["userId"]?>">\
<input name="commentForm[phone]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phone?>">\
<input name="commentForm[parentcomment_id]" type="hidden" class="form-control" id="txtboxCommentName" value="'+parentId+'">\
<button type="submit" class="btn btn-success green pull-right"><i class="glyphicon glyphicon-share"></i> Submit</button>\
<br/>\
<p class="help-block">Thank you for your contribution! Please watch your language and post only truthful information.</p>\
</form>\
</div>\
</div>\
<div style="clear: both"></div>\
<hr/><br/>\
</div>\
</div>';

		
		console.log(domComment);
		domComment.append(formHtml);
	});

	$('.voteup').click(function()
	{	
		var domComment = $(this).parent().parent().parent().parent().parent();
		var parentId = domComment.attr('commentid');
		//alert('loader_'+parentId);

		var loadUrl = "/vote.php";		
		$.ajax(
		{
			type: "POST",
			url: loadUrl,
			data: {refNo : parentId, state : 1},
			beforeSend: function(e)
			{
				$('#vote_'+parentId).hide();
				$('#loader_'+parentId).show();
			},
			success: function(returnVal) 
			{
				var obj = JSON.parse(returnVal);
				if(obj.result == true)
				{
					$('#vote_'+parentId).show();
					$('#loader_'+parentId).hide();
					$('#like_diff_'+parentId).text(obj.like_diff);
				}
				else
				{
					$('#vote_'+parentId).show();
					$('#loader_'+parentId).hide();
					$('#vote_result_'+parentId).show();
					if(typeof(obj.msg) === 'undefined')
					{
						$('#vote_result_'+parentId).text('an error occured!');
					}
					else
					{
						$('#vote_result_'+parentId).text(obj.msg);
					}
				}
			} //end success
		});
	});
	$('.votedown').click(function()
	{
		var domComment = $(this).parent().parent().parent().parent().parent();
		var parentId = domComment.attr('commentid');
		//alert('loader_'+parentId);
		

		var loadUrl = "/vote.php";		
		$.ajax(
		{
			type: "POST",
			url: loadUrl,
			data: {refNo : parentId, state : 0},
			beforeSend: function(e)
			{
				$('#vote_'+parentId).hide();
				$('#loader_'+parentId).show();
			},
			success: function(returnVal) 
			{
				var obj = JSON.parse(returnVal);
				if(obj.result == true)
				{
					$('#vote_'+parentId).show();
					$('#loader_'+parentId).hide();
					$('#like_diff_'+parentId).text(obj.like_diff);
				}
				else
				{
					$('#vote_'+parentId).show();
					$('#loader_'+parentId).hide();
					$('#vote_result_'+parentId).show();
					if(typeof(obj.msg) === 'undefined')
					{
						$('#vote_result_'+parentId).text('an error occured!');
					}
					else
					{
						$('#vote_result_'+parentId).text(obj.msg);
					}
				}
			} //end success
		});
	});
});
</script>



