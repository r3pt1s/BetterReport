<?php


namespace ReportSystem\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Server;
use ReportSystem\report\Report;
use ReportSystem\report\ReportManager;
use ReportSystem\ReportSystem;

class ReportCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (isset($args[0]) && isset($args[1])) {
            if (array_key_exists(strtolower($args[1]), ReportSystem::getInstance()->getReportManager()->getReasons())) {
                if (Server::getInstance()->hasOfflinePlayerData($args[0]) || ($player = Server::getInstance()->getPlayer($args[0])) != null) {
                    $reason = ReportSystem::getInstance()->getReportManager()->getReasons()[strtolower($args[1])];
                    $sender->sendMessage(ReportSystem::getPrefix() . "§aDu hast den Spieler §e" . $args[0] . " §aerfolgreich für §c§l" . $reason . " §r§areported!");
                    ReportSystem::getInstance()->getReportManager()->createReport(new Report($reason . "-" . ReportSystem::getInstance()->getReportManager()->getFreeId(), $reason, $args[0], $sender->getName()));
                } else {
                    $sender->sendMessage(ReportSystem::getPrefix() . "§cDiser Spieler war noch nie auf dem Server!");
                }
            } else {
                $sender->sendMessage(ReportSystem::getPrefix() . "§cInvalid Reason!");
                $sender->sendMessage(ReportSystem::getPrefix() . "§cValid Reasons: §e" . implode(", ", ReportSystem::getInstance()->getReportManager()->getReasons()));
            }
        } else {
            $sender->sendMessage(ReportSystem::getPrefix() . "§c/report <user> <reason>");
        }
        return true;
    }
}