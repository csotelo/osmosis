<h1>
	<?php
		printf(__d('blog','%s\' Blog', true), $blog['Member']['full_name']);
	?>
</h1>
<?php
	if ($Osmosis['active_member']['id']==$blog['Member']['id']) :
?>
	<ul class="reverse actions">
		<li class="add">
			<?php
				echo $html->link(
					__d('blog','New Post', true),
					array('controller'=> 'posts', 'action'=>'add', $blog['Blog']['id'])
				);
			?>
		</li>
	</ul>
<?php
	endif;
?>
<div class="blog-posts">
<?php
	if (!empty($blog['Post'])):
		$i = 0;
		foreach ($blog['Post'] as $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			echo '<div class="post">'.$this->element('post', array('post' => $post, 'single' => false)).'</div>';
		endforeach;
	else :
?>
	<p>
		<?php
			__d('blog','No Posts written yet');
		?>
	</p>
<?php
	endif;
?>
</div>
