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

namespace NoobMCBG\Freeze\commands;

use pocketmine\Player;
use pocketmine\command\{Command, CommandSender};
use pocketmine\plugin\{Plugin, PluginBase};
use NoobMCBG\Freeze\Freeze;

class FreezeCommands extends Command {

    public function __construct(Freeze $plugin) {
        $this->plugin = $plugin;
        parent::__construct("freeze", "Freeze Player", \null, ["fz"]);
        $this->setPermission("freeze.command");
    }

    public function getPlugin(){
    	return $this->plugin;
    }

    public function execute(CommandSender $sender, string $label, array $args){
        if(!isset($args[0])){
            $sender->sendMessage("§cUsage: §7/freeze <player>");
            return true;
        }
        $p = $this->getPlugin()->getServer()->getPlayerByPrefix($args[0]);
        if($this->getPlugin()->getConfig()->get("freeze-no-online") == true){
            if(!$p instanceof Player){
            	$this->getPlugin()->freeze->set(strtolower($p->getName()), true);
                $this->getPlugin()->freeze->save();
                $sender->sendMessage(str_replace(["{line}", "{player}", "&"], ["\n", $p->getName(), "§"], strval($this->getPlugin()->getConfig()->get("msg-freeze-successfully"))));
            	return true;
            }
        }else{
            $sender->sendMessage(str_replace(["{line}", "{player}", "&"], ["\n", $p->getName(), "§"], strval($this->getPlugin()->getConfig()->get("player-not-online"))));  
        }
    }
}