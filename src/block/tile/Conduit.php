<?php

namespace pocketmine\block\tile;

use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;

class Conduit extends Spawnable {

	public const TAG_TARGET = "Target"; //TAG_Long
	public const TAG_ACTIVATE = "Active"; //TAG_Byte

	private int $target = -1;
	private bool $activate = false;

	public function getTarget() : int{
		return $this->target;
	}

	public function setTarget(int $target) : void{
		$this->target = $target;
	}

	public function isActivate() : bool{
		return $this->activate;
	}

	public function setActivate(bool $activate) : void{
		$this->activate = $activate;
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt): void
	{
		$nbt->setLong(self::TAG_TARGET, $this->target);
		$nbt->setByte(self::TAG_ACTIVATE, $this->activate ? 1 : 0);
		$nbt->setByte("IsMovable", 1);
	}

	public function readSaveData(CompoundTag $nbt): void
	{
		$this->target = $nbt->getLong(self::TAG_TARGET, -1);
		$this->activate = $nbt->getByte(self::TAG_ACTIVATE, 0) === 1;
	}

	protected function writeSaveData(CompoundTag $nbt): void
	{
		$nbt->setLong(self::TAG_TARGET, $this->target);
		$nbt->setByte(self::TAG_ACTIVATE, $this->activate ? 1 : 0);
	}
}