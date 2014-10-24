(function($){
	
	var wpnukescp = {
			body_bg:function(hex){
					$('body').css('background-color','#' + hex);
				},
				
			heading_bg:function(hex){
					$('.heading, #advsearch').css({'background':'#' + hex});
					$('#letwelcareknow').css({'color':'#' + hex});
				},

			heading_text:function(hex){
					$('.heading, .heading span a').css('color','#'+hex);
				},

			navigation_text:function(hex){
					$('#menu li > a:first-child').css({'color': '#' + hex});
				},

			navigation_bg:function(hex){
					/* Main Menu Styling */
					$('.menu').css({'background':'#' + hex});
					$('.main-menu li ul').css({'background-color': '#' + hex });
					$('.main-menu ul li').mouseover( function(){
						$(this).css('background-color', '#' + hex);
						}).mouseout(function(){
							$(this).css('background-color', 'transparent');
						});
					$('.main-menu ul li ul a').hover( function(){
						$(this).css({'background-color': '#' + hex, 'filter': 'alpha(opacity=50)', '-moz-opacity':'0.50', '-khtml-opacity': '0.50', 'opacity': '0.50'});
						}, function(){
								$(this).css({'background-color': 'transparent', 'filter': 'alpha(opacity=100)', '-moz-opacity':'1', '-khtml-opacity': '1', 'opacity': '1'});
							})


				},
				
			title:function(hex){
					$('h3, h3 a, h2, h2 a, h1, h1 a, h4, h4 a, .helpus a, ul.gallery .short_details h4, .contents h3, .box_heading, .comment_box strong, .top-menu #contactbox h6').css({'color':'#' + hex});
					$('h3 a, h2 a, h4 a, .helpus a').hover( function(){
					 $(this).css({'filter': 'alpha(opacity=50)', '-moz-opacity':'0.50', '-khtml-opacity': '0.50', 'opacity': '0.50'});
					},function(){
						$(this).css({'color':'#' + hex, 'filter': 'alpha(opacity=100)', '-moz-opacity':'1', '-khtml-opacity': '1', 'opacity': '1'});
					});
					
					$('ul.gallery li a').hover( function()
					{
						$('ul.gallery .short_details h4').css({'filter': 'alpha(opacity=50)', '-moz-opacity':'0.50', '-khtml-opacity': '0.50', 'opacity': '0.50'});
					},function(){
						$('ul.gallery .short_details h4').css({'color':'#' + hex, 'filter': 'alpha(opacity=100)', '-moz-opacity':'1', '-khtml-opacity': '1', 'opacity': '1'});
					});
				},
				
			content_text:function(hex){
					$('body, .events p').css({'color':'#'+hex});
				},
			home_slider:function(hex){
					$('.banner_title').css('background-color', '#' + hex);
					$('.nivo-caption .banner_small_heading, .nivo-caption h3, .heading_gray, .heading_blue').css('color', '#' + hex);
					$('.heading_gray').css({'color': '#' + hex, 'filter': 'alpha(opacity=55)', '-moz-opacity':'0.55', '-khtml-opacity': '0.55', 'opacity': '0.55'});
					$('.nivo-caption a').css({'color': '#' + hex});
				},
			banner_color:function(hex){
					$('.page-banner h1, .page-banner p').css('color', '#' + hex);
				},
				
			/* Home Tabs Script 
			////////////////////////////// */	
			tabBG:function(hex){
					$('.boxBody').css('background-color', '#' + hex);
					$('ul#tabMenu li, .boxBody').css('border-color', '#' + hex);
				},
			tab_dropDown_bg:function(hex){
					$('.col-1 .block .dropdown, .col-2 .block .dropdown, .col-3 .block .dropdown, .col-4 .block .dropdown').css({'background-color': '#' + hex, 'background-image':'none', 'border-width':'0'});
					$('.col-1 .block .dropdownlist, .col-2 .block .dropdownlist, .col-3 .block .dropdownlist, .col-4 .block .dropdownlist').css({'background-color': '#' + hex});
				},
			tab_dropDown_text:function(hex){
					$('.col-1 .block .dropdown p.no-info, .col-2 .block .dropdownlist p.no-info, .col-2 .block .dropdown p.no-info, .col-3 .block .dropdownlist p.no-info, .col-4 .block .dropdown p.no-info, .col-4 .block .dropdownlist p.no-info, .col-4 .block .dropdown p.no-info, .col-4 .block .dropdownlist p.no-info').css({'color': '#' + hex});
				},
			
			tab_text_color:function(hex){
					$('ul#tabMenu li a, ul#tabMenu li').css('color', '#' + hex);
				},
			tab_content_text:function(hex){
					$('.boxBody h3, .boxBody').css('color', '#' + hex);
				},
			tab_button:function(hex){
					$('.boxBody .button').css('background-color', '#' + hex);
				},
			tab_button_text:function(hex){
					$('.boxBody .button').css('color', '#' + hex);
				},
			tab1:function(hex){
					$('.tab_1').css('background-color', '#' + hex);
					$('.tab_1 .poparr').css('border-top-color', '#' + hex);
				},
			tab2:function(hex){
					$('.tab_2').css('background-color', '#' + hex);
					$('.tab_2 .poparr').css('border-top-color', '#' + hex);
				},
			tab3:function(hex){
					$('.tab_3').css('background-color', '#' + hex);
					$('.tab_3 .poparr').css('border-top-color', '#' + hex);
				},
			tab4:function(hex){
					$('.tab_4').css('background-color', '#' + hex);
					$('.tab_4 .poparr').css('border-top-color', '#' + hex);
				},
			tab5:function(hex){
					$('.tab_5').css('background-color', '#' + hex);
					$('.tab_5 .poparr').css('border-top-color', '#' + hex);
				},
			buttons_bg:function(hex){
					$('.pagination a, #formnewsletter .submit, #signin_submit2, .signin_submit, .contact_submit, .donate_button a, a.reply, #signin_submit, .submit_form, .close img').css({'background-color': '#' + hex, 'background-image':'none', 'font':button_font_family ,'font-size':button_font_size+'px'});
				},
			buttons_text:function(hex){
					$('.pagination a, #formnewsletter .submit, #signin_submit2, #contact_submit, .donate_button a, a.reply, #signin_submit, .submit_form').css({'color': '#' + hex});
				},
			links:function(hex){
					$('.info_bottom a, .events p a, #more-galleries ul.galleries li a, .details a, .blog p a, .news p a, .general p a, .banner p a, .matter li a, .matter li p a').css('color','#' + hex);
					$('.alllink').css({'color' : '#' + hex, 'filter': 'alpha(opacity=80)', '-moz-opacity':'0.80', '-khtml-opacity': '0.80', 'opacity': '0.80'});
				},
			insurance_bg:function(hex){
					$('#insurance-companies').css({'background-color':'#'+hex});
				},
			footer_bg:function(hex){
					$('#footer, #copyrightarea').css({'background-color':'#'+hex});
				},
			footer_text:function(hex){
					$('#twiterfeed .leftside p, #footer p, #footer .block a, #copyrightarea, #copyrightarea a').css({'color':'#'+hex});
				},
			footer_title:function(hex){
					$('#footer h1, #footer h2, #footer h3').css({'color':'#'+hex});
				}
		}
		
	$(document).ready(function(){
		function componentToHex(c) {
			var hex = c.toString(16);
			return hex.length == 1 ? "0" + hex : hex;
		}
		
		function rgb(r, g, b)
		{
			return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
		}
		
	var activebox = '', mycolor = '',schemes = {blue:{body_bg:'dadce0',heading_bg:'',heading_text:'',title:'0082c0',tab_content_text:'',home_slider:'',banner_color:'',tab1:'',tab2:'',tab3:'',tab4:'',tab5:'',buttons:'',links:'',insurance_bg:'',footer_bg:''}};
	
	$('.wpnukescp').click(function(){
		activebox = $(this).attr('id');
		mycolor = eval($('div:first', this).css('background-color'));
	});
			
$('.wpnukescp').ColorPicker({
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(mycolor);
	},
	onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		if(! activebox) return;
		//$('#'+activebox).val(hex);
		$('#'+activebox+' div').css('background-color', '#' + hex);
		wpnukescp[activebox](hex);
	}
});

	/* Main Selector Script Starts 
	/////////////////////////////////////////////////////////////////////////////// */
	
		$('#main_selector').change(function(){
				var mainVal = $(this).val();
				//alert (mainVal);
				$('.cp_contnt > ul > li').css('display', 'none');
				if (mainVal == 'general'){
						$('li.general_style').slideToggle(300);
					}
				else if (mainVal == 'header'){	
						$('li.header_style').slideToggle(300);
				}
				else if (mainVal == 'home_slider'){	
						$('li.homeSlider_style').slideToggle(300);
				}
				else if (mainVal == 'banner'){	
						$('li.banner_style').slideToggle(300);
				}
				else if (mainVal == 'tabs'){	
						$('li.tabs_style').slideToggle(300);
				}
				else if (mainVal == 'navigation'){	
						$('li.navigation_style').slideToggle(300);
				}
				else if (mainVal == 'footer'){	
						$('li.footer_style').slideToggle(300);
				}
		}); // #main_selector script ends 
		$('#main_selector').trigger('change');
	}); // Document Ready Function ends 
})(jQuery);