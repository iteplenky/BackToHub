<?php

declare(strict_types=1);


namespace BackToHub\iteplenky\entity;


    use BackToHub\iteplenky\Main;
    use BackToHub\iteplenky\Utils;
    use BackToHub\iteplenky\commands\Hub;
    use Convert\Utils\ModelConvert;
    
    use pocketmine\{Player, Server};
    use pocketmine\entity\{Entity, Human};
    
    use pocketmine\math\Vector3;
    use pocketmine\event\entity\EntityDamageEvent;

    class Bed extends Human
    {
        public $height = 1.2;
        public $width = 0.8;

        public function attack(EntityDamageEvent $source): void
        {
            $source->setCancelled();
            Hub::teleport($source->getDamager());
        }
        
        public static function registerHubEntity(): void
        {
            Entity::registerEntity(Bed::class, true);
        }

        public static function createHubEntity($player, $rotate)
        {            
            $path = Main::getResourcePath();
            $texture = $path . 'bed.png';

            $skin = ModelConvert::getSkinFromFile($texture);
            
            $geometry = ModelConvert::makeGeometrySkin($skin, $path, 'bed');
            $nbt = ModelConvert::createEntityBaseNBT($player->asVector3());
            $npc = ModelConvert::pushCompoundTag($nbt, $geometry);

            $level = Server::getInstance()->getLevelByName('world');

            $entity = ModelConvert::createEntity('Bed', $level, $nbt);
            
            $entity->setRotation($rotate * 30, 0);
            $entity->setNameTag("§b§lHUB\n§7§oClick To Teleport");
            
            $entity->spawnToAll();
        }
    }

?>
