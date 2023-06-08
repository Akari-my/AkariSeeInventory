<?php

namespace Akari\commands;

use Akari\Main;
use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\Player;

class AkariSeeCommand extends Command {

    protected Main $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        parent::__construct("akarisee", "AkariSee Command");
        $this->setPermission("akarisee.use");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (count($args) < 1) {
                $sender->sendMessage("Use: /akarisee <player>");
                return true;
            }
            $target = $this->plugin->getServer()->getPlayer($args[0]);
            if ($target === null) {
                $sender->sendMessage("The player is not online.");
                return true;
            }
            $this->openInventory($sender, $target);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }

    public function openInventory(Player $viewer, Player $target): void {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST)
            ->setName("Inventario di " . $target->getName())
            ->setListener(function (\muqsit\invmenu\transaction\InvMenuTransaction $transaction): \muqsit\invmenu\transaction\InvMenuTransactionResult {
                return $transaction->discard();
            })
            ->setInventoryCloseListener(function (Player $viewer, InvMenuInventory $inventory) use ($target): void {
                $viewer->sendMessage("You have closed the inventory of " . $target->getName());
            });
        $menu->getInventory()->setContents($target->getInventory()->getContents());
        $menu->send($viewer);
    }
}
