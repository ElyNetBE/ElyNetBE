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

namespace pocketmine\world\generator\hell;

use pocketmine\world\generator\hell\populator\GlowstoneSphere;
use pocketmine\world\generator\hell\populator\Ore;
use pocketmine\world\generator\hell\populator\SoulSand;
use pocketmine\world\biome\BiomeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\biome\BiomeRegistry;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\Generator;
use pocketmine\world\generator\noise\Simplex;
use pocketmine\world\generator\object\OreType;
use pocketmine\world\generator\populator\Populator;
use function abs;

class Nether extends Generator {

	/** @var Populator[] */
	private array $populators = [];

	private int $waterHeight = 32;

	private int $emptyHeight = 64;

	private int $emptyAmplitude = 1;

	private float $density = 0.5;

	/** @var Populator[] */
	private array $generationPopulators = [];

	private Simplex $noiseBase;

	public function __construct(int $seed, string $preset) {
		parent::__construct($seed, $preset);

		$this->random->setSeed($seed);
		$this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 64);
		$this->random->setSeed($seed);

		$ores = new Ore();
		$ores->setOreTypes([
			new OreType(VanillaBlocks::NETHER_QUARTZ_ORE(), VanillaBlocks::NETHERRACK(), 14, 0, 0, 128)
		]);
		//$test = new Ore();
		//$test->setOreTypes([new OreType(BlockVanilla::ANCIENT_DEBRIS, VanillaBlocks::NETHERRACK(), 4, 0, 1, 10)]);
		//$this->populators[] = $test;
		$this->populators[] = $ores;
		$this->populators[] = new GlowstoneSphere();
		$this->populators[] = new SoulSand();
	}

	public function init(ChunkManager $world, Random $random): void {

	}

	public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->seed);

		$noise = $this->noiseBase->getFastNoise3D(16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);

		/** @var Chunk $chunk */
		$chunk = $world->getChunk($chunkX, $chunkZ);

		$bedrock = VanillaBlocks::BEDROCK()->getFullId();
		$netherrack = VanillaBlocks::NETHERRACK()->getFullId();
		$stillLava = VanillaBlocks::LAVA()->getFullId();

		for($x = 0; $x < 16; ++$x) {
			for($z = 0; $z < 16; ++$z) {
				$chunk->setBiomeId($x, $z, BiomeIds::NETHER);

				for($y = 0; $y < 128; ++$y) {
					if($y === 0 or $y === 127) {
						$chunk->setFullBlock($x, $y, $z, $bedrock);
						continue;
					}
					$noiseValue = (abs($this->emptyHeight - $y) / $this->emptyHeight) * $this->emptyAmplitude - $noise[$x][$z][$y];
					$noiseValue -= 1 - $this->density;

					if($noiseValue > 0) {
						$chunk->setFullBlock($x, $y, $z, $netherrack);
					} elseif($y <= $this->waterHeight) {
						$chunk->setFullBlock($x, $y, $z, $stillLava);
					}
				}
			}
		}

		foreach($this->generationPopulators as $populator) {
			$populator->populate($world, $chunkX, $chunkZ, $this->random);
		}
	}

	public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->seed);
		foreach($this->populators as $populator) {
			$populator->populate($world, $chunkX, $chunkZ, $this->random);
		}

		$biome = BiomeRegistry::getInstance()->getBiome(BiomeIds::NETHER);
		$biome->populateChunk($world, $chunkX, $chunkZ, $this->random);
	}
}