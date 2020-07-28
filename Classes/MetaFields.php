<?php

class MetaFields
{
    private $metaBoxes;
    function __construct($metaBoxes)
    {
        $this->metaBoxes = $metaBoxes;
        add_action('admin_enqueue_scripts', [$this, 'addMetaFieldsScripts']);
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('save_post', [$this, 'saveMetaFields']);
    }

    public function addMetaFieldsScripts($hook)
    {
        if ('post.php' == $hook || 'post-new.php' == $hook) {
            wp_enqueue_script('metabox-assets', get_template_directory_uri() . '/functions/assets/metabox.js', array('jquery', 'jquery-ui-sortable'));
            wp_enqueue_style('metabox-assets', get_template_directory_uri() . '/functions/assets/metabox.css');
        }
    }

    function addMetaBoxes()
    {
        foreach ($this->metaBoxes as $boxIndex => $box) {
            if ($this->metaBoxIsForCurrentPage($box)) {
                add_meta_box(
                    $box['post_type'] . '_meta_box_' . $boxIndex, // Идентификатор(id)
                    isset($box['title']) ? $box['title'] : 'Данные для страницы', // Заголовок области с мета-полями(title)
                    [$this, 'showMetaBox'], // Вызов(callback)
                    $box['post_type'], // Где будет отображаться наше поле
                    'normal',
                    'high',
                    $box['meta_fields']);
            }
        }
    }

    public function getPageMetaFields($page)
    {
        $pageMetaBoxes = array_filter($this->metaBoxes, function($box)use($page){
            return $this->metaBoxIsForCurrentPage($box, $page);
        });
        $currentPageFieldsSets = array_map(function ($metaBox) {
            return $metaBox['meta_fields'];
        }, $pageMetaBoxes);
        $res = [];
        foreach ($currentPageFieldsSets as $fieldsSet){
            foreach ($fieldsSet as $field){
                $res[] = $field;
            }
        }
        return $res;
    }

