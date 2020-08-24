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
