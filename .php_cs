<?php

require_once __DIR__.'/vendor/sllh/php-cs-fixer-styleci-bridge/autoload.php';

use SLLH\StyleCIBridge\ConfigBridge;

$header = <<<EOF
(c) Jean-Baptiste Audebert <audebert.jb@gmail.com>
(c) Jérémy Marodon         <marodon.jeremy@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$config = ConfigBridge::create()
    ->setUsingCache(true)
;

return $config
    ->setRules(array_merge($config->getRules(), [
        'header_comment' => ['header' => $header]
    ]))
;
