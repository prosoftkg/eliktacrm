$('.js_chat_modal').click(function (e) {
    e.preventDefault();
    // let receiverId = $(this).attr('data-receiver');
    $('#chatModal').modal();
    $('.js_modal_chat').focus();
    //$('.js_chat_receiver_id').val(receiverId);
});

//region socket
let socket;
let TYPING_TIMER_LENGTH = 3000; // ms
let typing = false;
let lastTypingTime;

let footer = $('#footer_id');
let user_auth_key = footer.text();
let global_user_id = footer.attr('data-id');
let site = document.domain;
if (user_auth_key.length && site != 'elit.loc') {
    //if (user_auth_key.length) {
    if (site === 'sebet.loc') { socket = io('http://' + site + ':2020'); }
    else { socket = io(); }
    console.log(socket);
    socket.emit('user_joined', { key: user_auth_key, id: global_user_id });
}

if (typeof socket !== 'undefined') {
    // Whenever the server emits 'new message', update the chat body
    socket.on('new message', function (data) {
        appendChatLine(data.chat_id, data.message);
        markAsUnread(data.chat_id);
        console.log('newMsg ' + data);
    });

    // Whenever the server emits 'typing', show the typing message
    socket.on('typing', function (data) {
        addChatTyping(data.chat_id);
    });

    // Whenever the server emits 'stop typing', kill the typing message
    socket.on('stop typing', function (data) {
        removeChatTyping(data.chat_id);
    });

    // Whenever someone logs in
    socket.on('user joined', function (user_id) {
        console.log('the other user joined ' + user_id);
        $('.js_online_status_' + user_id).removeClass('offline').addClass('online');
    });

    // Whenever someone logs out or closes browser
    socket.on('user left', function (user_id) {
        console.log('the other user left ' + user_id);
        $('.js_online_status_' + user_id).removeClass('online').addClass('offline');
    });
}
//endregion

function addChatTyping(chat_id) {
    $('.js_chat_' + chat_id).append("<div class='js_typing typing'>печатает...</div>");
}

function removeChatTyping(chat_id) {
    $('.js_chat_' + chat_id).find(".js_typing").remove();
}

function appendChatLine(chat_id, msg) {
    let d = new Date();
    let time = d.getHours() + ":" + addZero(d.getMinutes());
    let line = "<div class='chat_text'>" + msg + "<div class='font12 gray5 abs chat_date'>" + time + "</div></div>";
    let chat = $('.js_chat_' + chat_id);
    chat.find('.js_chatlines').append(line);
    chat.scrollTop(chat.prop("scrollHeight"));
}

function markAsUnread(chat_id) {
    let item_inner = $('.js_chat_item_' + chat_id);
    let item = item_inner.parent();
    if (item.hasClass('bg-purple')) {
        //it's active chat
        setTimeout(
            function () {
                $.ajax({
                    type: 'POST',
                    data: { chat_id: chat_id, _csrf: yii.getCsrfToken() },
                    url: '/chat/is-read',
                });
            },
            1000
        )
    }
    else {
        item.addClass('newchat');
        item.prependTo('.list-view');
        let promise = $('#myAudio').get(0).play();
        if (promise !== undefined) {
            promise.then(_ => {
                console.log('sound shuld played');
            }).catch(error => {
                console.log('Autoplay was prevented');
            });
        }
    }
}

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

