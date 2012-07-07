<?php
App::uses('AppController', 'Controller');
/**
 * Mains Controller
 *
 */
class MainsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;

	var $components = array('Acl', 'Auth', 'Session');
    var $helpers = array('Html', 'Form', 'Session');
 
	function beforeFilter(){
	    //$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
	    //$this->Auth->loginRedirect = array('controller' => 'mains', 'action' => 'home', 'home');
	    $this->Auth->allow('display','home');
	    //$this->Auth->authorize = 'controller';
	    $this->user = $this->Session->read('Auth.User');
	    if(!isset($this->user['id']) ){ unset($this->user); }
	    $this->set('userinfo',$this->user);
	     $this->set('sportlist',array('','tennis','racquetball'));
	}
	function isAuthorized() {
	    return true;
	}


	/* home: main view - if not logged in redirects to front page view, otherwise home page
	*/
	function home(){
		
	}
	//same thing as home
	function display(){
		
		if($this->user){ $this->redirect('/home'); }
		//if($this->Session){ print_r($this->Session); exit; }
		//$this->Session->setFlash('whaaat');
		$this->layout = 'Basic';
		$this->render('home');

	}

	function userhome(){
		if(!$this->user){ $this->redirect('/'); }
		//print "USERHOME ".$this->user['User']['username']."<br> ";
		$this->layout = 'main';
		$this->loadModel('Profile');
		$this->loadModel('Sport');
		$this->loadModel('Center');
		$this->loadModel('Network');

		//check if admin load admin options
		if($this->user['admin']){
			$networks_pending = $this->Network->find('all',
				array('conditions'=>array('Network.approved'=>0),
				array('joins'=>array(
					'tables'=>'users',
					'alias'=>'User',
					'type'=>'RIGHT',
					'conditions'=>array('User.id=Network.user_id'))
				)));
			$this->set('networks_pending',$networks_pending);
		}



		$profiles = $this->Profile->find('all',
			array('conditions'=>array('User.id'=>$this->user['id'])));
		$proflist = $this->Profile->find('list',array(
			'conditions'=>array('user_id'=>$this->user['id']),
			'fields'=>array('sport_id','user_id')
			));
		$this->set('profiles',$profiles);
		$this->set('plist',$proflist);

		
		$this->loadModel('CenterMember');
		$centers_owned = $this->Center->find('all',
			array('conditions'=>array('Center.owner'=>$this->user['id'])));
		$cm_options = array(
			'conditions'=>array('CenterMember.user_id'=>$this->user['id']),
			/*'joins'=>array(
				array('table')
				)*/
			);
		$centers_member = $this->CenterMember->find('all',$cm_options);
		$this->set('centers_owned',$centers_owned);
		$this->set('centers_member',$centers_member);

		$this->set('sports', $this->Sport->find('list'));
		$this->set('dumped',$this->user);

		$this->load_messages();

		$this->layout = 'main';
		$this->render('userhome');
	}

	function approve_network($nid){
		//print "APPROVE NETWORK $nid"; exit;
		//TODO: Send message to requester that network has been approved
		$this->loadModel('Network');
		$this->Network->id = $nid;
		$this->Network->set('approved',1);
		$this->Network->save();
		$this->Session->setFlash("Network $nid Approved");
		$this->redirect('/');

	}
	function deny_network($nid){
		print "Network $nid Removed"; exit;
		//TODO: Send message to requester that network has been removed
		//  	-Allow message for reason why
		//      -Remove network from db
	}

	function request_network(){
		//receive request creation data - insert into database
		if(!$this->data){ print "Missing Request Data"; exit; }
		$this->loadModel('Network');
		$modded = $this->data;
		$modded['Network']['user_id'] = $this->user['id'];
		$this->Network->create();
		if($this->Network->save($modded)){
			print "Success"; exit;
		}
		print "Unable to save data"; exit;
	}

	//front login/register page
	function front(){
		
	}

	function load_messages($doCount=false){
		//check for messages, waves and requests for user
		//if$doCount = true, then simply tally messages and return
		$this->loadModel('FriendRequest');
		$this->loadModel('Profile');
		

		$options = array();
		$options['fields']=array('FriendRequest.*','Profile.*','ProfileT.*');
		$options['joins'] = array(
		    array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = FriendRequest.profile2_id',
		        )
		    )
			);
		$options['conditions'] = array('ProfileT.user_id'=>$this->user['id']);
		$options['order'] = array('FriendRequest.created'=>'DESC');
		$requests = $this->FriendRequest->find('all',$options);
		$waves = array(); $messages = array();
		$this->set('requests',$requests);

		//get messages
		$options = array();
		$this->loadModel('Message');
		$options['fields']=array('Message.*','Profile.*','ProfileT.*');
		$options['order'] = array('Message.sent'=>'DESC','Profile.sport_id'=>'ASC');
		$options['joins'] = array(
		    array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = Message.to_id',
		        )
		    )
			);
		$options['conditions'] = array(
			'ProfileT.user_id'=>$this->user['id'],
			'OR' =>array( array('Message.type'=>'message'),array('Message.type'=>'system'))
			);
		$messages = $this->Message->find('all',$options);
		//print_r($messages); exit;
		//unset($options['conditions']['Message.type']);
		$options['order'] = array('Wave.created'=>'DESC');
		$options['fields'] = array('Wave.*','Profile.*','ProfileT.*');
		$options['joins'] = array(
		    array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = Wave.to_id',
		        )
		    )
			);
		unset($options['conditions']['OR']);
		$this->loadModel('Wave');
		$waves = $this->Wave->find('all',$options);

		//print_r($this->user); print "<br><Br>";
		//print_r($requests);
		//exit;
		$this->set('waves',$waves); $this->set('messages',$messages);
		if($doCount){ return; }
		$this->layout = 'Basic';
		$this->render('inbox');
	}

	

	function friends($saction,$sparam){

		if($saction=='approve'){
			$this->approveFriend($sparam); return;
		}
		if($saction=='deny'){
			$this->denyFriend($param); return;
		}
	}
	function approveFriend($fID){
		//print "FRIEND REQUEST $fID APPROVED"; exit;
		$this->loadModel("FriendRequest");
		$this->loadModel("Friend");
		//$this->FriendRequest->id = $fID;
		$fR = $this->FriendRequest->find('first',array('conditions'=>array(
			"FriendRequest.id"=>$fID)));
		if(!$fR || !$fR['FriendRequest']['profile_id']){
			print "Unable to find and approve friend request"; exit;
		}
		//print "FRIEND REQ $fID<br>";print_r($fR); exit;
		$this->Friend->create();
		$dat = array('Friend'=>array(
			'sport_id'=>$fR['FriendRequest']['sport_id'],
			'profile_id'=>$fR['FriendRequest']['profile_id'],
			'profile2_id'=>$fR['FriendRequest']['profile2_id']
			));
		if($this->Friend->save($dat)){
			if($this->FriendRequest->delete($fID)){
				print "Friend Request Accepted";
			}
			else { print "Friend added, but unable to delete request"; }
			exit;
		}
		print "Unable to approve friend request";
		exit;

	}
	function denyFriend($fID){
		$this->loadModel('FriendRequest');
		$this->FriendRequest->delete($fID);
		print "FRIEND REQUEST DENIED"; exit;
	}

}
