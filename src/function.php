<?php

if (! function_exists('dd')) {
    function dd(...$vars)
    {
        throw new Stvy\DumperException\DdException($vars);
    }
}