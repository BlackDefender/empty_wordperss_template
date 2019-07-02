<?php

class Utils
{
	const CSRF_TOKEN_NAME = 'csrf-token';
	
    public static function clearPhone($phone)
    {
        return str_replace(['-', ' ', '(', ')'], '', $phone);
    }

    public static function getAssetUrlWithTimestamp($pathFromThemeRoot)
    {
        if(mb_substr($pathFromThemeRoot, 0, 1) !== '/'){
            $pathFromThemeRoot = '/'.$pathFromThemeRoot;
        }
        $fullPath = get_template_directory().$pathFromThemeRoot;
        if(file_exists($fullPath)){
            return get_template_directory_uri().$pathFromThemeRoot.'?'.filemtime($fullPath);
        }else{
            return '';
        }
    }

    public static function getFileContentByAttachmentId($id)
    {
        $url = wp_get_attachment_url($id);
        if(!empty($url)){
            $file = file_get_contents($url);
        }
        return $file ? $file : '';
    }

    public static function getFullImageUrlByAttachmentId($id)
    {
        return wp_get_attachment_image_src( $id, 'full')[0];
    }

    public static function getNormalizedMetaData($postId = null, $postType = 'post', $showHiddenFields = false)
    {
        if(empty($postId)){
            global $post;
            $postId = $post->ID;
        }
        if (isset($postId)) {
            $metaData = get_metadata($postType, $postId, '');
            if (!empty($metaData)) {
                return self::normalizeMetaData($metaData, $showHiddenFields, $postId);
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    private static function normalizeMetaData($metaData, $showHiddenFields, $postId)
    {
        global $metaFieldsObj;
        if(!$showHiddenFields){
            $metaData = array_filter($metaData, function ($key) {
                return mb_substr($key, 0, 1) !== '_';
            }, ARRAY_FILTER_USE_KEY);
        }

        $metaDataUnserialized = array_map(function ($item) {
            return maybe_unserialize($item[0]);
        }, $metaData);

        $pageMetaFields = $metaFieldsObj->getPageMetaFields(get_post($postId));
        $pageMetaFieldsTypes = [];
        foreach ($pageMetaFields as $item){
            if(isset($item['id'])){
                $pageMetaFieldsTypes[$item['id']] = $item['type'];
            }
        }

        foreach ($metaDataUnserialized as $key => $value){
            if($pageMetaFieldsTypes[$key] === 'repeater'){
                $metaDataUnserialized[$key] = array_map(function ($item){ return (object)$item;}, $value);
            }
        }
        return $metaDataUnserialized;
    }

    // получить страницу связанную с шаблоном
    public static function getBindedPage($templateName)
    {
        $templateName = Utils::normalizeTemplateFileName($templateName);
        return (new WP_Query(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => basename($templateName),
        )))->posts[0];
    }

    public static function explodeList($listStr)
    {
        $listStr = str_replace("\n", ',', $listStr);
        $listStr = str_replace("\r", ',', $listStr);
        $list = explode(',', $listStr);
        $list = array_map('trim', $list);
        return array_filter($list);
    }

    private static function generateRandomString($stringLength = null)
    {
        if(empty($stringLength)){
            $stringLength = rand(40, 50);
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $stringLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function getRequestScheme()
    {
        return (!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https' : 'http';
    }

    public static function clearValue($val)
    {
        return nl2br(htmlspecialchars(trim($val), ENT_QUOTES));
    }

    public static function getNonceActionName()
    {
        $nonceActionFileName = __DIR__.'/_nonceActionName.php';
        if(file_exists($nonceActionFileName)){
            $actionName = require_once $nonceActionFileName;
        }else{
            $actionName = self::generateRandomString(10);
            file_put_contents($nonceActionFileName, "<?php return '$actionName';");
        }
        return $actionName;
    }
	
	public static function getTemplateFileName($postId)
    {
        return get_post_meta($postId, '_wp_page_template', true);
    }

    public static function getPageUrlByTemplateFileName($templateName)
    {
        return get_permalink(self::getBindedPage($templateName)->ID);
    }
	
	public static function emailRegExp() {
        return '/^(([^<>()\[\]\.,;:\s@"]+(\.[^<>()\[\]\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    }
	
	public static function emailValid($email) {
        return preg_match(self::emailRegExp(), $email);
    }
	
	public static function csrfField()
    {
        $nonce_field = '<input type="hidden" name="' . self::CSRF_TOKEN_NAME . '" value="' . wp_create_nonce( self::getNonceActionName() ) . '" />';
        $nonce_field .= wp_referer_field( false );
        return $nonce_field;
    }

    public static function getCsrfToken()
    {
        return wp_create_nonce( self::getNonceActionName() );
    }

	public static function cyr2lat($string)
	{
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '',    'ы' => 'y',   'ъ' => '',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
			'ґ' => 'g',   'і' => 'i',   'ї' => 'i',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
			'Ґ' => 'G',   'І' => 'I',   'Ї' => 'I',
		);
		return strtr($string, $converter);
	}

	private static function normalizeTemplateFileName($fileName)
    {
        if(substr($fileName, -4) !== '.php'){
            $fileName .= '.php';
        }
        return $fileName;
    }
}