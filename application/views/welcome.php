<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>BirdyMail!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Free, temporary e-mail with Twitter integration.">
    <meta name="author" content="Mimo">

    <!-- Le styles -->
    <link href="http://localhost/birdymail/public_html/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px;  60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <!-- <link href="http://localhost/birdymail/public_html/css/bootstrap-responsive.css" rel="stylesheet"> -->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://localhost/birdymail/public_html/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="img/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="img/ico/favicon.png">
</head>

<body>
	
	<div id="top-box"></div>    

    <div class="container">
		<div class="hero-unit">
			<h1>
				BirdyMail, free (like a bird!)
			</h1>
			<br />
			<div id="layegg" class="mywidth">
				<?php echo form_open(); ?>
					<div class="input-prepend input-append">
		  				<span class="add-on">@</span>
						<input type="text" id="twitter_name" placeholder="Enter Twitter handle" />
						<button class="btn btn-primary" type="submit">Join the Nest!</button>
					</div>
					<div class="alert alert-error fade in" id="does_not_exist">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> Twitter handle does not exist.
					</div>
					<div class="alert alert-error fade in" id="is_blank">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> You entered nothing.
					</div>
					<div class="alert alert-error fade in" id="invalid_chars">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> Invalid characters entered.
					</div>
					<div>
						<div class="input-prepend">
							<span class="add-on">Expire in</span>
							<select id="expire" class="span2">
								<option value="1">1 day</option>
								<option value="7">7 days</option>
								<option value="14">14 days</option>
								<option value="21" selected="selected">21 days</option>
								<option value="28">28 days</option>
							</select>
						</div>
					</div>
					<div>
						<label class="checkbox">
							<input type="checkbox" id="direct_message" />
							Send Alerts via Direct Message *
						</label>
					</div>
					<!-- <button type="submit" class="btn">
						Join the Nest!
					</button> -->
					<div id="twitter_rules">* Twitter rules state you must follow <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a> to receive DMs</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- Example row of columns -->
		<div class="row">
			<div class="span4">
				<div class="steps">
					<div class="steps-content">
						<h2>
							Step 1
						</h2>
						<p>
							Submit your Twitter handle, and after we check your input for errors,
							we will assign you a free, temporary @birdymail.me address to use as you
							wish. We will not tweet at you with anything except alerts.
						</p>
						<p>
						</p>
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="steps">
					<div class="steps-content">
						<h2>
							Step 2
						</h2>
						<p>
							Use your address anywhere on the web, and when you receive new mail, <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a>
							will tweet at you with a link to your inbox. To keep e-mails (semi-) private,
							use our Direct Message feature.
						</p>
						<p>
						</p>
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="steps">
					<div class="steps-content">
						<h2>
							Step 3
						</h2>
						<p>
							To extend or delete your @birdymail.me address, tweet 'extend' or 'stop'
							to <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a> from your public Twitter handle. By default, your address
							will expire after 21 days.
						</p>
						<p>
						</p>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<footer>
			<p>
				© BirdyMail 2013
			</p>
		</footer>
	</div>
<!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="js/bootstrap.min.js"></script> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
	<script src="http://platform.twitter.com/widgets.js"></script>
	
	<script src="http://localhost/birdymail/public_html/js/layegg.js"></script>

</body>
</html>