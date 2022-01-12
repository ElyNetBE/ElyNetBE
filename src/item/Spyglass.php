<?php

namespace pocketmine\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\item\StringToItemParser;

class Spyglass extends Item
{

    public function getMaxStackSize(): int
    {
        return 1;
    }
}