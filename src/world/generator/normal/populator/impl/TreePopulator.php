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

use pocketmine\world\generator\normal\object\Tree;
use pocketmine\world\generator\normal\populator\AmountPopulator;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class TreePopulator extends AmountPopulator {

	private ?TreeType $treeType;

	private bool $vines;

	private bool $high;

	public function __construct(int $baseAmount, int $randomAmount, int $spawnPercentage = 100, ?TreeType $treeType = null, bool $vines = false, bool $high = false) {
		$this->treeType = $treeType;
		$this->vines = $vines;
		$this->high = $high;

		parent::__construct($baseAmount, $randomAmount, $spawnPercentage);
	}

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if(!$this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, [VanillaBlocks::GRASS(), VanillaBlocks::MYCELIUM()], $x, $y, $z)) {
			return;
		}

		Tree::growTree($world, $chunkX * 16 + $x, $y, $chunkZ * 16 + $z, $random, $this->treeType, $this->vines, $this->high);
	}
}
