<?php


namespace ReportSystem\forms;


use FormAPI\SimpleForm;
use pocketmine\Player;
use ReportSystem\ReportSystem;

class OpenedReportsForm {

    public function send(Player $player) {
        $form = new SimpleForm(function (Player $player, $data = null): void {
            if ($data == null) {
                return;
            } else {
                if ($data == "back") {
                    $form = new ReportForm();
                    $form->send($player);
                } else {
                    $report = ReportSystem::getInstance()->getReportManager()->getReportByName($data);
                    if ($report != null) {
                        if ($report->isReviewed()) {
                            $player->sendMessage(ReportSystem::getPrefix() . "§cDer Report §e" . $report->getName() . " §cwird bereits bearbeitet!");
                        } else {
                            $player->sendMessage(ReportSystem::getPrefix() . "§aDu bearbeitest nun den Report §e" . $report->getName() . "§a...");
                            $form = new ViewReportForm();
                            $form->send($player, $report);
                            $report->setReviewed(true);
                        }
                    } else {
                        $player->sendMessage(ReportSystem::getPrefix() . "§cDer Report existiert nicht!");
                    }
                }
            }
        });
        $form->setTitle("§aOffene Reports");
        $form->setContent($this->getText());
        foreach (ReportSystem::getInstance()->getReportManager()->getReports() as $report) {
            if ($report->isReviewed()) {
                $form->addButton("§e" . $report->getName(), 0, "", $report->getName());
            } else {
                $form->addButton("§a" . $report->getName(), 0, "", $report->getName());
            }
        }
        $form->addButton("§4Zurück", 0, "", "back");
        $player->sendForm($form);
    }

    public function getText(): string {
        if (empty(ReportSystem::getInstance()->getReportManager()->getReports())) {
            return "§cEs sind keine Reports vorhanden!";
        } else {
            if (count(ReportSystem::getInstance()->getReportManager()->getReports()) == 1) {
                return "§aEs ist §e" . count(ReportSystem::getInstance()->getReportManager()->getReports()) . " Report §avorhanden!";
            } else {
                return "§aEs sind §e" . count(ReportSystem::getInstance()->getReportManager()->getReports()) . " Reports §avorhanden!";
            }
        }
    }
}