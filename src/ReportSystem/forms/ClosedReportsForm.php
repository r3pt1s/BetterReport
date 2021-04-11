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
                        $player->sendMessage(ReportSystem::getPrefix() . "§cThe closed Report does'nt exists!");
                    }
                }
            }
        });
        $form->setTitle("§cClosed Reports");
        $form->setContent($this->getText());
        foreach (ReportSystem::getInstance()->getReportManager()->getCReports() as $report) {
            $form->addButton("§c" . $report->getName(), 0, "", $report->getName());
        }
        $form->addButton("§4Back", 0, "", "back");
        $player->sendForm($form);
    }

    private function getText() {
        if (empty(ReportSystem::getInstance()->getReportManager()->getCReports())) {
            return "§cNo closed reports available!";
        } else {
            if (count(ReportSystem::getInstance()->getReportManager()->getCReports()) == 1) {
                return "§aThere are §e" . count(ReportSystem::getInstance()->getReportManager()->getCReports()) . " closed Report §aavailable!";
            } else {
                return "§aThere are §e" . count(ReportSystem::getInstance()->getReportManager()->getCReports()) . " closed Reports §aavailable!";
            }
        }
    }
}