const Profile = {

    update: function (btn) {
        let input = $(btn).closest('.form-data').find('input').not('input[type="hidden"]');
        let id = $(btn).closest('.form-data').find('input[name="id"]').val();
        let data = {
            action: 'updateUser',
            field: input[0].name,
            value: input[0].value,
            id: id
        };
        let callback = function (response) {
            console.log(response);
            let btn_text_default = $(btn).text();
            let result_text = response.result ? 'Сохранено' : 'Ошибка';
            $(btn).closest('li').find('span.value').text(response.data[0][data.field]);
            $(btn).text(result_text);
            setTimeout(function () {
                $(btn).text(btn_text_default);
            }, 2000);
        };
        ajax(location.href, 'JSON', data, callback);
    },

    edit: function (icon) {
        let li = $(icon).closest('li');
        let data = {
            action: 'edit',
            name: li.find('.name').text(),
            my_value: li.find('.value').text(),
            placeholder: 'Введите ' + li.find('.name').text()
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            li.append(response.input);
            $(icon).removeClass('fa-pencil-square-o').addClass('fa-times');
            $('.btn.update').on('click', function () {
                $this.update(this);
            });
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateProfileImage: function (button) {
        let src = $(button).closest('.form-data').find('input.upload-url').val();
        let img = $(button).closest('fieldset').find('img.profile-image');

        let data = {
            action: 'updateUser',
            field: 'profile_image',
            value: src,
            id: button.getAttribute('data-id')
        };
        let callback = function (response) {
            console.log(response);
            let btn_text_default = $(button).text();
            let result_text = response.result ? 'Сохранено' : 'Ошибка';
            $(button).text(result_text);
            setTimeout(function () {
                $(button).text(btn_text_default);
            }, 2000);
            if (response.result) {
                if (img[0] === undefined) {
                    $(button).closest('fieldset').prepend('<img src="' + response.data[0].profile_image + ' " alt="" class="profile-image"/><hr>')
                } else {
                    img[0].src = response.data[0].profile_image;
                }
                $('.preview').html('');
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    getField: function (fa, insertAfter = false) {
        let data = {
            action: 'getField',
            view: insertAfter ? fa.value : fa.getAttribute('data-view')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            if (!insertAfter) {
                $('.field-input').html(response.field);
                $('.add-public-data').on('click', function () {
                    $this.addPublicData(this);
                });
            }
            else {
                $(fa).closest('.edit-item').append(response.field);
                $('.add-public-data').on('click', function () {
                    $this.addPublicData(this, +fa.getAttribute('data-id') + 1);
                });
            }

        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateItem: function (btn) {
        let data = {
            action: 'updateItem',
            id: btn.getAttribute('data-id'),
            value: $(btn).closest('.form-data').find('input[type="text"], textarea').val(),
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
        };
        ajax(location.href, 'JSON', data, callback);
    },

    editItem: function (fa) {
        let data = {
            action: 'getField',
            view: fa.getAttribute('data-view')
        };
        console.log(data);
        let $this = this;
        let current_container = $(fa).closest('.edit-item');
        if (current_container.find('.form-data')[0] !== undefined) {
            return false;
        }
        let callback = function (response) {
            console.log(response);
            let current_value = current_container.find('.value-content').text();
            current_container.append(response.field);
            current_container.find('legend').text(current_container.find('legend').text().replace('Добавить', 'Изменить'));
            current_container.find('input[type="text"], textarea').val(current_value);
            let save_btn = current_container.find('.add-public-data');
            current_container.find('input[type="file"]').prop('multiple', false);
            save_btn
                .text('Изменить')
                .removeClass('add-public-data')
                .addClass('update-public-data')
                .attr('data-id', fa.getAttribute('data-id'))
                .on('click', function () {
                    $this.updateItem(this);
                });

            $('.add-public-data').on('click', function () {
                $this.addPublicData(this);
            });

            $('.field-select').removeClass('hidden');


            let select_value = data.view.replace('_field', '');

        };
        ajax(location.href, 'JSON', data, callback);
    },

    changeField: function (select) {
        let data = {
            action: 'changeField',
            value: select.value,
            id: select.getAttribute('data-id')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();

            let insertedSelect = $('select[data-id="' + data.id + '"]');
            let fontSize = select.value == 'subtitle' ? 24 : 16;

            insertedSelect.closest('.flex').find('input[name="set-font-size"]').val(fontSize);
            insertedSelect.closest('.edit-item').css({fontSize: fontSize});
            let style = insertedSelect.closest('.edit-item').attr('style');
            $this.updateStyle(style, data.id);

        };
        ajax(location.href, 'JSON', data, callback);

    },

    style: function (btn) {
        let container = $(btn).closest('.edit-item');
        let style = $(btn).attr('data-style');
        let prop = $(btn).attr('data-prop');
        let id = container.find('.fa-pencil-square-o').attr('data-id');
        if (prop !== 'font-size') {
            switch (prop) {
                case ('font-weight'):
                    style = $(btn).hasClass('active') ? '400' : style;
                    break;
                case ('font-style'):
                    style = $(btn).hasClass('active') ? 'normal' : style;
                    break;
                case ('text-decoration'):
                    style = $(btn).hasClass('active') ? 'none' : style;
                    break;
            }
            container.css(prop, $(btn).hasClass('active') ? '' : style);
            /*if(prop != 'cancel')
             container.css(prop,$(btn).hasClass('active') ? '' : style);
             else
             container.css({fontWeight:400, fontStyle:'normal', textDecoration:'none'});*/
        }
        else {
            let input = $(btn).closest('.font-size').find('input[name="set-font-size"]');
            let value = +input.val();
            console.log(value);
            let min = +input.attr('min');
            let max = +input.attr('max');
            let fontSize = style == 'larger' ? value + 1 : value - 1;

            if ((style == 'larger' && fontSize <= max) || (style == 'less' && fontSize >= min)) {
                container.css({fontSize: fontSize});
                input.val(fontSize);
            }
        }
        this.updateStyle(container.attr('style'), id);
    },

    styleAll: function (btn) {
        let container = $(btn).closest('.controls');
        let style = $(btn).attr('data-style');
        let prop = $(btn).attr('data-prop');
        if (prop !== 'font-size') {
            switch (prop) {
                case ('font-weight'):
                    style = $(btn).hasClass('active') ? '400' : style;
                    break;
                case ('font-style'):
                    style = $(btn).hasClass('active') ? 'normal' : style;
                    break;
                case ('text-decoration'):
                    style = $(btn).hasClass('active') ? 'none' : style;
                    break;
            }
            container.css(prop, $(btn).hasClass('active') ? '' : style);
        }
        else {
            let input = $(btn).closest('.font-size').find('input[name="set-font-size"]');
            let value = +input.val();
            console.log(value);
            let min = +input.attr('min');
            let max = +input.attr('max');
            let fontSize = style == 'larger' ? value + 1 : value - 1;

            if ((style == 'larger' && fontSize <= max) || (style == 'less' && fontSize >= min)) {
                container.css({fontSize: fontSize});
                input.val(fontSize);
            }
        }

        if($(btn).hasClass('align-btn'))
            container.find('.align-btn').removeClass('active');

        if($(btn).hasClass('active')){
            $(btn).removeClass('active');
        }else{
            $(btn).addClass('active');
        }

        this.updateStyleAll(container.attr('style'));
    },

    updateStyle: function (style, id) {
        let data = {
            action: 'updateStyle',
            style: style,
            id: id
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updateStyleAll: function (style) {
        let data = {
            action: 'updateStyleAll',
            style: style
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
        };
        ajax(location.href, 'JSON', data, callback);
    },

    uploadVideo: function (btn) {

        let container = $(btn).closest('.form-data').find('.upload');
        let url = container.find('input[name="video"]').val();
        if (!url)
            return false;

        container.find('.plyr').remove();
        let url_array = url.split('/');
        container.prepend('<div id="video-preview" data-plyr-provider="youtube" data-plyr-embed-id="' + url_array[url_array.length - 1] + '"></div>');
        let video_preview = new Plyr('#video-preview');

    },

    addPublicData: function (btn, id = false) {

        let data = {
            action: 'addPublicData',
            field: btn.getAttribute('data-name'),
            insertAfter: id
        };
        switch (data.field) {
            case ('image'):
                data.value = Upload.uploadedImages;
                break;
            case ('video'):
                data.value = $(btn).closest('.form-data').find('input[name="video"]').val();
                break;
            case ('subtitle'):
                data.value = $(btn).closest('.form-data').find('input[name="subtitle"]').val();
                break;
            case ('text'):
                data.value = $(btn).closest('.form-data').find('textarea[name="text"]').val().replace("\n", "<br>");
                break;
            case ('description'):
                data.value = $(btn).closest('.form-data').find('input[name="description"]').val();
                break;
            default:
                data.value = false;
        }
        if (!data.value)
            return false;

        console.log(data);

        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.initDraggable();
            $(btn).closest('.form-data').find('.preview').html('');
            $(btn).closest('.form-data').find('input[type="text"], textarea').val('');

            if ($('.fields button.submit-publication')[0] === undefined) {
                $('.fields').append('<button type="button" class="btn btn-primary submit-publication">Опубликовать</button>');
                $('.submit-publication').on('click', function () {
                    Profile.submitNewPost();
                });
            }

            $(btn).closest('.form-data').remove();

            $this.afterPublicUpdating();

            /*$('.edit-item .fa-times').on('click', function () {
             Profile.removePublicItem(this);
             });
             $('.fa.fa-checkbox').on('change', function () {
             Profile.setImageDefault(this);
             });
             $('select[name="field"]').on('change', function () {
             Profile.changeField(this);
             })*/
        };
        ajax(location.href, 'JSON', data, callback);
    },

    removePublicItem: function (fa) {
        let data = {
            action: 'removePublicItem',
            index: fa.getAttribute('data-id')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
            /*$this.initDraggable();
             $('.edit-item .fa-times').on('click', function () {
             Profile.removePublicItem(this);
             });
             $('.fa.fa-checkbox').on('change', function () {
             Profile.setImageDefault(this);
             });
             $('select[name="field"]').on('change', function () {
             Profile.changeField(this);
             })*/
        };
        ajax(location.href, 'JSON', data, callback);
    },

    updatePublicTitles: function (input) {
        let data = {
            action: 'updatePublicTitles',
            field: input.name,
            value: input.value
        };
        let callback = function (response) {
            console.log(response);
            let alias = $('input[name="alias"]');
            if (input.name == 'short_title') {
                alias.val(response.translit);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    draggable: {},

    initDraggable: function () {
        $('.edit-item')
            .draggable({
                cursor: 'move',
                containment: 'body',
                helper: function (event) {
                    return $("<div class='helper'>Перетащите элемент, куда Вам нужно</div>");
                },
                start: function () {
                    //this.style.zIndex = 999;
                    //$('.edit-item').addClass('smaller');
                    Profile.draggable.elementId = $(this).find('.fa-times').attr('data-id');
                },
                stop: function () {
                    //$('.edit-item').removeClass('smaller');
                }
            })
            .droppable({
                accept: ".edit-item",
                over: function (event, ui) {
                    $(this).addClass('over');
                },
                out: function () {
                    $(this).removeClass('over');
                },
                drop: function () {
                    Profile.replacePublicItem(this);
                }
            });
    },


    replacePublicItem: function (item) {

        let data = {
            action: 'replacePublicItem',
            idToReplace: this.draggable.elementId
        };

        if ($(item).prev('.edit-item')[0] === undefined) {
            data.idToInsertAfter = 0;
        } else {
            data.idToInsertAfter = $(item).prev('.edit-item').find('.fa-times').attr('data-id');
        }
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
            /* $this.initDraggable();
             $('.edit-item .fa-times').on('click', function () {
             Profile.removePublicItem(this);
             });
             $('.fa.fa-checkbox').on('change', function () {
             Profile.setImageDefault(this);
             });
             $('select[name="field"]').on('change', function () {
             Profile.changeField(this);
             })*/
        };
        ajax(location.href, 'JSON', data, callback);
    },

    setPublicationCategory: function (select) {
        let data = {
            action: 'setPublicationCategory',
            value: select.value
        };
        let callback = function (response) {
            console.log(response);
        };
        ajax(location.href, 'JSON', data, callback);
    },

    submitNewPost: function (btn) {

        let categorySelect = $('select[name="category"]');
        let shortTitleInput = $('input[name="short_title"]');

        if (!categorySelect.val()) {
            $(btn).addClass('btn-danger').text('Выберите категорию');
            categorySelect.focus();
            setTimeout(function () {
                $(btn).removeClass('btn-danger').text('Опубликовать');
            }, 2000);
            return false;
        } else if (!shortTitleInput.val()) {
            $(btn).addClass('btn-danger').text('Напишите заголовок');
            shortTitleInput.focus();
            setTimeout(function () {
                $(btn).removeClass('btn-danger').text('Опубликовать');
            }, 2000);
            return false;
        }

        let data = {
            action: 'submitNewPost'
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                location.href = '/public/' + response.result[0]['id'] + '::' + response.result[0]['alias'];
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },


    updatePost: function (btn) {
        let data = {
            action: 'updatePost'
        };
        let callback = function (response) {
            console.log(response);
            if (response.result) {
                location.href = '/public/' + response.result[0]['id'] + '::' + response.result[0]['alias'];
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    setImageDefault: function (checkbox) {
        let data = {
            action: 'setImageDefault',
            value: checkbox.getAttribute('data-value')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
            /*$this.initDraggable();
             $('.edit-item .fa-times').on('click', function () {
             Profile.removePublicItem(this);
             });
             $('.fa.fa-checkbox').on('change', function () {
             Profile.setImageDefault(this);
             });
             $('select[name="field"]').on('change', function () {
             Profile.changeField(this);
             })*/
        };
        ajax(location.href, 'JSON', data, callback);
    },

    import: function (btn) {
        let data = {
            action: 'import',
            url: $(btn).closest('.import').find('input[name="imported"]').val(),
            category: $('select[name="category"]').val()
        };
        let before = function () {
            $(btn).addClass('loader');
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $(btn).removeClass('loader');
            if (response.publication) {
                $('#publication').html(response.publication);

                $('input[name="short_title"]').val(response.data.short_title);
                $('input[name="long_title"]').val(response.data.long_title);
                $('textarea[name="description"]').val(response.data.description);
                $('input[name="alias"]').val(response.data.alias);
                $('input[name="hashtags"]').val(response.data.hashtags);

                $this.afterPublicUpdating();
                /* $this.initDraggable();
                 $('.edit-item .fa-times').on('click', function () {
                 Profile.removePublicItem(this);
                 });
                 $('.fa.fa-checkbox').on('change', function () {
                 Profile.setImageDefault(this);
                 });
                 $('select[name="field"]').on('change', function () {
                 Profile.changeField(this);
                 })*/
            }

        };
        let error = function (response) {
            console.log(response);
            $(btn).removeClass('loader');
            $(btn).addClass('btn-danger');
            setTimeout(function () {
                $(btn).removeClass('btn-danger');
            }, 2000);
            return false;
        };
        ajax('/upload/', 'JSON', data, callback, before, error);
    },

    afterPublicUpdating: function () {
        this.initDraggable();
        $('.edit-item .fa-times').on('click', function () {
            Profile.removePublicItem(this);
        });
        $('.fa.fa-checkbox').on('change', function () {
            Profile.setImageDefault(this);
        });
        $('select[name="field"]').on('change', function () {
            Profile.changeField(this);
        });
        $('select[name="insert_after"]').on('change', function () {
            Profile.getField(this, true);
        });
        $('.style').on('click', function () {
            Profile.style(this);
        });
        $('input[name="multi-remove"]').on('change', function () {
            let publicItem = $(this).closest('.edit-item');
            if(this.checked)
                publicItem.addClass('opacity-03');
            else
                publicItem.removeClass('opacity-03');
        });
    },


    //Снять с публикации
    unpublished: function (btn, to_do) {
        let data = {
            action: 'unpublished',
            to_do: to_do,
            id: btn.getAttribute('data-id')
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);

            let container = $(btn).closest('.user-public-item');

            if (to_do === 'delete') {
                container.remove();
                return true;
            }

            if (response.result) {
                if (response.result[0].status == "deleted") {
                    container.addClass('deleted');
                } else {
                    container.removeClass('deleted');
                }

                let indexOf = response.public_item.indexOf('>');
                response.public_item = response.public_item.slice(indexOf + 1).replace('/</div>/$', '');
                container.html(response.public_item);

                $this.updateAfterInserting();
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },


    updateAfterInserting: function () {
        $('.unpublished').on('click', function () {
            Profile.unpublished(this, 'unpublished');
            return false;
        });

        $('.published').on('click', function () {
            Profile.unpublished(this, 'published');
            return false;
        });
    },


    setAllTextFields: function (select) {
        let data = {
            action: 'setAllTextFields',
            value: select.value
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
        };
        ajax(location.href, 'JSON', data, callback);
    },

    cancelPublication: function (button) {
        let data = {
            action: 'cancelPublication'
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
            $(button).closest('form').find('input, textarea').val('');//[0].reset();
        };
        ajax(location.href, 'JSON', data, callback);
    },

    test: function () {
        console.log('test');
    },
    
    multiRemove: function (btn) {
        let data = {
            action: 'multiRemove',
            ids:[]
        };
        $('input[name="multi-remove"]:checked').each(function (i, el) {
            data.ids.push(el.value);
        });
        let $this = this;
        let before = function () {
          $(btn).addClass('loader');
        };
        let callback = function (response) {
            console.log(response);
            $(btn).removeClass('loader');
            $('#publication').html(response.publication);
            $this.afterPublicUpdating();
        };
        ajax(location.href, 'JSON', data, callback, before);
    },

    closeEdit: function (btn) {
        let data = {
            action: 'closeEdit'
        };

        let callback = function (response) {
            console.log(response);
            if(response.result){
                window.location.href=btn.getAttribute('data-refer');
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    isMouseDown:false

};


$(document).ready(function () {

    $("#tabs").tabs({
        active: localStorage.getItem('active-tab') ? localStorage.getItem('active-tab') : 0
    });

    $('.ui-tabs-tab').on('click', function () {
        localStorage.setItem('active-tab', $(this).index());
    });

    $('.edit').on('click', function () {

        if ($(this).hasClass('fa-times')) {
            $(this).closest('li').find('.form-data').remove();
            $(this).addClass('fa-pencil-square-o').removeClass('fa-times');
            return false;
        }
        Profile.edit(this);
        return true;
    });

    $('.update-profile-image').on('click', function () {
        Profile.updateProfileImage(this);
    });

    $('.action').on('click', function () {
        Profile.getField(this);
    });


    $('.edit-item .fa-times').on('click', function () {
        Profile.removePublicItem(this);
    });

    $('.title-input').on('keyup', function () {
        Profile.updatePublicTitles(this);
    });

    $('select[name="category"]').on('change', function () {
        Profile.setPublicationCategory(this);
    });

    $('.fa.fa-checkbox').on('change', function () {
        Profile.setImageDefault(this);
    });

    /*Перетаскивание элементов публикации*/
    (function () {
        Profile.initDraggable();
    })();

    /*Фиксация выбора действий*/

    /*let actions = $('.fields-select');
     let navHeight = $('.navbar-fixed-top').outerHeight() + 10;
     let actionsOffsetTop = actions.offset().top;
     let actionsOffsetLeft = actions.offset().left;

     $(window).on('scroll', function () {

     let scrollTop = $(this).scrollTop();

     if (scrollTop > actionsOffsetTop - navHeight) {
     actions.css({
     position: 'fixed',
     top: navHeight,
     left: actionsOffsetLeft
     });
     } else {
     actions.css({
     position: 'static'
     });
     }

     });*/


    /*Опубликовать новый пост*/
    $('.submit-publication').on('click', function () {
        Profile.submitNewPost(this);
    });


    /*Обновить  пост*/
    $('.update-publication').on('click', function () {
        Profile.updatePost(this);
    });


    $('select[name="field"]').on('change', function () {
        Profile.changeField(this);
    });


    $('select[name="insert_after"]').on('change', function () {
        Profile.getField(this, true);
    });


    //Импорт
    $('.import-public').on('click', function () {
        Profile.import(this);
    });


    $('.unpublished').on('click', function () {
        Profile.unpublished(this, 'unpublished');
        return false;
    });

    $('.published').on('click', function () {
        Profile.unpublished(this, 'published');
        return false;
    });

    $('.delete-publication').on('click', function () {
        if (confirm('Вы действительно хотите ужалить эту публиацию без возможности восстановления?'))
            Profile.unpublished(this, 'delete');
        return false;
    });


    //стили
    $('.style').on('click', function () {
        Profile.style(this);
    });

    $('.style-all').on('click', function () {
        Profile.styleAll(this);
    });


    //Установить значение для всех тектсовых полей
    $('select[name="all-fields"]').on('change', function () {
        Profile.setAllTextFields(this);
    });

    //Удалить неопубликованную публикачию и сбросить сессию
    $('.cancel-publication').on('click', function () {
        Profile.cancelPublication(this);
    });
    
    
    $('.multi-remove-elements').on('click', function () {
        Profile.multiRemove(this);
    });

    $('.close-edit-publication').on('click', function () {
        Profile.closeEdit(this);
    });

    $('input[name="multi-remove"]').on('change', function () {
       let publicItem = $(this).closest('.edit-item');
        if(this.checked)
            publicItem.addClass('opacity-03');
        else
            publicItem.removeClass('opacity-03');
    });

    $(window).on("mousedown", function(e) {
        Profile.isMouseDown = true;
    }).on("mousemove", function(e) {
        if(Profile.isMouseDown && $(e.target).closest('.edit-item')[0] !== undefined && ["FIELDSET", "TEXTAREA", "INPUT", "SELECT", "I", "BUTTON"].indexOf(e.target.tagName) == -1){
            $(e.target).closest('.edit-item').addClass('opacity-03').find('input[name="multi-remove"]').prop('checked', true);
        }
    }).on("mouseup", function(e) {
        Profile.isMouseDown = false;
    })


});