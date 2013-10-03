<?php

use Orangehill\Photon\MigrationGenerator;

class MigrationGeneratorTest extends PHPUnit_Framework_TestCase {

    public function testMigrationNameCreation() {
        // Check create command
        $this->assertEquals('create_test_table', MigrationGenerator::createMigrationName('create', 'test'));
        $this->assertEquals('create_test_repository_table', MigrationGenerator::createMigrationName('create', 'test-repository', array('age:int')));
        $this->assertEquals('create_test_repository_table', MigrationGenerator::createMigrationName('create', 'testRepository', array('age:int')));
        $this->assertEquals('create_table', MigrationGenerator::createMigrationName('create', ''));

        // Check destroy command
        $this->assertEquals('destroy_test_table', MigrationGenerator::createMigrationName('destroy', 'test'));
        $this->assertEquals('destroy_test_repository_table', MigrationGenerator::createMigrationName('destroy', 'test-repository', array('age:int')));
        $this->assertEquals('destroy_test_repository_table', MigrationGenerator::createMigrationName('destroy', 'testRepository', array('age:int')));
        $this->assertEquals('destroy_table', MigrationGenerator::createMigrationName('destroy', ''));

        // Check add command
        $this->assertEquals('add_to_test_table', MigrationGenerator::createMigrationName('add', 'test'));
        $this->assertEquals('add_age_to_test_repository_table', MigrationGenerator::createMigrationName('add', 'test-repository', array('age:int')));
        $this->assertEquals('add_age_and_title_to_test_repository_table', MigrationGenerator::createMigrationName('add', 'testRepository', array('age:int', 'title:string')));
        $this->assertEquals('add_age_and_title_and_bows_to_test_repository_table', MigrationGenerator::createMigrationName('add', 'testRepository', array('age:int', 'title:string', 'bows:text')));
        $this->assertEquals('add_age', MigrationGenerator::createMigrationName('add', '', array('age:int')));

        // Check remove command
        $this->assertEquals('remove_from_test_table', MigrationGenerator::createMigrationName('remove', 'test'));
        $this->assertEquals('remove_age_from_test_repository_table', MigrationGenerator::createMigrationName('remove', 'test-repository', array('age:int')));
        $this->assertEquals('remove_age_and_title_from_test_repository_table', MigrationGenerator::createMigrationName('remove', 'testRepository', array('age:int', 'title:string')));
        $this->assertEquals('remove_age_and_title_and_bows_from_test_repository_table', MigrationGenerator::createMigrationName('remove', 'testRepository', array('age:int', 'title:string', 'bows:text')));
        $this->assertEquals('remove_age', MigrationGenerator::createMigrationName('remove', '', array('age:int')));
    }

    /**
     * @expectedException \OrangeHill\Photon\MigrationException
     */
    public function testMigrationNameException() {
        $this->assertEquals('table', MigrationGenerator::createMigrationName('', ''));
    }

    public function testKeyParsing() {
        $this->assertSame('firstname', MigrationGenerator::parseFieldsToMigrationKey(array('firstname:string')));
        $this->assertSame('firstname_and_lastname', MigrationGenerator::parseFieldsToMigrationKey(array('firstname:string', 'lastname:string')));
        $this->assertSame('firstname_and_lastname', MigrationGenerator::parseFieldsToMigrationKey(array('firstname:string', 'lastname:string', 'unknown')));
        $this->assertEquals('', MigrationGenerator::parseFieldsToMigrationKey());
    }

    public function testFieldConcatenation() {
        $testCases = array(
            array(
                'expected' => '',
                'actual'   => MigrationGenerator::concatFields()
            ),
            array(
                'expected' => 'firstName:string, lastName:string',
                'actual'   => MigrationGenerator::concatFields(array('firstName:string', 'lastName:string'))
            ),
            array(
                'expected' => 'firstName:string, lastName:string:emotion',
                'actual'   => MigrationGenerator::concatFields(array('firstName:string', 'strangeField', 'lastName:string:emotion'))
            ),
            array(
                'expected' => 'firstName:string, lastName:string:emotion',
                'actual'   => MigrationGenerator::concatFields(array('firstName:string', array('one', 'two'), 'lastName:string:emotion'))
            )
        );

        foreach ($testCases as $case) {
            $this->assertSame($case['expected'], $case['actual']);
        }
    }

    public function testArgumentPreparation() {
        $testCases = array(
            array(
                'expected' => array(
                    'name'     => 'create_test_table',
                    '--fields' => ''
                ),
                'actual'   => MigrationGenerator::prepareArguments('create', 'test')
            ),
            array(
                'expected' => array(
                    'name'     => 'create_test_repository_table',
                    '--fields' => 'first_name:string, age:int:unsigned, last_name:string'
                ),
                'actual'   => MigrationGenerator::prepareArguments('create', 'test-repository', array(
                    'first_name:string', 'age:int:unsigned', 'pedro', 'last_name:string'
                ))
            ),
        );
        foreach ($testCases as $case) {
            $this->assertInternalType('array', $case['actual'], 'Invalid actual result type');
            $this->assertArrayHasKey('name', $case['actual'], 'Missing key `name` in array');
            $this->assertArrayHasKey('--fields', $case['actual'], 'Missing key `--fields` in array');
            $this->assertInternalType('string', $case['actual']['name'], 'Invalid type of `name` element');
            $this->assertInternalType('string', $case['actual']['--fields'], 'Invalid type of `--fields` element');

            $this->assertEquals($case['expected'], $case['actual'], 'Expected and actual not matching');
        }
    }

    /**
     * @expectedException Orangehill\Photon\MigrationException
     */
    public function testArgumentPreparationWithInvalidTableName() {
        MigrationGenerator::prepareArguments(5, 'table');
    }

    /**
     * @expectedException Orangehill\Photon\MigrationException
     */
    public function testArgumentPreparationWithInvalidCommandName() {
        MigrationGenerator::prepareArguments('create', array());
    }

}
