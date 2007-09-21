<?php 

loadModel('scorm.Scorm');

class CourseTestCase extends CakeTestCase {
	var $TestObject = null;
	function setUp() {
		$this->TestObject = new Scorm();
		$this->TestObject->useDbConfig = 'test_suite';
		$this->TestObject->tablePrefix = 'test_suite_';
		//$this->TestObject->loadInfo(true);
	}

	function tearDown() {
		unset($this->TestObject);
	}
	
	function testValidation1() {
		$data = array();
		$this->TestObject->data = $data;
		$valid = $this->TestObject->validates();
		$expectedErrors = array(
			'name'				=> 'Error.empty',
			'description'		=> 'Error.empty',
			'course_id' 		=> 'Error.empty'
		);
		$this->assertEqual($this->TestObject->validationErrors, $expectedErrors);
	}
	
	function testManifestExists() {
		$this->assertTrue($this->TestObject->manifestExists(TMP.'tests'));
		$this->assertFalse($this->TestObject->manifestExists(TMP.'fake'));
	}
	
	function testParseManifest() {
		$this->TestObject->parseManifest(TMP.'tests');
	}
	
	
	function testExtractOrganizations() {
$xml = <<<eof
	<organizations default="DMCE">
	    <organization identifier="DMCE">
			<title>SCORM 2004 3rd Edition Data Model Content Example 1.1</title>
			<metadata>
   				<adlcp:location>lesson1/lesson1MD.xml</adlcp:location>
			</metadata>
			<adlcp:timeLimitAction>exit,no message</adlcp:timeLimitAction>
			<adlcp:dataFromLMS>Some SCO Information</adlcp:dataFromLMS>
			<adlcp:completionThreshold>0.75</adlcp:completionThreshold>
	      <item identifier="WELCOME" identifierref="res_welcome" isvisible="false" parameters="?width=500&#038;length=300>
	        <title>Welcome</title>
	        <adlnav:presentation>
	          <adlnav:navigationInterface>
	            <adlnav:hideLMSUI>continue</adlnav:hideLMSUI>
	            <adlnav:hideLMSUI>previous</adlnav:hideLMSUI>
	          </adlnav:navigationInterface>
	        </adlnav:presentation>
	        <imsss:sequencing>
	          <imsss:controlMode 
				choice="true" 
				choiceExit="true" 
				flow="true" 
				forwardOnly="false" 
				useCurrentAttemptObjectiveInfo = "false" 
				useCurrentAttemptProgressInfo = "true"
				/>
				<imsss:sequencingRules>
					<imsss:preConditionRule>
						<imsss:ruleConditions conditionCombination="any">
						   <imsss:ruleCondition 
							referencedObjective = "some_objective_ID"
							measureThreshold = "0.5000"
							operator = "noOp"
							condition = "satisfied"
							/>
							<imsss:ruleCondition 
							referencedObjective = "some_objective_ID1"
							measureThreshold = "0.8000"
							operator = "not"
							condition = "completed"
							/>
							</imsss:ruleConditions>
						<imsss:ruleAction action = "disabled"/>
					</imsss:preConditionRule>
	
					<imsss:postConditionRule>
					   <imsss:ruleConditions>
					      <imsss:ruleCondition condition="satisfied"/>
					   </imsss:ruleConditions>
					   <imsss:ruleAction action="exitParent"/>
					</imsss:postConditionRule>
					<imsss:exitConditionRule>
					   <imsss:ruleConditions>
					      <imsss:ruleCondition condition="satisfied"/>
					   </imsss:ruleConditions>
					   <imsss:ruleAction action="exit"/>
					</imsss:exitConditionRule>
				</imsss:sequencingRules>
				<imsss:limitConditions attemptLimit="1" attemptAbsoluteDurationLimit="4 days"/><!-- xs>duration ??? --!>
				<imsss:rollupRules rollupObjectiveSatisfied = "true" rollupProgressCompletion = "true" objectiveMeasureWeight = "1.0000">
				   <imsss:rollupRule childActivitySet = "all" minimumCount = "0" minimumPercent = "0.0000" >
						<imsss:rollupConditions conditionCombination = "any">
						   <imsss:rollupCondition condition = "attempted" operator = "noOp"/>
						</imsss:rollupConditions>
				      <imsss:rollupAction action = "completed"/>
				   </imsss:rollupRule>
				</imsss:rollupRules>
			<imsss:objectives>
			<imsss:primaryObjective objectiveID = "PRIMARYOBJ" satisfiedByMeasure = "true">
				<imsss:minNormalizedMeasure>0.6</imsss:minNormalizedMeasure>
					<imsss:mapInfo
								targetObjectiveID = "obj_module_1"
								readSatisfiedStatus="false"
					          	readNormalizedMeasure = "false"
					          	writeSatisfiedStatus = "true"
								writeNormalizedMeasure="false"/>
	        </imsss:sequencing>
	      </item>
		</organization>
	</organizations>
eof;
	debug(new XML($xml));
		
	}

	function testGetSchemaVersion() {
		$manifest = new XMLNode('manifest');
		$metadata = new XMLNode('metadata');
		$schema = new XMLNode('schema', null, 'ADL SCORM');
		$schemaversion = new XMLNode('schemaversion', null, '2004 3rd Edition');
		$metadata->append($schemaversion);
		$metadata->append($schema);
		$manifest->append($metadata);
		$this->assertEqual(
			$this->TestObject->getSchemaVersion($manifest),
			'2004 3rd Edition'
		);
	}

	function testExtractResources() {
		$resources = new XMLNode('resources');
		$resource1 = new XMLNode('resource', array(
				'identifier' => 'RESOURCE1',
				'adlcp:scormType' => 'sco',
				'type' => 'webcontent',
				'href' => 'localitem1.html'
			)
		);
		$resources->append($resource1);
		$resource2 = new XMLNode('resource', array(
				'identifier' => 'RESOURCE2',
				'adlcp:scormType' => 'sco',
				'type' => 'webcontent',
				'href' => 'localitem2.html'
			)
		);
		$resources->append($resource2);
		$resource3 = new XMLNode('resource', array(
				'identifier' => 'RESOURCE3',
				'adlcp:scormType' => 'sco',
				'type' => 'webcontent',
				'href' => 'localitem3.html'
			)
		);
		$file = new XMLNode('file', array('href' => 'localitem3.html')); //Ignored
		$resource3->append($file);
		$resources->append($resource3);
		
		$manifest = new XMLNode('manifest');
		$manifest->append($resources);
		
		
		$this->assertEqual(
			$this->TestObject->extractResources($manifest),
			array (
			    'RESOURCE1' => array (
			            'identifier' => 'RESOURCE1',
			            'adlcp:scormType' => 'sco',
			            'type' => 'webcontent',
			            'href' => 'localitem1.html',
		        ),
			    'RESOURCE2' => array (
			            'identifier' => 'RESOURCE2',
			            'adlcp:scormType' => 'sco',
			            'type' => 'webcontent',
			            'href' => 'localitem2.html'
		        ),
			    'RESOURCE3' => array (
			            'identifier' => 'RESOURCE3',
			            'adlcp:scormType' => 'sco',
			            'type' => 'webcontent',
			            'href' => 'localitem3.html'
		        )

			)
		);
	}
}
?>