    private function metaBoxIsForCurrentPage($box, $currentPage = null)
    {
        if($currentPage === null){
            global $post;
            $currentPage = $post;
        }

        if ($currentPage->post_type != $box['post_type']) {
            return false;
        }

        if (isset($box['post_not_id'])) {
            if (is_array($box['post_not_id'])) {
                if (in_array($currentPage->ID, $box['post_not_id'])) {
                    return false;
                }
            } else {
                if ($box['post_not_id'] == $currentPage->ID) {
                    return false;
                }
            }
        }

        if (isset($box['post_id'])) {
            if (is_array($box['post_id'])) {
                if (!in_array($currentPage->ID, $box['post_id'])) {
                    return false;
                }
            } else {
                if ($box['post_id'] != $currentPage->ID) {
                    return false;
                }
            }
        }

        if (isset($box['template'])) {
            $currentPageTemplate = get_post_meta($currentPage->ID, '_wp_page_template', true);
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

    private function getItemByIndex($arr, $index)
    {
        return isset($arr[$index]) ? $arr[$index] : '';
    }

    private function repeaterItemHTML($fields, $metaDataItem, $metaDataItemIndex, $fieldName)
    {
        ?>
        <li>
            <?php
            foreach ($fields as $field):
                $fieldId = $field['id'];
                ?>
                <div class="combo-item-field-wrap">
                    <div class="combo-item-field-title"><?= $field['label']; ?></div>
                    <div class="combo-item-field-body">
                        <?php
                        switch ($field['type']) {
                            case 'text':
                                echo "<input type='text'
                                 value='" . format_to_edit($this->getItemByIndex($metaDataItem, $fieldId)) . "'
                                 data-field-id='$fieldId'
                                 name='{$fieldName}[$metaDataItemIndex][$fieldId]'>";
                                break;
                            case 'textarea':
                                echo "<textarea name='{$fieldName}[$metaDataItemIndex][$fieldId]' data-field-id='$fieldId'>".format_to_edit($metaDataItem[$fieldId])."</textarea>";
                                break;
                            case 'image':
                                $imageID = $this->getItemByIndex($metaDataItem, $fieldId);
                                $imageStyleAttr = '';
                                $imageName = '';
                                if(!empty($imageID)){
                                    $imageStyleAttr = "style='background-image: url(" . wp_get_attachment_image_url($imageID, 'thumbnail') . ")'";
                                    $imageName = basename(get_attached_file($imageID));
                                }
                                echo "<input type='hidden'
                                 value='$imageID'
                                 data-field-id='$fieldId'
                                 name='{$fieldName}[$metaDataItemIndex][$fieldId]'>
                          <div class='image-preview add-image' $imageStyleAttr><div class='remove'></div><div class='image-file-name'>$imageName</div></div>";
                                break;
                            case 'audio':
                                $fileUrl = $this->getItemByIndex($metaDataItem, $fieldId);
                                echo "<div class='file-input-container'>
                                      <input type='hidden'
                                             value='$fileUrl'
                                             data-field-id='$fieldId'
                                             name='{$fieldName}[$metaDataItemIndex][$fieldId]'>
                                      <input type='text' disabled class='no-index filename-input' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '').">
                                      <button type='button' class='button add-file-btn'
                                              data-title='Добавить аудиозапись'
                                              data-file-type='audio'>Добавить/изменить аудио</button>
                                      <button type='button' class='button remove-file-btn'>Удалить</button>
                                  </div>";
                                break;
                            case 'pdf':
                                $fileUrl = $this->getItemByIndex($metaDataItem, $fieldId);
                                echo "<div class='file-input-container'>
                                    <input type='hidden'
                                         value='$fileUrl'
                                         data-field-id='$fieldId'
                                         name='{$fieldName}[$metaDataItemIndex][$fieldId]'>
                                      <input class='no-index filename-input' type='text' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '')." disabled>
                                      <button type='button' class='button add-file-btn'
                                              data-title='Добавить PDF'
                                              data-file-type='pdf'>Добавить/изменить PDF</button>
                                      <button type='button' class='button remove-file-btn'>Удалить</button>
                                </div>";
                                break;
                            case 'video':
                                $fileUrl = $this->getItemByIndex($metaDataItem, $fieldId);
                                echo "<div class='file-input-container'>
                                    <input type='hidden'
                                         value='$fileUrl'
                                         data-field-id='$fieldId'
                                         name='{$fieldName}[$metaDataItemIndex][$fieldId]'>
                                      <input class='no-index filename-input' type='text' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '')." disabled>
                                      <button type='button' class='button add-file-btn'
                                              data-title='Добавить видео'
                                              data-file-type='video'>Добавить/изменить видео</button>
                                      <button type='button' class='button remove-file-btn'>Удалить</button>
                                </div>";
                                break;
                            case 'file':
                                $fileUrl = $this->getItemByIndex($metaDataItem, $fieldId);
                                echo "<div class='file-input-container'>
                                    <input type='hidden'
                                         value='$fileUrl'
                                         data-field-id='$fieldId'
                                         name='{$fieldName}[$metaDataItemIndex][$fieldId]'>
                                      <input class='no-index filename-input' type='text' ".(!empty($fileUrl) ? " value='".basename($fileUrl)."' " : '')." disabled>
                                      <button type='button' class='button add-file-btn'
                                              data-title='Добавить файл'
                                              data-file-type='any'>Добавить/изменить файл</button>
                                      <button type='button' class='button remove-file-btn'>Удалить</button>
                                </div>";
                                break;
                            case 'checkbox':
                                $checked = $this->getItemByIndex($metaDataItem, $fieldId) ? 'checked' : '';
                                echo "<input type='checkbox' value='1' name='{$fieldName}[$metaDataItemIndex][$fieldId]' $checked/>";
                                break;
                            case 'postsList':
                                $postsList = new WP_Query([
                                    'post_type' => $field['post_type'],
                                    'posts_per_page' => -1,
                                ]);
                                if ($postsList->have_posts()) {
                                    $currentItemData = $this->getItemByIndex($metaDataItem, $fieldId);
                                    echo "<select name='{$fieldName}[$metaDataItemIndex][$fieldId]' data-field-id='".$fieldId."' class='posts-list'>";
                                    if (empty($currentItemData)) {
                                        echo '<option disabled selected value="-1">' . $field['intro_text'] . '</option>';
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
            <button type="button" class="button remove-combo-item">Удалить элемент</button>
        </li>
        <?php
    }

    private function printRepeaterItems($metaData, $dataDescription, $fieldName)
    {
        if ($metaData) {
            foreach ($metaData as $metaDataItemIndex => $metaDataItem) {
                $this->repeaterItemHTML($dataDescription, $metaDataItem, $metaDataItemIndex, $fieldName);
            }
        }
    }

    public function showMetaBox($post, $meta_fields)
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
                    echo '<input type="text" name="' . $field['id'] . '" value="' . format_to_edit($meta) . '" />';
                    break;

                case 'textarea':
                    echo '<textarea name="' . $field['id'] . '" rows="4">' . format_to_edit($meta) . '</textarea>';
                    break;

                case 'checkbox':
                    echo '<input type="checkbox" value="1" name="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>';
                    break;

                case 'select':
                    echo '<select name="' . $field['id'] . '">';
                    foreach ($field['options'] as $option) {
                        echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                    }
                    echo '</select>';
                    break;

                case 'image':
                    $style = '';
                    $imageName = '';
                    if (!empty($meta)){
                        $style = 'background-image: url('.wp_get_attachment_image_url($meta, 'thumbnail').')';
                        $imageName = basename(get_attached_file($meta));
                    }
                    ?>
                    <div class="wrap">
                        <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                        <div class="image-preview add-image"<?= empty($style) ? '' : ' style="' . $style . '"'; ?>>
                            <div class="remove"></div>
                            <div class="image-file-name"><?= $imageName; ?></div>
                        </div>
                    </div>
                    <?php
                    break;

                case 'audio':
                    ?>
                    <div class="wrap file-input-container">
                        <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                        <input type="text" class="filename-input" disabled
                               value="<?= substr($meta, strrpos($meta, '/') + 1) ?>">
                        <button type="button" class="button add-file-btn"
                                data-title="Добавить аудиозапись"
                                data-file-type="audio">Добавить/изменить аудио</button>
                        <button type="button" class="button remove-file-btn">Удалить</button>
                    </div>
                    <?php
                    break;
                case 'pdf':
                    ?>
                    <div class="wrap file-input-container">
                        <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                        <input type="text" class="filename-input" disabled
                               value="<?= substr($meta, strrpos($meta, '/') + 1) ?>">
                        <button type="button" class="button add-file-btn"
                                data-title="Добавить PDF"
                                data-file-type="pdf">Добавить/изменить PDF</button>
                        <button type="button" class="button remove-file-btn">Удалить</button>
                    </div>
                    <?php
                    break;
                case 'video':
                    ?>
                    <div class="wrap file-input-container">
                        <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                        <input type="text" class="filename-input" disabled
                               value="<?= substr($meta, strrpos($meta, '/')+1) ?>">
                        <button type="button" class="button add-file-btn"
                                data-title="Добавить видео"
                                data-file-type="video">Добавить/изменить видео</button>
                        <button type="button" class="button remove-file-btn">Удалить</button>
                    </div>
                    <?php
                    break;
                case 'file':
                    ?>
                    <div class="wrap file-input-container">
                        <input type="hidden" name="<?= $field['id'] ?>" value="<?= $meta ?>">
                        <input type="text" class="filename-input" disabled
                               value="<?= substr($meta, strrpos($meta, '/')+1) ?>">
                        <button type="button" class="button add-file-btn"
                                data-title="Добавить файл"
                                data-file-type="any">Добавить/изменить файл</button>
                        <button type="button" class="button remove-file-btn">Удалить</button>
                    </div>
                    <?php
                    break;
                case 'postsList':
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
                case 'repeater':
                    ?>
                    <ul class="combo <?= $field['display'] ?>"
                        data-id="<?= $field['id']; ?>"
                        data-get-image-url="<?= get_template_directory_uri(); ?>/functions/assets/get-image-thumbnail-url.php">
                        <?php
                        $this->printRepeaterItems($meta, $field['fields'], $field['id']);
                        ?>
                    </ul>
                    <button type="button" class="button add-combo-item-btn <?= $field['behavior']; ?>">Добавить элемент</button>
                    <script type="template">
                        <?php
                        $this->repeaterItemHTML($field['fields'], [], '', $field['id']);
                        ?>
                    </script>
                    <?php
                    break;
            }
            echo '</td></tr>';
        }
        echo '</table>';
    }

    function saveMetaFields($post_id)
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


        foreach ($this->metaBoxes as $box) {
            if ($this->metaBoxIsForCurrentPage($box)) {
                foreach ($box['meta_fields'] as $field) {
                    // Тип header предназначен для вывода заголовков в таблице. Там нет никакой информации.
                    if ($field['type'] == 'header') {
                        continue;
                    }

                    $oldFieldValue = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
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

}
