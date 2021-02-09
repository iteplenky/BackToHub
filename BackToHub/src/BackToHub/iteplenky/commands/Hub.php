<?php

declare(strict_types=1);


namespace BackToHub\iteplenky\commands;


    use BackToHub\iteplenky\Main;
    use BackToHub\iteplenky\Utils;
    use BackToHub\iteplenky\entity\Bed;

    use Convert\Utils\ModelConvert;

    use pocketmine\Player;
    use pocketmine\Server;

    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;

    class Hub extends Command {

        public function __construct(string $name = "hub", string $description = "Make My Hub", string $usageMessage = null, array $aliases = []) 
        {
            parent::__construct($name, $description, $usageMessage, $aliases);
        }

        public function execute(CommandSender $sender, string $commandLabel, array $args) 
        {
            if (!isset($args[0])) 
                self::teleport($sender);

            else {
                if ($sender->isOp() and $sender instanceof Player) {
                    if ($args[0] == 'setpos') {
                            
                        $config = Main::getInstance()->getConfig();
                        $config->set('hubPosition', Utils::packPositionToRaw($sender->asPosition()));
                        $config->save();
    
                        $sender->sendMessage(' §r> Place for the hub §aset§r.');

                    } else if ($args[0] == 'bed') {
                        if (isset($args[1]) and is_numeric($args[1])) {
                            
                            Bed::createHubEntity($sender, $args[1]);

                        } else 
                            $sender->sendMessage(' §r> Angle of rotation §cnot specified§r or specified§c incorrect§r.');
                    } else if ($args[0] == 'clear') {
                        
                        $entity = Main::getHubEntity($sender);
                        if ($entity !== null) {
                            $entity->flagForDespawn();
                            $sender->sendMessage(' §r> Nearest bed has been §cremoved§r.');
                            return true;
                        }
                        $sender->sendMessage(' §r> Nearest bed §cnot found§r.');

                    } else self::helpMessage($sender);
                }
            }
        }

        protected static function helpMessage(CommandSender $player): void 
        {
            $player->sendMessage(' §r> /hub §csetpos §r- §7Set hub point.');
            $player->sendMessage(' §r> /hub §cclear §r- §7Remove nearest hub entity.');
            $player->sendMessage(' §r> /hub §cbed §r- §7Create a hub entity.');
        }

        public static function teleport(CommandSender $player): void 
        {
            if ($player instanceof Player) {
                            
                $config = Main::getInstance()->getConfig();

                if ($config->exists('hubPosition')) {

                    $pos = Utils::unpackRawLocation($config->get('hubPosition'));

                    $player->teleport($pos);

                    $player->sendMessage(' §r> You are §bmoved§r to hub!');
                    $player->sendTitle('§l§bHUB', '§eWelcome!', 5, 10, 5);

                } else 
                    $player->sendMessage(' §r> You §cnot set the§r hub point!');
            }
        }
    }

?>
