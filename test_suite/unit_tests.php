<?php
require_once(dirname(__FILE__).'/../gacl.class.php');
require_once(dirname(__FILE__).'/../gacl_api.class.php');
require_once(dirname(__FILE__).'/../admin/gacl_admin.inc.php');
require_once(dirname(__FILE__).'/phpunit/phpunit.php');

$suite = new TestSuite("phpGACL API");

class phpgacl_api_test extends TestCase {
   
    function phpgacl_api_test($name) {
        global $gacl, $gacl_api;
        
        $this->TestCase($name);
        $this->gacl_api = &$gacl_api;
    }
    
    function setUp() {
        //$this->abc = new String("abc");
    }
    
    function tearDown() {
        //unset($this->abc);
    }
    
    function get_version() {
        $result = $this->gacl_api->get_version();
        $expected = '/[0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2}/i';

        $this->assertRegexp($expected, $result, "Version incorrect.");
    }
    function get_schema_version() {
        $result = $this->gacl_api->get_schema_version();
        $expected = '/[0-9]{1,2}.[0-9]{1,2}/i';

        $this->assertRegexp($expected, $result, "Schema Version incorrect.");
    }

    function count_all() {
		//Create array
		$arr = array(	'Level1a' =>
									array(	'Level2a' =>
										  				array(	'Level3a' => 1,
																'Level3b' => 1),
											'Level2b' => 1,
										),
						'Level1b' => 1,
						'Level1c' =>
									array(	'Level2c' =>
										  				array(	'Level3c' => 1,
																'Level3d' => 1),
											'Level2d' => 1,
										),
						'Level1d' => 1,
					);
        $result = $this->gacl_api->count_all($arr);
		$this->gacl_api->showarray($result);
        $this->assert($result == 12, "Incorrect array count(), Should be 12.");
    }




    function get_object_section_section_id_aco() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'ACO');
        $message = "get_object_section_section_id failed";

        $this->assert($result, $message);
        
        return $result;
    }
    function add_object_section_aco() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'ACO');
        $message = "add_object_section failed";

        $this->assert($result, $message);
    }
    function del_object_section_aco() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_aco(), 'ACO');
        $message = "del_object_section failed";
        $this->assert($result, $message);
    }
    function get_object_id_aco() {
        $result = $this->gacl_api->get_object_id('unit_test','enable_tests', 'ACO');
        $message = "get_object_id failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function add_object_aco() {
        $result = $this->gacl_api->add_object('unit_test', 'Enable - Tests', 'enable_tests', 999, 0, 'ACO');
        $message = "add_object failed";
        $this->assert($result, $message);        
    }
    function del_object_aco() {
        $result = $this->gacl_api->del_object($this->get_object_id_aco(), 'ACO');
        $message = "del_object failed";
        $this->assert($result, $message);        
    }


    function get_object_section_section_id_aro() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'ARO');
        $this->_aco_section_id = $result;
        $message = "get_object_section_section_id failed";

        $this->assert($result >= 0, $message);
        
        return $result;
    }
    function add_object_section_aro() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'ARO');
        $message = "add_object_section failed";

        $this->assert($result, $message);
    }
    function del_object_section_aro() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_aro(), 'ARO');
        $message = "del_object_section failed";
        $this->assert($result, $message);
    }
    function get_object_id_aro() {
        $result = $this->gacl_api->get_object_id('unit_test','john_doe', 'ARO');
        $message = "get_object_id failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function add_object_aro() {
        $result = $this->gacl_api->add_object('unit_test', 'John Doe', 'john_doe', 999, 0, 'ARO');
        $message = "add_object failed";
        $this->assert($result, $message);        
    }
    function del_object_aro() {
        $result = $this->gacl_api->del_object($this->get_object_id_aro(), 'ARO');
        $message = "del_object failed";
        $this->assert($result, $message);        
    }


    function get_object_section_section_id_axo() {
        $result = $this->gacl_api->get_object_section_section_id('unit_test', 'unit_test', 'AXO');
        $message = "get_object_section_section_id failed";

        $this->assert($result, $message);
        
        return $result;
    }
    function add_object_section_axo() {
        $result = $this->gacl_api->add_object_section('unit_test', 'unit_test', 999, 0, 'AXO');
        $this->_aco_section_id = $result;
        $message = "add_object_section failed";

        $this->assert($result, $message);
    }
    function del_object_section_axo() {
        $result = $this->gacl_api->del_object_section($this->get_object_section_section_id_axo(), 'AXO');
        $message = "del_object_section failed";
        $this->assert($result, $message);
    }
    function get_object_id_axo() {
        $result = $this->gacl_api->get_object_id('unit_test','object_1', 'AXO');
        $message = "get_object_id failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function add_object_axo() {
        $result = $this->gacl_api->add_object('unit_test', 'Object 1', 'object_1', 999, 0, 'AXO');
        $message = "add_object failed";
        $this->assert($result, $message);        
    }
    function del_object_axo() {
        $result = $this->gacl_api->del_object($this->get_object_id_axo(), 'AXO');
        $message = "del_object failed";
        $this->assert($result, $message);        
    }

    function get_group_id_parent_aro() {
        $result = $this->gacl_api->get_group_id('ARO Group 1', 'ARO');
        $message = "get_group_id_parent_aro failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function get_group_id_child_aro() {
        $result = $this->gacl_api->get_group_id('ARO Group 2', 'ARO');
        $message = "get_group_id_child_aro failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function add_group_parent_aro() {
        $result = $this->gacl_api->add_group('ARO Group 1', 0, 'ARO');
        $message = "add_group_parent_aro failed";
        $this->assert($result, $message);
    }
    function del_group_parent_aro() {
        $result = $this->gacl_api->del_group($this->get_group_id_parent_aro(), TRUE, 'ARO');
        $message = "del_group_parent_aro failed";
        $this->assert($result, $message);        
    }
    function add_group_child_aro() {
        $result = $this->gacl_api->add_group('ARO Group 2', $this->get_group_id_parent_aro(), 'ARO');
        $message = "add_group_child failed";
        $this->assert($result, $message);
    }
    function del_group_child_aro() {
        $result = $this->gacl_api->del_group($this->get_group_id_child_aro(), TRUE, 'ARO');
        $message = "del_group failed";
        $this->assert($result, $message);        
    }
    function add_group_object_aro() {
        $result = $this->gacl_api->add_group_object($this->get_group_id_parent_aro(), 'unit_test', 'john_doe', 'ARO');
        $message = "add_group_object failed";
        $this->assert($result, $message);        
    }
    function del_group_object_aro() {
        $result = $this->gacl_api->del_group_object($this->get_group_id_parent_aro(), 'unit_test', 'john_doe', 'ARO');
        $message = "del_group_object failed";
        $this->assert($result, $message);        
    }


    function get_group_id_parent_axo() {
        $result = $this->gacl_api->get_group_id('AXO Group 1', 'AXO');
        $message = "get_group_id_parent_aro failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function get_group_id_child_axo() {
        $result = $this->gacl_api->get_group_id('AXO Group 2', 'AXO');
        $message = "get_group_id_child_axo failed";
        $this->assert($result, $message);
        
        return $result;
    }
    function add_group_parent_axo() {
        $result = $this->gacl_api->add_group('AXO Group 1', 0, 'AXO');
        $message = "add_group failed";
        $this->assert($result, $message);
    }
    function del_group_parent_axo() {
        $result = $this->gacl_api->del_group($this->get_group_id_parent_axo(), TRUE, 'AXO');
        $message = "del_group failed";
        $this->assert($result, $message);        
    }
    function add_group_child_axo() {
        $result = $this->gacl_api->add_group('AXO Group 2', $this->get_group_id_parent_axo(), 'AXO');
        $message = "add_group failed";
        $this->assert($result, $message);
    }
    function del_group_child_axo() {
        $result = $this->gacl_api->del_group($this->get_group_id_child_axo(), TRUE, 'AXO');
        $message = "del_group failed";
        $this->assert($result, $message);        
    }
    function add_group_object_axo() {
        $result = $this->gacl_api->add_group_object($this->get_group_id_parent_axo(), 'unit_test', 'object_1', 'AXO');
        $message = "add_group_object failed";
        $this->assert($result, $message);        
    }
    function del_group_object_axo() {
        $result = $this->gacl_api->del_group_object($this->get_group_id_parent_axo(), 'unit_test', 'object_1', 'AXO');
        $message = "del_group_object failed";
        $this->assert($result, $message);        
    }
}

	
$suite->addTest(new phpgacl_api_test("get_version"));
$suite->addTest(new phpgacl_api_test("get_schema_version"));

