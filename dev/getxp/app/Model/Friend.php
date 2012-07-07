<?php
App::uses('AppModel', 'Model');
/**
 * Friend Model
 *
 */
class Friend extends AppModel {

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
