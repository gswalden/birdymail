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
		var pattern = /^[\w]+$/;
		if (!pattern.test(name)) {
		    $("div#invalid_chars").show();
			$("input#twitter_name").focus();
			return false;
		}
		$('#layegg').hide();
		$("div#loader").show();
		var expire_days = $("#expire").val();
		var dm = 0;
		if ($('#direct_message').is(':checked')) {
			dm = 1;
		}
		var csrf = $("input[name=csrf_test_name]").val();
		var dataString = 'twitter_name=' + name + '&expire_days=' + expire_days + '&direct_message=' + dm + '&csrf_test_name=' + csrf;
		var big_url = "http://birdymail.me/create/new"; 
		
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
				$("div#loader").hide();
				$('#layegg').show()
;		      	$('#layegg').html("<div class='alert alert-info' id='success-message'></div><div class='input-append' id='mail_id'><input type='text' value='" + id + "@birdymail.me' onclick='this.select()' readonly /><button class='btn btn-primary' id='d_clip_button' data-clipboard-text='"+id+"@birdymail.me'><i class='icon-copy icon-white'></i></button></div>");
		      	$('#success-message').html("<button type='button' class='close' data-dismiss='alert'>&times;</button><i class='icon-thumbs-up'></i> Welcome to the nest!")
		      	.hide()
		      	.fadeIn(500, function() {
		        	
		      	});
		      	$('#mail_id').hide()
		      	.fadeIn(500, function() {
		        	
		      	});
		      	clip = new ZeroClipboard( document.getElementById('d_clip_button') );
		      	clip.glue( document.getElementById('d_clip_button') );
		      	clip.on( 'complete', function(client, args) {
		        	
			    } );
		      	return true;
		    },
		    error: function() {
		    	$("div#loader").hide();
		    	$('#layegg').fadeIn(500, function() {
		        	
		      	});
		      	$("div#does_not_exist").fadeIn(500, function() {
		        	
		      	});
				$("input#twitter_name").focus();
				return false;
		    }
		});
		return false;
	  
	});
});