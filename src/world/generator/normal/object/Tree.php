<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\world\generator\normal\object;

use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\BirchTree;
use pocketmine\world\generator\object\JungleTree;
use pocketmine\world\generator\object\OakTree;

abstract class Tree extends \pocketmine\world\generator\object\Tree {

	public static function growTree(ChunkManager $world, int $x, int $y, int $z, Random $random, ?TreeType $type = null, bool $vines = false, bool $high = false): void {
		$type = $type ?? TreeType::OAK();
		if($type->equals(TreeType::OAK())) {
			if($vines) {
				(new SwampTree(VanillaBlocks::OAK_WOOD(), VanillaBlocks::OAK_LEAVES()))->placeObject($world, $x, $y, $z, $random);
				return;
			}
			if($random->nextBoundedInt(10) == 0) {
				(new BigOakTree($random))->placeObject($world, $x, $y, $z, $random);
				return;
			}
			(new OakTree())->getBlockTransaction($world, $x, $y, $z, $random)?->apply();
			return;
		}

		if($type->equals(TreeType::BIRCH())) {
			(new BirchTree($high))->getBlockTransaction($world, $x, $y, $z, $random)?->apply();
			return;
		}

		if($type->equals(TreeType::JUNGLE())) {
			(new JungleTree())->getBlockTransaction($world, $x, $y, $z, $random)?->apply();
			return;
		}

		if($type->equals(TreeType::ACACIA())) {
			(new AcaciaTree(VanillaBlocks::ACACIA_WOOD(), VanillaBlocks::ACACIA_LEAVES()));
			return;
		}

		if($type->equals(TreeType::DARK_OAK())) {
			(new DarkOakTree(VanillaBlocks::DARK_OAK_WOOD(), VanillaBlocks::DARK_OAK_LEAVES()));
			return;
		}
	}

	public function growMushroom(ChunkManager $world, int $x, int $y, int $z, Random $random): void {
		(new HugeMushroom(VanillaBlocks::BROWN_MUSHROOM(), VanillaBlocks::BROWN_MUSHROOM()))->placeObject($world, $x, $y, $z, $random);
	}
}