$(document).on('click', '.js_open_chat', function (e) {
    e.preventDefault();
    $('.js_chat_id').hide();
    $('.js_chat_bg').hide();
    $('.js_show_child_hover').removeClass('bg-purple');

    let el = $(this);
    let chat_id = el.attr('data-id');
    let receiver_id = el.attr('data-receiver');
    let receiver_key = el.attr('data-key');
    let chat_box = $('.js_chat_' + chat_id);

    let input = $('.js_chat_input');
    input.val(chat_box.attr('data-text'));
    input.parent().show();
    input.focus();
    el.parents('.js_show_child_hover').addClass('bg-purple').removeClass('newchat');
    input.attr('data-key', receiver_key);


    if (chat_box.length) {
        chat_box.show();
    }
    else {
        let link = el.attr('data-link');
        let subject = el.attr('data-subject');
        let slink = "<pre class=''>Тема: <a href='" + link + "' target='_blank'>" + subject + "</a></pre><div class='js_chatlines chatlines'></div>";
        let names = el.attr('data-names');
        let box = $("<div class='chat_id js_chat_id js_chat_" + chat_id + "' data-text='' data-id='" + chat_id + "' data-receiver='" + receiver_id + "' data-loaded=''></div>");
        box.html(slink);
        $('.js_chatbox_wrap').append(box);
        loadChat(chat_id, names, '');
        $('.js_chat_' + chat_id).animate({ scrollTop: $(document).height() }, 400);
    }
    //for mobile screen
    $('.js_chat_container').addClass('only_desk');
    $('.js_board_container').removeClass('only_desk');
    $('.js_chat_back').addClass('mob_iblock');
    $('.js_chat_input').css('height', '30px');

});

//for mob screen
$('.js_chat_back').click(function () {
    $(this).removeClass('mob_iblock');
    $('.js_chat_container').removeClass('only_desk');
    $('.js_board_container').addClass('only_desk');
});

function loadChat(chat_id, names, offset) {
    $.ajax({
        type: 'POST',
        data: { chat_id: chat_id, names: names, offset: offset, _csrf: yii.getCsrfToken() },
        url: '/chat/load',
        beforeSend: function () { $('.js_chat_loading').show(); },
        success: function (data) {
            prependOlders(chat_id, data);
        }
    });
}

function prependOlders(chat_id, data) {
    let chat = $('.js_chat_' + chat_id);
    let oldH = chat.prop("scrollHeight");
    if (data) {
        let div = $("<div style='display: none;'>" + data + "</div>");
        chat.find('.js_chatlines').prepend(div.fadeIn());
    }
    else { chat.attr('data-loaded', 'all') }
    $('.js_chat_loading').hide();
    listen_again();
    let newH = chat.prop("scrollHeight");
    let dif = newH - oldH;
    chat.scrollTop(dif);
}

$(document).on('keypress', '.js_chat_input', function (e) {
    let text = $(this).val();
    let activeChat = $('.js_chat_id:visible');
    activeChat.attr('data-text', text);

    let code = e.keyCode || e.which;
    if (code === 13) {
        if ($('.js_send_enter').is(":checked") && $('.js_chat_back').is(':hidden')) {
            e.preventDefault();
            sendChat();
        }
    }

    updateTyping(activeChat.attr('data-id'), $(this).attr('data-key'), activeChat.attr('data-receiver'));
});

// Updates the typing event
function updateTyping(chat_id, receiver_key, receiver_id,) {
    if (!typing) {
        typing = true;
        if (typeof socket !== 'undefined') {
            socket.emit('typing', { chat_id: chat_id, receiver_key: receiver_key, receiver_id: receiver_id });
        }
    }
    lastTypingTime = (new Date()).getTime();

    setTimeout(function () {
        let typingTimer = (new Date()).getTime();
        let timeDiff = typingTimer - lastTypingTime;
        if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
            if (typeof socket !== 'undefined') {
                socket.emit('stop typing', { chat_id: chat_id, receiver_key: receiver_key, receiver_id: receiver_id });
            }
            typing = false;
        }
    }, TYPING_TIMER_LENGTH);
}

$(document).on('click', '.js_chat_send', function () {
    sendChat();
});

