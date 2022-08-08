(function($){
    'use strict';
    $(document).ready(function(){
        //alert(22);
        $('.ua_input_select,.wcmmq_select_terms').select2();
        
        /**
         * Support terms -> on after change,
         * form will be submit
         */
         $(document.body).on('change','#wcmmq_supported_terms',function(){
            $('#wcmmq_form_submit_button').trigger('click');
        });
        
        $(document.body).on('submit', 'form#wcmmq-main-configuration-form', function (){
            var min_val = $(this).find('.config_min_qty').val();
            var max_val = $(this).find('.config_max_qty').val();
            if(min_val!=''){
                //Just added parseInt() beacuse of data type was string.
                if (parseInt(min_val) > parseInt(max_val)) {
                    alert("Please make sure that your minimum quantity is smaller than maximum quantity!");
                    return false;
                }
            }
        });

       
    });

    /** Save Floating button  **/
    var btnHtml = '<div class="float-section ultraaddons-button-wrapper ultraaddons-panel no-background">';
    btnHtml += '<button type="submit" name="configure_submit" class="float-btn button-primary primary button">Save Change</button>';
    btnHtml += '</div>';
    //wcmmq-main-configuration-form

    var colSetsLen = $('#wcmmq-supported-terms').length;
    if( colSetsLen > 0 ){
        $('#wcmmq-main-configuration-form').append(btnHtml);
    } 
   
   //var $elem = $('.float-section');
   $(window).on('scroll',function(){
    
    let targetElement = $('.float-btn');
    
    
    let bodyHeight = $('#wpbody').height();
    let scrollTop = $(this).scrollTop();
    let screenHeight = $(this).height();

    let configFormElement = $('#wcmmq-supported-terms');
    if(configFormElement.length < 1) return;

    let conPass = bodyHeight - screenHeight - 300 - targetElement.height();
    let leftWill = configFormElement.width() - targetElement.width() - 20;
    

    targetElement.css({
        left: leftWill,
        right: 'unset'
    });
    if(scrollTop < conPass){
        targetElement.attr('id','stick_on_scroll-on');
    }else{
        targetElement.removeAttr('id');
    }
    
    if(scrollTop > 100 && colSetsLen > 0){
        targetElement.attr('id','stick_on_scroll-on');
    }else if(colSetsLen > 0){
        targetElement.removeAttr('id');
    }
    

});
})(jQuery);