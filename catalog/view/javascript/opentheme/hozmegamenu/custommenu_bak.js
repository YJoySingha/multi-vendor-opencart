$(document).ready(function(){
    $(".pt_menu_link ul li").each(function(){
        var url = document.URL;
        $(".pt_menu_link ul li a").removeClass("act");
        $('.pt_menu_link ul li a[href="'+url+'"]').addClass('act');
    });
	
	$(".pt_menu_link a").each(function(){
		var url = document.URL;
		var href = $(this).attr('href');
		
        var url1 = url.split("/");
		if(url1[4] && url1[4]!=""){
			url3 = url1[4].split("&");
			url2 = "/" + url1[3] + "/" + url3[0];
		}
		else url2 = "/" + url1[3];
        $(".pt_menu_link a").removeClass("act");
		if(url.indexOf(href) != -1) {
			$(this).parent().parent().addClass('act');
		}
        $('.pt_menu_link a[href="'+url2+'"]').parent().parent().addClass('act');
        $('.pt_menu_link a[href="'+url+'"]').parent().parent().addClass('act');
    });
    
    $('.pt_menu_no_child').hover(function(){
        $(this).addClass("active");
    },function(){
        $(this).removeClass("active");
    });
    
    $('.pt_menu').hover(function(){
        if($(this).attr("id") != "pt_menu_link"){
            $(this).addClass("active");
        }
    },function(){
        $(this).removeClass("active");
    });
    
    $('.pt_menu').hover(function(){
       /*show popup to calculate*/
       $(this).find('.popup').css('display','inline-block');
       
       /* get total padding + border + margin of the popup */
       var extraWidth       = 0
       var wrapWidthPopup   = $(this).find('.popup').outerWidth(true); /*include padding + margin + border*/
       var actualWidthPopup = $(this).find('.popup').width(); /*no padding, margin, border*/
       extraWidth           = wrapWidthPopup - actualWidthPopup;    
       
       /* calculate new width of the popup*/
       var widthblock1 = $(this).find('.popup .block1').outerWidth(true);
       var widthblock2 = $(this).find('.popup .block2').outerWidth(true);
       var new_width_popup = 0;
       if(widthblock1 && !widthblock2){
           new_width_popup = widthblock1;
       }
       if(!widthblock1 && widthblock2){
           new_width_popup = widthblock2;
       }
       if(widthblock1 && widthblock2){
            
                new_width_popup = widthblock1;
           
       }
       var new_outer_width_popup = new_width_popup + extraWidth;
       
       /*define top and left of the popup*/
       var wraper = $('#pt_custommenu');
       var wWraper = wraper.outerWidth();
       var posWraper = wraper.offset();
       var pos = $(this).offset();
       
       var xTop = pos.top - posWraper.top + $(this).find(".parentMenu > a").outerHeight(true);
	   
       var xLeft = pos.left - posWraper.left;
       if ((xLeft + new_outer_width_popup) > wWraper) xLeft = wWraper - new_outer_width_popup;

       $(this).find('.popup').css('top',xTop);
       // $(this).find('.popup').css('top','100%');
	   if(xLeft<0){
			$(this).find('.popup').css('left',0);   
	   } else {
			$(this).find('.popup').css('left',xLeft);
	   }
       
       
       /*set new width popup*/
       $(this).find('.popup').css('width',new_width_popup);
       $(this).find('.popup .block1').css('width',new_width_popup);
       
       /*return popup display none*/
       $(this).find('.popup').css('display','none');
       
       /*show hide popup*/
       $(this).find('.popup').stop(true,true).show();
    },function(){
       $(this).find('.popup').stop(true,true).hide();
    })
});