<?php
/**
 * src/IcyData/NHL/Resource/Builder/Teams/Get.php
 *
 * @package    icydata/php-nhl-api
 * @author     William Lang <william@icydata.hockey>
 * @link       https://github.com/williamlang/php-nhl-api
 */

namespace IcyData\NHL\Resource\Builder\Teams;

use IcyData\NHL\NHLObject\Team;
use IcyData\NHL\Resource;
use IcyData\NHL\Resource\Builder;
use IcyData\NHL\Resource\Teams;

/**
 * Get a specific team
 *
 * @author William Lang <william@icydata.hockey>
 */
class Get extends Builder {

    /**
     * The id of the team
     *
     * @var int
     */
    protected $id;

    /**
     * Constructor
     *
     * @param Resource $resource
     * @param int $id
     */
    public function __construct(Resource $resource, int $id) {
        parent::__construct($resource);
        $this->withId($id);
    }

    /**
     * Set the id of the builder
     *
     * @param int $id
     * @return Get
     */
    public function withId(int $id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Add an expand type to the request
     *
     * @see src/IcyData/NHL/Resource/Teams.php
     * @param string $expandType
     * @return ListBuilder
     */
    public function withExpand(string $expand) {
        if (!in_array($expand, [
            Teams::TYPE_NAMES,
            Teams::TYPE_ROSTER,
            Teams::TYPE_SCHEDULE_NEXT,
            Teams::TYPE_SCHEDULE_PREV,
            Teams::TYPE_STATS
        ])) {
            throw new NHLException("Invalid expand type.");
        }

        $this->expand = $expand;
        return $this;
    }

    /**
     * Get info on a specific season, ie: 2018
     *
     * @param integer $season
     * @return ListBuilder
     */
    public function withSeason(int $season) {
        if (strlen($season) > 4 || strlen($season) < 4) {
            throw new NHLException("Invalid season. Format: yyyy");
        }

        $this->season = sprintf("%d%d", $season, $season + 1);
        return $this;
    }

    /**
     * Send the request
     *
     * @return Team
     */
    public function send() {
        $opts = ['query' => []];

        if (!empty($this->expand)) {
            $opts['query']['expand'] = $this->expand;
        }

        if (!empty($this->season)) {
            $opts['query']['season'] = $this->season;
        }

        $url = sprintf('/api/%s/teams/%d', $this->version, $this->id);
        $json = $this->get($url, $opts);
        return new Team($json['teams'][0]);
    }
}