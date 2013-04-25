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
		if (!pattern.test(name)) {
		    $("div#invalid_chars").show();
			$("input#twitter_name").focus();
			return false;
		}
		var expire_days = $("#expire").val();
		var dm = 0;
		if ($('#direct_message').is(':checked')) {
			var dm = 1;
		}
		var csrf = $("input[name=csrf_test_name]").val();
		var dataString = 'twitter_name=' + name + '&expire_days=' + expire_days + '&direct_message=' + dm + '&csrf_test_name=' + csrf;
		var big_url = "http://localhost/birdymail/public_html/index.php/create/new"; 
		$.ajax({
		    type: "POST",
		    url: big_url,
		    data: dataString,
		    dataType: "json",
		    success: function(data) {
		      	var id;
		      	$.each(data, function(key, value) {
				  id = value;
				});
		      	$('#layegg').html("<div class='alert alert-success alert-block' id='success-message'></div>");
		      	$('#success-message').html("Weclome to the nest!<br />Your BirdyMail egg is<br />")
		      	.append("<div class='input-append'><input type='text' value='" + id + "@birdymail.me' onclick='this.select()' readonly /><button class='btn btn-primary'><i class='icon-share icon-white'></i></button></div>")
		      	.hide()
		      	.fadeIn(1500, function() {
		        	
		      	});
		    },
		    error: function() {
		      	$('#layegg').html("<div class='alert alert-error' id='success-message'></div>");
		      	$('#success-message').html("Nope!")
		      	.hide()
		      	.fadeIn(1500, function() {
		        	
		      	});
		    }
		});
		return false;
	  
	});
});