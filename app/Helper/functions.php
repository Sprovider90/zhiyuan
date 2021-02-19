<?php

function getSql ()
{
    app('db')->listen(function ($sql) {
        $singleSql = $sql->sql;
        if ($sql->bindings) {
            foreach ($sql->bindings as $replace) {
                $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                $singleSql = preg_replace('/\?/', $value, $singleSql, 1);
            }
        }
        dump($singleSql);
    });
}
function arrayToArrayKey($arr, $field, $group = 0)
{
    $array = [];
    if (empty($arr)) {
        return $array;
    }
    if ($group == 0) {

        foreach ($arr as $v) {
            if (array_key_exists($field, $v)) {
                $array[$v[$field]] = $v;
            }
        }
    } else {
        foreach ($arr as $v) {
            if (array_key_exists($field, $v)) {
                $array[$v[$field]][] = $v;
            }
        }
    }

    return $array;
}
