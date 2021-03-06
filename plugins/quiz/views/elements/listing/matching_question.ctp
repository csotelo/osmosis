<?php
	$num_choices = sizeof($question['SourceChoice']);
	$question = $question['MatchingQuestion'];
?>
<li class="question-list-element">
	<?php
		$num = __('%s Match items', true);
		if ($num_choices==1) {
			$num = __('%s Match item', true);
		}
	?>
	<h4><?php echo $html->link($text->truncate(strip_tags($question['body']), 200), array(
			'controller' => 'matching_questions',
			'action' => 'preview',
			$question['id'],
			'course_id' => $course['Course']['id']
			),array('class' => 'question-preview-link')); ?>
	</h4>
	<?php
		echo $html->tag('span',sprintf($num, $num_choices));
		echo $html->div('question-list-content','');
		echo $this->element(
			'selection_list.add_question',
			array('question_id' => $question['id'], 'i' => $questionIndex, 'type'=>'MatchingQuestion')
		);
	?>
</li>