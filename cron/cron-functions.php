<?php

/**
 * Минифицирует css.
 *
 * @param string $css
 *
 * @return string
 * */
function minimizeCSS($css)
{
    $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
    $css = preg_replace('/\s{2,}/', ' ', $css);
    $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
    $css = preg_replace('/;}/', '}', $css);
    return $css;
}

/**
 * Добавляем к объявлению шрифта параметр font-display:swap;
 * Это необходимо чтоб браузер рендерил текст не дожидаясь загрузки шрифтов.
 * После загрузки произойдет подмена системного шрифта на указанный.
 *
 * @param string $css
 *   CSS-код подключения шрифтов.
 *
 * @return string
 *
 * */
function fontSwap($css)
{
    return str_replace('@font-face{', '@font-face{font-display:swap;', $css);
}

/**
 * Пропускает данные через набор фильтров.
 *
 * @param array $filters
 *   Массив названий функций фильтрации.
 *   Порядок выполнения слева направо.
 *
 * @param mixed $data
 *   Данные для обработки
 *
 * @return mixed
 *   Обработанные данные
 *
 * */
function filtersConveyor($filters, $data)
{
    return array_reduce($filters, function ($data, $filter) {
        return call_user_func($filter, $data);
    }, $data);
}

/**
 * Делает GET-запрос и созраняет результат в файл.
 * Если файл существует он будет перезаписан.
 *
 * @param string $url
 *   Адрес запроса
 *
 * @param string $cacheFileName
 *   Имя файла для сохранения данных
 *
 * @param array $filters
 *   Массив названий функций фильтрации.
 *   Порядок выполнения слева направо.
 * */
function makeCache($url, $cacheFileName, $filters = null)
{
    $data = file_get_contents($url);
    if ($data) {
        if ($filters) {
            $data = filtersConveyor($filters, $data);
        }
        $fontsFileName = __DIR__ . '/../cache/' . $cacheFileName;
        file_put_contents($fontsFileName, $data, LOCK_EX);
    }
}