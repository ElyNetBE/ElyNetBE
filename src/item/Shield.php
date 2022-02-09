<?php

namespace pocketmine\item;

use pocketmine\item\Durable;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class Shield extends Durable{

    public function getMaxDurability(): int{
        return 336;
    }

    public function getMaxStackSize(): int{
        return 1;
    }
}