<?php
class QuizzesController extends AppController {

	var $name = 'Quizzes';
	var $helpers = array('Html', 'Form' );

	function index() {
		$this->Quiz->recursive = 0;
		$this->set('quizzes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid Quiz.');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('quiz', $this->Quiz->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->cleanUpFields();
			$this->Quiz->create();
			if ($this->Quiz->save($this->data)) {
				$this->Session->setFlash('The Quiz has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Quiz could not be saved. Please, try again.');
			}
		}
		$associationQuestions = $this->Quiz->AssociationQuestion->find('list');
		$choiceQuestions = $this->Quiz->ChoiceQuestion->find('list');
		$clozeQuestions = $this->Quiz->ClozeQuestion->find('list');
		$matchingQuestions = $this->Quiz->MatchingQuestion->find('list');
		$orderingQuestions = $this->Quiz->OrderingQuestion->find('list');
		$textQuestions = $this->Quiz->TextQuestion->find('list');
		$this->set(compact('associationQuestions', 'choiceQuestions', 'clozeQuestions', 'matchingQuestions', 'orderingQuestions', 'textQuestions'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Quiz');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			$this->cleanUpFields();
			if ($this->Quiz->save($this->data)) {
				$this->Session->setFlash('The Quiz has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Quiz could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Quiz->read(null, $id);
		}/*
		$associationQuestions = $this->Quiz->AssociationQuestion->find('list');
		$choiceQuestions = $this->Quiz->ChoiceQuestion->find('list');
		$clozeQuestions = $this->Quiz->ClozeQuestion->find('list');
		$matchingQuestions = $this->Quiz->MatchingQuestion->find('list');
		$orderingQuestions = $this->Quiz->OrderingQuestion->find('list');
		$textQuestions = $this->Quiz->TextQuestion->find('list');
		$this->set(compact('associationQuestions','choiceQuestions','clozeQuestions','matchingQuestions','orderingQuestions','textQuestions'));*/
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Quiz');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Quiz->del($id)) {
			$this->Session->setFlash('Quiz #'.$id.' deleted');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}

}
?>