<?php

/**
 * Description of Utilities
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Creator;

use Orangehill\Photon\Library\Creator\MigrationGenerator;
use Orangehill\Photon\Library\Support\ArrayLib;

class Utilities
{

    const MODULES_TABLE_NAME = 'modules';
    const FIELDS_TABLE_NAME = 'fields';

    public static function changesetToMigration(array $changeGroup)
    {
        if (ArrayLib::isDeepEmpty($changeGroup)) return true;
        foreach ($changeGroup as $changesets) {
            foreach ($changesets as $changeset) {
                foreach ($changeset['changes'] as $change) {
                    
                }
            }
        }
//        MigrationGenerator::update()
    }

}
