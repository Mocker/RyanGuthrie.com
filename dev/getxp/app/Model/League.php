<?php
App::uses('AppModel', 'Model');
/**
 * League Model
 *
 */
class League extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	public $belongsTo = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Network' => array(
			'className' => 'Network',
			'foreignKey' => 'network_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)

	);
}
