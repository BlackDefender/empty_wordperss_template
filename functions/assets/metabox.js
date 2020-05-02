document.addEventListener('DOMContentLoaded',function() {

    var file_frame;
    var $document = jQuery(document);

    /* Image */
    $document.on('click', '.add-image', function() {
        var image = this;
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
            image.parentElement.querySelector('input[type="hidden"]').value = attachment.id;
            image.style.backgroundImage = 'url('+imageURL+')';
            image.querySelector('.image-file-name').textContent = attachment.filename;
            image.classList.remove('empty');
        });
        file_frame.open();
    });
    $document.on('click', '.add-image .remove', function (e) {
        e.stopPropagation();
        jQuery(this).parent().removeAttr('style').addClass('empty').parent().find('input[type="hidden"]').val('');
        this.parentElement.querySelector('.image-file-name').textContent = '';
    });
    $document.on('mouseenter', '.add-image', function () {
        var value = jQuery(this).parent().find('input[type="hidden"]').val();
        if(value === '' && !this.classList.contains('empty')){
            this.classList.add('empty');
        }else{
            if(value !== '' && this.classList.contains('empty')){
                this.classList.remove('empty');
            }
        }
    });


    $document.on('click', '.add-file-btn', function(event) {
        event.preventDefault();
        var target = this;
        var title = this.dataset.title;
        var fileType = this.dataset.fileType;
        if (file_frame) file_frame.close();
        file_frame = wp.media({
            title: title,
            button:{
                text: title
            },
            multiple: false
        });
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            if('any' === fileType || attachment.type === fileType || attachment.subtype === fileType){
                target.parentElement.querySelector('input[type="hidden"]').value = attachment.url;
                target.parentElement.querySelector('input[type="text"]').value = attachment.filename;
            }
        });
        file_frame.open();
    });


	/* Remove file */
    $document.on('click', '.remove-file-btn', function(e) {
        e.preventDefault();
        jQuery(this).closest('.file-input-container').find('input').val('');
    });

    /* Repeater */
    if(document.querySelector('.combo')){
        Array.prototype.forEach.call(document.querySelectorAll('.combo'), function (currentCombo) {
            makeSortable(currentCombo);
        });

        function getNewJqueryComboItem($currentCombo){
            var itemIndex = $currentCombo.children('li').length;
            var id = $currentCombo.data('id');
            var template = $currentCombo.closest('td').find('script[type="template"]').text();
            var $newItem = jQuery(template);
            $newItem.find('input:not(.no-index), textarea, select').each(function (inputIndex, item) {
                item.name = id + '[' + itemIndex + '][' + item.dataset.fieldId + ']';
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

        jQuery('.add-combo-item-btn.list').click(function (e) {
            e.preventDefault();
            var $currentCombo = jQuery(this).parent().find('.combo');
            var $newCurrentComboItem = getNewJqueryComboItem($currentCombo);
            appendNewItemToComboAnimated($currentCombo, $newCurrentComboItem);
        });

        jQuery('.add-combo-item-btn.gallery').click(function (e) {
            e.preventDefault();
            var $currentCombo = jQuery(this).parent().find('.combo');
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
            jQuery(this).parents('li').animate({ opacity: 0, height: 0, width: 0 }, 300, function() {
                var $this = jQuery(this);
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
        jQuery(list).sortable({
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
