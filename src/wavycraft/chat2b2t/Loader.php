<?php

declare(strict_types=1);

namespace wavycraft\chat2b2t;

use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}
