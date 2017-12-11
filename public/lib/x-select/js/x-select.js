$(document).ready(function () {
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function (val) {
            var i = this.length;
            while (i--) {
                if (this[i] == val) return i;
            }
            return -1;
        }
    }

    tagContainer = $('.x-tag-container');

    function addTag(val,parent) {
        tagContainer.append('<div class="x-tag">' + val +
            '<div data-parent="'+parent+'" class="x-tag-remove"><i class="fa fa-times" aria-hidden="true"></i></div>' +
            '</div>');
    }

    checker_container = {}
    $.each($('.x-select-item-wrap.checker'),function () {
       var parent = $(this).data('parent');
       checker_container[parent] = [];
       var val = $(this).children('.x-select').attr('value');
       if(val !== 'null')
       {
           checker_container[parent]= val.split(',');
       }
        xCheck($(this));
    });

    $(document).on('click','.x-tag-remove',function () {
        var parent = $(this).data('parent');
        var val = $(this).parent('.x-tag').text();
        $.each($('.checker[data-parent="'+parent+'"]').find('.x-select-tt-item'),function () {
            if($(this).text() == val)
            {
                $(this).trigger('click');
            }
        });
    });



    $(document).click(function(){
        $('.adv_select-tt').closest('.x-select-item-wrap').removeClass('opened');
        $('.x-select-tt').closest('.x-select-item-wrap').removeClass('opened');
    });

    $(".adv_select, .adv_select-tt, .x-select, .x-select-tt").click(function(e) {
        e.stopPropagation();
        return false;
    });


    $('.x-select').click(function () {
        $('.x-select').not(this).parent('div').removeClass('opened');
        $(this).parent('div').toggleClass('opened');
    });

    $('.x-select-tt-item').hover(function () {
        $(this).closest('.x-select-tt').find('.x-select-tt-item').removeClass('hovered');
        $(this).addClass('hovered');
    });




    $('.x-select-tt-item').click(function () {
        var option = $(this).closest('.x-select-item-wrap');
        var choise = $(this).text();
        var choise_val = $(this).data("value");
        if (!option.hasClass('checker')) {
            option.find('.x-select').text(choise);
            option.find('.x-select').attr("value",choise_val);
            $(this).closest('.x-select-tt').find('.x-select-tt-item').removeClass('checked');
            $(this).addClass('checked');
            option.removeClass('opened');
        }
        else {
            var checker = option;
            var parent = checker.data('parent');
            var data = checker_container[parent];
            $.each(data,function (k,v) {
                data[k] = parseInt(v);
            });
            var index = data.indexOf(parseInt(choise_val));
            if (data.indexOf(choise_val) < 0) {
                data.push(choise_val);
            }
            else {
                data.splice(index, 1);
            }
            xCheck(checker);
        }
    });

    var currencies = ['UAH','USD','EUR'];
    $("#swiper").click(function () {
        var e = $(this).children('div');
        var cur_val = e.text();
        var index = (currencies.indexOf(cur_val) + 1 < 3 ? currencies.indexOf(cur_val) + 1 : 0);
        e.text(currencies[index]);
    });


    function xCheck(checker) {
        var parent = checker.data('parent');
        var data = checker_container[parent];
        var def = checker.data('default');
        var str = def;
        var val = 'null';
        checker.find('.x-select-tt-item').removeClass('checked');

        if (data.length !== 0)
        {
            data.sort();
            var val = data.join();
        }

        if(checker.hasClass('tag'))
        {
            tagContainer.empty();
            $.each(data, function () {
                var item = checker.find('.x-select-tt-item[data-value="' + this + '"]');
                item.addClass('checked');
                var val = item.text();
                addTag(val, parent);
            });
        }
        else
        {
            $.each(data,function () {
                var item = checker.find('.x-select-tt-item[data-value="'+this+'"]');
                item.addClass('checked');
            });


            if (data.length !== 0) {
                str = '';
                for (i = 0; i < data.length; i++) {
                    if (i == 0) {
                        str += data[i];
                    }
                    else {
                        str += ',' + data[i];
                    }
                }
                if (data.length == 1) {
                    str += '-комнатная';
                }
                else if (data.length < 3) {
                    str += ' комн.';
                }
                else {
                    str += ' к.';
                }
            }
        }
        checker.find('.x-select').text(str);
        checker.find('.x-select').attr("value",val);
    }




    //adv_select
    $('.adv_select').click(function(){
        // $('.adv_select').not(this).siblings('div').slideUp();
        $(this).siblings('div').slideToggle(function(){
            $(this).children('div').children('.adv_from').focus();
        });
    });
    function adv_replace_text(from, to, elem){
        var adv_select_str = "";
        if(to == ""){
            adv_select_str = "От "+from;
        }else if(from == ""){
            adv_select_str = "До "+to;
        }else{
            adv_select_str = from+" - "+to;
        }
        elem.html(adv_select_str);
    }
    $('.adv_select-tt>ul>li').click(function(e){
        var adv_select_val = $(this).html();
        if(!$(this).hasClass('text-right')){
            $(this).parent('ul').siblings('div').children('.adv_from').val(adv_select_val);
            var from = adv_select_val;
            var to = $(this).parent('ul').siblings('div').children('.adv_to').val();
            // var elem = $(this).parent('ul').parent('.adv_select-tt').siblings('.adv_select');
            var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
            adv_replace_text(from, to, elem);
            $(this).parent('ul').siblings('div').children('.adv_to').focus();
            $(this).parent('ul').children('li').addClass('text-right');
            e.stopPropagation();
            return false;
        }else{
            $(this).parent('ul').siblings('div').children('.adv_to').val(adv_select_val);
            var from = $(this).parent('ul').siblings('div').children('.adv_from').val();
            var to = adv_select_val;
            // var elem = $(this).parent('ul').parent('.adv_select-tt').siblings('.adv_select');
            var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
            adv_replace_text(from, to, elem);
            // $(this).parent('ul').parent('div').slideUp();
            $(this).closest('.x-select-item-wrap').removeClass('opened');
        }
    });
    $(".adv_from").change(function(){
        var from = $(this).val();
        var to = $(this).siblings('.adv_to').val();
        // var elem = $(this).parent('.adv_select_inputs').parent('.adv_select-tt').siblings('.adv_select');
        var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
        adv_replace_text(from, to, elem);
    });
    $(".adv_to").change(function(){
        var from = $(this).siblings('.adv_from').val();
        var to = $(this).val();
        // var elem = $(this).parent('.adv_select_inputs').parent('.adv_select-tt').siblings('.adv_select');
        var elem = $(this).closest('.x-select-item-wrap').find('.x-select');
        adv_replace_text(from, to, elem);
        // $(this).parent('div').parent('div').slideUp();
        $(this).closest('.x-select-item-wrap').removeClass('opened');
    });

    $('.adv_from').focus(function(){
        $(this).parent('div').siblings('ul').children('li').removeClass('text-right');
        var limit = parseInt($(this).siblings('.adv_to').val());
        if(limit !== ""){
            $(this).parent('div').siblings('ul').children('li').each(function(){
                $(this).show();
                if(parseInt($(this).html()) > limit){
                    $(this).hide();
                }
            });
        }

    });
    $('.adv_to').focus(function(){
        $(this).parent('div').siblings('ul').children('li').addClass('text-right');
        var limit = parseInt($(this).siblings('.adv_from').val());
        if(limit !== ""){
            $(this).parent('div').siblings('ul').children('li').each(function(){
                $(this).show();
                if(parseInt($(this).html()) < limit){
                    $(this).hide();
                }
            });
        }
    });


});
