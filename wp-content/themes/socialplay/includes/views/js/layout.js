jQuery(document).ready(function($){

    "use strict";

    var layout = 
    {
        widget : $('#layout-widget'),
        editor : $('#layout-dialog').dialog({autoOpen : false, modal : true, show : {effect: "drop", direction: "up", duration: 300}, hide : {effect: "drop", direction: "down", duration: 300}}),
        send_to_editor : window.send_to_editor,
        focused : null,
        input : null,
		counter: 0,
        init : function(){
            
            $('ul.layouts, ul.patterns').on('click', 'li', layout.selection);
            $('#layout-ctrls a').on('click', layout.controls);
            
            $('#layout-content').on('click', '.plus-size, .minus-size', layout.size);
            $('#layout-content').on('click', '.remove-widge', layout.remove);
             
            $('#layout-content').on('click', '.edit-widge', layout.edit);
            
            $('#layout-content').sortable({
                    placeholder: "ui-state-highlight", handle: ".move-widge",
                    change:function( event, ui ){
                        $.each($('#layout-content div.dialog-container'), function(k){                            
                            $.each($(':input', $(this)), function(){
                                $(this).attr('name', $(this).attr('name').replace(/\]\[\d+\]/, ']['+k+']'));
                            });
                        });
                    }
                });
            

			/*$.each(field_settings, function(index, array){

				if( array !== undefined && array !== '' && index !== 'structure' && index !== 'sidebars' )
				{
					layout.build(array.id, index, array);
					layout.counter = parseInt(layout.counter) + 1;
				}
		
			});*/
			layout.structure(struct, sidebars_selected);
			
            $('.ui-dialog').on('click', '.wp-layout-gallery', function(){
                send_to_editor = window.send_to_editor;
                layout.input = $(this).parent().find(':input:first');
                layout.focused = layout.input.val();
                layout.focused = (layout.focused) ? layout.focused.split(', ') : [];

                window.send_to_editor = function(HTML){
                
                    /** Check for multiple rows */
					var rows = HTML.split("\n");
					
					layout.process_img(rows);
                    window.send_to_editor = send_to_editor;
                };
            });
        },
        
        remove : function(e){
            e.preventDefault();
            $(this).parents('div[class^="pb-col"]:first').remove();
        },
        
        edit : function(e){
            e.preventDefault();
                
                var parent = $(this).parents('.pb-widget:first').find('.layout-dialog-model');
                var html = $(parent).find('.dialog-container');
                var saved = false;

				
                layout.editor.dialog({title : $(this).parents('.pb-widget:first').find('.hold-up i').text(), autoOpen : true,
                        open : function(){
                            $(this).html('');
                            $(html).appendTo(this);
							$(".form_style").jqTransform({imgPath:"/includes/views/js/img/"});
                        },
                        
                        close : function(){
                                $(this).children().appendTo(parent);
                                
								$(':input', parent).each(function(){
									if(saved) {this.defaultValue = this.value;}
									else {this.value = this.defaultValue;}
								});
                            },
                        
                        buttons : {
								Save : {'class':'btn btn-green', 'text' : 'Save', 'click' : function(){
									saved = true;
									$(this).dialog("close"); 
								}
						},
						Cancel : {class:'btn btn-red', 'text': 'Cancel', 'click' :function(){$(this).dialog("close");}}
                        }
                        });
        },
        
        size : function(e){
            e.preventDefault();
            
            var parent = $(this).parents('div[class^="pb-col"]:first');
            var type = $(this).attr('class');
            var settings = $('.layout-dialog-model > .dialog-container > .widgetSettings', parent);
            var ratio = $('.set_cols', settings).val();
            var id = $('.set_id').val();
            var sizes = $('.hold-up > span', parent);
            if(type === 'plus-size' && ratio < default_settings[id]['max-col']){
                
                ratio = parseInt(ratio) + 1;
                var Class = "pb-col"+(ratio);
                $(parent).attr('class', Class);
                $(sizes).text((ratio)+'/4');
                
                $('.set_cols', settings).val(ratio);
            }else if(type === 'minus-size')
            {
                if(ratio > default_settings[id]['min-col']){
                    ratio = parseInt(ratio) - 1;
                    var Class = "pb-col"+(ratio);
                    $(parent).attr('class', Class);
                    $(sizes).text((ratio)+'/4');
                    $('.set_cols', settings).val(ratio);
                }
            }            
        },

        selection : function(){
            
			$(this).addClass('chosen').siblings().removeClass('chosen');
            
            /** Get the current style */
            var style = $(this).attr('class');
            
			
			/** Change the layout structure */
            style = style.split(' '); /** TEMPORARY CODE FOR DEVELOPMENT ONLY */
            
			$('#l-structure').val(style[0]);
           switch(style[0])
           {
                case "col-full" : 
                    layout.structure(style[0], {});
                break;
                case "col-right" :
                    layout.structure(style[0], {right:'sidebar'});
                break;
                
                case "col-left" : 
                    layout.structure(style[0], {left:'sidebar'});
                break;
                
                case "col-left2" :
                    layout.structure(style[0], {left:'sidebar',right:'sidebar'});
                break;
                
                case "col-right2" :
                    layout.structure(style[0], {right:'sidebar',left:'sidebar'});
                break;
                case "col-both" :
                    layout.structure(style[0], {left:'sidebar',right:'sidebare'});
                break;
           }
        },

        controls : function(){
			
            var ctrl = $(this).children('i:first').attr('class').substr(5);

            if(undefined !== default_settings[ctrl])
            {
                var settings =  $.extend({'min-col':1,'max_col':4,'cols':4,'title':'','delete':true,'edit':true, 'id':ctrl, 'counter':$('#layout-content > div').length}, default_settings[ctrl]);

                var widgetSettings = '';
                
                $.each(settings, function(k, v){
                    widgetSettings += '<input type="hidden" name="layout['+settings.counter+']['+k+']" value="'+v+'" class="set_'+k+'" />';
                });

                settings["widgetSettings"] = widgetSettings;
                //$.template("widgetFields", $('#layout-'+ctrl+'-style'));
                $.tmpl(layout.widget, settings).appendTo('#layout-content');
				
				var id = settings.id+'__'+settings.counter;
				
				$.tmpl($('#layout-'+ctrl+'-style'), settings).appendTo( $('#'+ id + ' .dialog-container'));
            }
        },
		
        build: function(ctrl, index, values){
			
		
			var def = values.data;
			delete values.data;
			var settings =  $.extend({'min-col':1,'max_col':4,'title':'','delete':true,'edit':true, 'id':ctrl, 'counter':index, 'cols':values.cols}, values);

			settings = $.extend(settings, def);
			
			var widgetSettings = '';

			$.each(settings, function(k, v){

				widgetSettings += '<input type="hidden" name="layout['+settings.counter+']['+k+']" value="'+layout.htmlEntities(v)+'" class="set_'+k+'" />';
			});
			
			settings["widgetSettings"] = widgetSettings;
			$.template("widgetFields", $('#layout-'+ctrl+'-style'));
			
			$.tmpl(layout.widget, settings).appendTo('#layout-content');
			
		},
        structure : function(name, pattern)
        {
            $('#layout-structure > section.pb-section').remove();

            var html = '';

            $.each(pattern, function(k, v){
				html = '<section class="pb-section"><div class="pb-heading">'+k+' Sidebar</div><div class="pb-sect-box"><select name="layout[sidebars]['+k+']">';
				$.each(sidebars, function( sk, sv ){
					if( v === sk ) {html += '<option value="'+sk+'" selected="selected">'+sv+'</option>';}
					else {html += '<option value="'+sk+'">'+sv+'</option>';}
				});
				html += '</select></div></section>';
                $('#layout-structure').append(html);

            });
        },
        
        
    add_image : function(data)
	{
		var info = $.extend({'id':'','img':'','alt':'','caption':''}, data);
		info.caption = (info.caption) ? info.caption : info.alt;
		
		layout.focused.push(info.id);
	},
	
	process_img : function(rows)
	{
		$.each(rows, function(k, v){
			if($.trim(v)){
				if ( v.indexOf('[gallery') !== -1 ) {
					$('#gallery_spinner').css('display','block');
					var ids = v.match(/ids=['"](.*?)['"]/);

				}else{

					var caption = '', html = $('<div>'+v+'</div>');

					if( v.indexOf('[caption') !== -1 ){
						var mycap = v.replace(/<[^>]*>/g,'').match(/\[[^]*\](.*?)\[/);
						caption = mycap[1];
					}

					var id = $('img', html).attr('class').match(/wp-image-(\d+)/);
					layout.add_image({'img':$('img', html).attr('src'), 'alt': $('img', html).attr('alt'), 'caption': caption, 'id':id[1]});
				}
			}
		});
		
		var uniqueIDs = [];
        $.each(layout.focused, function(i, el){
			if($.inArray(el, uniqueIDs) === -1) {uniqueIDs.push(el);}
        });
        
		$(layout.input).val(uniqueIDs.join(', '));
	},
	htmlEntities: function(str) {
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}
};
	
	layout.init();
	
});