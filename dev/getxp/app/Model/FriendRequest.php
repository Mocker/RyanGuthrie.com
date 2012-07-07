<?php
App::uses('AppModel', 'Model');
/**
 * FriendRequest Model
 *
 */
class FriendRequest extends AppModel {


		public $belongsTo = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
