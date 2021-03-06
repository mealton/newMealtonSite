function ajax(url, type, data, callback, beforeSend, error) {

    type = type ? type : "HTML";
    beforeSend = beforeSend ? beforeSend : function () {
        return false;
    };

    error = error ? error : function () {
        return false;
    };

    $.ajax({
        url: url,
        type: "POST",
        dataType: type,
        data: (data),
        beforeSend: function () {
            beforeSend();
        },
        success: function (response) {
            callback(response);
        },
        error: function (response) {
            error(response);
        }
    });

}

function randomInteger(min, max) {
    var rand = min - .5 + Math.random() * (max - min + 1);
    rand = Math.round(rand);
    return rand;
}


const Signin = {
    auth: function (form) {
        let data = {
            action: 'auth',
            username: form.elements['username'].value,
            password: form.elements['password'].value
        };
        let submit = $(form).find('button[type="submit"]');
        let submit_value_default = submit.text();
        let callback = function (response) {
            console.log(response);
            if (response.result === "true") {

                $('ul.nav.navbar-nav').html(response.navidation_html);

                $(".modal").modal("hide");
                $('#log_out').on('click', function () {
                    Signin.logout(this);
                });
            } else {
                submit.text('Пользватель не найден');
                setTimeout(function () {
                    submit.text(submit_value_default);
                }, 2000);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },
    logout: function (a) {
        let data = {action: 'logout'};
        let callback = function (response) {
            console.log(response);
            if (response.result === "true") {
                $('ul.nav.navbar-nav').html(response.navidation_html);
                window.location.href = "/";
            }
        };
        ajax(location.href, 'JSON', data, callback);
    }
};


const Upload = {

    uploadedImages: [],

    uploadUrlImage: function (button) {
        let url = $(button).closest('.form-data').find('input.upload-url').val();
        if (!url)
            return false;

        let data = {
            action: 'uploadUrl',
            url: url
        };
        let $this = this;
        this.uploadedImages = [];
        let callback = function (response) {
            console.log(response);
            $(button).closest('.upload-url-btn').removeClass('loader');
            if (response.result === 'success' && response.src) {
                let preview = $(button).closest('.form-data').find('.preview');
                preview.append(
                    '<div class="uploaded-image remove-img">' +
                    '   <img src="' + response.src + '" class="comment-image">' +
                    '   <i class="fa fa-window-close" aria-hidden="true"></i>' +
                    '</div>'
                );
                $this.uploadedImages.push(response.src);
                $(button).prev('input.upload-url').val(response.src.replace('preview', 'fullsize'));
                $('.remove-img').on('click', function () {
                    let src = $(this).find('img').attr('src');
                    let index = $this.uploadedImages.indexOf(src);
                    if (index !== -1)
                        $this.uploadedImages.splice(index, 1);

                    $this.removeImg(this);
                    console.log($this.uploadedImages);
                });
            }
        };
        let before = function () {
            $(button).closest('.upload-url-btn').addClass('loader');
        };
        ajax('/upload/', 'JSON', data, callback, before);
    },

    uploadFromComputer: function (input) {
        if (input.files.length === 0)
            return false;

        let formData = new FormData();
        let $this = this;
        let size = 0;

        $(input.files).each(function (i, file) {
            size += file.size;
        });

        //console.log(size);

        if (size > 167772160) {
            //alert("Загружайте не более 10 картинок");
            alert("Превышен максимальный размер загружаемых файлов");
            return false;
        }

        $(input.files).each(function (i, file) {
            formData.append("file[" + i + "]", file);
        });

        this.uploadedImages = [];


        $.ajax({
            type: "POST",
            url: location.href,
            cache: false,
            dataType: "JSON",
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function () {
                $(input).closest('.upload-label').addClass('loader');
            },
            success: function (response) {
                console.log(response);
                $(input).closest('.upload-label').removeClass('loader');
                let preview = $(input).closest('.form-data').find('.preview');
                let input_url = $(input).closest('.upload').find('input[name="profile_image"]');
                response.files.forEach(function (src) {
                    preview.append(
                        '<div class="uploaded-image remove-img">' +
                        '   <img src="' + src + '" class="comment-image">' +
                        '<i class="fa fa-window-close" aria-hidden="true"></i>' +
                        '</div>');
                    input_url.val(src);
                    $this.uploadedImages.push(src);
                });

                $('.remove-img').on('click', function () {
                    let src = $(this).find('img').attr('src');
                    let index = $this.uploadedImages.indexOf(src);
                    if (index !== -1)
                        $this.uploadedImages.splice(index, 1);

                    $this.removeImg(this);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
                $(input).closest('.upload-label').removeClass('loader');
                alert('Ошибка (((');
            }
        });
    },

    removeImg: function (image) {
        let data = {
            action: 'deleteImg',
            src: $(image).find('img').attr('src')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            let input_url = $(image).closest('.form-data').find('input[name="profile_image"]');
            if (response.result) {
                $(image).remove();
                input_url.val('');
            }
        };
        ajax('/upload/', 'JSON', data, callback);
    }
};


const Session = {
    config: function (a) {
        let data = {
            action: 'sessionConfig',
            content: a.getAttribute('data-content'),
            id: a.getAttribute('data-id'),
            menu: a.getAttribute('data-id'),
        };
        let callback = function (response) {
            console.log(response);
            location.href = a.href;
        };
        ajax(location.href, 'JSON', data, callback);
    }
};

const Search = {

    switchKeyboard: function (value) {

        let values = {
            q: 'й', w: 'ц', e: 'у', r: 'к', t: 'е', y: 'н', u: 'г', i: 'ш', o: 'щ', p: 'з', '[': 'х', ']': 'ъ', a: 'ф',
            s: 'ы', d: 'в', f: 'а', g: 'п', h: 'р', j: 'о', k: 'л', l: 'д', ':': 'ж', '"': 'э', z: 'я', x: 'ч', c: 'с',
            v: 'м', b: 'и', n: 'т', m: 'ь', ',': 'б', '.': 'ю', '/': '.'
        };
        let result = '';

        value.split('').forEach(function (char) {
            if (values[char] !== undefined) {
                result += values[char];
            } else {
                result += char;
            }
        });
        return result;
    },

    search: function (input) {
        if (input.value.length < 2) {
            $('#search-options').hide().html('');
            $(input).closest('form').find('button[type="submit"]').prop('disabled', true);
            return false;
        }

        $(input).closest('form').find('button[type="submit"]').prop('disabled', false);
        let data = {
            action: 'search',
            value: input.value
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            if (response.data.length > 0) {
                $('#search-options').show().html(response.html);
                $('.search-option').each(function (i, el) {
                    el.innerHTML = el.innerText.replace(new RegExp(input.value, 'gi'), '<mark>$&</mark>')
                });
            } else {
                //input.value = $this.switchKeyboard(input.value);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    }
};


const Sidebar = {
    offset: 5,
    step:0,
    count:5,
    scroll: function (btn) {

        let container = $('.sidebar-scroll-container-inner');

        if ($(btn).hasClass('to-up')) {
            $('.to-down').prop('disabled', false);
            this.offset--;
            this.step++;
            this.count--;
            container.animate({
                top: parseInt(container.css('top')) + container.find('.sidebar-public').first().outerHeight() + 20
            }, 500);
            if (this.count == 5) {
                $('.to-up').prop('disabled', true);
            }
            return true;
        }
        else if (Sidebar.offset == sidebarOffsetMax - 1) {
            $('.to-down').prop('disabled', true);
        }

        this.count++;

        let data = {
            action: 'scroll',
            add: true,
            offset: ++this.offset + this.step
        };


        let callback = function (response) {
            console.log(response);
            if ($(btn).hasClass('to-down')) {
                $('.to-up').prop('disabled', false);
                container.append(response.html);
                let toUpDistance = parseInt(container.css('top')) - container.find('.sidebar-public').last().outerHeight() - 20;
                container.animate({top: toUpDistance}, 500);
            }
        };

        ajax(location.href, 'JSON', data, callback);

    }
};


jQuery(document).ready(function ($) {


    if (window.location.pathname != "/profile/") {
        localStorage.removeItem('active-tab');
    }

    if (window.location.pathname != "/index/") {
        localStorage.removeItem('page');
    }


    if ($('#canvas').length) {

        var doughnutData = [{
            value: 70,
            color: "#f85c37"
        },
            {
                value: 30,
                color: "#ecf0f1"
            }
        ];
        var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);
    }
    ;

    if ($('#canvas2').length) {
        var doughnutData = [{
            value: 90,
            color: "#f85c37"
        },
            {
                value: 10,
                color: "#ecf0f1"
            }
        ];
        var myDoughnut = new Chart(document.getElementById("canvas2").getContext("2d")).Doughnut(doughnutData);
    }

    if ($('#canvas3').length) {
        var doughnutData = [{
            value: 55,
            color: "#f85c37"
        },
            {
                value: 45,
                color: "#ecf0f1"
            }
        ];
        var myDoughnut = new Chart(document.getElementById("canvas3").getContext("2d")).Doughnut(doughnutData);
    }

    if ($('#canvas4').length) {
        var doughnutData = [{
            value: 55,
            color: "#f85c37"
        },
            {
                value: 45,
                color: "#ecf0f1"
            }
        ];
        var myDoughnut = new Chart(document.getElementById("canvas4").getContext("2d")).Doughnut(doughnutData);
    }


    //Авторизация пользователей
    $('form.sing-in-form').on('submit', function () {
        Signin.auth(this);
        return false;
    });
    $('#log_out').on('click', function () {
        Signin.logout(this);
    });


    //Загрузка картинок
    $('.upload-url-btn').on('click', function () {
        Upload.uploadUrlImage(this);
    });

    $('.file-upload-input').on('change', function () {
        Upload.uploadFromComputer(this);
    });


    //сессии
    /* $('a.session-config').on('click', function () {
     Session.config(this);
     return false;
     });*/


    $('.lift').on('click', function () {
        if ($(this).hasClass('lift-visible-animate'))
            $('html, body').animate({scrollTop: 0}, 1000);
    });

    $('.scroll-sidebar').on('click', function () {
        Sidebar.scroll(this);
    });


    $(window).on('scroll', function () {
        let lift = $('.lift');
        if ($(this).scrollTop() > 1000) {
            lift.addClass('lift-visible-animate');
            lift.removeClass('lift-hidden-animate');
        }
        else {
            lift.addClass('lift-hidden-animate');
            lift.removeClass('lift-visible-animate');
        }
    }).on('load', function () {
        let inner = $('.sidebar-scroll-container-inner');
        let height = inner.outerHeight();
        $('.sidebar-scroll-container').css({
            height: height
        });
        inner.css({
            position: 'absolute',
            left: 0,
            top: 0,
            width: '100%',
            height: '100%'
        })
    });


    $('.search-input')
        .on('keyup', function () {
            Search.search(this);
        }).bind('paste', function (e) {
        this.value = (e.originalEvent || e).clipboardData.getData('text/plain');
        Search.search(this);
        return false;
    });


});
