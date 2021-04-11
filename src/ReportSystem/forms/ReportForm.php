<?php


namespace ReportSystem\forms;


use FormAPI\SimpleForm;
use pocketmine\item\ItemFactory;
use pocketmine\Player;

class ReportForm {

    public function send(Player $player) {
        $form = new SimpleForm(function (Player $player, $data = null): void {
            if ($data == null) {
                return;
            }

            if ($data == "or") {
                $form = new OpenedReportsForm();
                $form->send($player);
            } else if ($data == "cr") {
                $form = new ClosedReportsForm();
                $form->send($player);
            } else if ($data == "s") {
                $form = new SettingsForm();
                $form->send($player);
            } else if ($data == "close") {
                $player->removeAllWindows();
            }
        });
        $form->setTitle("§eReportSystem");
        $form->setContent("§eChoose an Option!");
        $form->addButton("§aOpen Reports", 0, "", "or");
        $form->addButton("§cClosed Reports", 0, "", "cr");
        $form->addButton("§bSettings", 0, "", "s");
        $form->addButton("§4Close", 0, "", "close");
        $player->sendForm($form);
    }
}