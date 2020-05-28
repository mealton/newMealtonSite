/**
 * Created by Юрчик on 15.05.2020.
 */

/*
const Index = {

    appender: function () {
        let page = localStorage.getItem('page') ? +localStorage.getItem('page') + 1 : 2;
        let data = {
            action: 'appender',
            page: page
        };
        let callback = function (response) {
            console.log(response);
            if (response.data.length > 0) {
                $('#publications-container').append(response.html);
                localStorage.setItem('page', page);
                history.pushState(null, null, '/index/' + page);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    }
};


$(document).ready(function () {
    let toAppend = true;
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > $('#f').offset().top - screen.height && toAppend){
           Index.appender();
            toAppend = false;
            setTimeout(function () {
                toAppend = true;
            }, 1000);
        }
    });
});*/
