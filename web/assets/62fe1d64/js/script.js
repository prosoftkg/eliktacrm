$('document').ready(function () {
    var flats = [];
    var globalPrice = new Array();
    var globalFlat = 0;
    var global_plan = 0;
    $(".append_entry").click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var form = $('.orders-form-gq');
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        // submit form
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function (response) {
                if (response == true) {
                    $.pjax.reload({ container: '#reload_block' });
                }
            }
        });
        return false;
    });

    $(".entry-tabs > li:first").addClass('active');
    $(".flat-wrap:first").addClass('active in');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('#load-content').html("");
        flats = [];
        if ($(".tab-content > .active").hasClass('disable-form')) {
            $('.form_block_right').hide();
        }
        else {
            $('.form_block_right').show();
        }
        $('.flat_square').removeClass('flat-toggle');
        $('.flat_square').removeClass('inactive');
        $('.button-option').css('display', 'none');
    });

    (function ($) {
        $.fn.clickToggle = function (func1, func2) {
            var funcs = [func1, func2];
            this.data('toggleclicked', 0);
            this.click(function (e) {
                if ($(e.target).hasClass("room_amount")) return;
                var data = $(this).data();
                var tc = data.toggleclicked;
                $.proxy(funcs[tc], this)();
                data.toggleclicked = (tc + 1) % 2;
            });
            return this;
        };
    }(jQuery));


    $("body").on("click", ".flat_square", function (e) {
        if (!$(this).hasClass("flat-toggle")) {
            if ($(e.target).hasClass("room_amount")) return;
            var dollarPrice = $(this).find(".dollar_price").attr("dollarPrice");
            var somPrice = $(this).find(".apart_price").attr("somPrice");
            globalPrice.push(dollarPrice);
            var entryId = $(this).siblings(".tooltip-text");
            flats.push($(this).attr("id"));
            flatStatus = $(this).attr('status');
            $(this).addClass('flat-toggle');
            $(".flat_square[status!=" + flatStatus + "]").addClass('inactive');
            if (flats.length == 1) {
                $('.flash-success').css('display', 'none');
                if ($(this).hasClass('status-1')) {
                    $('.button-sold').css('display', 'inline-block');
                    $('.button-return').css('display', 'inline-block');
                }
                if ($(this).attr('status') == 0) {
                    if ($(this).find('.apart_price').length != 0) {
                        $('.button-book').css('display', 'inline-block');
                        $('.button-sold').css('display', 'inline-block');
                        $('.button-price').css('display', 'inline-block');
                        $('.button-agent').css('display', 'inline-block');
                        $('.button-commercial').css('display', 'inline-block');
                        $('.button-commercial').attr('href', '/apartment/proposal/' + $('.flat-toggle').attr('id'));
                    }
                    $('.button-plan').css('display', 'inline-block');
                    $('.button-reserve').css('display', 'inline-block');


                }
                if ($(this).hasClass('status-2')) {
                    $('.button-return').css('display', 'inline-block');
                    $('.button-plan').css('display', 'inline-block');
                    $('.button-reserve').css('display', 'none');
                }
            }

            else if (flats.length > 1) {
                $("#load-content").html("");
                if ($(this).hasClass('status-0')) {
                    $('.button-book').css('display', 'none');
                    $('.button-sold').css('display', 'none');
                    $('.button-commercial').css('display', 'none');

                    if ($(this).find('.apart_price')) {
                        $('.button-price').css('display', 'none');
                    }
                }

                if ($(this).hasClass('status-1')) {
                    $('#book-form').css('display', 'none');
                    $('.button-sold').css('display', 'none');
                    $('.button-commercial').css('display', 'none');
                }
            }
            globalFlat = $(this);
        }

        else {
            if ($(e.target).hasClass("room_amount")) return;
            $(this).addClass("clicked");
            $(this).removeClass('flat-toggle');
            $('#book-form').css('display', 'none');
            flats.splice($.inArray($(this).attr("id"), flats), 1);
            if (flats.length == 1) {
                if ($(this).hasClass('status-1')) {
                    $('.button-sold').css('display', 'inline-block');
                }
                if ($('.flat-toggle').hasClass('status-0')) {
                    if ($('.flat-toggle').children().length > 1) {
                        $('.button-book').css('display', 'inline-block');
                        $('.button-sold').css('display', 'inline-block');
                        $('.button-commercial').css('display', 'inline-block');
                        $('.button-price').css('display', 'inline-block');
                        $('.button-commercial').attr('href', '/apartment/proposal/' + $('.flat-toggle').attr('id'));
                    }
                }
                if ($(this).hasClass('status-2')) {
                    $('.button-return').css('display', 'inline-block');
                }
            }
            if (flats.length < 1) {
                $("#load-content").html("");
                $('.button-option').css('display', 'none');
                $(".flat_square").removeClass("inactive");
            }

        }
    }
    );

    /*$(document).mouseup(function (e) {
     var container = $(".flat_aligner");
     var buttonOption = $(".button-option");

     if (!container.is(e.target) // if the target of the click isn't the container...
     && container.has(e.target).length === 0 // ... nor a descendant of the container
     && !buttonOption.is(e.target))
     {
     $(".flat_square").removeClass("inactive flat-toggle");
     flats = [];
     $('.button-option').css('display', 'none');
     $('#book-form').css('display', 'none');
     }
     });*/

    $("body").on("click", ".plan-select", function () {
        var current = $(this).parent();
        global_plan = $(this).attr('id');
        current.siblings().removeClass('plan-selected');
        current.addClass('plan-selected');
    });

    $(document).on("click", ".plan_send", function (e) {
        e.preventDefault();
        let objectId = $('.js_minor_heading').attr('object');
        if (global_plan > 0) {
            $.ajax({
                url: '/apartment/plan',
                type: 'post',
                data: { plan: global_plan, flats: JSON.stringify(flats), object_id: objectId },
                success: function (data) {
                    flats = [];
                    $.pjax.reload({ container: '#reload_block' });
                }
            });
        }
        else {
            alert("Необходимо выбрать план");
        }
    });

    $("body").on("click", ".button-reserve", function (e) {
        e.preventDefault();
        let dis = $(this);
        $.ajax({
            url: '/apartment/reserve',
            type: 'post',
            data: { plan: global_plan, flats: JSON.stringify(flats) },
            success: function () {
                flats = [];
                $(".flat-toggle").addClass('status-2').attr('status', 2);
                $(".flat_square").removeClass("inactive flat-toggle status-0");
                $("#load-content").html('<div class=flash-success>Квартира зарезервирована</div>');
                //$("a.button-return").show();
                dis.hide();
            }
        });
    });

    $("body").on("click", ".button-delete-entry", function () {
        var entryId = $(".tab-content > .active").attr('entry_id');
        $.ajax({
            url: '/entry/remove/' + entryId,
            type: 'post',
            data: { entryId: entryId },
            success: function () {
                $.pjax.reload({ container: '#reload_block' });
            }
        });
    });


    $("a.button-return").on("click", function (e) {
        e.preventDefault();
        let dis = $(this);
        $.ajax({
            url: '/apartment/return',
            type: 'post',
            data: { plan: global_plan, flats: JSON.stringify(flats) },
            success: function () {
                dis.hide();
                // $('a.button-reserve').show();
                $(".flat-toggle").removeClass("status-1 status-2").attr('status', 0);
                $(".flat_square").removeClass("inactive flat-toggle");
                $("#load-content").html('<div class=flash-success>Квартира возвращена в продажу</div>');
                flats = [];
            }
        });
    });

    $("body").on("submit", ".book-form-gq", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var apartment = $('.flat-toggle').attr('id');
        var companyId = $('.minor_heading').attr('company');
        var objectId = $('.minor_heading').attr('object');
        var form2 = $(this);
        if (form2.find('.has-error').length) {
            return false;
        }
        $.ajax({
            url: '/apartment/book?id=' + apartment,
            type: 'post',
            data: form2.serialize() + "&companyId=" + companyId + "&objectId=" + objectId,
            success: function () {
                $(".flat-toggle").addClass("status-1").attr('status', 1);
                $("#load-content").html('<div class=flash-success>Квартира забронирована</div>');
                flats = [];
                $('.button-option').css('display', 'none');
                $('.book-form-gq').css('display', 'block');
                $(".flat_square").removeClass("inactive flat-toggle status-0");
            }
        });
        return false;
    });

    $("body").on("submit", ".sold-form-gq", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var companyId = $('.minor_heading').attr('company');
        var objectId = $('.minor_heading').attr('object');
        var form2 = $(this);
        var apartment = $('.flat-toggle').attr('id');
        if (form2.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: '/apartment/sold?id=' + apartment,
            type: 'post',
            data: form2.serialize() + "&companyId=" + companyId + "&objectId=" + objectId,
            success: function (data) {
                if (data == "form") {
                    flats = [];
                    $(".flat-toggle").addClass("status-3").attr('status', 3);
                    $(".flat-square").removeClass("flat-toggle inactive");
                    $('.button-option').css('display', 'none');
                    $('.sold-form-gq').css('display', 'block');
                    $("#load-content").html('<div class=flash-success>Сделка завершена</div>');
                }
            }
        });
        return false;
    });

    $("body").on("click", ".room_amount", function (e) {
        var numberId = $(this).data('id');
        $(".modal-header #modal_number").text($(this).data('number'));
        $.ajax({
            url: '/apartment/data',
            type: 'post',
            data: { number: numberId },
            success: function (data) {
                if (data !== false)
                    $('.modal-body').html("<div class='data_content'>" + data + "</div>");
                else
                    $('.modal-body').html("");
            }
        });
    });

    $("body").on("click", ".button-sold", function () {
        $('#book-form').css('display', 'block');
        var apartment = $('.flat-toggle').attr('id');
        var link = '/apartment/sold/' + apartment;
        var cont = document.getElementById('load-content');
        var loading = document.getElementById('loading');
        cont.innerHTML = loading.innerHTML;
        $.get(link, function (data) {
            $(cont).html(data);
        });
    }
    );

    $("body").on("click", ".button-book", function () {
        $('#book-form').css('display', 'block');
        var apartment = $('.flat-toggle').attr('id');
        var link = '/apartment/book/' + apartment;
        var cont = document.getElementById('load-content');
        var loading = document.getElementById('loading');
        cont.innerHTML = loading.innerHTML;
        $.get(link, function (data) {
            $(cont).html(data);
            jQuery('#w0-kvdate').kvDatepicker();
        });
    }
    );

    $("body").on("click", ".button-price", function (e) {
        e.preventDefault();
        $.ajax({
            url: '/apartment/price',
            success: function (html) {
                $('#load-content').html(html);
            }
        });
    });

    $(document).on('submit', '.price-form-gq', function (e) {
        e.preventDefault();
        var form2 = $(this), usd = $("#pricecorrector-usd").val(), kgs = $("#pricecorrector-kgs").val();
        if (form2.find('.has-error').length) {
            return false;
        }
        $.ajax({
            url: '/apartment/price',
            type: 'post',
            data: { PriceCorrector: { usd: usd, kgs: kgs, apartments: flats } },
            success: function (data) {
                //location.reload();
                $.pjax.reload({ container: '#reload_block' });
                flats = [];
            }
        });
        return false;
    });


    $('body').on("keyup", "#deal-discount", function () {
        //var left_sum = $('#sold-left_sum').attr('primary');
        var left_sum = $('#deal-left_sum').attr('base');
        var newVal = left_sum - $(this).val() - $('#deal-prepay').val();
        $('#deal-left_sum').val(newVal);
    });

    $('body').on("keyup", "#deal-prepay", function () {
        var left_sum = $('#deal-left_sum').attr('base');
        var newVal = left_sum - $(this).val() - $('#deal-discount').val();
        $('#deal-left_sum').val(newVal);
    });

    $("body").on("click", ".button-commercial", function (e) {
        e.preventDefault();
        var apartment = $('.flat-toggle').attr('id');
        var floor = $('.flat-toggle').attr('floor');
        var link = '/apartment/loadproposal?id=' + apartment + '&floor=' + floor;
        var cont = document.getElementById('load-content');
        var loading = document.getElementById('loading');
        cont.innerHTML = loading.innerHTML;
        $.get(link, function (data) {
            $(cont).html(data);
        });
    });
});