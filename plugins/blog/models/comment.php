<?php
class Comment extends BlogAppModel {

	var $name = 'Comment';
	var $useTable = 'blog_comments';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Post' => array('className' => 'Blog.Post',
								'foreignKey' => 'post_id',
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'counterCache' => ''),
								
			'Member' => array('className' => 'Member',
								'foreignKey' => 'member_id',
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'counterCache' => ''),
	);

}
?>
