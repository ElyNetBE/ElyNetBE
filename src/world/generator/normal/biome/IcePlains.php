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

use pocketmine\world\generator\normal\biome\types\GrassyBiome;
use pocketmine\world\generator\normal\biome\types\SnowyBiome;
use pocketmine\world\generator\normal\populator\impl\TreePopulator;
use pocketmine\block\utils\TreeType;

class IcePlains extends GrassyBiome implements SnowyBiome {

	public function __construct() {
		parent::__construct(0, 0.5);
		$this->addPopulator(new TreePopulator(2, 2, 90, TreeType::SPRUCE()));
		$this->setElevation(100, 106);
	}

	public function getName(): string {
		return "Ice Plains";
	}
}