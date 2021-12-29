<?php

/*
 *   
 *   ███╗░░██╗░█████╗░░█████╗░██████╗░███╗░░░███╗░█████╗░██████╗░░██████╗░
 *   ████╗░██║██╔══██╗██╔══██╗██╔══██╗████╗░████║██╔══██╗██╔══██╗██╔════╝░
 *   ██╔██╗██║██║░░██║██║░░██║██████╦╝██╔████╔██║██║░░╚═╝██████╦╝██║░░██╗░
 *   ██║╚████║██║░░██║██║░░██║██╔══██╗██║╚██╔╝██║██║░░██╗██╔══██╗██║░░╚██╗
 *   ██║░╚███║╚█████╔╝╚█████╔╝██████╦╝██║░╚═╝░██║╚█████╔╝██████╦╝╚██████╔╝
 *   ╚═╝░░╚══╝░╚════╝░░╚════╝░╚═════╝░╚═╝░░░░░╚═╝░╚════╝░╚═════╝░░╚═════╝░
 *
 *               Copyright (C) 2021-2022 NoobMCBG
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *              (at your option) any later version.
 *
 */

declare(strict_types=1);

namespace NoobMCBG\Freeze;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerMoveEvent;
use NoobMCBG\Freeze\commands\FreezeCommands;
use NoobMCBG\Freeze\commands\UnFreezeCommands;

class Freeze extends PluginBase implements Listener {

	public static $instance;

	public static function getInstance(){
		return self::$instance;
	}

	public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->freeze = new Config($this->getDataFolder() . "freeze.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("Freeze", new FreezeCommands($this));
        $this->getServer()->getCommandMap()->register("Freeze", new UnFreezeCommands($this));
	}

    public function onMove(PlayerMoveEvent $ev){
        $player = $ev->getPlayer();
        if($this->freeze->get(strtolower($player->getName())) == true){
            $ev->cancel();
            if($this->getConfig()->getAll()["msg-freeze"]["mode"] == "message"){
                $player->sendMessage(str_replace(["{line}", "{player}", "&"], ["\n", $player->getName(), "§"], strval($this->getConfig()->getAll()["msg-freeze"]["msg"])));
            }
            if($this->getConfig()->getAll()["msg-freeze"]["mode"] == "popup"){
                $player->sendPopup(str_replace(["{line}", "{player}", "&"], ["\n", $player->getName(), "§"], strval($this->getConfig()->getAll()["msg-freeze"]["msg"])));
            }
            if($this->getConfig()->getAll()["msg-freeze"]["mode"] == "title"){
                $player->addTitle(str_replace(["{line}", "{player}", "&"], ["\n", $player->getName(), "§"], strval($this->getConfig()->getAll()["msg-freeze"]["msg"])));
            }
        }
    }
}