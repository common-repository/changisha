(function($){
	$('#changishaForm').validate({
		rules: {
			changishaName: {
				required: true,
			},
			changishaPhoneNumber: {
				required: true,
				digits: true,
				minlength: 10,
				maxlength: 10,
			},
			changishaAmount: {
				required: true,
				number: true,
				max: 70000,
			},
		},
		messages: {
			changishaName: {
				required: "Please, enter your full name.",
			},
			changishaPhoneNumber: {
				required: "Please, enter your MPesa phone number.",
				digits: "Please, enter a valid MPesa phone number.",
				minlength: "Please, enter a valid MPesa phone number.",
				maxlength: "Please, enter a valid MPesa phone number.",
			},
			changishaAmount: {
				required: "Please, enter the total amount of donation.",
				number: "Please, enter a valid total amount of donation.",
				max: "Please, enter an amount less than or equal to KES70000.00",
			},
		},
		errorElement: 'small',
		errorPlacement: function (error, element) {
			error.addClass('changisha-form-error');
			element.closest('.changisha-form-text').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('changisha-form-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('changisha-form-invalid');
		}
	});

	$(document).on('submit', '#changishaForm', function(e){
		e.preventDefault();
		var disForm = $(this);
		var request, alertBox = disForm.find('#alertBox');
		var actionPath = disForm.attr('action');
		var dataString = disForm.serialize();
		if(request){
			request.abort();
		}
		request = $.ajax({
			type: 'POST',  
			url: actionPath,
			data: dataString,
			dataType: 'json'
		});
		request.done(function(data, textStatus, XMLHttpRequest){
			if(data.status == '1'){
				alertBox.removeClass('hide').addClass('success').text(data.message);
			} else if(data.status == '2'){
				alertBox.removeClass('hide').addClass('error').text(data.message);
			} else if(data.status == '3'){
				alertBox.removeClass('hide').addClass('info').text(data.message);
			} else {
				alert(data.message, 'Error!');
			}
		});
		request.fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.error("The following error occurred: " + textStatus, errorThrown);
		});
		request.always(function(){
			request.abort();
		});
	});
})(jQuery);