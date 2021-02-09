<?php

declare(strict_types=1);


namespace BackToHub\iteplenky;


    use BackToHub\iteplenky\Main;

    use pocketmine\Player;
    use pocketmine\level\{Position, Location};

    use JsonException;
    use InvalidArgumentException;

    class Utils {

        public static function unpackRawLocation(string $rawLocation): Location 
        {
            $rawLocationArray = json_decode($rawLocation, true, 512, JSON_THROW_ON_ERROR);

            $x = isset($rawLocationArray["x"]) ? (float)$rawLocationArray["x"] : null;
            if (is_null($x)) 
                throw new InvalidArgumentException(" > Coordinate position not specified 'X'");
            
            $y = isset($rawLocationArray["y"]) ? (float)$rawLocationArray["y"] : null;
            if (is_null($y)) 
                throw new InvalidArgumentException(" > Coordinate position not specified 'Y");
            
            $z = isset($rawLocationArray["z"]) ? (float)$rawLocationArray["z"] : null;
            if (is_null($z)) 
                throw new InvalidArgumentException(" > Coordinate position not specified 'Z'");
            
            $worldName = isset($rawLocationArray["world"]) ? $rawLocationArray["world"] : null;
            if (is_null($worldName)) {
                $worldName = Main::getInstance()->getServer()->getDefaultLevel()->getFolderName();
                Main::getInstance()->getLogger()->notice(" > You did not specify the world, so it was set §cworld");
            }

            if (!Main::getInstance()->getServer()->isLevelLoaded($worldName)) 
                Main::getInstance()->getServer()->loadLevel($worldName);
            
            $world = Main::getInstance()->getServer()->getLevelByName($worldName);
            if (is_null($world)) 
                throw new InvalidArgumentException(" > The world doesn't exist");

            return new Location($x, $y, $z, 0, 0, $world);
        }

        public static function packPositionToRaw(Position $location): string 
        {
            $rawLocationArray =
                [
                    'x' => $location->getX(),
                    'y' => $location->getY(),
                    'z' => $location->getZ(),
                    'world' => $location->getLevel()->getFolderName(),
                ];

            return json_encode($rawLocationArray, JSON_THROW_ON_ERROR);
        }
    }

?>
