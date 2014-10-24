var $_fw = (undefined !== $_fw && typeof($_fw) === 'object') ? $_fw : {};
var core = '';
(function ($) {
	"use strict";
	
	$_fw.core = core = {
			mydom : '',
			uploaded_file : '',
			upload_name : '',
			parameter : function(name){
					return new decodeURI( (new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
				},
				
			obj_count : function(Obj){
					var c = 0;
					$.each(Obj, function(k, v){
						c++;
					});
					return c;
				},
			_default : function(){
					core.fw_submit_form();
					//core.nav_request();
					core.media_upload();
					$('.nuke-color-field').wpColorPicker();
					$(".form_style").jqTransform({imgPath:"/includes/views/js/img/"});
					$('.control-group').on('click', 'a#google_font_update', function(){
						core.google_fonts($(this));
					});
					setTimeout(function() {
						core.content_wrap();
					}, 800 );

			},
			fw_submit_form : function(){
						
						//var $ = jQuery;
						$('#fw_form a.fw_submit').click(function(e){
							e.preventDefault();
					
							core.overlay(true); //show overlay
							var newloc = $('#fw_form').attr('action');
							
							var data = new FormData($('#fw_form')[0]);
							newloc = ( newloc.indexOf('ajax=true') > 0 ) ? newloc : newloc+'&ajax=true';
							
							$.ajax({
								url: newloc,
								type: "POST",
								data: data,
								processData: false,
								contentType: false,
								success: function ( response ) {
									$('article.tb-builder-content').html(response);
									core.overlay(false); // hide overlay
									$('html, body').animate({ scrollTop: 0 }, "slow");
									core._default();
									//core.nav_request();
								}
							});
							return false;
						});
				},
			nav_request : function(){
						//navigation ajax requrest
						$('aside ul.tb-nav li a, #fw_cancel').click(function(e){
							e.preventDefault();
							var href = $(this).attr('href');
							if( href.indexOf('http') < 0 && $(this).attr('id') !== 'fw_cancel') {return;}
							core.overlay(true);
							href = ( href.indexOf('ajax=true') > 0 ) ? href : href+'&ajax=true';
							
							$.get(href, function(data){
								$('article.tb-builder-content').html(data);
								core.overlay(false);
								core._default();

							});
							return false;
						});
			},
			overlay : function(show){
				
					//var $ = jQuery;
					if(show === true ){
						$('.overlay-wrap').css('display', 'block');
						$('.loading-icon').css('display', 'block');
					}else{
						$('.overlay-wrap').css('display', 'none');
						$('.loading-icon').css('display', 'none');
					}
				},
			media_upload : function(){
					$('#fw_form').on('click', '.upload_image', function(){
						core.mydom = this;
						tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
						return false;
					});
			},
			fw_edit_sidebar : function(){
					$('.FW_create_sidebar').live('click', function(e){
						e.preventDefault();
						var value = $(this).prev().val();
						if( value !== '' )
						{
							var tr = '<tr><td>'+value+'</td><td class="action-icon"><a href="#" class="icon-config FW_edit_sidebar"></a><a href="#" class="icon-trash FW_del_sdiebar"></a></td><input type="hidden" value="'+value+'" name="DYNAMIC[create_sidebar][]"></tr>';
							$('table.table tbody').append(tr);
							$(this).prev().val('');
							$('html, body').animate({
									scrollTop: $('table.table tbody tr:last').offset().top
							}, 2000);
						}
						else {alert('Please enter sidebar name');}

						return false;
					});
					
					$('.FW_del_sdiebar').live('click', function(e){
						e.preventDefault();
						if( confirm( 'Are you sure to delete?' ) ) 
						{
							$(this).parents('tr').remove();
						}
						return false;
					});
					
					$('.FW_edit_sidebar').live('click', function(e){
						e.preventDefault();

						var value = $(this).parent().next().val();
						
						if( value !== '' && value !== undefined )
						{
							$('input', '#sidebar_field').val(value);
							$('a', '#sidebar_field').removeClass('FW_create_sidebar');
							$('a', '#sidebar_field').addClass('FW_update_sidebar');
							$('a', '#sidebar_field').attr('data-id', value);
						}

						return false;
					});
					
					$('.FW_update_sidebar').live('click', function(e){
						e.preventDefault();

						var value = $(this).prev().val();
						var prev = $(this).attr('data-id');
						
						if( value !== '' && value !== undefined )
						{
							var field = $('input[value="'+prev+'"]');
							$(field).parent().children('td:first-child').text(value);
							$(field).val(value);
							
							$('a', '#sidebar_field').removeClass('FW_update_sidebar');
							$('a', '#sidebar_field').addClass('FW_create_sidebar');
							$('a', '#sidebar_field').attr('data-id', '');
							$('input', '#sidebar_field').val('');
						}

						return false;
					});
			},
			google_fonts: function(event){
					var parent = $(event).parents('.controls');
					var apikey = $('input#font_api_key', parent).val();
					if( apikey !== '' )
					{
						core.overlay(true); // show overlay
						$.ajax({
							url: ajaxurl+'?page=fw_theme_options',
							type: 'POST',
							data: 'action=webnukes_ajax_custom&subaction=google_fonts&apikey='+apikey,
							dataType:"json",
							success: function(res)
							{
								if( res.status === 'success'){
									
									$('.system_msg').html(res.msgs);
									core.overlay(false); // hide overlay
									$('html, body').animate({ scrollTop: 0 }, "slow");
									//core._default();
									core.nav_request();
								}
							}
						});
					}else{
						alert('Please Enter API key to fetch fonts');
					}
			},
			content_wrap: function(){
				$('article:first').removeAttr('style');
				var content_wrap = $('#content-wrap > .tb-contents-section');
				var content_height = $('article:first', content_wrap).height();
				var aside_height = $('aside:first', content_wrap).height();

				if( aside_height > content_height ){
					$('article:first', content_wrap).css('height', aside_height+'px');
				}else{
					$('article:first', content_wrap).css('height', content_height+'px');
				}
			},
		};			
})(jQuery);

jQuery(document).ready(function($) {
	
	"use strict";
	var path = null;
	var mydom = null;
	
	core.media_upload();
	
	window.send_to_editor = function(html) {
		var imgurl = $('img',html).attr('src');
		$(core.mydom).parent().find('input:first').val(imgurl);
		$(core.mydom).parents('.controls:first').find('.preview-hold img').attr('src',imgurl);
		tb_remove();
	};
	
	//Switch button
	$('#fw_form').on('click', '.btn-group .btn', function(){
			var group = $(this).parent();
			$('.btn', group).removeClass('btn-active');
			$(this).addClass('btn-active');
			$(group).parent().find('input[type=hidden]').val($(this).attr('id')).trigger('change');
		});
		
	$('h3.clickable').click(function()
	{
		window.location = $('a.optionlink', this).attr('href');
	});

	

	var count = jQuery('h3[id^="imactive"]').attr('id');
	if(count) {
		var selected = parseInt(count.substr(9));
	}
	
	
	$('#close_message').live('click', function(){
			$(this).parent('div').slideUp('slow');
			$(this).remove();
		});
				
	$(".cb-enable").live('click',function(){
		var parent = jQuery(this).parents('p.switch');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('input', parent).val($(this).attr('id'));
	});
	$(".cb-disable").live('click',function(){
		var parent = $(this).parents('p.switch');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('input', parent).val($(this).attr('id'));
	});
		
	$('#importData').click(function(e)
	{
		if (!confirm("Attention: the data import function will replace all of your existing \nadmin panel and widgets settings to default. \n\nstill want to continue?")) {e.preventDefault();}
	});
	

	/*** Settings Bar scroll function ***/
	$(window).scroll(function(){
	
			if ($(this).scrollTop() > 100) {
				$('#wpnukes_wrap .settings-bar').addClass('fixed-settings-bar');
			} else {
				$('#wpnukes_wrap .settings-bar').removeClass('fixed-settings-bar');
			}
	});
	
	core._default();
	core.nav_request();
	core.fw_edit_sidebar();
	
	
});


