<?php
class phpgacl_api_test extends TestCase {
	
	var $gacl_api;
	
    function phpgacl_api_test($name) {
        $this->TestCase($name);
        $this->gacl_api = &$GLOBALS['gacl_api'];
    }
    
    function setUp() {
        //$this->abc = new String('abc');
    }
    
    function tearDown() {
        //unset($this->abc);
    }
    
    /** VERSION **/
    
    function get_version() {
        $result = $this->gacl_api->get_version();
        $expected = '/^[0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2}[a-zA-Z]{1}[0-9]{1,2}$/i';
		
        $this->assertRegexp($expected, $result, 'Version incorrect.');
    }
    function get_schema_version() {
        $result = $this->gacl_api->get_schema_version();
        $expected = '/^[0-9]{1,2}.[0-9]{1,2}$/i';
		
        $this->assertRegexp($expected, $result, 'Schema Version incorrect.');
    }
	
	/** GENERAL **/
	
    function count_all() {
		//Create array
		$arr = array(
			'Level1a' => array(
				'Level2a' => array(
					'Level3a' => 1,
					'Level3b' => 2
				),
				'Level2b' => 3,
			),
			'Level1b' => 4,
			'Level1c' => array(
				'Level2c' => array(
					'Level3c' => 5,
					'Level3d' => 6
				),
				'Level2d' => 7,
			),
			'Level1d' => 8
		);
		
		//Keep in mind count_all only counts actual values. So array()'s don't count as +1        
		$result = $this->gacl_api->count_all($arr);
		
        $this->assert($result == 8, 'Incorrect array count, Should be 8.');
    }
	
	/** ACO SECTION **/
	
    function get_object_section_section_id_aco() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'ACO');
        $message = 'get_object_section_section_id failed';
		
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_object_section_aco() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'ACO');
        $message = 'add_object_section failed';
		
        $this->assert($result, $message);
    }
    
    function del_object_section_aco() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_aco(), 'ACO');
        $message = 'del_object_section failed';
        $this->assert($result, $message);
    }
    
    /** ACO **/
    
    function get_object_id_aco() {
        $result = $this->gacl_api->get_object_id('unit_test','enable_tests', 'ACO');
        $message = 'get_object_id failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_object_aco() {
        $result = $this->gacl_api->add_object('unit_test', 'Enable - Tests', 'enable_tests', 999, 0, 'ACO');
        $message = 'add_object failed';
        $this->assert($result, $message);
    }
    
    function del_object_aco() {
        $result = $this->gacl_api->del_object($this->get_object_id_aco(), 'ACO');
        $message = 'del_object failed';
        $this->assert($result, $message);
    }
	
	/** ARO SECTION **/
	
    function get_object_section_section_id_aro() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'ARO');
        $this->_aco_section_id = $result;
        $message = 'get_object_section_section_id failed';
        $this->assert($result >= 0, $message);
        
        return $result;
    }
    
    function add_object_section_aro() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'ARO');
        $message = 'add_object_section failed';
        $this->assert($result, $message);
    }
    
    function del_object_section_aro() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_aro(), 'ARO');
        $message = 'del_object_section failed';
        $this->assert($result, $message);
    }
    
    /** ARO **/
    
    function get_object_id_aro() {
        $result = $this->gacl_api->get_object_id('unit_test','john_doe', 'ARO');
        $message = 'get_object_id failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_object_aro() {
        $result = $this->gacl_api->add_object('unit_test', 'John Doe', 'john_doe', 999, 0, 'ARO');
        $message = 'add_object failed';
        $this->assert($result, $message);
    }
    
    function del_object_aro() {
        $result = $this->gacl_api->del_object($this->get_object_id_aro(), 'ARO');
        $message = 'del_object failed';
        $this->assert($result, $message);
    }
	
	/** AXO SECTION **/
	
    function get_object_section_section_id_axo() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'AXO');
        $message = 'get_object_section_section_id failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_object_section_axo() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'AXO');
        $this->_aco_section_id = $result;
        $message = 'add_object_section failed';
        $this->assert($result, $message);
    }
    
    function del_object_section_axo() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_axo(), 'AXO');
        $message = 'del_object_section failed';
        $this->assert($result, $message);
    }
    
    /** AXO **/
    
    function get_object_id_axo() {
        $result = $this->gacl_api->get_object_id('unit_test','object_1', 'AXO');
        $message = 'get_object_id failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_object_axo() {
        $result = $this->gacl_api->add_object('unit_test', 'Object 1', 'object_1', 999, 0, 'AXO');
        $message = 'add_object failed';
        $this->assert($result, $message);
    }
    
    function del_object_axo() {
        $result = $this->gacl_api->del_object($this->get_object_id_axo(), 'AXO');
        $message = 'del_object failed';
        $this->assert($result, $message);
    }
	
	/** ARO GROUP **/
	
    function get_group_id_parent_aro() {
        $result = $this->gacl_api->get_group_id('ARO Group 1', 'ARO');
        $message = 'get_group_id_parent_aro failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function get_group_id_child_aro() {
        $result = $this->gacl_api->get_group_id('ARO Group 2', 'ARO');
        $message = 'get_group_id_child_aro failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_group_parent_aro() {
        $result = $this->gacl_api->add_group('ARO Group 1', 0, 'ARO');
        $message = 'add_group_parent_aro failed';
        $this->assert($result, $message);
    }
    
    function del_group_parent_aro() {
        $result = $this->gacl_api->del_group($this->get_group_id_parent_aro(), TRUE, 'ARO');
        $message = 'del_group_parent_aro failed';
        $this->assert($result, $message);
    }
    
    function add_group_child_aro() {
        $result = $this->gacl_api->add_group('ARO Group 2', $this->get_group_id_parent_aro(), 'ARO');
        $message = 'add_group_child failed';
        $this->assert($result, $message);
    }
    
    function del_group_child_aro() {
        $result = $this->gacl_api->del_group($this->get_group_id_child_aro(), TRUE, 'ARO');
        $message = 'del_group failed';
        $this->assert($result, $message);
    }
    
    function add_group_object_aro() {
        $result = $this->gacl_api->add_group_object($this->get_group_id_parent_aro(), 'unit_test', 'john_doe', 'ARO');
        $message = 'add_group_object failed';
        $this->assert($result, $message);
    }
    
    function del_group_object_aro() {
        $result = $this->gacl_api->del_group_object($this->get_group_id_parent_aro(), 'unit_test', 'john_doe', 'ARO');
        $message = 'del_group_object failed';
        $this->assert($result, $message);
    }
	
	/** AXO GROUP **/
	
    function get_group_id_parent_axo() {
        $result = $this->gacl_api->get_group_id('AXO Group 1', 'AXO');
        $message = 'get_group_id_parent_aro failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function get_group_id_child_axo() {
        $result = $this->gacl_api->get_group_id('AXO Group 2', 'AXO');
        $message = 'get_group_id_child_axo failed';
        $this->assert($result, $message);
        
        return $result;
    }
    
    function add_group_parent_axo() {
        $result = $this->gacl_api->add_group('AXO Group 1', 0, 'AXO');
        $message = 'add_group failed';
        $this->assert($result, $message);
    }
    
    function del_group_parent_axo() {
        $result = $this->gacl_api->del_group($this->get_group_id_parent_axo(), TRUE, 'AXO');
        $message = 'del_group failed';
        $this->assert($result, $message);
    }
    
    function add_group_child_axo() {
        $result = $this->gacl_api->add_group('AXO Group 2', $this->get_group_id_parent_axo(), 'AXO');
        $message = 'add_group failed';
        $this->assert($result, $message);
    }
    
    function del_group_child_axo() {
        $result = $this->gacl_api->del_group($this->get_group_id_child_axo(), TRUE, 'AXO');
        $message = 'del_group failed';
        $this->assert($result, $message);
    }
    
    function add_group_object_axo() {
        $result = $this->gacl_api->add_group_object($this->get_group_id_parent_axo(), 'unit_test', 'object_1', 'AXO');
        $message = 'add_group_object failed';
        $this->assert($result, $message);
    }
    
    function del_group_object_axo() {
        $result = $this->gacl_api->del_group_object($this->get_group_id_parent_axo(), 'unit_test', 'object_1', 'AXO');
        $message = 'del_group_object failed';
        $this->assert($result, $message);
    }
}

