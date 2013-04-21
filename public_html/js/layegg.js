$(function() {
	$('.alert').hide();
	$(".btn").click(function() {
		// validate and process form here

		$('.alert').hide();
		var name = $("input#twitter_name").val();
		name = $.trim(name);
		while (name.charAt(0) === '@')
		    name = name.substr(1);
		if (name == "") {
			$("div#is_blank").show();
			$("input#twitter_name").focus();
			return false;
		}
		var pattern = /^[A-Za-z0-9_]+$/;
		if (!pattern.test(name)){
		    $("div#invalid_chars").show();
			$("input#twitter_name").focus();
			return false;
		}

		var dataString = 'twitter_name=' + name;
		 //alert (dataString);return false;
		 /*$.ajax({
		    type: "POST",
		    url: "http://birdymail.me/layegg",
		    data: dataString,
		    success: function() {
		      	$('#contact_form').html("<div id='message'></div>");
		      	$('#message').html("<h2>Contact Form Submitted!</h2>")
		      	.append("<p>We will be in touch soon.</p>")
		      	.hide()
		      	.fadeIn(1500, function() {
		        	$('#message').append("<img id='checkmark' src='images/check.png' />");
		      	});
		    }
		  });*/
		  return false;
	  
	});
});