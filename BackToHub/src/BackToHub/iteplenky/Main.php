<?php

declare(strict_types=1);


namespace BackToHub\iteplenky;


    use BackToHub\iteplenky\commands\Hub;
    use BackToHub\iteplenky\entity\Bed;
 
    use pocketmine\plugin\PluginBase;
    use pocketmine\Player;
 
    class Main extends PluginBase 
    {
        private static $instance;

        public function onLoad(): void 
        {
            self::setInstance($this);
        }
     
        public function onEnable(): void
        {
            $this->saveDefaultConfig();
            $this->config = $this->getConfig()->getAll();

            $this->saveResource("config.yml", true);
        
            Bed::registerHubEntity();
            
            $this->getServer()->getCommandMap()->register('hub', new Hub());
        }

        private static function setInstance(Main $instance): void 
        {
            self::$instance = $instance;
        }

        public static function getInstance(): Main
        {
            return self::$instance;
        }

        public static function getResourcePath() {
            return self::getInstance()->getFile() . "/resources/";
        }

        public static function getHubEntity(Player $player): ?Bed
        {
            $level = $player->getLevel();
            foreach ($level->getEntities() as $entity) {
                if ($entity instanceof Bed) {
                    if ($player->distance($entity) <= 5 && $entity->distance($player) > 0) {
                        return $entity;
                    }
                }
            }
            return null;
        }
    }

?>
