<?php
function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function dumpToJs($data, $variableName)
{
    echo '<script> const '.$variableName.'='.json_encode($data).'</script>';
}