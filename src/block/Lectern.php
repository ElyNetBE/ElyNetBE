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

namespace pocketmine\block;

use pocketmine\block\tile\Lectern as TileLectern;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\item\Item;
use pocketmine\item\WritableBookBase;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Lectern extends Transparent{
	use FacesOppositePlacingPlayerTrait;
	use HorizontalFacingTrait;

	protected int $viewedPage = 0;
	protected ?WritableBookBase $book = null;

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = BlockDataSerializer::readLegacyHorizontalFacing($stateMeta);
	}

	public function writeStateToMeta() : int{
		return BlockDataSerializer::writeLegacyHorizontalFacing($this->facing);
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileLectern){
			$this->viewedPage = $tile->getViewedPage();
			$this->book = $tile->getBook();
		}
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileLectern){
			$tile->setViewedPage($this->viewedPage);
			$tile->setBook($this->book);
		}
	}

	public function getStateBitmask() : int{
		return 0b11;
	}

	public function getFlammability() : int{
		return 30;
	}

	public function getViewedPage() : int{
		return $this->viewedPage;
	}

	/** @return $this */
	public function setViewedPage(int $viewedPage) : self{
		$this->viewedPage = $viewedPage;
		return $this;
	}

	public function getBook() : ?WritableBookBase{
		return $this->book !== null ? clone $this->book : null;
	}

	/** @return $this */
	public function setBook(?WritableBookBase $book) : self{
		if($book !== null){
			$book = clone $book;
			$book->setCount(1);
		}
		$this->book = $book;
		$this->viewedPage = 0;
		return $this;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($this->book === null && $item instanceof WritableBookBase){
			$this->position->getWorld()->setBlock($this->position, $this->setBook($item));
			$item->pop();
			return true;
		}
		return false;
	}

	public function onAttack(Item $item, int $face, ?Player $player = null) : bool{
		if($this->book !== null && !$this->book->isNull()){
			$this->position->getWorld()->dropItem($this->position->up(), $this->book);
			$this->position->getWorld()->setBlock($this->position, $this->setBook(null));
		}
		return false;
	}
}
