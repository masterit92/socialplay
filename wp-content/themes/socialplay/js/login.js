(function($){
	
	$('#socialplay_login').on('submit', function(e){
		e.preventDefault();
		
		//Validate form
		var form = $(this),
			data = form.serializeArray(),
			submitBtn = form.find('input[type="submit"]'),
			oldValue = submitBtn.val();
			
		form.find('.alert').remove();
		submitBtn.val('...');
		
		$.post(ajaxurl, data, function(r){
			
			if( ! r.success )
			{
				form.prepend('<div class="alert alert-error">'+r.data+'</div>');
				
			}else
			{
				form.prepend('<div class="alert alert-success">'+r.data.login_msg+'</div>');
				window.location = r.data.redirect_to;
			}
			
			submitBtn.val(oldValue);
		}, 'json');
	});

	$('#socialplay_forgot').on('submit', function(e){
		
		e.preventDefault();
		
		var form = $(this),
			data = form.serializeArray(),
			submitBtn = form.find('input[type="submit"]'),
			oldValue = submitBtn.val();
			
		form.find('.alert').remove();
		submitBtn.val('...');
		
		$.post(ajaxurl, data, function(r){
			
			if( ! r.success)
			{
				form.prepend('<div class="alert alert-error">'+r.data+'</div>');
			}else
			{
				form.prepend('<div class="alert alert-success">'+r.data+'</div>');
			}
			
			submitBtn.val(oldValue);
		}, 'json');
			
	});
})(jQuery);