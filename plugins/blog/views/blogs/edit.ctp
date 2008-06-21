<div class="blogs form">
<?php echo $form->create('Blog');?>
	<fieldset>
 		<legend><?php __('Edit Blog');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('title');
		echo $form->input('description');
		echo $form->input('owner');
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Blog.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Blog.id'))); ?></li>
		<li><?php echo $html->link(__('List Blogs', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'blogs', 'action'=>'view', $form->value('Blog.id'))); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add', $form->value('Blog.id'))); ?> </li>
	</ul>
</div>
