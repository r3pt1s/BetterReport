<?php


namespace ReportSystem\listener;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use ReportSystem\api\API;
use ReportSystem\ReportSystem;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        if ($event->getPlayer()->hasPermission("reports.command")) {
            API::initPlayer($event->getPlayer()->getName());
        }

        if (isset(ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()])) {
            $player = $event->getPlayer();
            if (isset(ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()]["Status"])) {
                if (isset(ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()]["Reason"])) {
                    if (isset(ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()]["Report"])) {
                        if (isset(ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()]["Player"])) {
                            $reportedPlayer = ReportSystem::getInstance()->reportEdited[$player->getName()]["Player"];
                            if (($status = ReportSystem::getInstance()->reportEdited[$event->getPlayer()->getName()]["Status"]) == "ACCEPTED") {
                                $player->sendMessage(ReportSystem::getPrefix() . "§aWhile you were offline, your report was §2accepted §against §e" . $reportedPlayer . "§a!");
                                unset(ReportSystem::getInstance()->reportEdited[array_search($player->getName(), ReportSystem::getInstance()->reportEdited)]);
                            } else if ($status == "DENIED") {
                                $player->sendMessage(ReportSystem::getPrefix() . "§aWhile you were offline, your report was §crejected §against §e" . $reportedPlayer . "§a!");
                                unset(ReportSystem::getInstance()->reportEdited[array_search($player->getName(), ReportSystem::getInstance()->reportEdited)]);
                            }
                        }
                    }
                }
            }
        }
    }
}