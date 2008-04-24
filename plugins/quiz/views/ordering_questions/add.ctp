<?php debug($form->validationErrors);?><div class="orderingQuestion choices">
<?php echo $form->create('OrderingQuestion');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Create Ordering Question', true));?></legend>
		<div class="information">
			<p class="description">
				<?php
					__('This type of question has many options (with one or more correct answers) and requests the student to select the correct answer.');
				?>
			</p>

	<?php
		echo $form->input('body');
		echo '<div class="checkbox">' . $form->input('shuffle', array('label' => __('Always shuffle the order of the choices.', true))). '</div>';
		// echo $form->input(
		// 		'max_choices',
		// 		array(
		// 			'after' => '<span class="help">Maximum number of choices that the student is allowed to select. Leave empty to have no restriction.</span>'
		// 		)
		// 	);
		// 	echo $form->input(
		// 		'min_choices',
		// 		array(
		// 			'after' => '<span class="help">Minimum number of choices that the student is required to select to form a . Leave empty to have no restriction.</span>'
		// 		)
		// 	);
	?>
		</div>
		<fieldset class="question-choices">
			<legend><?php echo sprintf(__('Choices', true));?></legend>
			<p class="description">
				<?php __('Write the choices in the correct order. Leave both fields empty to ignore.')?>
			</p>
			<?php
				for ($i=0;$i<$totalChoices;$i++) :
					$class = '';
					if ($i%2==0) {
						$class = ' altrow';
					}
			?>
				<div class="choice<?php echo $class?>">
			<?php
				echo '<div class="text">';
				echo $form->input('OrderingChoice.'.$i.'.text', array('rows' => 2));
				echo '</div>';
				echo '<div class="position">';
				echo $form->input(
					'OrderingChoice.'.$i.'.position',
					array('label' => __('Fixed Position',true))
				);
				echo '</div>';
			?>
				</div>
			<?php
				endfor;
				echo $form->submit(__('Add New Choice',true),array('name' => 'data[UI][addChoice]','value' => 1));
			?>
		</fieldset>
		<?php
			echo $form->input('Quiz.0.id');
		?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('OrderingQuestions', true)), array('action'=>'index'));?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Ordering Choices', true)), array('controller'=> 'ordering_choices', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Ordering Choice', true)), array('controller'=> 'ordering_choices', 'action'=>'add')); ?> </li>
	</ul>
</div>
