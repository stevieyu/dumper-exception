<?php

use Stvy\DumperException\Dumper\CliDumper;
use Stvy\DumperException\Cloner\VarCloner;

if (! function_exists('dd')) {
    function dd(...$vars)
    {
        if(in_array(PHP_SAPI, ['cli', 'phpdbg'], true)){
            $dumper = new CliDumper();
            $cloner = new VarCloner();

            foreach ($vars as $var){
                $dumper->dump($cloner->cloneVar($var));
            }
        }
        throw new Stvy\DumperException\DdException($vars);
    }
}