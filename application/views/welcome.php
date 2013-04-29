<body>
	
	<div id="top-box"></div>    

    <div class="container">
		<div class="hero-unit">
			<h1>
				<a href="http://birdymail.me">BirdyMail</a>, free (like a bird!)
			</h1>
			<br />
			<div id="loader">
				<img src="img/ajax-loader.gif" alt="Loading">
			</div>
			<div id="layegg" class="mywidth">
				<?php echo form_open(); ?>
					<div class="input-prepend input-append">
		  				<span class="add-on">@</span>
						<input type="text" id="twitter_name" placeholder="Enter Twitter handle" />
						<button class="btn btn-primary" type="submit">Join the Nest!</button>
					</div>
					<div class="alert alert-error fade in" id="does_not_exist">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i> Twitter handle does not exist.
					</div>
					<div class="alert alert-error fade in" id="is_blank">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i> You entered nothing.
					</div>
					<div class="alert alert-error fade in" id="invalid_chars">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i> Invalid character(s) entered.
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
					<div id="twitter_rules">* Twitter rules state you must follow <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a> to receive DMs</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		
		<div class="row">
			<div class="span4">
				<div class="steps">
					<h2>
						Step 1
					</h2>
					<p>
						Submit your Twitter handle, and after a quick validation,
						we will instantly assign you a free, temporary @birdymail.me address to use as you
						wish. We will not tweet at you with anything except alerts.
					</p>
				</div>
			</div>
			<div class="span4">
				<div class="steps">
					<h2>
						Step 2
					</h2>
					<p>
						Use your new address anywhere on the web, and when you receive new e-mail, <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a>
						will tweet at you with a link to your inbox. To keep e-mails (semi-) private,
						use our Direct Message feature.
					</p>
				</div>
			</div>
			<div class="span4">
				<div class="steps">
					<h2>
						Step 3
					</h2>
					<p>
						To extend or delete your @birdymail.me address, tweet 'extend' or 'stop'
						to <a href="http://twitter.com/intent/user?screen_name=BirdyMailMe">@BirdyMailMe</a> from your public Twitter handle. By default, your address
						will expire after 21 days.
					</p>
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
	
	<script src="http://birdymail.me/js/layegg.js"></script>
	<script src="http://www.birdymail.me/js/ZeroClipboard.js"></script>
    <script>
    	ZeroClipboard.setDefaults( { moviePath: 'http://www.birdymail.me/js/ZeroClipboard.swf', trustedDomains: 'birdymail.me' } );
      	var clip = new ZeroClipboard( document.getElementById('d_clip_button') );
      	clip.glue( document.getElementById('d_clip_button') );
      	clip.on( 'complete', function(client, args) {
        	 // document.getElementById("d_clip_button").value="DONE";
	    } );
    </script>

</body>
</html>