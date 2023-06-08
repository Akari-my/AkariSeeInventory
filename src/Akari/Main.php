<?php

namespace Akari;

use Akari\commands\AkariSeeCommand;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase{

    public function onEnable(){
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->saveDefaultConfig();

        $this->getLogger()->info(TF::RED . "==========( AkariSeeInventory )=========");
        $this->getLogger()->info(TF::GRAY . "» Version: " . $this->getDescription()->getVersion());
        $this->getLogger()->info(TF::GRAY . "» Author: Akari_my");
        $this->getLogger()->info(TF::GRAY . "» Support: https://discord.gg/hcQCmsvE");
        $this->getLogger()->info(TF::RED . "==========( AkariSeeInventory )=========");

        $this->registerCommands();
    }

    public function registerCommands(): void{
        $register = $this->getServer()->getCommandMap();
        $register->register("akariseeinventory", new AkariSeeCommand($this));
    }
}