$suite->addTest(new phpgacl_api_test("count_all"));

$suite->addTest(new phpgacl_api_test("add_object_section_aco"));
$suite->addTest(new phpgacl_api_test("get_object_section_section_id_aco"));
$suite->addTest(new phpgacl_api_test("add_object_aco"));
$suite->addTest(new phpgacl_api_test("get_object_id_aco"));

$suite->addTest(new phpgacl_api_test("add_object_section_aro"));
$suite->addTest(new phpgacl_api_test("get_object_section_section_id_aco"));
$suite->addTest(new phpgacl_api_test("add_object_aro"));
$suite->addTest(new phpgacl_api_test("get_object_id_aro"));

$suite->addTest(new phpgacl_api_test("add_object_section_axo"));
$suite->addTest(new phpgacl_api_test("get_object_section_section_id_axo"));
$suite->addTest(new phpgacl_api_test("add_object_axo"));
$suite->addTest(new phpgacl_api_test("get_object_id_axo"));

$suite->addTest(new phpgacl_api_test("add_group_parent_aro"));
$suite->addTest(new phpgacl_api_test("get_group_id_parent_aro"));
$suite->addTest(new phpgacl_api_test("add_group_child_aro"));
$suite->addTest(new phpgacl_api_test("get_group_id_child_aro"));
$suite->addTest(new phpgacl_api_test("add_group_object_aro"));
$suite->addTest(new phpgacl_api_test("get_group_object_id_aro"));

$suite->addTest(new phpgacl_api_test("add_group_parent_axo"));
$suite->addTest(new phpgacl_api_test("get_group_id_parent_axo"));
$suite->addTest(new phpgacl_api_test("add_group_child_axo"));
$suite->addTest(new phpgacl_api_test("get_group_id_child_axo"));
$suite->addTest(new phpgacl_api_test("add_group_object_axo"));
$suite->addTest(new phpgacl_api_test("get_group_object_id_axo"));



//Clean up...
$suite->addTest(new phpgacl_api_test("del_group_object_aro"));
$suite->addTest(new phpgacl_api_test("del_group_child_aro"));
$suite->addTest(new phpgacl_api_test("del_group_parent_aro"));


$suite->addTest(new phpgacl_api_test("del_group_object_axo"));
$suite->addTest(new phpgacl_api_test("del_group_child_axo"));
$suite->addTest(new phpgacl_api_test("del_group_parent_axo"));

$suite->addTest(new phpgacl_api_test("del_object_aco"));
$suite->addTest(new phpgacl_api_test("del_object_section_aco"));

$suite->addTest(new phpgacl_api_test("del_object_aro"));
$suite->addTest(new phpgacl_api_test("del_object_section_aro"));

$suite->addTest(new phpgacl_api_test("del_object_axo"));
$suite->addTest(new phpgacl_api_test("del_object_section_axo"));

?>