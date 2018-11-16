jQuery(function($) {

    var file_frame;
    var $document = $(document);

    /* Image */
    $document.on('click', '.add-image', function() {
        var $image = $(this);
        if (file_frame) file_frame.close();
        file_frame = wp.media({
            title: 'Добавить изображение',
            button:{
                text: 'Добавить изображение'
            },
            multiple: false
        });
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            if(attachment.type !== 'image') return;
            var imageURL = getAttachedImageURL(attachment);
            $image.parent().find('input:hidden').attr('value', attachment.id);
            $image.css('background-image', 'url('+imageURL+')');
        });
        file_frame.open();
    });
    $document.on('click', '.add-image .remove', function (e) {
        e.stopPropagation();
        $(this).parent().removeAttr('style').addClass('empty').parent().find('input[type="hidden"]').val('');
    });
    $document.on('mouseenter', '.add-image', function () {
        var value = $(this).parent().find('input[type="hidden"]').val();
        if(value === '' && !this.classList.contains('empty')){
            this.classList.add('empty');
        }else{
            if(value !== '' && this.classList.contains('empty')){
                this.classList.remove('empty');
            }
        }
    });

    /* PDF */
    $document.on('click', '.add-pdf', function(e) {
        e.preventDefault();
        var $btn = $(this);
        if (file_frame) file_frame.close();
        file_frame = wp.media({
            title: 'Добавить PDF',
            button:{
                text: 'Добавить PDF'
            },
            multiple: false
        });
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            if(attachment.subtype !== 'pdf') return;
            $btn.parent().find('input[type="hidden"]').attr('value', attachment.url);
            $btn.parent().find('input[type="text"]').val( attachment.filename);
        });
        file_frame.open();
    });
	
	/* Video */
    $(document).on('click', '.add-video', function(e) {
        e.preventDefault();
        var that = $(this);
        if (file_frame) file_frame.close();
        file_frame = wp.media({
            title: 'Добавить видео',
            button:{
                text: 'Добавить видео'
            },
            multiple: false
        });
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            if(attachment.type !== 'video') return;
            that.parent().find('input[type="hidden"]').attr('value', attachment.url);
            that.parent().find('input[type="text"]').val( attachment.filename);
        });
        file_frame.open();
    });

    /* Audio */
    $document.on('click', '.add-audio', function(e) {
        e.preventDefault();
        var $btn = $(this);
        if (file_frame) file_frame.close();
        file_frame = wp.media({
            title: 'Добавить аудиозапись',
            button:{
                text: 'Добавить аудиозапись'
            },
            multiple: false
        });
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            if(attachment.type !== 'audio') return;
            $btn.parent().find('input[type="hidden"]').attr('value', attachment.url);
            $btn.parent().find('input[type="text"]').attr('value', attachment.filename);
        });
        file_frame.open();
    });

	/* Remove file */
    $document.on('click', '.remove-file-btn', function(e) {
        e.preventDefault();
        $(this).closest('.wrap').find('input').val('');
    });

    /* Combo */
    if(document.querySelector('.combo')){
        Array.prototype.forEach.call(document.querySelectorAll('.combo'), function (currentCombo) {
            makeSortable(currentCombo);
        });

        function getNewJqueryComboItem($currentCombo){
            var itemIndex = $currentCombo.children('li').length;
            var id = $currentCombo.data('id');
            var template = $currentCombo.closest('td').find('script[type="template"]').text();
            var $newItem = $(template);
            $newItem.find('input:not(.no-index), textarea').each(function (inputIndex, item) {
                item.name = id + '[' + itemIndex + '][' + inputIndex + ']';
            });
            return $newItem;
        }

        function appendNewItemToComboAnimated($combo, $newItem){
            $combo.append($newItem);
            if($combo.hasClass('stack')){
                var itemHeight = $newItem[0].scrollHeight + 'px';
                $newItem.css({ opacity: 0, height: 0, width: 0 });
                $newItem.animate({ opacity: 1, height: itemHeight, width: '100%' }, 300, function () {
                    $newItem.css({ height: 'auto' });
                });
            }
        }

        $('.add-combo-item-btn.list').click(function (e) {
            e.preventDefault();
            var $currentCombo = $(this).parent().find('.combo');
            var $newCurrentComboItem = getNewJqueryComboItem($currentCombo);
            appendNewItemToComboAnimated($currentCombo, $newCurrentComboItem);
        });

        $('.add-combo-item-btn.gallery').click(function (e) {
            e.preventDefault();
            var $currentCombo = $(this).parent().find('.combo');
            if (file_frame) file_frame.close();
            file_frame = wp.media({
                title: 'Добавить изображения',
                button:{
                    text: 'Добавить изображения'
                },
                multiple: true
            });
            file_frame.on( 'select', function() {
                var attachments = file_frame.state().get('selection').toJSON();
                attachments.forEach(function (attachment) {
                    if(attachment.type !== 'image') return false;
                    var imageURL = getAttachedImageURL(attachment);
                    var $newcurrentComboItem = getNewJqueryComboItem($currentCombo);

                    $newcurrentComboItem.find('.image-preview').eq(0).css('background-image', 'url('+imageURL+')')
                        .parent().find('input[type="hidden"]').attr('value', attachment.id);

                    appendNewItemToComboAnimated($currentCombo, $newcurrentComboItem);
                });
            });
            file_frame.open();

        });

        $document.on('click', '.remove-combo-item', function(e) {
            e.preventDefault();
            $(this).parents('li').animate({ opacity: 0, height: 0, width: 0 }, 300, function() {
                var $this = $(this);
                $this.remove();
                resetIndex($this.parents('ul')[0]);
            });
        });

    }

    function getAttachedImageURL(attachment) {
        var imageURL = '';
        if(attachment.sizes.thumbnail) imageURL = attachment.sizes.thumbnail.url;
        else if(attachment.sizes.medium) imageURL = attachment.sizes.medium.url;
        else if(attachment.sizes.large) imageURL = attachment.sizes.medium.url;
        else if(attachment.sizes.full) imageURL = attachment.sizes.full.url;
        return imageURL;
    }

    /* функции перетаскивания */
    function makeSortable(list) {
        $(list).sortable({
            opacity: 0.6,
            stop: function() {
                resetIndex(list);
            }
        });
    }

    function resetIndex(list) {
        var listItems = [].slice.call(list.querySelectorAll('li'));
        listItems.forEach(function (item, index) {
            var fields = [].slice.call(item.querySelectorAll('input:not(.no-index), textarea'));
            fields.forEach(function (field) {
                var fieldName = field.getAttribute('name');
                var id = fieldName.substring(0, fieldName.indexOf('['));
                var subIndex =  fieldName.substring(fieldName.indexOf(']') + 1);
                field.setAttribute('name', id + "[" + index + "]" + subIndex);
            });
        });
    }
});