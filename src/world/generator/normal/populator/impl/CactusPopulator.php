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

namespace pocketmine\world\generator\normal\populator\impl;

use pocketmine\world\generator\normal\populator\AmountPopulator;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class CactusPopulator extends AmountPopulator {

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if(!$this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, [VanillaBlocks::SAND(), VanillaBlocks::RED_SAND()], $x, $y, $z)) {
			return;
		}

		$x += $chunkX * 16;
		$z += $chunkZ * 16;

		if(
			!$world->getBlockAt($x + 1, $y, $z)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x, $y, $z + 1)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x - 1, $y, $z)->isSameType(VanillaBlocks::AIR()) ||
			!$world->getBlockAt($x, $y, $z - 1)->isSameType(VanillaBlocks::AIR())
		) {
			return;
		}

		$size = $random->nextBoundedInt(4);
		for($i = 0; $i < $size; ++$i) {
			$world->setBlockAt($x, $y + $i, $z, VanillaBlocks::CACTUS());
		}
	}
}
