<?php


namespace ReportSystem\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use ReportSystem\forms\ReportForm;

class ReportsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("reports.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                $form = new ReportForm();
                $form->send($sender);
            }
        }
        return true;
    }
}