const Public = {

    like: function (fa) {
        let hrefParse = location.href.split('/');
        let data = {
            action: 'like',
            to_do: fa.getAttribute('data-like'),
            id: fa.getAttribute('data-id')
        };
        let callback = function (response) {
            console.log(response);
            $('#likes').html(response.html);
            $('.likes .fa').on('click', function () {
                Public.like(this);
            });
        };
        ajax(location.href, 'JSON', data, callback);
    },

    comment: function (form) {

        let data = {
            action: 'comment',
            comment: {
                is_reply: 0,
                is_replied: 0,
                comment_id_reply: 0,
                post_id: form.elements['post_id'].value,
                user_id: form.elements['user_id'].value,
                comment: form.elements['comment'].value,
                action: 'set',
                images: this.commentImages
            }
        };

        let $this = this;
        let callback = function (response) {
            console.log(response);
            if (response.result === 'false')
                return false;

            $('.comments-block').html(response.comment);
            $this.afterCommentInsert(form);
            //let newCommentHeight = comments.find('.panel').first().outerHeight();
            //let addPictureBtn = $('.add-picture');
            //$('html, body').animate({scrollTop: $(window).scrollTop() + newCommentHeight}, 500);
            //$('.upload, .preview').addClass('hidden');
            //$('.preview').html('');
            //addPictureBtn.show();

        };

        let submitButton = $(form).find('button[type="submit"]');

        if (!data.comment.comment && this.commentImages.length == 0) {
            form.elements['comment'].focus();
            submitButton.text('Пустой комментарий');
            setTimeout(function () {
                submitButton.text('Отправить');
            }, 2000);
            return false;
        }

        //console.log(data);
        ajax(location.href, 'JSON', data, callback);
    },

    commentLike: function (button) {
        let data = {
            action: 'commentLike',
            post_id: $(button).attr('data-post-id'),
            id: $(button).attr('data-id'),
            likeDislike: $(button).hasClass('btn-hover-success') ? 'likes' : 'dislikes'
        };
        let $this = this;
        let callback = function (response) {
            console.log(response);
            if (response.result == 'false+')
                return false;

            $('.comments-block').html(response.comment);
            $this.afterCommentInsert();
            //$(button).find('span.likes-counter').text(response[data.likeDislike]);
        };
        ajax(location.href, 'JSON', data, callback);
    },

    commentReplyForm: function (button) {
        let form = $('#comment-form').html();
        $(button).closest('.media-body').find('.comment-reply').first().html(
            '<form action="" method="post" class="comment-reply-form">' +
            '<input type="hidden" name="comment_id" value="' + $(button).attr('data-id') + '">' +
            form +
            '</form>');
        let commentReplyForm = $('.comment-reply-form');
        let $this = this;
        $(button).hide();
        //$('html, body').animate({scrollTop:$(window).scrollTop() + commentReplyForm.outerHeight()}, 600);

        commentReplyForm.find('.upload').addClass('hidden');
        commentReplyForm.find('.add-picture').show();
        /*Скрипт на отправку формы*/
        commentReplyForm.on('submit', function () {
            $this.commentReply(this);
            return false;
        });

        $('.add-picture').on('click', function () {
            $(this).hide();
            $(this).closest('.media').find('.upload').removeClass('hidden');
        });

        $('.upload-url-btn').on('click', function () {
            Public.uploadUrlImage(this)
        });

        $('.file-upload-input').on('change', function () {
            Public.uploadFromComputer(this)
        });

        return true;
    },

    afterCommentInsert:function (form) {
        if(form !== undefined)
            form.reset();

        let $this = this;

        $('.preview').html('');

        $('.comment-like').on('click', function () {
            $this.commentLike(this);
            return false;
        });
        $('.comment-reply-btn').on('click', function () {
            $this.commentReplyForm(this);
            return false;
        });

        $('.add-picture').on('click', function () {
            $(this).hide();
            $(this).closest('.media').find('.upload').removeClass('hidden');
        });

        $('.panel .comment-image').on('click', function () {
            Public.showModalImg(this);
        });

        this.commentImages = [];
    },

    commentReply: function (form) {
        let data = {
            action: 'comment',
            comment: {
                is_reply: 1,
                is_replied: 0,
                comment_id_reply: form.elements['comment_id'].value,
                post_id: form.elements['post_id'].value,
                user_id: form.elements['user_id'].value,
                comment: form.elements['comment'].value,
                action: 'set',
                images: this.commentImages
            }
        };
        let $this=  this;
        let callback = function (response) {
            console.log(response);
            if (response.result === 'false')
                return false;

            $('.comments-block').html(response.comment);
            $this.afterCommentInsert(form);

            //let comments = $(form).closest('.media-body').find('.comment-replies').first();
            //comments.prepend(response.comment);
            //let newCommentHeight = comments.find('.panel').first().outerHeight();
            //$('html, body').animate({scrollTop: $(window).scrollTop() + newCommentHeight}, 500);
            //form.remove();
            //$('.preview').html('');

            /*$('.comment-like').on('click', function () {
             Public.commentLike(this);
             return false;
             });*/
        };

        let submitButton = $(form).find('button[type="submit"]');

        if (!data.comment.comment && this.commentImages.length === 0) {
            form.elements['comment'].focus();
            submitButton.text('Пустой комментарий');
            setTimeout(function () {
                submitButton.text('Отправить');
            }, 2000);
            return false;
        }
        //console.log(data);
        ajax(location.href, 'JSON', data, callback);
    },

    commentImages: [],

    uploadUrlImage: function (button) {
        let url = $(button).prev('input[name="image-url"]').val();
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
                let preview = $(button).closest('.media').find('.preview');
                preview.append(
                    '<div class="comment-image remove-img">' +
                    '   <img src="' + response.src + '" class="comment-image">' +
                    '   <i class="fa fa-window-close" aria-hidden="true"></i>' +
                    '</div>'
                );
                $this.commentImages.push(response.src);
                $(button).prev('input[name="image-url"]').val('');
                $('.remove-img').on('click', function () {
                    $this.removeImg(this);
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
                let preview = $(input).closest('.media').find('.preview');
                response.files.forEach(function (src) {
                    preview.append(
                        '<div class="comment-image remove-img">' +
                        '   <img src="' + src + '" class="comment-image">' +
                        '<i class="fa fa-window-close" aria-hidden="true"></i>' +
                        '</div>');
                    $this.commentImages.push(src);
                    //input.files = [];
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
            if (response.result) {
                $(image).remove();
                let index = $this.commentImages.indexOf(data.src);
                if (index !== -1)
                    $this.commentImages.splice(index, 1);
            }
        };
        ajax(location.href, 'JSON', data, callback);
    },

    showNextModalImg: function (img, isPublication) {
        let nextImg = isPublication ? $(img).closest('.col').next('.col').find('img')[0] : $(img).next('img')[0];
        $('.modal-block').remove();
        $('body').css({overflow: 'inherit'});
        if (nextImg !== undefined) {
            this.showModalImg(nextImg, isPublication);
            let nextCol = $(nextImg).closest('.col');
            let scrollTop = nextCol.offset().top - (screen.height - nextCol.outerHeight()) / 2;
            console.log(screen.height + " *** " + nextCol.outerHeight());
            $('html, body').animate({scrollTop: scrollTop}, 500);
        }
    },

    showModalImg: function (img, isPublication = false) {
        let src = img.src.replace('preview', 'fullsize');
        let description = $(img).prev('h4.image-description')[0] !== undefined ? $(img).prev('h4.image-description').text() : '';
        let $this = this;
        $('body').append(
            '<div class="modal-block">' +
            '   <div>' +
            '       <p>' + description + '</p>' +
            '       <img src="' + src + '" class="modal-image"/>' +
            '   </div>' +
            '</div>'
        ).css({overflow: 'hidden'});
        $('.modal-block').on('click', function (event) {
            if (event.target.tagName === 'IMG') {
                $this.showNextModalImg(img, isPublication);
            } else {
                $(this).remove();
                $('body').css({overflow: 'inherit'});
            }
        });
    }
};

$(document).ready(function () {

    $('.likes .fa').on('click', function () {
        Public.like(this);
    });

    $('#comment-form').on('submit', function () {
        Public.comment(this);
        return false;
    });

    $('.comment-like').on('click', function () {
        Public.commentLike(this);
        return false;
    });

    $('.comment-reply-btn').on('click', function () {
        Public.commentReplyForm(this);
        return false;
    });

    $('.add-picture').on('click', function () {
        $(this).hide();
        $(this).next('.upload').removeClass('hidden');
    });

    $('.upload-url-btn').on('click', function () {
        Public.uploadUrlImage(this);
    });

    $('.file-upload-input').on('change', function () {
        Public.uploadFromComputer(this)
    });

    $('.panel .comment-image').on('click', function () {
        Public.showModalImg(this);
    });

    $('.image-item img').on('click', function () {
        Public.showModalImg(this, true);
    });

});