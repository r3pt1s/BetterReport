<?php


namespace ReportSystem\forms;


use FormAPI\SimpleForm;
use pocketmine\Player;
use ReportSystem\report\Report;
use ReportSystem\ReportSystem;

class ViewCReportForm {

    public function send(Player $player, Report $report) {
        $form = new SimpleForm(function (Player $player, $data = null) use($report) {
            if ($data === null) {
                $form = new ClosedReportsForm();
                $form->send($player);
                return true;
            }

            switch ($data) {
                case 0:
                    $form = new ClosedReportsForm();
                    $form->send($player);
            }
        });
        $text = "§cSpieler: §e" . $report->getPlayer() . "\n§cReporter: §e" . $report->getReporter() . "\n§cGrund: §e" . $report->getReason() . "\n\n§aWähle eine Option aus!";
        $form->setTitle("§e" . $report->getName());
        $form->setContent($text);
        $form->addButton("§cZurück");
        $player->sendForm($form);
    }
}