/**
 * Created with JetBrains PhpStorm.
 * User: khalid.junaid
 * Date: 10/6/13
 * Time: 7:33 PM
 * To change this template use File | Settings | File Templates.
 */
function centerModal() {
    $(this).css('display', 'block');
    var $dialog = $(this).find(".modal-dialog");
    var offset = ($(window).height() - $dialog.height()) / 2;
    // Center modal vertically in window
    $dialog.css("margin-top", offset);
}
function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
    //console.log(c);
};
function checkCoords() {
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
};
var loader = '<div class="loader"><img src="/assets/images/ajax-loader.gif"></div>';
var jcrop_api;
var CropMedia = {
    media_id: '',
    page_id: '',
    FQN: '',
    CropMediaInstanse: '',
    AddLoader: function () {
        if ($('.sonata-ba-form').find('.loader').length == 0) {
            $('.sonata-ba-form').prepend(loader);
        }
    },
    RemoveLoader: function () {
        $('.loader').remove();
    },
    IniCropSizes: function (data) {
        var definedsizes = '<h2>Available Sizes</h2>';
        if ($(data.data.sizes).length > 0) {
            $(data.data.sizes).each(function (i, v) {
                definedsizes += '<div data-key="' + v.key + '" data-width="' + v.width + '" data-height="' + v.height + '" class="thumbs crop_sizes"><img src="' + data.data.small + '" /><span>' + v.key + '<br>Dimensions:' + v.width + ' x ' + v.height + '</span></div>';
            });
        }
        $('#crop_thumbs_container').html(definedsizes);
        $('.crop_sizes').on('click', function () {
            var width = parseFloat($(this).data('width'));
            var height = parseFloat($(this).data('height'));
            var ratio = parseFloat(width / height);
            var image_id = CropMedia.CropMediaInstanse.attr('id') + '_cropbox';
            if (width > $('#' + image_id).width() || height > $('#' + image_id).height()) {
                alert('Target image is smaller than selected size resultant thumb will be pixelated')
                jcrop_api.setOptions({
                    minSize: [100, 100],
                    aspectRatio: ratio
                });
            } else {
                jcrop_api.setOptions({
                    minSize: [width, height],
                    aspectRatio: ratio
                });
            }

            $('.crop_sizes').removeClass('selected');
            $(this).addClass('selected');
            $('#key').val($(this).data('key'));
        });
    },
    IniCroppedMedia: function (data) {
        var definedsizes = '<h2>Cropped Media</h2>';
        if ($(data.data.thumbs).length > 0) {
            $(data.data.thumbs).each(function (i, v) {
                var cropClass = '';
                definedsizes += '<div data-key="' + v.sizeKey + '" data-id="' + v.id + '" data-name="' + v.name + '"  data-created="' + v.createdAt + '"  data-updated="' + v.updatedAt + '" class="thumbs cropped_sizes ' + cropClass + '">';
                definedsizes += '<img src="/' + v.path + '" /><span>' + v.name + '<br>' + v.sizeKey + '<br>Media cropped for ' + v.meta + '</span>';
                definedsizes += '</div>';
            });
        } else {
            definedsizes += 'No records found';
        }
        definedsizes += '</hr>';
        $('#already_cropped_media').html(definedsizes);
        /*$('.cropped_sizes').on('click', function () {

         });*/
    },
    IniCrop: function (data, id) {
        var $crop_manager = $('.crop_manager');
        $crop_manager.find('.image_container div').html('<img id="' + id + '_cropbox" src="' + data.data.reference + '" />');
        $crop_manager.find('#' + id + '_cropbox').Jcrop({
            /*aspectRatio: 1,*/
            boxWidth: 1200,
            boxHeight: 600,
            minSize: [100, 100],
            onSelect: updateCoords
        }, function () {
            jcrop_api = this;
        });
    },
    IniCropAction: function (media_id) {
        $('#crop_image').unbind('click');
        $('#crop_image').on('click', function () {
            if (!checkCoords()) {
                return false;
            }
            CropMedia.AddLoader();
            jQuery.ajax({
                url: Routing.generate('media_cropping_save_image', {
                    id: CropMedia.media_id,
                    entity: CropMedia.page_id,
                    entityType: CropMedia.FQN
                }),
                dataType: 'JSON',
                data: {
                    x: $('#x').val(),
                    y: $('#y').val(),
                    w: $('#w').val(),
                    h: $('#h').val(),
                    key: $('#key').val(),
                    exist: $('#exist').val()
                },
                success: function (data) {
                    if (data.success) {
                        CropMedia.RemoveLoader();
                        CropMedia.CropMediaInstanse.trigger('click');

                    } else if (data.key == 'exist') {
                        if (!confirm(data.message + " Are you sure you want to replace it.?")) {
                            CropMedia.RemoveLoader();
                            return false;
                        } else {
                            $('#exist').val(1);
                            CropMedia.AddLoader();
                            $('#crop_image').trigger('click');

                        }
                    } else {
                        CropMedia.RemoveLoader();
                    }
                }
            }).always(function () {
                $('#exist').val(0);
            });

        });
    },
    IniCropMedia: function () {
        $('.sonata-crop-media').unbind('click');
        $('.sonata-crop-media').on('click', function () {
            CropMedia.AddLoader();
            CropMedia.CropMediaInstanse = $(this);
            CropMedia.media_id = $(this).data('media-id');
            CropMedia.page_id = $(this).data('admin');
            CropMedia.FQN = $(this).data('admin-class');
            var id = $(this).attr('id');
            jQuery.ajax({
                url: Routing.generate('media_cropping_image', {id: CropMedia.media_id}),
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {
                        CropMedia.IniCrop(data, id);
                        CropMedia.IniCropSizes(data);
                        CropMedia.IniCroppedMedia(data);
                        CropMedia.IniCropAction(CropMedia.media_id);
                        $('.crop_manager').dialog({
                            autoOpen: true,
                            resizable: true,
                            height: 500,
                            width: 1200,
                            show: {
                                effect: "fade",
                                duration: 1000
                            },
                            hide: {
                                effect: "clip",
                                duration: 1000
                            }
                        });
                    }
                }
            }).always(function () {
                CropMedia.RemoveLoader();
            });
        });

    }
};
$(document).ready(function () {


    if ($('.sonata-crop-media').length > 0) {
        CropMedia.IniCropMedia();
        var $crop_manager, HTML;
        $crop_manager = $('.crop_manager');
        HTML = '<div id="already_cropped_media" class="thumbs_container"></div>\
            <div id="crop_thumbs_container" class="thumbs_container"></div>\
            <hr/>\
            <div id="image_container" class="image_container">\
                <div></div>\
                <a class="btn btn-success" href="javascript:void(0);" id="crop_image">Crop</a>\
            </div>\
            <input type="hidden" id="x" name="x"/>\
            <input type="hidden" id="y" name="y"/>\
            <input type="hidden" id="w" name="w"/>\
            <input type="hidden" id="h" name="h"/>\
            <input type="hidden" id="key" name="key"/>\
            <input type="hidden" id="exist" name="exist" value="0"/>';

        $crop_manager.html(HTML);

    }

    jQuery('.icon-large').on('click', function () {
        jQuery('.filter_container').slideToggle("slow", function () {
            if (jQuery('.filter_container').is(':visible')) {
                jQuery('.icon-large').removeClass('icon-plus-sign');
                jQuery('.icon-large').addClass('icon-minus-sign');
            } else {
                jQuery('.icon-large').addClass('icon-plus-sign');
                jQuery('.icon-large').removeClass('icon-minus-sign');
            }
        });
    });
    if ($('.media_upload_field').length > 0) {
        $('.media_upload_field').bind('change', function () {
            $(this).after('<span style="color:red;" class="file_size_error"></span>')

            if (this.files[0].size > 2097152) {
                $(this).attr('type', 'text');
                $(this).attr('type', 'file');
                $('.file_size_error').text('Selected file is too large to upload');
                setTimeout(function () {
                    $('.file_size_error').fadeOut('slow')
                    $('.file_size_error').remove()
                }, 3000);
            }

        });
    }
    if ($('.modal-dialog .modal-content .modal-body img').length > 0) {
        var closePopup = '<span class="traffic_admin_close_popup">x</span>';
        $('.modal-dialog .modal-content .modal-body').append(closePopup);
        $('.traffic_admin_close_popup').on('click', function () {
            $('.modal.fade.in').trigger('click');
        });

    }
    if ($('.toggle_media_usage').length > 0) {
        $('.toggle_media_usage').on('click', function () {
            $(this).next('ul.toggle_media_usage_container').slideToggle();
        });
    }
    if ($('.media_embedded_field_preview').length > 0) {
        $('.media_embedded_field_preview').on('click', function () {
            $('.modal').on('show.bs.modal', centerModal);
            $(window).on("resize", function () {
                $('.modal:visible').each(centerModal);
            });
        });
    }
    if ($(".datetimepicker").length > 0) {
        $('.datetimepicker').datetimepicker({
            showSecond: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss',
            stepHour: 1,
            stepMinute: 5,
            stepSecond: 30
        });

        $('input[type="date"]').prop('type', 'text');
        $('.datetimepicker').focus(function () {
            var position = $('.datetimepicker').position();
            var fieldidheight = $('.datetimepicker').height();
            var top = fieldidheight + position.top;
            $('#ui-datepicker-div').css('top', top + 'px');
        })
    }
});
