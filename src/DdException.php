<?php

namespace Stvy\DumperException;

use Exception;
use Stvy\DumperException\Cloner\VarCloner;
use Stvy\DumperException\Dumper\HtmlDumper;
use Stvy\DumperException\Dumper\CliDumper;

class DdException extends Exception
{
    public $vars = [];

    public function __construct(array $vars)
    {
        $this->vars = $vars;
        $this->message = json_encode($vars);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $dump = function ($var) {
            if(in_array(PHP_SAPI, ['cli', 'phpdbg'], true)){
                return (new CliDumper());
            }
            $data = (new VarCloner())->cloneVar($var)->withMaxDepth(3);

            return (string) (new HtmlDumper(false))->dump($data, true, [
                'maxDepth' => 3,
                'maxStringLength' => 160,
            ]);
        };

        return collect($this->vars)->map($dump)->implode('');
    }
}