// general
$suite->addTest(new phpgacl_api_test('get_version'));
$suite->addTest(new phpgacl_api_test('get_schema_version'));

$suite->addTest(new phpgacl_api_test('count_all'));

// build structure
$suite->addTest(new phpgacl_api_test('add_object_section_aco'));
$suite->addTest(new phpgacl_api_test('get_object_section_section_id_aco'));
$suite->addTest(new phpgacl_api_test('add_object_aco'));
$suite->addTest(new phpgacl_api_test('get_object_id_aco'));

$suite->addTest(new phpgacl_api_test('add_object_section_aro'));
$suite->addTest(new phpgacl_api_test('get_object_section_section_id_aco'));
$suite->addTest(new phpgacl_api_test('add_object_aro'));
$suite->addTest(new phpgacl_api_test('get_object_id_aro'));

$suite->addTest(new phpgacl_api_test('add_object_section_axo'));
$suite->addTest(new phpgacl_api_test('get_object_section_section_id_axo'));
$suite->addTest(new phpgacl_api_test('add_object_axo'));
$suite->addTest(new phpgacl_api_test('get_object_id_axo'));

$suite->addTest(new phpgacl_api_test('add_group_parent_aro'));
$suite->addTest(new phpgacl_api_test('get_group_id_parent_aro'));
$suite->addTest(new phpgacl_api_test('add_group_child_aro'));
$suite->addTest(new phpgacl_api_test('get_group_id_child_aro'));
$suite->addTest(new phpgacl_api_test('add_group_object_aro'));
$suite->addTest(new phpgacl_api_test('get_group_object_id_aro'));

$suite->addTest(new phpgacl_api_test('add_group_parent_axo'));
$suite->addTest(new phpgacl_api_test('get_group_id_parent_axo'));
$suite->addTest(new phpgacl_api_test('add_group_child_axo'));
$suite->addTest(new phpgacl_api_test('get_group_id_child_axo'));
$suite->addTest(new phpgacl_api_test('add_group_object_axo'));
$suite->addTest(new phpgacl_api_test('get_group_object_id_axo'));

// clean up...
$suite->addTest(new phpgacl_api_test('del_group_object_aro'));
$suite->addTest(new phpgacl_api_test('del_group_child_aro'));
$suite->addTest(new phpgacl_api_test('del_group_parent_aro'));

$suite->addTest(new phpgacl_api_test('del_group_object_axo'));
$suite->addTest(new phpgacl_api_test('del_group_child_axo'));
$suite->addTest(new phpgacl_api_test('del_group_parent_axo'));

$suite->addTest(new phpgacl_api_test('del_object_aco'));
$suite->addTest(new phpgacl_api_test('del_object_section_aco'));

$suite->addTest(new phpgacl_api_test('del_object_aro'));
$suite->addTest(new phpgacl_api_test('del_object_section_aro'));

$suite->addTest(new phpgacl_api_test('del_object_axo'));
$suite->addTest(new phpgacl_api_test('del_object_section_axo'));

// done.

?>