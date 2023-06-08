<?php

namespace Akari\commands;

use Akari\Main;
use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class AkariSeeCommand extends Command {

    protected Main $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        parent::__construct("akarisee", "AkariSee Command");
        $this->setPermission("akarisee.use");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
        if ($sender instanceof Player) {
            if ($sender->hasPermission("akarisee.use")) {
                if (count($args) < 1) {
                    $sender->sendMessage($config->get("prefix") . $config->get("command_usage"));
                    return true;
                }
                $target = $this->plugin->getServer()->getPlayer($args[0]);
                if ($target === null) {
                    $sender->sendMessage($config->get("prefix") . $config->get("player_not_found"));
                    return true;
                }
                $this->openInventory($sender, $target);
            } else {
                $sender->sendMessage($config->get("prefix") . $config->get("player_not_permession"));
            }
        } else {
            $sender->sendMessage($config->get("prefix") . $config->get("command_console"));
        }
        return true;
    }

    public function openInventory(Player $viewer, Player $target): void {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST)
            ->setName("Inventory of §c" . $target->getName())
            ->setListener(function (\muqsit\invmenu\transaction\InvMenuTransaction $transaction): \muqsit\invmenu\transaction\InvMenuTransactionResult {
                return $transaction->discard();
            })#
            ->setInventoryCloseListener(function (Player $viewer, InvMenuInventory $inventory) use ($target): void {
                $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
                $viewer->sendMessage($config->get("prefix") . $config->get("player_inventory_closed") . $target->getName());
            });
        $menu->getInventory()->setContents($target->getInventory()->getContents());
        $menu->send($viewer);
    }
}