$(function(){
    $(".add_class").on('click',function(){
        $(".msgbox_class").show();
    })
    $(".add_supplier").on('click',function(){
        $(".msgbox_supplier").show();
    })
    $('.msgbox .close').on('click',function(){
        $('.msgbox').hide();
    })
    $('.msgbox').on('click',function(event){
        if(event.target==this){
            $(this).hide();
        }else{
            return;
        }
    })
})