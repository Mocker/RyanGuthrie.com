<?php
App::uses('AppModel', 'Model');
/**
 * CenterMember Model
 *
 */
class CenterMember extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'type';

	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
