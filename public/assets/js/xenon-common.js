
$(document).on('click','.collect',function (e) {
    e.preventDefault();

    var fieldset = $(this).siblings('fieldset');
    var tpl = fieldset.find('span').data('template');
    console.log(tpl);

    if($(this).hasClass('add'))
    {
        var index =  fieldset.find('input[type="text"]').length ;
        tpl = tpl.replace('__index__',index);
        fieldset.find('span').before(tpl);
        if(fieldset.find('input[type="text"]').length > 1)
        {
            $(this).siblings('.collect.rm').removeClass('hidden');
        }
    }
    else if($(this).hasClass('rm'))
    {
        if(fieldset.find('input[type="text"]').length > 1)
        {
            fieldset.find('input[type="text"]').last().remove();

            if(fieldset.find('input[type="text"]').length < 2)
            {
                $(this).addClass('hidden');
            }
        }
    }
});

$(document).ready(function () {
    if($('.tabs li a'))
    {
        $.each($('.tabs li a'),function () {
            $(this).unbind('click');
        });
    }

    $('.has-error input').change(function () {
        var el = $(this).closest('.has-error');
        el.removeClass('has-error');
        el.find('ul').remove();
    })

});//End of document ready

//HTML Elements
var phone_input = '<input style="margin-top: 5px;" type="text" name="phone[]" class="form-control phone_number" placeholder="Введите номер телефона" value="">';