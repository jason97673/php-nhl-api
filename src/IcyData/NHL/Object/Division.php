<?php

namespace IcyData\NHL\Object;

use IcyData\NHL\Object;
use IcyData\NHL\Object\Conference;

/**
 * Represents a Division
 *
 * @author William Lang <william@icydata.hockey>
 */
class Division extends Object {

    /**
     * Transform incoming parameters if need be
     *
     * @param array $parameters
     * @return void
     */
    protected function transform(&$parameters) {
        $parameters['conference'] = new Conference($parameters['conference']);
    }
}
