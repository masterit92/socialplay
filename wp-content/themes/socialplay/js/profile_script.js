/*!
 * Socialplay v.1.0.0
 * Copyright 2012 wpnukes.com
 * 
 */

jQuery(document).ready( function($){

	"use strict";
	$('.video-thumb').mouseover( function(){
		var thumbHeight	= $('img.thumb', this).height() -20;
		$('.thumb-hover span',this).css({'height':thumbHeight});
	});
	
	/*** Open video on clicking video thumb ***/
	$('.vid-play').click( function(e){
		$(this).children('iframe').css('display','block');
		e.preventDefault();
	});
	
	$('.tabbable').tabs();
	

	
	/*** Dialog Script ***/
	$( "#dialog-load, #dialog-editProfile, #dialog-addPlaylist, #dialog-uploadVideo, #dialog-advancedSettings" ).dialog({
		autoOpen: false,
		draggable: false,
		show: {
			effect: "drop",
			direction: "left",
			duration: 300
		},
		hide: {
			effect: "drop",
			direction: "right",
			duration: 300
		}
	});
		
	
}); // document ready function ends //