<?php
App::uses('AppModel', 'Model');
/**
 * Wave Model
 *
 */
class Wave extends AppModel {

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
