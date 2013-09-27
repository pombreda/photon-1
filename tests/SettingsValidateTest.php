<?php

use Mockery as m;
use Orangehill\Photon\ModuleEloquentRepository;
use Orangehill\Photon\FieldEloquentRepository;
use Orangehill\Photon\SettingsValidate;

class SettingsValidateTest extends PHPUnit_Framework_TestCase {

    protected $module;
    protected $field;

    public function setUp()
    {
        $this->module = m::mock(new ModuleEloquentRepository);
        $this->field = m::mock(new FieldEloquentRepository);
        $app = $this->mockApp();
        $this->settingsValidate = new SettingsValidate($this->module, $this->field);
        $this->settingsValidate->app = $app;
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

    public function testCheckDependantModulesExist()
    {
        $this->settingsValidate->module
            ->shouldReceive('countDependantModules')
            ->with('1')
            ->once()
            ->andReturn('foo');
        $output = $this->settingsValidate->checkDependantModules('1');
        $expected = "'foo' is a child module of current module. Removal operation is not possible.";

        $this->assertEquals($expected, $output);
    }

    public function testCheckDependantModulesDoesntExist()
    {
        $this->settingsValidate->module
            ->shouldReceive('countDependantModules')
            ->with('1')
            ->once()
            ->andReturn(false);
        $output = $this->settingsValidate->checkDependantModules('1');

        $this->assertFalse($output);
    }

    public function testCheckDependantFieldsExist()
    {
        $this->settingsValidate->field
            ->shouldReceive('countDependantFields')
            ->with('1')
            ->once()
            ->andReturn('foo');
        $output = $this->settingsValidate->checkDependantFields('1');
        $expected = "'foo' uses current module as a relation table. Removal operation is not possible.";

        $this->assertEquals($expected, $output);
    }

    public function testCheckDependantFieldsDoesntExist()
    {
        $this->settingsValidate->field
            ->shouldReceive('countDependantFields')
            ->with('1')
            ->once()
            ->andReturn(false);
        $output = $this->settingsValidate->checkDependantFields('1');

        $this->assertFalse($output);
    }

    public function testCheckNoModuleFields()
    {
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with('is_folder')
            ->once()
            ->andReturn(0);
        $output = $this->settingsValidate->validate(array());
        $expected = "No module fields specified.";

        $this->assertEquals($expected, $output);
    }

    public function testValidates()
    {
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with('is_folder')
            ->andReturn(0);
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with('remove_request')
            ->once()
            ->andReturn(0);
        $this->settingsValidate->app['request']
            ->shouldReceive('all')
            ->once()
            ->andReturn(array());
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with('field_name1')
            ->once()
            ->andReturn('FN1');
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with('column_name1')
            ->once()
            ->andReturn('CN1');
        $this->settingsValidate->app['validator']
            ->shouldReceive('make')
            ->with(array(), m::any(), m::any())
            ->once()
            ->andReturn($this->settingsValidate->app['validator']);
        $this->settingsValidate->app['validator']
            ->shouldReceive('fails')
            ->once()
            ->andReturn(true);
        $this->settingsValidate->app['validator']
            ->shouldReceive('messages')
            ->once()
            ->andReturn($this->settingsValidate->app['validator']);
        $this->settingsValidate->app['validator']
            ->shouldReceive('first')
            ->once()
            ->andReturn('Message');
        $expected = "Message";
        $output = $this->settingsValidate->validate([1]);

        $this->assertEquals($expected, $output);
    }

    public function testGetNotInArray()
    {
        $fieldIds = [1, 2, 3, 4];
        $name = 'field';
        $excludeId = 2;
        $this->settingsValidate->app['request']
            ->shouldReceive('get')
            ->with(m::any())
            ->times(3)
            ->andReturn('field1', 'field3', 'field4');
        $expected = 'field1,field3,field4';
        $output = $this->settingsValidate->getNotInArray($fieldIds, $name, $excludeId);

        $this->assertEquals($expected, $output);
    }

}
