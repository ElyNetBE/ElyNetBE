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

namespace pocketmine\world\generator\normal;

use pocketmine\world\generator\normal\biome\Badlands;
use pocketmine\world\generator\normal\biome\BadlandsPlateau;
use pocketmine\world\generator\normal\biome\Beach;
use pocketmine\world\generator\normal\biome\BirchForest;
use pocketmine\world\generator\normal\biome\DeepOcean;
use pocketmine\world\generator\normal\biome\Desert;
use pocketmine\world\generator\normal\biome\DesertHills;
use pocketmine\world\generator\normal\biome\ExtremeHills;
use pocketmine\world\generator\normal\biome\ExtremeHillsEdge;
use pocketmine\world\generator\normal\biome\ExtremeHillsMutated;
use pocketmine\world\generator\normal\biome\Forest;
use pocketmine\world\generator\normal\biome\ForestHills;
use pocketmine\world\generator\normal\biome\FrozenOcean;
use pocketmine\world\generator\normal\biome\FrozenRiver;
use pocketmine\world\generator\normal\biome\IceMountains;
use pocketmine\world\generator\normal\biome\IcePlains;
use pocketmine\world\generator\normal\biome\Jungle;
use pocketmine\world\generator\normal\biome\MushroomIsland;
use pocketmine\world\generator\normal\biome\MushroomIslandShore;
use pocketmine\world\generator\normal\biome\Ocean;
use pocketmine\world\generator\normal\biome\Plains;
use pocketmine\world\generator\normal\biome\River;
use pocketmine\world\generator\normal\biome\RoffedForestHills;
use pocketmine\world\generator\normal\biome\RoofedForest;
use pocketmine\world\generator\normal\biome\Savanna;
use pocketmine\world\generator\normal\biome\SavannaPlateau;
use pocketmine\world\generator\normal\biome\SunflowerPlains;
use pocketmine\world\generator\normal\biome\Swampland;
use pocketmine\world\generator\normal\biome\Taiga;
use pocketmine\world\generator\normal\biome\TaigaHills;
use pocketmine\world\generator\normal\biome\TallBirchForest;
use pocketmine\world\generator\normal\biome\types\Biome;
use pocketmine\world\biome\BiomeIds;
use InvalidArgumentException;
use function array_key_exists;

class BiomeFactory implements BiomeIds {

	private static BiomeFactory $instance;

	/** @var Biome[] */
	private array $biomes = [];

	public function registerBiome(int $id, Biome $biome): void {
		$biome->setId($id);

		$this->biomes[$id] = $biome;
	}

	public function getBiome(int $id): Biome {
		if(!array_key_exists($id, $this->biomes)) {
			throw new InvalidArgumentException("Biome with id $id is not registered.");
		}

		return $this->biomes[$id];
	}

	private static function init(): void {
		BiomeFactory::$instance = new self;

		BiomeFactory::$instance->registerBiome(BiomeIds::OCEAN, new Ocean());
		BiomeFactory::$instance->registerBiome(BiomeIds::PLAINS, new Plains());
		BiomeFactory::$instance->registerBiome(BiomeIds::DESERT, new Desert());
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS, new ExtremeHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::FOREST, new Forest());
		BiomeFactory::$instance->registerBiome(BiomeIds::TAIGA, new Taiga());
		BiomeFactory::$instance->registerBiome(BiomeIds::SWAMP, new Swampland());
		BiomeFactory::$instance->registerBiome(BiomeIds::RIVER, new River());
		BiomeFactory::$instance->registerBiome(BiomeIds::FROZEN_OCEAN, new FrozenOcean());
		BiomeFactory::$instance->registerBiome(BiomeIds::FROZEN_RIVER, new FrozenRiver());
		BiomeFactory::$instance->registerBiome(BiomeIds::ICE_PLAINS, new IcePlains());
		BiomeFactory::$instance->registerBiome(BiomeIds::ICE_MOUNTAINS, new IceMountains());
		BiomeFactory::$instance->registerBiome(BiomeIds::MUSHROOM_ISLAND, new MushroomIsland());
		BiomeFactory::$instance->registerBiome(BiomeIds::MUSHROOM_ISLAND_SHORE, new MushroomIslandShore());
		BiomeFactory::$instance->registerBiome(BiomeIds::BEACH, new Beach());
		BiomeFactory::$instance->registerBiome(BiomeIds::DESERT_HILLS, new DesertHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::FOREST_HILLS, new ForestHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::TAIGA_HILLS, new TaigaHills());
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS_EDGE, new ExtremeHillsEdge());
		BiomeFactory::$instance->registerBiome(BiomeIds::JUNGLE, new Jungle());
		// TODO: Ids 21 - 23
		BiomeFactory::$instance->registerBiome(BiomeIds::DEEP_OCEAN, new DeepOcean());
		// TODO: Ids 25 - 26
		BiomeFactory::$instance->registerBiome(BiomeIds::BIRCH_FOREST, new BirchForest());
		// TODO: Id 28
		BiomeFactory::$instance->registerBiome(BiomeIds::ROOFED_FOREST, new RoofedForest());
		// TODO Ids 30 - 34
		BiomeFactory::$instance->registerBiome(BiomeIds::SAVANNA, new Savanna());
		BiomeFactory::$instance->registerBiome(BiomeIds::SAVANNA_PLATEAU, new SavannaPlateau());
		BiomeFactory::$instance->registerBiome(BiomeIds::BADLANDS, new Badlands());
		BiomeFactory::$instance->registerBiome(BiomeIds::BADLANDS_PLATEAU, new BadlandsPlateau());
		// TODO Ids 39 - 128
		BiomeFactory::$instance->registerBiome(BiomeIds::SUNFLOWER_PLAINS, new SunflowerPlains());
		// TODO Id 130
		BiomeFactory::$instance->registerBiome(BiomeIds::EXTREME_HILLS_MUTATED, new ExtremeHillsMutated());
		// TODO Ids 132 - 154
		BiomeFactory::$instance->registerBiome(BiomeIds::TALL_BIRCH_FOREST, new TallBirchForest());
		// TODO Id 156
		BiomeFactory::$instance->registerBiome(BiomeIds::ROOFED_FOREST_HILLS, new RoffedForestHills());
	}

	public static function getInstance(): BiomeFactory {
		if(!isset(BiomeFactory::$instance)) {
			BiomeFactory::init();
		}

		return BiomeFactory::$instance;
	}
}