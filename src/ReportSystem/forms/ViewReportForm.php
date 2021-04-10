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
                $player->sendMessage(ReportSystem::getPrefix() . "§cDu bearbeitest nun nicht mehr den Report §e" . $report->getName() . "§c!");
                $report->setReviewed(false);
                return true;
            } else if ($data == "cr") {
                $player->sendMessage(ReportSystem::getPrefix() . "§aDu hast den Report §e" . $report->getName() . " §ageschlossen!");
                $report->setReviewed(false);
                ReportSystem::getInstance()->getReportManager()->closeReport($report, "CLOSED");

                foreach (ReportSystem::getInstance()->getReportManager()->getReports() as $reports) {
                    if ($reports->getPlayer() == $report->getPlayer()) {
                        ReportSystem::getInstance()->getReportManager()->closeReport($reports, "CLOSED");
                    }
                }
            } else if ($data == "dr") {
                $player->sendMessage(ReportSystem::getPrefix() . "§aDu hast den Report §e" . $report->getName() . " §cabgelehnt§a!");
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
                $player->sendMessage(ReportSystem::getPrefix() . "§aDu hast den Report §e" . $report->getName() . " §2angenommen§a!");
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
                $player->sendMessage(ReportSystem::getPrefix() . "§cDu bearbeitest nun nicht mehr den Report §e" . $report->getName() . "§c!");
                $report->setReviewed(false);
                $form = new OpenedReportsForm();
                $form->send($player);
            }
        });
        $form->setTitle("§e" . $report->getName());
        $text = "§cSpieler: §e" . $report->getPlayer() . "\n§cReporter: §e" . $report->getReporter() . "\n§cGrund: §e" . $report->getReason() . "\n\n§aWähle eine Option aus!";
        $form->setContent($text);
        $form->addButton("§aReport Annehmen", 0, "", "ar");
        $form->addButton("§cReport Ablehnen", 0, "", "dr");
        $form->addButton("§4Report Schließen", 0, "", "cr");
        $form->addButton("§cZurück");
        $player->sendForm($form);
    }
}