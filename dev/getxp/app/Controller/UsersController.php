<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Acl', 'Auth', 'Session');
    var $helpers = array('Html', 'Form', 'Session');

	function beforeFilter() {
    	parent::beforeFilter(); 
    	//$this->Auth->allow(array('*'));
    	$this->Auth->allowedActions = array('login','logout','register');
    	//$this->Auth->allowedActions = array('login','logout');
	}


	function login(){
		//$hashed = $this->Auth->hashPasswords($this->data);
		//$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
		$manUser = $this->User->find('first', array(
			'conditions'=>array(
				'User.email'=>$this->data['User']['username'],
				'User.password'=>$this->Auth->password($this->data['User']['password'])
				)));
		if(!$manUser){
			$this->Session->setFlash('Login Invalid');
			$this->redirect('/');
		}
		else {
		$this->Session->write('Auth.User',$manUser['User']); 


		//$logged = $this->Auth->login($this->data);
		$user = $this->Session->read('Auth.User');
		$datuser = $this->Auth->user();
		
		//$datpwd = $this->Auth->password($this->data['User']['password']);
		//print_r($datuser); print_r($datpwd); exit;
		if(isset($user['id'])){
			//print "FOUND USER --------";
			//print_r($user); exit;
			//print "USER ID FOUND - ".$user['id']; exit;
			$this->redirect('/home');
		}
		
		
		}
	}
	public function logout(){
		$this->Session->setFlash('Logout');
		$this->Auth->logout();
		$this->redirect('/');
		//$this->redirect($this->Auth->logout());
	}

	public function register() {
		if ($this->request->is('post')) {
			if(isset($this->request->data['User'])){
				$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
				$this->request->data['User']['email'] = $this->request->data['User']['username'];
			}
			
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				//$this->Session->setFlash(__('The user has been saved'));
				$this->Session->setFlash('Registration complete. You can now login');
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
