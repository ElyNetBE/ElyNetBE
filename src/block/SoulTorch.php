<?php

namespace pocketmine\block;

use pocketmine\block\Torch;

class SoulTorch extends Torch {

    public function getLightLevel() : int{
        return 10;
    }
}