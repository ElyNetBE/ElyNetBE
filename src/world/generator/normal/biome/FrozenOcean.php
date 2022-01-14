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

namespace pocketmine\world\generator\normal\biome;

use pocketmine\world\generator\normal\biome\types\Biome;
use pocketmine\block\VanillaBlocks;

class FrozenOcean extends Biome {

	public function __construct() {
		parent::__construct(0, 0.5);

		$this->setGroundCover([
			VanillaBlocks::GRAVEL(),
			VanillaBlocks::GRAVEL()
		]);

		$this->setElevation(55, 57);
	}

	public function isFrozen(): bool {
		return true;
	}

	public function getName(): string {
		return "Frozen Ocean";
	}
}