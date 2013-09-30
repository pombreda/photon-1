<?php

use Mockery as m;
use Orangehill\Photon\ModuleEloquentRepository;
use Orangehill\Photon\FieldEloquentRepository;
use Orangehill\Photon\SettingsValidator;

class SettingsValidatorTest extends PHPUnit_Framework_TestCase {

    protected $module;
    protected $field;

    public function setUp()
    {
        $this->module = m::mock(new ModuleEloquentRepository);
        $this->field = m::mock(new FieldEloquentRepository);
        $app = $this->mockApp();
        $this->settingsValidator = new SettingsValidator($this->module, $this->field);
        $this->settingsValidator->app = $app;
    }

    public function tearDown()
    {
        m::close();
    }

    private function mockApp()
    {
        // Mocks the application components that
        // are not Confide's responsibility
        $app = array();

        $app['request'] = m::mock( 'Request' );
        $app['validator'] = m::mock( 'Validator' );

        return $app;
    }

    public function testFormatResponse()
    {
        $output1 = $this->settingsValidator->formatResponse('Test Message.', false);
        $expected1 = array('fails' => false, 'message' => 'Test Message.');
        $output2 = $this->settingsValidator->formatResponse('', true);
        $expected2 = array('fails' => true);

        $this->assertEquals($expected1, $output1);
        $this->assertEquals($expected2, $output2);
    }

    public function testCheckDependantModulesExist()
    {
        $this->settingsValidator->module
            ->shouldReceive('getDependantModuleName')
            ->with('1')
            ->once()
            ->andReturn('foo');
        $output = $this->settingsValidator->checkDependantModules('1');
        $expected = array("fails" => true, "message" => "'foo' is a child module of current module. Removal operation is not possible.");

        $this->assertEquals($expected, $output);
    }

    public function testCheckDependantModulesDoesntExist()
    {
        $this->settingsValidator->module
            ->shouldReceive('getDependantModuleName')
            ->with('1')
            ->once()
            ->andReturn(false);
        $output = $this->settingsValidator->checkDependantModules('1');
        $expected = array("fails" => false);

        $this->assertEquals($expected, $output);
    }

    public function testCheckDependantFieldsExist()
    {
        $this->settingsValidator->field
            ->shouldReceive('getDependantFieldName')
            ->with('1')
            ->once()
            ->andReturn('foo');
        $output = $this->settingsValidator->checkDependantFields('1');
        $expected = array("fails" => true, "message" => "'foo' uses current module as a relation table. Removal operation is not possible.");

        $this->assertEquals($expected, $output);


        $this->assertEquals($expected, $output);
    }

    public function testCheckDependantFieldsDoesntExist()
    {
        $this->settingsValidator->field
            ->shouldReceive('getDependantFieldName')
            ->with('1')
            ->once()
            ->andReturn(false);
        $output = $this->settingsValidator->checkDependantFields('1');
        $expected = array("fails" => false);

        $this->assertEquals($expected, $output);
    }

    public function testCheckNoModuleFields()
    {
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with('is_folder')
            ->once()
            ->andReturn(0);
        $output = $this->settingsValidator->validate(array());
        $expected = array("fails" => true, "message" => "No module fields specified.");

        $this->assertEquals($expected, $output);
    }

    public function testValidates()
    {
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with('is_folder')
            ->andReturn(0);
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with('remove_request')
            ->once()
            ->andReturn(0);
        $this->settingsValidator->app['request']
            ->shouldReceive('all')
            ->once()
            ->andReturn(array());
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with('field_name1')
            ->once()
            ->andReturn('FN1');
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with('column_name1')
            ->once()
            ->andReturn('CN1');
        $this->settingsValidator->app['validator']
            ->shouldReceive('make')
            ->with(array(), m::any(), m::any())
            ->once()
            ->andReturn($this->settingsValidator->app['validator']);
        $this->settingsValidator->app['validator']
            ->shouldReceive('fails')
            ->once()
            ->andReturn(true);
        $this->settingsValidator->app['validator']
            ->shouldReceive('messages')
            ->once()
            ->andReturn($this->settingsValidator->app['validator']);
        $this->settingsValidator->app['validator']
            ->shouldReceive('first')
            ->once()
            ->andReturn('Message');
        $expected = array("fails" => true, "message" => "Message");
        $output = $this->settingsValidator->validate([1]);

        $this->assertEquals($expected, $output);
    }

    public function testGetNotInArray()
    {
        $fieldIds = [1, 2, 3, 4];
        $name = 'field';
        $excludeId = 2;
        $this->settingsValidator->app['request']
            ->shouldReceive('get')
            ->with(m::any())
            ->times(3)
            ->andReturn('field1', 'field3', 'field4');
        $expected = 'field1,field3,field4';
        $output = $this->settingsValidator->getNotInArray($fieldIds, $name, $excludeId);

        $this->assertEquals($expected, $output);
    }

}
