<?php

require_once __DIR__.'/../../../../wp-load.php';
require_once './../autoload.php';

if( !wp_verify_nonce($_POST['csrf-token'], Utils::getNonceActionName()) || (!empty($_POST['email']) && !Utils::emailValid($_POST['email'])) ){
    SendMail::badRequest();
}

$fields = [
    ['name', 'Имя'],
    ['tel', 'Номер телефона'],
    ['email', 'E-mail'],
    ['message', 'Сообщение'],
];

$sendMail = new SendMail($fields);
$sendMail->serveRequest();