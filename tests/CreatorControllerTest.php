<?php

/**
 * Description of CreatorValidationTest
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */
use Mockery as m;
use Orangehill\Photon\Library\Creator\ModuleCreator;

class CreatorControllerTest extends PHPUnit_Framework_TestCase
{
    /** @var ModuleCreator */
    protected $creator;
    
    public function setUp()
    {
        $this->creator = new ModuleCreator;
    }

    public function tearDown()
    {
        m::close();
    }

    public function testMocking()
    {
        $input = m::mock('Input');
        $input->shouldReceive('all')->andReturn(array('name' => 'Joseph'));
        $this->assertArrayHasKey('name', $input->all());
    }

    public function testCreatorResponse()
    {
        $this->creator->validateModule(array('name' => 'Ivan'));
    }

}
