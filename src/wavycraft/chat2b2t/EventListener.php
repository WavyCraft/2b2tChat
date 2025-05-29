<?php

declare(strict_types=1);

namespace wavycraft\chat2b2t;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat as TextColor;

class EventListener implements Listener {

    public function chat(PlayerChatEvent $event) : void {
        $sender = $event->getPlayer();
        $originalMessage = $event->getMessage();

        if (str_starts_with($originalMessage, ">")) {
            $event->setMessage(TextColor::GREEN . $originalMessage);
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

            $formatted = $sender->getName() . ": " . $customMessage;
            $receiver->sendMessage($formatted);
        }
    }
}
