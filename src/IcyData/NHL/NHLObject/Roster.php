<?php
/**
 * src/IcyData/NHL/Object/Roster.php
 *
 * @package    icydata/php-nhl-api
 * @author     William Lang <william@icydata.hockey>
 * @link       https://github.com/williamlang/php-nhl-api
 */

namespace IcyData\NHL\NHLObject;

use IcyData\NHL\NHLObject;

/**
 * Represents a Roster
 *
 * @author William Lang <william@icydata.hockey>
 */
class Roster extends NHLObject {

    /**
     * Players
     *
     * @var Player[]
     */
    private $players = [];

    /**
     * @inheritDoc
     */
    protected function transform(&$parameters) {
        if (is_array($parameters['roster'])) {
            $roster = $parameters['roster'];
            $parameters['roster'] = [];

            foreach ($roster as $player) {
                $parameters['roster'][] = new Player($player);
            }
        }
    }
}
