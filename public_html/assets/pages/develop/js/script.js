const Admin = {

    getFormData: function (form) {
        let data = {};
        let fields = form.elements;
        $(fields).each(function (i, input) {
            if ((input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') && input.name) {
                if (input.getAttribute('type') === 'radio' && !input.checked)
                    return;
                data[input.name] = input.value;
            }
        });
        return data;
    },

    addUser: function (form) {
        let data = this.getFormData(form);
        data.action = 'addUser';
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                $('ul.userlist').append(response.data);
                form.reset();
                $(form).find('.preview').html('');
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateUser: function (button) {
        let form = $(button).closest('form');
        let data = this.getFormData(form[0]);
        data.action = 'updateUser';
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                let profile_img = response.data[0].profile_image ?
                    '<img src="' + response.data[0].profile_image.replace('fullsize', 'preview')  + '" alt="#" class="profile-image-mini">' : '';
                let status = response.data[0].status === "admin" ? '(админ)' : '';
                $(button).closest('li.item').find('.user-title').html(profile_img + '<span class="user-data">' +response.data[0].name + ' - ' + response.data[0].username + status + '</span>');
                let save_btn_default_text = $(button).text();
                form.find('.preview').html('');
                $(button).text('Сохранено');
                setTimeout(function () {
                    $(button).text(save_btn_default_text);
                }, 2000);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    resetPassword:function (button) {
        let data = {
            action: 'resetPassword',
            id: $(button).closest('form').find('input[name="id"]').val()
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                button.innerText = 'Пароль сброшен';
                setTimeout(function () {
                    button.innerText = 'Сбросить пароль';
                }, 2000);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    deleteUser: function (input) {
        let data = {
            action: 'deleteUser',
            isActive: input.checked ? '' : 'deleted',
            id: input.value
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                let item = $(input).closest('li.item');
                if (response.data[0].isActive === 'deleted') {
                    item.addClass('deleted');
                } else {
                    item.removeClass('deleted');
                }
            }
        };
        ajax(location.href, 'JSON', data, callback);

    },

    showDetails: function (userTitle) {
        if ($(userTitle).hasClass('opened')) {
            $(userTitle).next('.details').addClass('hidden');
            $(userTitle).removeClass('opened');
        } else {
            $(userTitle).next('.details').removeClass('hidden');
            $(userTitle).addClass('opened');
        }
    },

    passwordGenerator: function (span) {
        let input = $(span).closest('.form-data').find('input'),
            chars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
                's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '_', '!', '$', '%', '#', '*', '^', '@',
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            password = '',
            random,
            key;
        for (var i = 0; i < 8; i++) {
            random = randomInteger(1, 5);
            key = randomInteger(1, chars.length - 1);
            if (random < 3) {
                password += chars[key].toUpperCase();
            } else {
                password += chars[key];
            }
        }
        input.val(password);
    },

    uploadUrlImage: function (button) {
        let url = $(button).prev('input[name="profile_image"]').val();
        if (!url)
            return false;

        let data = {
            action: 'uploadUrl',
            url: url
        };
        let $this = this;
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
                $(button).prev('input[name="profile_image"]').val(response.src.replace('preview', 'fullsize'));
                $('.remove-img').on('click', function () {
                    $this.removeImg(this);
                });
            }
        };
        let before = function () {
            $(button).closest('.upload-url-btn').addClass('loader');
        };
        ajax('/public/', 'JSON', data, callback, before);
    },

    uploadFromComputer: function (input) {
        if (input.files.length === 0)
            return false;

        let formData = new FormData();
        let $this = this;

        if (input.files.length > 10) {
            alert("Загружайте не более 10 картинок");
            return false;
        }

        $(input.files).each(function (i, file) {
            formData.append("file[" + i + "]", file);
        });


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
                });

                $('.remove-img').on('click', function () {
                    $this.removeImg(this);
                })
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
        ajax('/public/', 'JSON', data, callback);
    },
    
    searchUser: function (text) {
        let reg = new RegExp(text, 'gi');
        $('.user-data').each(function (i, user) {
            user.innerHTML = user.innerText;
            if(user.innerText.match(reg)){
                user.innerHTML = user.innerHTML.replace(text, '<mark>' + text + '</mark>');
                $(user).closest('li.item').show();
            }else{
                $(user).closest('li.item').hide();
            }
        });
    },




    //Меню
    addMenuOption:function (form) {
        let data = this.getFormData(form);
        data.action = 'addMenuOption';
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                $('ul.menu-list').append(response.data);
                $('ul.nav.navbar-nav').html(response.nav);
                form.reset();
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateMenuOption: function (button) {
        let form = $(button).closest('form');
        let data = this.getFormData(form[0]);
        data.action = 'updateMenuOption';
        let callback = function (response) {
            console.log(response);
            let btn_text_default = $(button).text();
            let result_text = response.result ? 'Сохранено' : 'Ошибка';
            $(button).text(result_text);
            setTimeout(function () {
                $(button).text(btn_text_default);
            }, 2000);
            if(response.result){
                $(button).closest('li').find('.menu-title').text(response.data[0].menu_option);
                $('ul.nav.navbar-nav').html(response.nav);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    deleteMenuOption:function (input) {
        let data = {
            action: 'deleteMenuOption',
            isActive: input.checked ? '' : 'deleted',
            id: input.value
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                let item = $(input).closest('li.item');
                $('ul.nav.navbar-nav').html(response.nav);
                if (response.data[0].isActive === 'deleted') {
                    item.addClass('deleted');
                } else {
                    item.removeClass('deleted');
                }
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    //Категории
    addCategory:function (form) {
        let data = this.getFormData(form);
        data.action = 'addCategory';
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                $('ul.category-list').append(response.data);
                //$('ul.nav.navbar-nav').html(response.nav);
                form.reset();
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateCategory: function (button) {
        let form = $(button).closest('form');
        let data = this.getFormData(form[0]);
        data.action = 'updateCategory';
        let callback = function (response) {
            console.log(response);
            let btn_text_default = $(button).text();
            let result_text = response.result ? 'Сохранено' : 'Ошибка';
            $(button).text(result_text);
            setTimeout(function () {
                $(button).text(btn_text_default);
            }, 2000);
            if(response.result){
                $(button).closest('li').find('.category-title').text(response.data[0].rubric_name);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    deleteCategory:function (input) {
        let data = {
            action: 'deleteCategory',
            isActive: input.checked ? '' : 'deleted',
            id: input.value
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                let item = $(input).closest('li.item');
                if (response.data[0].isActive === 'deleted') {
                    item.addClass('deleted');
                } else {
                    item.removeClass('deleted');
                }
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },


    translit:function (input1, input2) {
        let data = {
            action:'translit',
            text:input1.value
        };
        let callback = function (response) {
            input2.val(response.translit);
        };
        ajax(location.href, 'JSON', data, callback);
    }
};


$(document).ready(function () {

    $('fieldset form').on('submit', function () {
        let action = this.elements['action'].value;
        Admin[action](this);
        return false;
    });

    $('.generate-password span').on('click', function () {
        Admin.passwordGenerator(this);
    });

    $('input.delete-user').on('change', function () {
        Admin.deleteUser(this);
    });

    $('.show-form').on('click', function () {
        if ($(this).hasClass('opened')) {
            $(this).next('form').addClass('hidden');
            $(this).removeClass('opened');
        } else {
            $(this).next('form').removeClass('hidden');
            $(this).addClass('opened');
        }
    });

    $('.reset-password').on('click', function () {
        Admin.resetPassword(this);
    });


    $('.save-changes-user').on('click', function () {
        Admin.updateUser(this);
    });

    
    $('input.search').on('keyup', function () {
        $('.userlist li.item').show();
        Admin.searchUser(this.value);
    });

    $('input[name="menu_option"]').on('keyup', function () {
        let input = $(this).closest('form').find('input[name="menu_option_url"]');
        Admin.translit(this, input);
    });

    $('input[name="rubric_name"]').on('keyup', function () {
        let input = $(this).closest('form').find('input[name="rubric_url_name"]');
        Admin.translit(this, input);
    });

    $('.save-changes-menu').on('click', function () {
        Admin.updateMenuOption(this);
    });
    $('input.delete-menu-option').on('change', function () {
        Admin.deleteMenuOption(this);
    });


    $('.save-changes-category').on('click', function () {
        Admin.updateCategory(this);
    });
    $('input.delete-category').on('change', function () {
        Admin.deleteCategory(this);
    });

});
