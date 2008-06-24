<?php
/* SVN FILE: $Id$ */
/**
 * Ósmosis LMS: <http://www.osmosislms.org/>
 * Copyright 2008, Ósmosis LMS
 *
 * This file is part of Ósmosis LMS.
 * Ósmosis LMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ósmosis LMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ósmosis LMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @filesource
 * @copyright		Copyright 2008, Ósmosis LMS
 * @link			http://www.osmosislms.org/
 * @package			org.osmosislms
 * @subpackage		org.osmosislms.app
 * @since			Version 2.0 
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 */
class ModelLog extends AppModel {

	var $name = 'ModelLog';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
	
	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->__findMethods['sql'] = true;
	}
	
	function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
		if (is_string($conditions) && $conditions == 'log') {
			return $this->_findLog($fields);
		} else {
			return parent::find($conditions,$fields,$order,$recursive);
		}
	}
	
	function _findLog($options) {
		$logs = $this->find('all',
			array(
				'conditions' => array(
					'time >' => strtotime("-1 weeks"),
					'model' => array_keys($options['models']),
					'course_id' => $options['course_id']
				),
				'limit' => 50
			)
		);

		$results = $queried = array();
		foreach ($logs as $i => $log) {
			$modelLog = $log['ModelLog'];
			$modelName = $modelLog['model'];
			$entity_id = $modelLog['entity_id'];
			
			if (isset($queried[$modelName][$entity_id]))
				$data = $queried[$modelName][$entity_id];
			else {
				$data = $this->__getModelData(
					$modelName,
					${$modelName},
					$options['plugin'],
					$entity_id,
					isset($options['models'][$modelName]['contain']) ? $options['models'][$modelName]['contain'] : array(),
					$options['models'][$modelName]['fields']
				);
					
				if (!$data) continue;
				$queried[$modelName][$entity_id] = $data;
			}
			
			$log['ModelLog']['data'] = $data;
			if (isset($options['models'][$modelName]['order_by'])){
				$entity_id = Set::extract($options['models'][$modelName]['order_by'] . '[:first]', $log['ModelLog']['data'] );
				$entity_id = $entity_id[0];
			}
			$results[$modelLog['course_id']][$modelName][$entity_id][] = $log;
		}
		return $results;
	}
	
	function __getModelData($modelName, &$Model, $plugin, $id, $contain, $fields) {
		if (!$Model) {
			$Model = ClassRegistry::init($plugin.'.'.$modelName);
		}
		if ($contain) {
			$Model->contain($contain);
		} elseif ($contain === false) {
			$recursive = -1;
		}
		
		$conditions = array($modelName . '.id' => $id);
		$result = $Model->find('first',compact('conditions','fields','recursive'));
		return $result;
	}

}
?>
