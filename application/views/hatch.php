<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail Egg Hatcher</title>
	<meta name="description" content="BirdyMail">
	<meta name="author" content="mimo">
		<link rel="stylesheet" href="http://birdymail.me/css/styles.css">

  	<!--[if lt IE 9]>
  	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  	<![endif]-->
</head>
<body>
	<div id="expirebox">
		<?php echo $this->uri->segment(2); ?>@birdymail.me will expire <?php echo $expire ?>.<br />
		See <a href="http://twitter.com/BirdyMailMe">@BirdyMailMe</a> to extend or delete.
	</div>
		<div id="box">
			<div class="outer">
				<div class="inner">
					<?php
					if ($query->num_rows() < 1) 
						echo '<div class="mail">No e-mails for this egg.</div>';
					else {
						foreach ($query->result() as $row): 
							echo '<div class="mail">';
							echo "Sender: " . $row->sender . "<br />";
							echo "Subject: " . $row->subject . "<br />";
							echo "Body html: " . stripslashes($row->htmlbody) . "<br />";
							//echo "Body text: " . stripslashes($row->textbody) . "<br />";
							echo "</div>";
						endforeach;
					} ?>
				</div>
			</div>
		</div>
</body>
</html>