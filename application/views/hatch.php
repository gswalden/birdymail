<body>
	
	<div id="top-box"></div>   

	<div class="row">
		<div class="container">
			<div class="span5 birdy-link">
				<h3><a href="http://birdymail.me"><i class="icon-arrow-left"></i> BirdyMail</a></h3><br/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="container">
			<div class="span5">
				<div class="mail">
					<div class="mail-content">
						<?php echo $this->uri->segment(2); ?>@birdymail.me will expire <?php echo $expire ?>.<br />
						See <a href="http://twitter.com/BirdyMailMe">@BirdyMailMe</a> to extend or delete.
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	$num_rows = $query->num_rows();
	if ($num_rows == 0):
?>
		<div class="row">
	    	<div class="container">
				<div class="span12">
					<div class="mail">
						<div class="mail-content">
							No e-mails for this egg.
						</div>
					</div>
				</div>
			</div>
	    </div>
<?php 
	else: 
		foreach ($query->result() as $row): ?>
			<div class="row">
		    	<div class="container">
					<div class="span12">
						<div class="mail">
							<div class="mail-content">
<?php 
								echo "Sender: <strong>" . $row->sender . "</strong><br />";
								echo "Subject: <strong>" . $row->subject . "</strong><br />";
								if (strlen($row->htmlbody) > 0)
									echo stripslashes($row->htmlbody);
								else
									echo stripslashes($row->textbody);
								echo "</div>";
?>
							</div>
						</div>
					</div>
				</div>
		    </div>
<?php 	
		endforeach;
	endif;	 
?>
<div class="row">
	<div class="container">
		<div class="span12">
			<hr>
			<footer>
				<p>
					© BirdyMail 2013
				</p>
			</footer>
		</div>
	</div>
</div>
</body>
</html>