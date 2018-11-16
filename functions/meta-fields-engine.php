<?php
/* Пользовательские поля в посте */


// подключение скриптов для галереи
function add_metabox_scripts($hook)
{
    if ('post.php' == $hook || 'post-new.php' == $hook) {
        wp_enqueue_script('metabox-assets', get_template_directory_uri() . '/functions/assets/metabox.js',
            array('jquery', 'jquery-ui-sortable'));
        wp_enqueue_style('metabox-assets', get_template_directory_uri() . '/functions/assets/metabox.css');
    }
}

add_action('admin_enqueue_scripts', 'add_metabox_scripts');


require_once 'meta-fields-data.php';


function metaBoxIsForCurrentPage($box)
{
    global $post;

    if ($post->post_type != $box['post_type']) {
        return false;
    }

    if (isset($box['post_not_id'])) {
        if (is_array($box['post_not_id'])) {
            if (in_array($post->ID, $box['post_not_id'])) {
                return false;
            }
        } else {
            if ($box['post_not_id'] == $post->ID) {
                return false;
            }
        }
    }

    if (isset($box['post_id'])) {
        if (is_array($box['post_id'])) {
            if (!in_array($post->ID, $box['post_id'])) {
                return false;
            }
        } else {
            if ($box['post_id'] != $post->ID) {
                return false;
            }
        }
    }

    if (isset($box['template'])) {
        $currentPageTemplate = get_post_meta($post->ID, '_wp_page_template', true);
        if (is_array($box['template'])) {
            if (!in_array($currentPageTemplate, $box['template'])) {
                return false;
            }
        } else {
            if ($box['template'] != $currentPageTemplate) {
                return false;
            }
        }
    }
    return true;
}

function add_custom_meta_box()
{
    // подключение metabox к конкретному посту
    global $post;
    global $meta_boxes;
    foreach ($meta_boxes as $box_index => $box) {
        if (metaBoxIsForCurrentPage($box)) {
            add_meta_box(
                $box['post_type'] . '_meta_box_' . $box_index, // Идентификатор(id)
                isset($box['title']) ? $box['title'] : 'Данные для страницы', // Заголовок области с мета-полями(title)
                'show_custom_metabox', // Вызов(callback)
                $box['post_type'], // Где будет отображаться наше поле
                'normal',
                'high',
                $box['meta_fields']);
        }
    }
}

add_action('add_meta_boxes', 'add_custom_meta_box'); // Запускаем функцию

function getItemByIndex($arr, $index)
{
    return isset($arr[$index]) ? $arr[$index] : '';
}

function repeaterItemHTML($dataDescription, $metaDataItem, $metaDataItemIndex, $fieldName)
{
    ?>
    <li>
        <?php
        foreach ($dataDescription as $dataDescriptionItemIndex => $dataDescriptionItem):
            ?>
            <div class="combo-item-field-wrap">
                <div class="combo-item-field-title"><?= $dataDescriptionItem['label']; ?></div>
                <div class="combo-item-field-body">
                    <?php
                    switch ($dataDescriptionItem['type']) {
                        case 'text':
                            echo "<input type='text'
                                 value='" . format_to_edit(getItemByIndex($metaDataItem, $dataDescriptionItemIndex)) . "'
                                 name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]'>";
                            break;
                        case 'textarea':
                            echo "<textarea name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]'>".format_to_edit($metaDataItem[$dataDescriptionItemIndex])."</textarea>";
                            break;
                        case 'image':
                            $imageID = getItemByIndex($metaDataItem, $dataDescriptionItemIndex);
                            $imageStyleAttr = '';
                            if(!empty($imageID)){
                                $imageStyleAttr = "style='background-image: url(" . wp_get_attachment_image_src($imageID)[0] . ")'";
                            }
                            echo "<input type='hidden'
                                 value='" . $imageID . "'
                                 name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]'>
                          <div class='image-preview add-image' ". $imageStyleAttr ."><div class='remove'></div></div>";
                            break;
                        case 'audio':
                            $fileUrl = getItemByIndex($metaDataItem, $dataDescriptionItemIndex);
                            echo "<input type='hidden'
                                 value='" . $fileUrl . "'
                                 name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]'>
                          <input type='text' disabled class='no-index filename-input' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '').">
                          <button class='button add-audio add-file-btn'>Добавить/изменить аудиозапись</button>";
                            break;
                        case 'pdf':
                            $fileUrl = getItemByIndex($metaDataItem, $dataDescriptionItemIndex);
                            echo "<input type='hidden'
                                 value='" . $fileUrl . "'
                                 name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]'>
                          <input class='no-index filename-input' type='text' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '')." disabled>
                          <button class='button add-pdf add-file-btn'>Добавить/изменить PDF</button>";
                            break;
						case 'postsList':
                            $postsList = new WP_Query([
                                'post_type' => $dataDescriptionItem['post_type'],
                                'posts_per_page' => -1,
                            ]);
                            if ($postsList->have_posts()) {
                                $currentItemData = getItemByIndex($metaDataItem, $dataDescriptionItemIndex);
                                echo "<select name='{$fieldName}[$metaDataItemIndex][$dataDescriptionItemIndex]' class='posts-list'>";
                                if (empty($currentItemData)) {
                                    echo '<option disabled selected value="-1">' . $dataDescriptionItem['intro_text'] . '</option>';
                                }
                                foreach ($postsList->posts as $p){
                                    $selected = $currentItemData == $p->ID ? ' selected="selected"' : '';
                                    ?>
                                    <option value="<?= $p->ID; ?>" <?= $selected; ?> ><?= $p->post_title; ?></option>
                                    <?php
                                }
                                echo '</select>';
                            }
                            break;
                    }
                    ?>
                </div>
            </div>
        <?php
        endforeach;
        ?>
        <button class="button remove-combo-item">Удалить элемент</button>
    </li>
    <?php
}