function sendChat() {
    let input = $('.js_chat_input');
    let text = input.val();
    let chat = $('.js_chat_id:visible');
    let activeChat = chat.attr('data-text', '').find('.js_chatlines');

    let date = '';
    let curdate = new Date();
    let curmonth = ("0" + (curdate.getMonth() + 1)).slice(-2);
    let curday = ("0" + curdate.getDate()).slice(-2);
    let myDate = curday + "/" + curmonth + "/" + curdate.getFullYear();
    let curmin = ("0" + curdate.getMinutes()).slice(-2);
    let chatDate = activeChat.find('.js_date:last').text();
    if (chatDate !== myDate) { date = "<div class='text-center font12 color5 mb10 pt10 clear js_date'>" + myDate + "</div>" }

    text = nl2br(text);
    text = Linkify(text);
    activeChat.append(date + "<div class='chat_text my_text'>" + text + "<div class='font12 gray5 abs chat_date'>" + curdate.getHours() + ':' + curmin + "</div></div>").parent();
    chat.scrollTop(chat.prop("scrollHeight"));
    input.val("");

    let chat_id = chat.attr('data-id');
    let receiver_id = chat.attr('data-receiver');
    let receiver_key = input.attr('data-key');
    if (typeof socket !== 'undefined') {
        socket.emit('stop typing', { chat_id: chat_id, receiver_key: receiver_key, receiver_id: receiver_id, });
        socket.emit('new message', { chat_id: chat_id, receiver_key: receiver_key, receiver_id: receiver_id, message: text });
        console.log('emitted chat_id:' + chat_id + '; receiver_key:' + receiver_key + '; text:' + text);
    }
    $.ajax({
        type: 'POST',
        data: { chat_id: chat_id, text: text, receiver_id: receiver_id, _csrf: yii.getCsrfToken() },
        url: '/chat/post'
        //beforeSend: function () {},
        //success:function(data){}
    });


    input.css('height', '30px');
}
if ($('.js_chat_container').length) {
    $.ajax({
        type: 'GET',
        url: '/chat/check',
        dataType: 'json',
        //beforeSend: function () {},
        success: function (data) {
            if (data.length) {
                $.each(data, function (index, el) {
                    $("div[data-key='" + el.chat_id + "']").addClass('newchat');
                });
            }
        }
    });
}

//show counter badge
$.ajax({
    type: 'GET',
    url: '/chat/count-new',
    dataType: 'json',
    //beforeSend: function () {},
    success: function (data) {
        if (data) {
            $('.js_new_msg_count').text(data);
        }
    }
});

//load old msgs when scroll up
function listen_again() {
    let all = document.querySelectorAll(".js_chat_id");
    for (i = 0; i < all.length; i++) {
        all[i].onscroll = chatScroll;
    }
}
let lastScrollTop = 0;
function chatScroll() {
    let chat = $('.js_chat_id:visible');
    let pos = chat.scrollTop();
    if ($(this).is(':animated')) {//do nothing
    } else {
        if (pos < lastScrollTop) {//if direction of scroll is up
            if (pos === 0) {
                let chat_id = chat.attr('data-id');
                let chat_loaded = chat.attr('data-loaded');
                if (chat_loaded !== 'all') {
                    let offset = chat.find('.js_chat_text:first').attr('data-lineid');
                    loadChat(chat_id, '', offset);
                }
            }
        }
        lastScrollTop = pos;
    }
}

//archive
$(document).on('click', '.js_archive_chat', function () {
    let parent = $(this).parents('.js_show_child_hover');
    let id = parent.attr('data-key');
    $.ajax({
        type: 'POST',
        data: { chat_id: id, _csrf: yii.getCsrfToken() },
        url: '/chat/archive',
        //beforeSend: function () {},
        success: function (data) {
            parent.hide();
            if ($('.js_chat_' + id).is(':visible')) {
                $('.js_chat_id').hide();
                $('.js_chat_input').parent().hide();
                $('.js_chat_bg').show();
            }
        }
    });
});

$(document).on('click', '.js_delete_chat', function () {
    let parent = $(this).parents('.js_show_child_hover');
    let id = parent.attr('data-key');
    $.ajax({
        type: 'POST',
        data: { id: id, _csrf: yii.getCsrfToken() },
        url: '/chat/delete?id=' + id,
        //beforeSend: function () {},
        success: function (data) {
            parent.hide();
            if ($('.js_chat_' + id).is(':visible')) {
                $('.js_chat_id').hide();
                $('.js_chat_input').parent().hide();
                $('.js_chat_bg').show();
            }
        }
    });
});

function Linkify(inputText) {
    //URLs starting with http://, https://, or ftp://
    let replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    let replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with www. (without // before it, or it'd re-link the ones done above)
    let replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    return replacedText
}

function nl2br(str, is_xhtml) {
    let breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
$(".js_chat_input").keyup(function (e) {
    while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
        $(this).height($(this).height() + 1);
    }
});