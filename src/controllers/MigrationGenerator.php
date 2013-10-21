<?php

namespace Orangehill\Photon;

class MigrationGenerator {

    /**
     * Prepares an argument list for the Artisan command
     * @param string $command Command name
     * @param string $table Table name
     * @param array $fields Array of fields ([name => type])
     * @return array
     * @throws MigrationException
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testArgumentPreparation
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testArgumentPreparationWithInvalidTableName
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testArgumentPreparationWithInvalidCommandName
     */
    public static function prepareArguments($command, $table, array $fields = array()) {
        if (!is_string($table)) {
            throw new MigrationException("Invalid `table` argument. Must be a string.");
        } else if (!is_string($command)) {
            throw new MigrationException("Invalid `command` argument. Must be a string.");
        };

        $args = array(
            'name'     => self::createMigrationName($command, $table, $fields),
            '--fields' => self::concatFields($fields)
        );
        return $args;
    }

    /**
     * Creates a migration name based on input parameters
     * @param string $command
     * @param string $table
     * @param array $fields
     * @return string
     * @throws MigrationException
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testMigrationNameCreation
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testMigrationNameException
     */
    public static function createMigrationName($command, $table, array $fields = array()) {
        $key = self::parseFieldsToMigrationKey($fields);
        $table = (string) $table;
        $command = (string) $command;
        $name = '';
        switch ($command) {
            case 'create':
                $name = "create_{$table}_table";
                break;
            case 'add':
                $name = "add_{$key}";
                $name .= $table ? "_to_{$table}_table" : '';
                break;
            case 'remove':
                $name = "remove_{$key}";
                $name .= $table ? "_from_{$table}_table" : '';
                break;
            case 'destroy':
                $name = "destroy_{$table}_table";
                break;
            default:
                throw new MigrationException("Migration method `{$command}` does not exist");
                break;
        }
        return str_replace('__', '_', snake_case(str_replace('-', '_', $name)));
    }

    /**
     * Returns a key segment for the migration file name
     * @param array $fields
     * @return string
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testKeyParsing
     */
    public static function parseFieldsToMigrationKey(array $fields = array()) {
        $entries = array();
        foreach ($fields as $field) {
            $exp = explode(':', $field);
            if (count($exp) > 1) {
                $entries[] = strtolower($exp[0]);
            }
        }
        return join('_and_', $entries);
    }

    /**
     * Calls the Artisan command 
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic($name, $arguments) {
        \Artisan::call('generate:migration', self::prepareArguments($name, $arguments[0], $arguments[1]));
    }

    /**
     * Concats the array values, excluding invalid elements
     * @param array $array
     * @return string
     * @see Orangehill\Photon\MigrationGenerator\MigrationGeneratorTest::testFieldConcatenation
     */
    public static function concatFields(array $array = array()) {
        $output = array();
        foreach ($array as $key => $type) {
            if (is_string($type) && count(explode(':', $type)) > 1) {
                $output[] = $type;
            }
        }
        $out = implode(', ', $output);
        return $out;
    }

}
