function uploadImageProfile() {
		showLoading();

		// $('#loaderFile').show();
		var preview = document.getElementById('profileImage');
	  	var file    = document.querySelector('input[type=file]').files[0];
	  	var reader  = new FileReader();
	  	preview.src = "";

	  	reader.onloadend = function () {
	    	preview.src = reader.result;

	    	hideLoading();

		  	$('#profileImage').attr('crossorigin', "anonymous");
		  	$('#profileImage').css('width', "auto");

		  	$('#profileImage').cropbox({
				width: 250,
				height: 250,
			}).on('cropbox', function (e, result, img) {
	            $('#saveImageProfile').attr('data', img.getDataURL());
		    });

			$('#saveImageProfile').css('display', 'flex');
	  	}

	    reader.readAsDataURL(file);

	}

	2K09T8JT
