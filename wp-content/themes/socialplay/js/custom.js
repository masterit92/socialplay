jQuery(document).ready(function($) {
    
	"use strict";
	$('a[id="video_like"], a[id="video_dislike"]').live('click', function(e){
		e.preventDefault();
		
		if( $(this).data('thumbup') === true ) {return;}
		$(this).data('thumbup', true);

		var type = $(this).attr('id');
		var post = $(this).parent().attr('data-id');
		var element = $(this);
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: "action=users_ajax_callback&subaction=voting&type="+type+"&postid="+post,
			success: function(res){
				if( res !== '0' ) 
				{
					$('span', element).text(res);
				}else {
					alert( 'Please login to vote');
				}
			},
		});
		return false;
	});
	
	
	$('#dialog-uploadVideo').on('click', '#fw_add_audio', function(){
		if($('input[name="link"]').val() === '' && $('textarea[name="embed_code"]').val() === '') 
		{
			alert( 'Please fill in atleast one field');
			return false;
		}
		
		return true;
	});
	
	function ajax_fetch_video(data)
	{
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: "action=users_ajax_callback&"+data,
			success: function(res){
				$('#dialog-editProfile').html(res);
				$( "#dialog-load" ).dialog('close');
				$( "#dialog-editProfile" ).dialog('open');
			},
		});
		return false;
	}
	
	
	$('.edit_video, .edit_audio').on('click', this, function(){
		$('.dialog-overlay').fadeIn();
		$( "#dialog-load" ).dialog('open');
		var type = $(this).attr('class');
		var subaction = (type === 'edit_audio') ? 'fetch_audio' : 'fetch_video';
		var fields = 'subaction='+subaction+'&type='+type+'&data_id='+$(this).parent().attr('data-id');
		ajax_fetch_video(fields);
		return false;
	});
	
	$('.edit_term').on('click', this, function(e){
		e.preventDefault();
		var type = $(this).attr('data-type');
		var term_id = $(this).parent().attr('data-id');
		var data = 'subaction=edit_term&type='+type+'&data_id='+term_id;
		$('.dialog-overlay').fadeIn();
		$('#dialog-load').dialog('open');
		ajax_fetch_video(data);
		return false;
	});
	$('#user_profile').on('click', this, function(e){
		e.preventDefault();
		
		var data = 'subaction=user_profile_form&type=user_profile';
		$('.dialog-overlay').fadeIn();
		$('#dialog-load').dialog('open');
		ajax_fetch_video(data);
		return false;
	});
	
	$('.video_comment_flag').on('click', this, function(){
		
		/** Prevent continuous clicks */
		if( $(this).data('comment_flag_inappropriate') === true) {return;}
		
		if( confirm(' Are you sure to flag this comment as inapropriate?') )
		{
			var id = $(this).attr('data-id');
			if( !id ) {return;}
			$(this).data('comment_flag_inappropriate', true);
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: 'action=users_ajax_callback&subaction=comment_report&type=inappropriate&data_id='+id,
				success: function(res){
					
				},
			});
		}
	});
	
	$('.del_term').on('click', this, function(){
		
		
		if( !confirm( 'Are you really want to delete?')) {return;}
		
		if( $(this).data('social_del_term') === true) {return;}
		$(this).data('social_del_term', true);
		
		var id = $(this).parent().attr('data-id');
		var data_type = $(this).attr('data-type');
		var parent = $(this).parents('.chan-box:first');
		var term_type = '';
		
		if( id ){
			$('.dialog-overlay').fadeIn();
			$('#dialog-load').dialog('open');
		}
		
		switch( data_type )
		{
			case 'add_channel':
				term_type = 'video_channel';
			break;
			case 'add_playlist':
				term_type = 'video_playlist';
			break;
			case 'add_album':
				term_type = 'audio_album';
			break;
		}
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: 'action=users_ajax_callback&subaction=del_term&type='+term_type+'&data_id='+id,
			success: function(res){
				
				$(this).data('social_del_term', false);
				$('#dialog-load').dialog('close');
				$('.dialog-overlay').fadeOut();
				
				if( res !== 0 && res === 'success' ){
					$(parent).fadeOut().delay(1).queue(function() { $(this).remove(); });
				}else if(res === 'failed'){
					alert('Failed to complete the action');
				}else{
					alert('Please Login to complete this action');
				}
				
			},
		});
	});
	
	
	$('.del_video, .del_audio').on('click', this, function(){
		
		
		if( !confirm( 'Are you really want to delete?')) {return;}
		
		if( $(this).data('social_del_video') === true) {return;}
		$(this).data('social_del_video', true);
		
		var id = $(this).parent().attr('data-id');
		var data_type = $(this).attr('class');
		var parent = $(this).parents('.vid-box:first');
		var vid_type = '';
		
		if( id ){
			$('.dialog-overlay').fadeIn();
			$('#dialog-load').dialog('open');
		}
		
		switch( data_type )
		{
			case 'del_video':
				vid_type = 'wpnukes_videos';
			break;
			case 'del_audio':
				vid_type = 'wpnukes_audios';
			break;
		}
		
		if( vid_type === '' ){
			alert("Please choose a valid video");
			return;
		}
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: 'action=users_ajax_callback&subaction=del_video&type='+vid_type+'&data_id='+id,
			success: function(res){
				
				$(this).data('social_del_video', false);
				$('#dialog-load').dialog('close');
				$('.dialog-overlay').fadeOut();
				if( res !== 0 && res === 'success' ){
					$(parent).fadeOut().delay(1).queue(function() { $(this).remove(); });
				}else if(res === 'failed'){
					alert('Failed to complete the action');
				}else{
					alert('Please Login to complete this action');
					
				}
				
			},
		});
	});
	
	$('.snap a.close').live('click', function(){
		
		var parent = $(this).parents('.clearfix');
		$('input:first', (parent).prev('.file_upload')).val('');
		$(this).parent('div.snap').remove();
		return false;
	});
	
	//$('.video-thumb .mejs-container').css('position', 'absolute');
	//$('.video-thumb .mejs-container').css('display', 'none');
	
	
	
});