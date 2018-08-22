
$(document).ready(function(){
    $("#pt_vmegamenu #pt_ver_menu_link ul li").each(function(){
        var url = document.URL;
        $("#pt_ver_menu_link ul li a").removeClass("act");
        $('#pt_ver_menu_link ul li a[href="'+url+'"]').addClass('act');
    }); 
    
    $('#pt_vmegamenu .pt_ver_menu_no_child').hover(function(){
        $(this).addClass("active");
    },function(){
        $(this).removeClass("active");
    })
    
    $('#pt_vmegamenu .pt_ver_menu').hover(function(){
        if($(this).attr("id") != "pt_ver_menu_link"){
            $(this).addClass("active");
        }
    },function(){
        $(this).removeClass("active");
    })
    
    $('#pt_vmegamenu .pt_ver_menu').hover(function(){
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
       var new_outer_width_popup = new_width_popup + extraWidth ;
       
       /*define top and left of the popup*/
       var wraper = $('#pt_vmegamenu .pt_custommenu');
       var wWraper = wraper.outerWidth();
       var posWraper = 0;
       if(wraper.offset() != null) {
            posWraper = wraper.offset()
       }
       var pos = $(this).offset();
       
       var xTop = pos.top - posWraper.top + CUSTOMMENU_POPUP_TOP_OFFSET;
       var xLeft = pos.left - posWraper.left;
       if ((xLeft + new_outer_width_popup) > wWraper) xLeft = wWraper - new_outer_width_popup;

       $(this).find('.popup').css('top',xTop);
       $(this).find('.popup').css('left',xLeft);
       
       /*set new width popup*/
       $(this).find('.popup').css('width',new_width_popup);
       $(this).find('.popup .block1').css('width',new_width_popup);
       
       /*return popup display none*/
       $(this).find('.popup').css('display','none');
       
       /*show hide popup*/
       if(CUSTOMMENU_POPUP_EFFECT == 0) $(this).find('.popup').stop(true,true).slideDown('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 1) $(this).find('.popup').stop(true,true).fadeIn('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 2) $(this).find('.popup').stop(true,true).show('slow');
    },function(){
       if(CUSTOMMENU_POPUP_EFFECT == 0) $(this).find('.popup').stop(true,true).slideUp();
       if(CUSTOMMENU_POPUP_EFFECT == 1) $(this).find('.popup').stop(true,true).fadeOut('slow');
       if(CUSTOMMENU_POPUP_EFFECT == 2) $(this).find('.popup').stop(true,true).hide('fast');
    });
    
    $('.more-ver-menu').click(function() {
       $('.more-wrap').hide();
       $('.over-menu').slideDown('slow'); 
    });
    
    $('.close-ver-menu').click(function() {
       $('.more-wrap').show();
       $('.over-menu').slideUp();  
    });
    
});