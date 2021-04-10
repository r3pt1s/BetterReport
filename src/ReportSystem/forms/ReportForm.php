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
        $form->setContent("§7Wähle eine Option aus!");
        $form->addButton("§aOffene Reports", 0, "", "or");
        $form->addButton("§cGeschlossene Reports", 0, "", "cr");
        $form->addButton("§bEinstellungen", 0, "", "s");
        $form->addButton("§4Schließen", 0, "", "close");
        $player->sendForm($form);
    }
}