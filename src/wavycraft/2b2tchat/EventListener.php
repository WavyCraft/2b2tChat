<?php

declare(strict_types=1);

namespace wavycraft\2b2tchat;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat as TextColor;

class EventListener implements Listener {

    public function chat(PlayerChatEvent $event) : void{
        $sender = $event->getPlayer();
        $originalMessage = $event->getMessage();
        $format = $event->getFormat();

        if (str_starts_with($originalMessage, ">")) {
            $greenMessage = TextColor::GREEN . $originalMessage;
            $event->setMessage($greenMessage);
            return;
        }

        $event->cancel();

        foreach ($sender->getServer()->getOnlinePlayers() as $receiver) {
            $customMessage = $originalMessage;

            $customMessage = str_replace("@here", TextColor::YELLOW . "@here" . TextColor::RESET, $customMessage);

            foreach ($sender->getServer()->getOnlinePlayers() as $mentioned) {
                $mentionTag = "@" . $mentioned->getName();
                if (str_contains($customMessage, $mentionTag)) {
                    if ($receiver->getName() === $mentioned->getName()) {
                        $customMessage = str_replace(
                            $mentionTag,
                            TextColor::YELLOW . $mentionTag . TextColor::RESET,
                            $customMessage
                        );
                    }
                }
            }

            $formatted = str_replace(["%s", "%s"], [$sender->getName(), $customMessage], $format);
            $receiver->sendMessage($formatted);
        }
    }
}