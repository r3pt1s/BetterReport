<?php


namespace ReportSystem\forms;


use FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\utils\Process;
use ReportSystem\report\Report;
use ReportSystem\ReportSystem;

class ViewReportForm {

    public function send(Player $player, Report $report) {
        $form = new SimpleForm(function (Player $player, $data = null) use($report) {
            if ($data === null) {
                $player->sendMessage(ReportSystem::getPrefix() . "§cYou are no longer editing the §e" . $report->getName() . " §cReport!");
                $report->setReviewed(false);
                return true;
            } else if ($data == "cr") {
                $player->sendMessage(ReportSystem::getPrefix() . "§aYou are closed the Report §e" . $report->getName() . "§a!");
                $report->setReviewed(false);
                ReportSystem::getInstance()->getReportManager()->closeReport($report, "CLOSED");

                foreach (ReportSystem::getInstance()->getReportManager()->getReports() as $reports) {
                    if ($reports->getPlayer() == $report->getPlayer()) {
                        ReportSystem::getInstance()->getReportManager()->closeReport($reports, "CLOSED");
                    }
                }
            } else if ($data == "dr") {
                $player->sendMessage(ReportSystem::getPrefix() . "§aYou §crejected §athe §e" . $report->getName() . " §aReport!");
                $report->setReviewed(false);
                ReportSystem::getInstance()->getReportManager()->closeReport($report, "DENIED");

                foreach (ReportSystem::getInstance()->getReportManager()->getReports() as $reports) {
                    if ($reports->getPlayer() == $report->getPlayer()) {
                        if ($reports->getReason() == $report->getReason()) {
                            ReportSystem::getInstance()->getReportManager()->closeReport($reports, "DENIED");
                        }
                    }
                }
            } else if ($data == "ar") {
                $player->sendMessage(ReportSystem::getPrefix() . "§aYou §2accepted §athe §e" . $report->getName() . " §aReport!");
                $report->setReviewed(false);
                ReportSystem::getInstance()->getReportManager()->closeReport($report, "ACCEPTED");

                foreach (ReportSystem::getInstance()->getReportManager()->getReports() as $reports) {
                    if ($reports->getPlayer() == $report->getPlayer()) {
                        if ($reports->getReason() == $report->getReason()) {
                            ReportSystem::getInstance()->getReportManager()->closeReport($reports, "ACCEPTED");
                        }
                    }
                }
            } else {
                $player->sendMessage(ReportSystem::getPrefix() . "§cYou are no longer editing the §e" . $report->getName() . " §cReport!");
                $report->setReviewed(false);
                $form = new OpenedReportsForm();
                $form->send($player);
            }
        });
        $form->setTitle("§e" . $report->getName());
        $text = "§cPlayer: §e" . $report->getPlayer() . "\n§cReporter: §e" . $report->getReporter() . "\n§cReason: §e" . $report->getReason() . "\n\n§eChoose an Option!";
        $form->setContent($text);
        $form->addButton("§aAccept the Report", 0, "", "ar");
        $form->addButton("§cReject the Report", 0, "", "dr");
        $form->addButton("§4Close the Report", 0, "", "cr");
        $form->addButton("§cBack");
        $player->sendForm($form);
    }
}