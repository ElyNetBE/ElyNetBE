<?php

namespace pocketmine\block;

use pocketmine\block\BlockIds;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Beehive extends Opaque {
    use HorizontalFacingTrait;

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
        $this->facing = match($player->getHorizontalFacing()){
            Facing::NORTH => Facing::DOWN,
            Facing::EAST => Facing::UP,
            Facing::SOUTH => Facing::NORTH,
            Facing::WEST => Facing::SOUTH,
            default => $face
        };
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}