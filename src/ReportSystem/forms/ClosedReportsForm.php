<?php


namespace ReportSystem\forms;


use FormAPI\SimpleForm;
use pocketmine\Player;
use ReportSystem\ReportSystem;

class ClosedReportsForm {

    public function send(Player $player) {
        $form = new SimpleForm(function (Player $player, $data = null): void {
            if ($data == null) {
                return;
            } else {
                if ($data == "back") {
                    $form = new ReportForm();
                    $form->send($player);
                } else {
                    $report = ReportSystem::getInstance()->getReportManager()->getCReportByName($data);
                    if ($report != null) {
                        $form = new ViewCReportForm();
                        $form->send($player, $report);
                    } else {
                        $player->sendMessage(ReportSystem::getPrefix() . "§cDer geschlossene Report existiert nicht!");
                    }
                }
            }
        });
        $form->setTitle("§cGeschlossene Reports");
        $form->setContent($this->getText());
        foreach (ReportSystem::getInstance()->getReportManager()->getCReports() as $report) {
            $form->addButton("§c" . $report->getName(), 0, "", $report->getName());
        }
        $form->addButton("§4Zurück", 0, "", "back");
        $player->sendForm($form);
    }

    private function getText() {
        if (empty(ReportSystem::getInstance()->getReportManager()->getCReports())) {
            return "§cEs sind keine geschlossenen Reports vorhanden!";
        } else {
            if (count(ReportSystem::getInstance()->getReportManager()->getCReports()) == 1) {
                return "§aEs ist §e" . count(ReportSystem::getInstance()->getReportManager()->getCReports()) . " geschlossener Report §avorhanden!";
            } else {
                return "§aEs sind §e" . count(ReportSystem::getInstance()->getReportManager()->getCReports()) . " geschlossene Reports §avorhanden!";
            }
        }
    }
}