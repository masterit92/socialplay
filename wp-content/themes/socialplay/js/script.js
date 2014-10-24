/*
	*
	* Socialplay v.1.0.0
	* Copyright 2014 extracoding.com
	* 
*/

jQuery(document).ready( function($){
	"use strict";
	
	$('.video-thumb').mouseover( function(){
		var thumbHeight	= $('img.thumb', this).height() -20;
		$('.thumb-hover span',this).css({'height':thumbHeight});
	});
	
	$('#dialog-uploadVideo,#dialog-editProfile, #dialog-addPlaylist, #dialog-uploadVideo, #dialog-overlay').dialog({
		
		autoOpen: false,
		draggable: false,

		show:
		{
			effect: "drop",
			direction: "left",
			duration: 300
		},
		
		hide:
		{
			effect: "drop",
			direction: "right",
			duration: 300
		}
	});
	
	$('#add_video, #upload_video, #upload_audio, #add_playlist, #add_channel, #add_audio, #add_album').on('click', this, function(e){
		
		e.preventDefault();
		
		$(".dialog-overlay").fadeIn();
		$( "#dialog-load" ).dialog("open");
		
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: "action=users_ajax_callback&subaction=user_profile_form&type="+$(this).attr('id'),
			
			success: function(res){
				
				$('#dialog-uploadVideo').html(res);
				$( "#dialog-load" ).dialog('close');
				$( "#dialog-uploadVideo" ).dialog('open');
			},
		});
	});
	
	var fw_bar = $('.bar');
	var fw_percent = $('.percent');
	var fw_status = $('#status');
	
	$('body').on('submit', '#fws_upload_audio, #fws_upload_video', function(e){
		e.preventDefault();
		
		$('.file-upload-status', this).remove();

		var thisform = this;
		
		/*
		if( ! $('#fileUpload', this).val())
		{
			$('label:first').after('<span class="file-upload-status alert-error">Please select a file to upload.</span>');
			return false;
		}*/

		fw_bar = $( '.bar' );
		fw_percent = $('.percent' );
		fw_status = $( '#status' );
		
		$(fw_bar).width('0%');
		$(fw_percent).text('0%');
		$('#progress-data').fadeIn('slow');
		
		var elements = [];
		var a = $(this).formToArray('', elements);
		var formdata = new FormData(thisform);

		if( fw_bar.length === 0 ){
			fw_bar = $( '.bar' );
			fw_percent = $('.percent' );
			fw_status = $( '#status' );
		}
		
		$.ajax({
			url: ajaxurl+'?action=users_ajax_callback&subaction='+$(this).attr('id').replace(/^fws_|fw_/, ''),
			data: formdata,
			processData: false,
			contentType: false, 
			cache:false,
			type: 'POST',
			xhr: function(){
				
				var xhr = $.ajaxSettings.xhr();

                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position; /*event.position is deprecated*/
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
						
						var percentVal = percent + '%';

						fw_bar.css('width', percentVal);
						
						fw_percent.html(percentVal);
						
                    }, false);
                }
                return xhr;
			},
			
			beforeSend: function() {
				fw_status.empty();
				var percentVal = '0%';
				fw_percent.html(percentVal);
			},
			
			success: function(res) {
				var percentVal = '100%';
				console.log(res);
				fw_percent.html(percentVal);
			},
			
			complete: function(xhr) {
				
				console.log(xhr);
				if(xhr.responseText.match(/<div/))
				{
					$(thisform).parent().html(xhr.responseText);
				}else $('label:first').after('<span class="file-upload-status alert-error">'+xhr.responseText+'</span>');
				
				$('#progress-data').fadeOut('slow');
			}
		}); 

	});
	
	(function() {
		"use strict";
	 
		var video, $output;
		var scale = 0.25;
	 
		var initialize = function() {
			$output = $(".video-capture-container span.images");
			video = $("#video").get(0);
			$("button.capture-video").live('click', captureImage);                
		};
	 
		var captureImage = function() {
			
			video = $("#video").get(0);
			$output = $(".video-capture-container span.images");

			var canvas = document.createElement("canvas");
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;

			canvas.getContext('2d')
				  .drawImage(video, 0, 0, canvas.width, canvas.height);
	 
			var img = document.createElement("img");
			img.src = canvas.toDataURL();
			
			$output.html(img);
			var video_canvas_image = $('input[name="video_canvas_image"]');
			if( video_canvas_image.length === 0 ){
				$('form').prepend('<input type="hidden" name="video_canvas_image" value="" />');
				video_canvas_image = $('input[name="video_canvas_image"]');
			}
			video_canvas_image.val(canvas.toDataURL());
		};
	 
		$(initialize);            
	 
	}());
	
	$('.ui-dialog .ui-dialog-titlebar-close').click( function(){
		$('.dialog-overlay').fadeOut();
	});
				
	/*** hide elements on ESCAPE ***/
    $(document).bind('keydown', function(e) {
		if (e.keyCode === 27) {
			$(".dialog-overlay").fadeOut();
		}
    });

	/*** Main Menu ***/		
	$('.open-menu').click( function(){
		$('.menu-bar').slideToggle();
	});

	function mobileMenu (){
		
		var resolution = $(window).width();
		
		if(resolution <= 767){

			$('.menu-bar').addClass('menu-mobile');
			$('.menu-bar').css('display', 'none');
			
			$(".menu > li").each(function(e){
				if($(this).has("ul").length){

						if( $(this).class !== 'menu-expand'){
							$(this).addClass('menu-expand');
						}

						$(this).click( function(){
								$(this).children('ul').slideToggle();
								$(this).toggleClass('menu-collapse');
								e.preventDefault();
							});
						}
				});
		} // if condition ends 
		else {
			$('.menu-bar').removeClass('menu-mobile');
			$('.menu > li').removeClass('menu-expand menu-collapse');
			$('.menu > li').children('ul').hide().removeAttr("style");
		} // else condition ends

	} /*** mobileMenu function ends **/
	
	$(window).resize( function(){
		mobileMenu ();
	}); /*** window resize function ends **/
	
	mobileMenu ();

	/*** convert tp-menu into select **/
	$("<select />").appendTo(".tp-bar .container");
	
	$("<option />", {
		"selected": "selected",
		"value"   : "",
		"text"    : "Categories"
	}).appendTo(".tp-bar .container select");
	
	$(".tp-bar .container ul.tp-links li a").each(function() {
		var el = $(this);
		$("<option />", {
			"value"   : el.attr("href"),
			"text"    : el.text()
		}).appendTo(".tp-bar .container select");
	});
	
	$(".tp-bar .container select").change(function() {
		window.location = $(this).find("option:selected").val();
	}); /*** convert tp-menu ends ***/

	/*** Alert script ***/
	$('.alert a.close').click( function(e){
		$(this).parents('.alert').fadeOut();
		e.preventDefault();
	});

	/*** Filters Block script ***/
	$('.filters a.btn-filter').click( function(e){
		$('.filters .filter-content').slideToggle();
		$(this).toggleClass('selected');
		e.preventDefault();
	});
	
	/*** Dropdown box ***/
	$('.dropdown > a').click (function(){
		$(this).siblings('ul.drop-menu').toggle();
		$(this).toggleClass('drop-select');
	});
		
	/*** tooltip ***/
	$(function() {
		$('.tooltip').tooltip({
			track: false,
			position: {
				my: "center bottom",
				at: "center top",
			}
		});
	});
		

	/*** News Ticker Script ***/		
	if( $('#ticker').length !== 0 )
	{
		$('#ticker').totemticker({
			row_height	:	'36px',
			next		:	'.news-strip .down',
			previous	:	'.news-strip .up',
			mousestop	:	true,
		});
	}


	$('section.video-thumb').on('click', 'a', function(e){
		e.preventDefault();
		$(this).next('iframe').css('display','block');
		$('.mejs-container', $(this).parent('.video-thumb')).css('display','block');
		var video = document.getElementsByTagName('video')[0];
		if(undefined !== video)
		video.play();
		$('html, body').animate({ scrollTop: $('#page-single').offset().top }, 'slow');
		$(this).remove();
	});

	/*** Open video on clicking video thumb ***/	
	$('#home_load_more').click(function(e) {
		e.preventDefault();
		if( $(this).data('home_load_more') === false ) { return;}
		$(this).data('home_load_more', false);
		$('.icon-refresh', this).css('visibility', 'visible');
		var datapage = $(this).attr('data-page');
		var thisdata = this;
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: 'action=_ajax_callback&subaction=home_load_more&paged='+datapage,
			success: function(res){
				if( $.trim(res) !== '' )
				{
					$(thisdata).data('home_load_more', true);
					$('#page .vid-row').append(res);
					$(thisdata).attr('data-page', (parseInt(datapage) + 1));
					$('.video-thumb').mouseover( function(){
						var thumbHeight	= $('img.thumb', this).height() -20;
						$('.thumb-hover span',this).css({'height':thumbHeight});
					});
				}
				$('.icon-refresh').css('visibility', 'hidden');
			},
		});
		
	});

	if($("a[data-rel^='prettyPhoto']").length !== 0){
		$("a[data-rel^='prettyPhoto']").prettyPhoto({
			animation_speed:'normal',
			slideshow:3000,
			autoplay_slideshow: false
		});
	}
	
}); // document ready function ends //

