<?php
function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function dumpToJs($data, $variableName = 'data')
{
    echo '<script> const '.$variableName.'='.json_encode($data).'</script>';
}
