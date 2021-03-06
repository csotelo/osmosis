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
uses('Folder');
class ScormsController extends ScormAppController {

	var $name = 'Scorms';
	var $components = array('Zip');
	var $helpers = array('Html', 'Form', 'Tree', 'Javascript','Dynamicjs','Time');
	var $uses = array('Scorm.Scorm', 'Scorm.ScormAttendeeTracking');
	
	function _setActiveCourse() {
		$actions = array('view','toc','delete');
		if (in_array($this->action,$actions) && !empty($this->params['pass'][0]) && !isset($this->params['named']['course_id'])) {
			$this->activeCourse = $this->Scorm->field('course_id',array('id' => $this->params['pass'][0]));
		} else
			parent::_setActiveCourse();
	}
	
	function isAuthorized() {
		if ($this->action == 'toc')
			return $this->params['requested'];
			
		return parent::isAuthorized();;
	}
	
	function index() {
		$this->Scorm->recursive = -1;
		$this->set('scorms', $this->paginate(array('course_id' => $this->activeCourse)));
		$recent = $this->Scorm->recent($this->Auth->user('id'));
		$this->set(compact('recent'));
	}
	
	function toc($id) {
		$this->set('scorm', $this->Scorm->find(array('id' => $id), array('Scorm.*')));
		$this->Scorm->Sco->contain(false);
		$this->set('scos', $this->Scorm->Sco->findAllThreaded(array('Sco.scorm_id' => $id), array('Sco.*')));
		$this->plugin = 'scorm';
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid Scorm', 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'), null, true);
		}
		$trackings = $this->ScormAttendeeTracking->findAll(
			array(
				'student_id' => ''.$this->Auth->user('id'),
				'datamodel_element' => 'cmi__completion_status',
				'value' => 'completed'
			), 
			'sco_id', 'sco_id ASC'
		);
		$trackings = Set::extract($trackings, '{n}.ScormAttendeeTracking.sco_id');
		$scos = $this->Scorm->Sco->findAll(
			array('scorm_id' => $id, 'href IS NOT NULL'),
			null, 'id ASC', null, 1, -1
		);
		$show_sco = array();
		foreach ($scos as $sco) {
			$sco = $sco['Sco'];
			if (in_array($sco['id'], $trackings)) continue;
			$show_sco['id'] = $sco['id']; 
			$show_sco['href'] = $sco['href'];
			break;
		}
		// If no sco was found show first
		if (empty($show_sco)) {
			$show_sco['id'] = $scos[0]['Sco']['id'];
			$show_sco['href'] = $scos[0]['Sco']['href'];
		}
		$this->set('show_sco', $show_sco);
		$this->Scorm->recursive = -1;
		$this->set('scorm', $this->Scorm->find(array('id' => $id), array('Scorm.*')));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Scorm->create();
			$uploaded_file = $this->data['Scorm']['file_name'];
			
			$is_uploaded = is_uploaded_file($uploaded_file['tmp_name']); 
			if ($is_uploaded) {
				$this->data['Scorm']['file_name'] = $uploaded_file['name'];
				// Extract the zip file to TMP
				$this->Zip->begin($uploaded_file['tmp_name']);
				$scorm_files_location = TMP.'tests'.DS.'imsmanifests'.DS.'uploads'.DS.$uploaded_file['name'].DS;
				$this->data['Scorm']['path']= str_replace(APP, '', $scorm_files_location);
				if ($this->Zip->extract($scorm_files_location)===false) {
					$this->Session->setFlash(__d('scorm','The Scorm file could not be unzipped',true), 'default', array('class' => 'error'));
					return;
				}
				$this->Zip->close();
				// Parse
				if (!$this->Scorm->parseManifest($scorm_files_location)){
					$folder = new Folder($scorm_files_location);
					$folder->delete($scorm_files_location);
					$this->Session->setFlash(__d('scorm','The Scorm file could not be parsed',true), 'default', array('class' => 'error'));
					return;
				}
				if ($this->Scorm->data['Scorm']['version']!= '2004 3rd Edition'){
					$folder = new Folder($scorm_files_location);
					$folder->delete($scorm_files_location);
					$this->Session->setFlash(__d('scorm','The Scorm doesn\'t have the right version',true), 'default', array('class' => 'error'));
				
				} elseif ($this->Scorm->save($this->data)) {
					$this->Session->setFlash(__d('scorm','The Scorm has been saved',true), 'default', array('class' => 'success'));
					$this->redirect(array('action'=>'index', 'course_id' => $this->data['Scorm']['course_id']), null, true);
				} else {
					$folder = new Folder(TMP.'tests'.DS.'imsmanifests'.DS.'uploads'.DS.$uploaded_file['name'].DS);
					$folder->delete();
				}
			} else { 
				$this->Session->setFlash(__d('scorm','The Scorm could not be uploaded. Please, try again.',true), 'default', array('class' => 'error'));
			}
		}
		$this->set('course_id',$this->activeCourse);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__d('scorm','Invalid Scorm',true), 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			if ($this->Scorm->save($this->data)) {
				$this->Session->setFlash(__d('scorm','The Scorm saved',true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash(__d('scorm','The Scorm could not be saved. Please, try again.',true), 'default', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Scorm->read(null, $id);
		}
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__d('scorm','Invalid id for Scorm',true), 'default', array('class' => 'error'));
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->data = $this->Scorm->find(array('id'=> $id),null,null,null);			
			$uploaded_file = $this->data['Scorm']['file_name'];
			$scorm_files_location = TMP.'tests'.DS.'imsmanifests'.DS.'uploads'.DS.$uploaded_file;
			$folder = new Folder($scorm_files_location);
			$folder->delete($scorm_files_location);
		if ($this->Scorm->del($id)) {			
			$this->Session->setFlash(__d('scorm','Scorm deleted',true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'), null, true);
		}
	}
	
	function __selectLayout() {
		if ($this->action == 'view') {
			$this->layout = 'viewport';
		} else {
			parent::__selectLayout();
		}
	}
	
	
}
?>
