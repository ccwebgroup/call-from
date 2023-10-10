<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/css/comment.css" />
<script type="text/javascript" src="<?php echo $base_url;?>/js/parsley.min.js"></script>

<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	$phone = $userPhone;
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
	<div class="row">
		<div class="col-md-8">
			<form action="#" id="frmRate" method="POST">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-header">Rate this number</h3>
						<input required class="rb-rating" type="text" name="rating">
						<input required type="hidden" name="phoneNo" value="<?php echo  $phoneData['userId']?>">
						<div class="clearfix"></div>

						<hr>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<button class="btn btn-success">Submit</button>
						<span class="loading hide"></span>
					</div>
					
				</div>

				<div class="row" style="margin-top: 20px;">
					<div class="col-md-12 hide"  id="msgSuccess">
						<div class="alert alert-success">
						<strong>Success! </strong> You put a rating in the phone number.
						</div>
					</div>
					<div class="col-md-12 hide"  id="msgFail">
						<div class="alert alert-danger">
						<strong>Error! </strong> An error occured!
						</div>
					</div>
				</div>
			</form>
			<!--Comments section... --> 
			
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
		<!--End Comments section... -->
	</div>
		<hr/>
		<div class="row">
			<h3>Report a call from <?php echo substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6); ?></h3>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="widget-area no-padding blank">
					<div class="">
						<form data-parsley-validate method="POST" onsubmit="return verify(this);" action="<?php echo $base_url?>/phonenosubmitreview.php" id="commentform" name="commentform">
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
									<option value="q">Unknown</option>
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
							<div class="form-group">
								<label for="inputPassword3" class="">Prove that you are a human</label>
								<div class="g-recaptcha" id="comment_captcha" data-sitekey="6LeFYQkTAAAAAMpzETg598KEYfFnbn8Xiq8choF3"></div>
								<p style="color: #A33" id="captcha-error"></p>
							</div>
							<div class="form-group">
								<input name="commentForm[phone_id]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phoneData['userId']?>">
								<input name="commentForm[phone]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phone?>">
								<button type="submit" class="btn btn-success green"><i class="glyphicon glyphicon-share"></i> Submit</button>
							</div>
							<div style="clear: both"></div>
							<br/>
							<p class="help-block">Thank you for your contribution! Please watch your language and post only truthful information.</p>
						</form>
					</div><!-- Status Upload  -->
				</div><!-- Widget Area -->
			</div>
		</div>




	</div> <!--md8-->


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
<div class="form-group">\
<input name="commentForm[phone_id]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phoneData["userId"]?>">\
<input name="commentForm[phone]" type="hidden" class="form-control" id="txtboxCommentName" value="<?php echo $phone?>">\
<input name="commentForm[parentcomment_id]" type="hidden" class="form-control" id="txtboxCommentName" value="'+parentId+'">\
<button type="submit" class="btn btn-success green pull-right"><i class="glyphicon glyphicon-share"></i> Submit</button>\
</div>\
<br/>\
<p class="help-block">Thank you for your contribution! Please watch your language and post only truthful information.</p>\
</form>\
</div>\
</div>\
<div style="clear: both"></div>\
<hr/><br/>\
</div>\
</div>';
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

	$('.rb-rating').rating({
        'showCaption': true,
        'stars': '10',
        'min': '0',
        'max': '10',
        'step': '1',
        'size': 'xs',
        'starCaptions': 
        {
        	0: 'nix', 
        	1: 'spam', 
        	2: 'spam', 
        	3: 'spam',
        	4: 'neutral',
        	5: 'neutral',
        	6: 'neutral',
        	7: 'good',
        	8: 'good',
        	9: 'great',
        	10: 'excellent',
        },

		starCaptionClasses: function (val) 
		{
			if (val <= 3) 
			{
				return 'label label-danger';
			}
			else if(val <= 6)
			{
				return 'label label-warning';
			}
			else if(val <= 8)
			{
				return 'label label-info';
			}
			else if(val <= 9)
			{
				return 'label label-primary';
			}
			else if(val == 10)
			{
				return 'label label-success';
			}
		},
		hoverOnClear: false
    });


    $('#frmRate').submit(function()
    {
    	var outerthis = $(this);
    	$('#msgSuccess').addClass("hide");
		$('#msgFail').addClass("hide");
		$('#msgFail').html("");
    	$.ajax
    	({
    		url : 'rate.php',
    		type : 'POST',
    		data : $(this).serialize(),
    		beforeSend : function()
    		{
    			$('.loading').removeClass("hide");
    		},
    		success : function(data)
    		{
    			$('.loading').addClass("hide");
    			var obj = JSON.parse(data);
    			if(obj.result)
    			{
    				$('#msgSuccess').removeClass("hide");
    			}
    			else
    			{
    				$('#msgFail').removeClass("hide");
    				$('#msgFail').html("<div class='alert alert-danger'>"+obj.msg+"</div>");
    			}
    		}
    	});

    	return false;
    })
});

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



