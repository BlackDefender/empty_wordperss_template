<?php

// описание полей
/*
$exampleFieldsSet = [
    [
        'label' => 'Текст заголовка',
        'type'  => 'header',
    ],
    [
        'label' => 'Текстовое поле',
        'desc'  => 'Описание для поля.',
        'id'    => 'mytextinput',
        'type'  => 'text',
    ],
    [
        'label' => 'Большое текстовое поле',
        'desc'  => 'Описание для поля.',
        'id'    => 'mytextarea',
        'type'  => 'textarea',
    ],
    [
        'label' => 'Чекбоксы (флажки]',
        'desc'  => 'Описание для поля.',
        'id'    => 'mycheckbox',
        'type'  => 'checkbox',
    ],
    [
        'label' => 'Всплывающий список',
        'desc'  => 'Описание для поля.',
        'id'    => 'myselect',
        'type'  => 'select',
        'options' => [  // Параметры, всплывающие данные
            'one' => [
                'label' => 'Вариант 1',  // Название поля
                'value' => '1',  // Значение
            ],
            'two' => [
                'label' => 'Вариант 2',
                'value' => '2',
            ],
            'three' => [
                'label' => 'Вариант 3',
                'value' => '3',
            ],
        ],
    ],
    [
        'label' => 'Список заголовков постов',
        'desc'  => '',
        'id'    => 'author',
        'type'  => 'posts-list',
        'target_post_type' => 'workers',// тип постов для вывода
        'intro_text' => 'Вступительный текст для первого пункта меню',
    ],
    [
        'label' => 'Изображение',
        'desc'  => 'Выберите изображение',
        'id'    => 'my_image',  //айдишник элемента, у инпутов используется в качестве имени. нужен при выборке метаданных
        'type'  => 'image',  // Указываем тип поля.
    ],
    [
        'label' => 'Аудиозапись',
        'id' => 'audio',
        'type' => 'audio',
    ],
    [
        'label' => 'PDF-файл',
        'id' => 'pdf',
        'type' => 'pdf',
    ],
    [
        'label' => 'Видео',
        'id' => 'video',
        'type' => 'video',
    ],
    [
        'label' => 'Любой файл',
        'id' => 'any-file',
        'type' => 'file',
    ],
    [
        'label' => 'Визуальный редактор',
        'id' => 'visual_editor', // только нижнее подчеркивание. с тире будет ошибка
        'type' => 'wysiwyg',
    ],
    [
        'label' => 'Галерея',
        'desc'  => 'Описание для поля.',
        'id'    => 'my_gallery',
        'type'  => 'repeater',
        'display' => 'line', // stack || line
        'behavior' => 'gallery', // list || gallery
        'fields' => [
            [
                'label' => 'Изображение',
                'type' => 'image',
                'id' => 'image_field_id',
            ],
            [
                'label' => 'Текстовый ввод',
                'type' => 'text',
                'id' => 'alt_field_id',
            ],
        ]
    ],
    [
        'label' => 'Смешанный массив',
        'desc'  => 'Описание для поля.',
        'id'    => 'my_repeater',
        'type'  => 'repeater',
        'display' => 'stack', //  stack || line
        'behavior' => 'list', // list || gallery
        'fields' => [
            [
                'label' => 'Текстовое поле',
                'type' => 'textarea',
                'id' => 'textarea_field_id',
            ],
            [
                'label' => 'Текстовый ввод',
                'type' => 'text',
                'id' => 'text_field_id',
            ],
            [
                'label' => 'Изображение',
                'type' => 'image',
                'id' => 'image_field_id',
            ],
            [
                'label' => 'Аудиозапись',
                'type' => 'audio',
                'id' => 'audio_field_id',
            ],
            [
                'label' => 'PDF',
                'type' => 'pdf',
                'id' => 'pdf_field_id',
            ],
            [
                'label' => 'Видео',
                'type' => 'video',
                'id' => 'video_field_id',
            ],
            [
                'label' => 'Любой файл',
                'type' => 'file',
                'id' => 'any-file',
            ],
            [
                'label' => 'Страница',
                'type' => 'postsList',
                'id' => 'postsList_field_id',
                'post_type' => 'post',
                'intro_text' => 'Выберите страницу',
            ],
        ],
    ],
];
*/


// metaboxes description
$meta_boxes = [
/*
    [
        'post_type' => 'page',
        'post_id' => '2',
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
    [
        'post_type' => 'page',
        'post_id' => [3, 4, 5],
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
	[
        'post_type' => 'page',
        'post_not_id' => ['2', 3, 4],
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
    [
        'post_type' => 'post',
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
    [
        'template' => 'page-sample.php',
        'post_type' => 'page',
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
	[
        'template' => ['page-sample.php', 'page-other.php'],
        'post_type' => 'page',
        'meta_fields' => $exampleFieldsSet,
        'title' => 'Данные для страницы',
    ],
	*/
];
