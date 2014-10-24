jQuery(document).ready(function($) {
	
	"use strict";
	$('ul.layouts li, ul.patterns li').live('click', function(){
		$(this).siblings().removeClass('chosen');
		$('input[type="radio"]', $(this).siblings() ).removeAttr('checked');
		$('input[type="radio"]', $(this) ).attr('checked', true);
		$(this).addClass('chosen');
		
	});

	$('.alert  .close-alert').click ( function() {
			var alrt = $(this).parents('.alert');
			$(alrt, this).fadeOut();
			return false;
	});

	$('.tb-block .close-block').click ( function() {
			var tbBlock = $(this).parents('.tb-block');
			$(tbBlock, this).fadeOut();
			return false;
	}); 

	$('.icon-trash').click ( function() {
			var row = $(this).parents('tr');
			$(row, this).fadeOut();
			return false;
	});

	/*$('.close-preview').click ( function() {
			var preview = $(this).siblings('.preview-hold');
			$('img', preview).fadeOut();
			return false;
	});*/

	$('.close-preview').live('click', function() {
			var preview = $(this).siblings('.preview-hold');
			var upload_field = $(this).parent('.upload-preview');
			upload_field = $(upload_field).siblings('#imageupload');
			upload_field = $(upload_field).children('input');
			$('img', preview).fadeOut();
			$(upload_field).val('');
			
			return false;
	});
	
	/*** Sidebar Clopse ***/
	$('.tb-nav li').click(function(){
		$('ul', this).slideToggle();
		setTimeout(function() {
			$('article:first').removeAttr('style');
			var content_wrap = $('#content-wrap > .tb-contents-section');
			var content_height = $('article:first', content_wrap).height();
			var aside_height = $('aside:first', content_wrap).height();
			if( aside_height > content_height ){
				$('article:first', content_wrap).css('height', aside_height+'px');
			}
			
		}, 500);
		
		
		return false;
	});
	
	/*** Data Picker ***/
	$(function() {
   $( "#datepicker" ).datepicker();
  });



	/*** Dialog Script ***/
	$( "#dialog-modal" ).dialog({
		autoOpen: false,
		show: {
			effect: "drop",
			direction: "up",
			duration: 300
		},
		hide: {
			effect: "drop",
			direction: "down",
			duration: 300
		}
	});
		/*** Trigger Dialog ***/
		$(this).on('click', '.edit-widge', function(e){
			$('.dialog-overlay').fadeIn();
			$( "#dialog-modal" ).dialog('open');
			e.preventDefault();
		});


		$('.edit-widge').click( function(){
			$('.dialog-overlay').fadeIn();
			$( "#dialog-modal" ).dialog('open');
		});


		$('.ui-dialog .ui-dialog-titlebar-close').click( function(){
			$('.dialog-overlay').fadeOut();
		});


	/*** Remove Widget ***/
	$(this).on('click', '.remove-widge', function(e){
		$(this).parents('div[class^="pb-col"]:first').slideUp({'height':'0'},50).delay(1).queue(function() { $(this).remove(); });
		e.preventDefault();
	});

	$('.availed-list').on('click', '.addRem-widge', function(e){
			e.preventDefault();
			$(this).parents('li:first').remove();
	});
	
	
	/*** Sortable Widgets ***/
	$('.pb-col-container').sortable({
		placeholder: "ui-state-highlight",
		handle: ".move-widge"
	});


	/*** clone / create widget ***/
	$('.available-list li .addRem-widge').click( function(){
		var widget = $(this).parents('li:first');
		$(widget).clone().appendTo('.availed-list');
		return false;
	});
	
	$('.availed-list').sortable({
		placeholder: "ui-state-highlight"
	});
	
	/*** clone widget in the center area ***/
	$('.pb-nav li a').click( function(){

		var cloning = $('.pb-widget').first().clone().html();
		var pbcol = "<div class='pb-col4'><div class='pb-widget'>";
		var pbColEnd = "</div></div>";

		var attach = pbcol+cloning+pbColEnd;
		$(attach).appendTo('.pb-col-container');
		
		return false;
	});
	
	
	

});