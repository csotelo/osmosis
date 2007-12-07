<?php
App::import('Model', 'scorm.RollupConsideration');

class RollupConsiderationTestCase extends CakeTestCase {
	var $TestObject = null;
	var $fixtures = array('rollup_consideration');

	function setUp() {
		$this->TestObject = new RollupConsideration();
		$this->TestObject->useDbConfig = 'test_suite';
		$this->TestObject->tablePrefix = 'test_suite_';
	}

	function tearDown() {
		unset($this->TestObject);
	}

	function testValidation1() {
		$data = array();
		$this->TestObject->data = $data;
		$valid = $this->TestObject->validates();
		$expectedErrors = array();
		$this->assertEqual($this->TestObject->validationErrors, $expectedErrors);
	}
	
	function testValidation2() {
		$data = array(
			'RollupConsideration' => array(
				'requiredForSatisfied'			=> 'yes',
				'requiredForNotSatisfied'		=> 'nope',
				'requiredForComplete'			=> 'yes',
				'requiredForIncomplete'			=> 'nope',
				'measureSatisfactionIfActive'	=> 'yes',
			)
		);
		$this->TestObject->data = $data;
		$valid = $this->TestObject->validates();
		$expectedErrors = array(
			'requiredForSatisfied'			=> 'scormplugin.rollupconsideration.requiredforsatisfied.token',
			'requiredForNotSatisfied'		=> 'scormplugin.rollupconsideration.requiredfornotsatisfied.token',
			'requiredForComplete'			=> 'scormplugin.rollupconsideration.requiredforcomplete.token',
			'requiredForIncomplete'			=> 'scormplugin.rollupconsideration.requiredforincomplete.token',
			'measureSatisfactionIfActive'	=> 'scormplugin.rollupconsideration.measuresatisfactionifactive.boolean',
		);
		$this->assertEqual($this->TestObject->validationErrors, $expectedErrors);
	}
	
	function testValidation3() {
		$token = array ('always', 'ifAttempted', 'ifNotSkipped', 'ifNotSuspended');
		foreach ($token as &$value){
		$this->TestObject->create();
		$data = array(
			'RollupConsideration' => array(
				'requiredForSatisfied'			=> $value,
				'requiredForNotSatisfied'		=> $value,
				'requiredForComplete'			=> $value,
				'requiredForIncomplete'			=> $value,
				'measureSatisfactionIfActive'	=> 'true',
			)
		);
			$this->TestObject->data = $data;
			$valid = $this->TestObject->validates();
			$expectedErrors = array();
			$this->assertEqual($this->TestObject->validationErrors, $expectedErrors);
		}		
	}
	
	function testValidation4() {
			$token = array ('always', 'ifAttempted', 'ifNotSkipped', 'ifNotSuspended');
			foreach ($token as &$value){
			$this->TestObject->create();
			$data = array(
				'RollupConsideration' => array(
					'requiredForSatisfied'			=> $value,
					'requiredForNotSatisfied'		=> $value,
					'requiredForComplete'			=> $value,
					'requiredForIncomplete'			=> $value,
					'measureSatisfactionIfActive'	=> 'false',
				)
			);
				$this->TestObject->data = $data;
				$valid = $this->TestObject->validates();
				$expectedErrors = array();
				$this->assertEqual($this->TestObject->validationErrors, $expectedErrors);
			}		
		}
	
	function testSave() {
		$data = array(
    		'RollupConsideration' => array(
					'requiredForSatisfied'			=> 'always',
					'requiredForNotSatisfied'		=> 'always',
					'requiredForComplete'			=> 'always',
					'requiredForIncomplete'			=> 'always',
					'measureSatisfactionIfActive'	=> 'true',
			)
		);
		$this->TestObject->save($data);
		$this->assertEqual(2,$this->TestObject->findCount());
	}
	
	function testSave2() {
		$data = array();
		$this->TestObject->save($data);
		$this->assertEqual(1,$this->TestObject->findCount());
	}
	
}
?>