function printRepeaterItems($metaData, $dataDescription, $fieldName)
{
    if ($metaData) {
        foreach ($metaData as $metaDataItemIndex => $metaDataItem) {
            repeaterItemHTML($dataDescription, $metaDataItem, $metaDataItemIndex, $fieldName);
        }
    }
}


// Отрисовка метаполей
function show_custom_metabox($post, $meta_fields)
{
    // Выводим скрытый input, для верификации.

    echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

    echo '<table class="form-table">';
    foreach ($meta_fields['args'] as $field) {
        // вывод заголовков
        if ($field['type'] == 'header') {
            echo '<tr><td colspan="2"><div class="metabox-header">' . $field['label'] . '</div></td></tr>';
            continue;
        }
        // Получаем значение если оно есть для этого поля
        $meta = get_post_meta($post->ID, $field['id'], true);

        // Начинаем выводить таблицу
        echo '<tr><th>' . $field['label'];
        if (isset($field['desc']) && $field['desc'] != '') {
            echo '<br><span class="metabox-item-description">(' . $field['desc'] . ')</span>';
        }
        echo '</th><td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . format_to_edit($meta) . '" size="98" />';
                break;

            case 'textarea':
                echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="100" rows="4">' . format_to_edit($meta) . '</textarea>';
                break;

            case 'checkbox':
                echo '<input type="checkbox" value="1" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>';
                break;

            case 'select':
                echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                }
                echo '</select>';
                break;

            case 'image':
                $image = wp_get_attachment_image_src($meta);
                $style = '';
                if ($image) {
                    $style = "background-image: url( $image[0] )";
                }
                ?>
                <div class="wrap">
                    <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                    <div class="image-preview add-image"<?= empty($style) ? '' : ' style="' . $style . '"'; ?>>
                        <div class="remove"></div>
                    </div>
                </div>
                <?php
                break;

            case 'audio':
                ?>
                <div class="wrap">
                    <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                    <input type="text" class="filename-input" disabled
                           value="<?= substr($meta, strrpos($meta, '/') + 1) ?>">
                    <button class="button add-audio add-file-btn">Добавить/изменить аудиозапись</button>
                </div>
                <?php
                break;
            case 'pdf':
                ?>
                <div class="wrap">
                    <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                    <input type="text" class="filename-input" disabled
                           value="<?= substr($meta, strrpos($meta, '/') + 1) ?>">
                    <button class="button add-pdf add-file-btn">Добавить/изменить PDF</button>
                </div>
                <?php
                break;
			case 'video':
                ?>
                <div class="wrap">
                    <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                    <input type="text" class="filename-input" disabled value="<?= substr($meta, strrpos($meta, '/')+1) ?>">
                    <button class="button remove-file-btn">Удалить</button>
                    <button class="button add-video add-file-btn">Добавить/изменить видео</button>
                </div>
                <?php
                break;
            case 'posts-list':
                $posts_list = new WP_Query(array('post_type' => $field['target_post_type']));
                if ($posts_list->have_posts()) {
                    echo '<select name="' . $field['id'] . '" class="posts-list">';
                    if (!$meta) {
                        echo '<option disabled selected value="-1">' . $field['intro_text'] . '</option>';
                    }
                    for ($i = 0; $i < count($posts_list->posts); ++$i) {
                        echo '<option value="' . $posts_list->posts[$i]->ID . '" ', $meta == $posts_list->posts[$i]->ID ? ' selected="selected"' : '', '>' . $posts_list->posts[$i]->post_title . '</option>';
                    }
                    echo '</select>';
                }
                break;
            case 'wysiwyg':
                wp_editor($meta, $field['id']);
                break;
            case 'combo':
                ?>
                <ul class="combo <?= $field['display'] ?>"
                    data-id="<?= $field['id']; ?>"
                    data-get-image-url="<?= get_template_directory_uri(); ?>/functions/assets/get-image-thumbnail-url.php">
                    <?php
                    printRepeaterItems($meta, $field['data-description'], $field['id']);
                    ?>
                </ul>
                <button class="button add-combo-item-btn <?= $field['behavior']; ?>">Добавить элемент</button>
                <script type="template">
                    <?php
                    repeaterItemHTML($field['data-description'], [], '', $field['id']);
                    ?>
                </script>
                <?php
                break;
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

// Функция для сохранения
function save_custom_meta_fields($post_id)
{
    // проверяем наш проверочный код
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
        return;
    }
    // Проверяем авто-сохранение
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Проверяем права доступа
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return;
    }

    global $meta_boxes;
    foreach ($meta_boxes as $box) {
        if (metaBoxIsForCurrentPage($box)) {
            foreach ($box['meta_fields'] as $field) {
                // Тип header предназначен для вывода заголовков в таблице. Там нет никакой информации.
                if ($field['type'] == 'header') {
                    continue;
                }

                $oldFieldValue = get_post_meta($post_id, $field['id'],
                    true); // Получаем старые данные (если они есть), для сверки
                $newFieldValue = $_POST[$field['id']];

                if ($newFieldValue && $newFieldValue != $oldFieldValue) {  // Если данные новые
                    update_post_meta($post_id, $field['id'], $newFieldValue); // Обновляем данные
                } elseif (empty($newFieldValue) && $oldFieldValue) {
                    delete_post_meta($post_id, $field['id'], $oldFieldValue); // Если данных нет, удаляем мету.
                }
            }
        }
    }
}

add_action('save_post', 'save_custom_meta_fields'); // Запускаем функцию сохранения

