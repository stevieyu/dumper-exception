<?php

if (! function_exists('dd')) {
    function dd(...$vars)
    {
        throw new Stevie\DumperException\DdException($vars);
    }
}