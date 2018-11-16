<?php

require_once 'cron-functions.php';

makeCache('https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,700,900&subset=cyrillic', 'fonts.css', ['minimizeCSS', 'fontSwap']);