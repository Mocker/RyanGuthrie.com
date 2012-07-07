<?php
App::uses('AppController', 'Controller');
/**
 * Mains Controller
 *
 */
class SportsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;

	var $components = array('Acl', 'Auth', 'Session');
    var $helpers = array('Html', 'Form', 'Session','Js');

    /*
    var leagueMatchTypes = array(
	    'best_3_6_sets'=>'Best of 3 - 6 Game Sets',
				'best_3_tiebreaker'=>'Best of 3- 3rd Set Tiebreaker',
				'best_5_6_sets'=>'Best of 5 - 6 Game Sets',
				'best_5_tiebreaker'=>'Best of 5 - 5th Set Tiebreaker',
				'8_superset'=>'Eight-game Superset',
				'10_superset'=>'10-game Superset',
				'normal_6'=>'Normal 6 Game Set'
				);
	*/ 
	function beforeFilter(){
	    //$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
	    //$this->Auth->loginRedirect = array('controller' => 'mains', 'action' => 'home', 'home');
	    $this->Auth->allow('display','home');
	    //$this->Auth->authorize = 'controller';
	    $this->user = $this->Session->read('Auth.User');
	    $this->set('userinfo',$this->user);
	    $this->levels = array('Absolute Beginner','Advanced Beginner','Intermediate','Upper Intermediate','Advanced','Highly Advanced');
	    $this->levelsa = array('Absolute Beginner'=>'Absolute Beginner','Advanced Beginner'=>'Advanced Beginner','Intermediate'=>'Intermediate','Upper Intermediate'=>'Upper Intermediate','Advanced'=>'Advanced','Highly Advanced'=>'Highly Advanced');
	    $this->set('sportlist',array('','tennis','racquetball'));
	}
	function isAuthorized() {
	    return true;
	}


	/* home: main view - if not logged in redirects to front page view, otherwise home page
	*/
	function home($sname){
		//print(count($this->params['pass']));exit;
		$this->set('sname',$sname);
		if(!isset($this->user['id'])){ $this->redirect('/home'); return; }
		$this->load_sport($sname);
		$this->load_messages(); $this->load_centers();
		$this->sname = $sname;
		if(!isset($this->user) || !isset($this->user['id'])){ $this->redirect('/'); }
		if(count($this->params['pass'])>2){ $svalue=$this->params['pass'][2]; }
		if(count($this->params['pass'])>1){ $saction=$this->params['pass'][1]; }
		if($sname=='profiles'){ $this->doregister(); return; }
		if(!isset($svalue)){ $svalue = null; }
		if(isset($saction)){
			if($saction=='register'){ $this->register($sname); return; }
			if($saction=='profile'){
				if($svalue=='changeStatus'){ $this->profileStatus($sname,$svalue); return; } 
				$this->profile($sname,$svalue); return; }
			if($saction=='centers'){
				if($svalue=='create'){ $this->center_create($sname); return; }
				else if($svalue=='docreate'){ $this->center_docreate($sname); return; }
				else if($svalue=='edit'){ $this->center_edit($sname,$this->params['pass'][3]); return; }
				else if($svalue=='update'){ $this->center_update($sname); return; }
				else if($svalue){ $this->center_view($sname,$svalue); return; }
				else { $this->center_viewall(); return;}
			}
			if($saction=='search'){
				$this->show_search($sname,$svalue);
				return;
			}
			if($saction=='friends'){
				if($svalue=='request'){ $this->friend_request($sname); return;}
				if($svalue=='sendmsg'){ $this->friend_message($sname); return; }
				$this->show_friends($sname);
				return;
			}
			if($saction=='ims'){
				if($svalue=='send'){ $this->send_im(); return; }
				if(isset($svalue)){ $this->get_im($svalue); return; }
				$this->get_im(0); //fetch all users ims
				return;
			}
			if($saction=='chat'){
				if($svalue=='send'){ $this->send_chat(); return; }
				//$sID = $this->params['pass'][3];
				$this->get_chat($svalue);
				return;
			}
			if($saction=='network'){
				if($svalue=='join'){ $this->net_join(); return; }
				if($svalue=='edit'){
					$sID = $this->params['pass'][3];
					$this->net_edit($sID); return; 
				}
				if($svalue){ $this->net_view($svalue); return; }
			}
			if($saction=='photos' && count($this->params['pass'])>3){
				$sID = -1;
				if(1||count($this->params['pass'])>3){  $sID = $this->params['pass'][3]; }
				$this->photos($sname,$svalue,$sID);
				return;
			}
			if($saction=='videos' && count($this->params['pass'])>3){
				$sID = -1;
				if(1 || count($this->params['pass'])>3){ $sID = $this->params['pass'][3]; }
				$this->videos($sname,$svalue,$sID);
				return;
			}
			if($saction=='ajax'){
				$this->parse_ajax($svalue); return;
			}
			if($saction=='tournament'){	
				//tournament registration/creation/viewing/management
				if($svalue=='create'){ $this->tourn_create(); return; }
				if($svalue=='ajax'){ $this->tourn_ajax(); return; }
				if($svalue){
					$this->tourn_view($svalue); return;
				}
			}
			if($saction=='league'){
				if($svalue=='join'){ $sID = $this->params['pass'][3];
					$this->comp_join($sID); return; }
				if($svalue=='create'){
					$this->comp_create(); return; }
				if($svalue=='search'){
					$this->comp_search(); return; 
				}
				if($svalue){
					$this->comp_view($svalue);
					return;
					}
				}
				
			
		}

		//print "Sports Page ".$sname." - <br>\n"; 
		//print_r($this->user);
		//exit;
		//print "SPORT HOME"; exit;
		$this->set('sname',$sname);
		$this->layout="Sport";
		$this->render("home");
	}

	function load_sport($sname){
		$this->loadModel('Sport');
		$this->sport = $this->Sport->find('first',array('conditions'=>array('name'=>$sname),'recursive'=>0));
		//load associated user profile
		$this->loadModel('Profile');
		$this->SProfile = $this->Profile->find('first',array('conditions'=>array(
			'sport_id'=>$this->sport['Sport']['id'],
			'user_id'=>$this->user['id'])));
		if($this->SProfile){
			$this->set('SProfile',$this->SProfile);
		}
		if(!isset($this->params['pass'][1]) || $this->params['pass'][1]!= 'register'){
		$update_query = 'INSERT INTO profile_updateds (sport_id,user_id,profile_id) VALUES ('.
			$this->sport['Sport']['id'].','.$this->user['id'].','.$this->SProfile['Profile']['id'].') ON DUPLICATE KEY UPDATE modified = CURRENT_TIMESTAMP';
		$pUpdated = $this->Profile->query($update_query);
		$this->set('profileUpdated',$pUpdated);
		}

		//load friends
		$this->loadModel('Friend');
		$options = array(
			'fields'=>array('Friend.*','Profile.*','ProfileT.*','Updated.*','UpdatedT.*'),
			'conditions'=>array(
				'Friend.sport_id'=>$this->sport['Sport']['id'],
				'OR'=>array(
				'Profile.user_id'=>$this->user['id'],
				'ProfileT.user_id'=>$this->user['id']
				)),
			'joins'=>array(
				array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = Friend.profile2_id',
		        	)
		        ),
		        array('table'=>'profile_updateds',
		        'alias'=>'Updated',
		        'type'=>'LEFT',
		        'conditions' => array(
		        	'Updated.profile_id = Friend.profile_id',
			        )
			    ),
			    array('table'=>'profile_updateds',
		        'alias'=>'UpdatedT',
		        'type'=>'LEFT',
		        'conditions' => array(
		        	'UpdatedT.profile_id = Friend.profile2_id',
			        )
			    )
		    
				));
		$this->FriendList = $this->Friend->find('all',$options);
		$this->set('friends',$this->FriendList);
		//
		
		
	}

	function load_centers(){
		//load centers and networks user belongs to into array for view
		$this->loadModel("Center");
		$this->loadModel("CenterMember");
		$this->loadModel("Network");
		$centersOwned = $this->Center->find('all',array('conditions'=>array(
			'Center.owner'=>$this->user['id'])));
		$centersMember = $this->CenterMember->find('all',array('conditions'=>array(
			'CenterMember.user_id'=>$this->user['id'])));
		$networksOwned = $this->Network->find('all',array('conditions'=>array(
			'Network.user_id'=>$this->user['id'])));
		$this->set('centersOwned',$centersOwned);
		$this->set('centersMember',$centersMember);
		$this->set('networksOwned',$networksOwned);
		return;
	}

	//Friends display/add etc
	function friend_request($sname){
		
		if(!$this->data){ print "MISSING FORM DATA"; exit; }
		//print "FRIEND REQUEST RECEIVED for sport ".$sname." profile:".$this->data['friendRequestID']; 
		//exit;
		$this->loadModel('FriendRequest');
		$this->loadModel('Profile');
		$prof = $this->Profile->find('first',array('conditions'=>array('sport_id'=>$this->sport['Sport']['id'],
		'user_id'=>$this->user['id'])));
		$this->FriendRequest->create();
		$mdat = array('FriendRequest'=>array(
			'profile_id'=>$prof['Profile']['id'],
			'profile2_id'=>$this->data['friendRequestID'],
			'message'=>$this->data['friendRequestMSG'],
			'added'=>'now()',
			'sport_id'=>$this->sport['Sport']['id']));
		if($this->FriendRequest->save($mdat)){
			print "Friend Request sent";
		}
		else { print "Unable to add friend request, please try again"; }


		exit;
	}
	function friend_message($sname){
		
		if(!$this->data){ print "MISSING FORM DATA"; exit; }
		$this->loadModel('Profile');
		$prof = $this->Profile->find('first',array('conditions'=>array('sport_id'=>$this->sport['Sport']['id'],
		'user_id'=>$this->user['id'])));
		$this->loadModel('Message');
		$this->Message->create();
		$mdat = array('Message'=>array(
			'profile_id'=>$prof['Profile']['id'],
			'to_id'=>$this->data['friendID'],
			'message'=>$this->data['friendMSG'],
			'sent'=>DboSource::expression('NOW()'),
			'type'=>'message'));
		if($this->Message->save($mdat)){
			print "Message sent";
		}
		else { print "Unable to send message, please try again"; }
		exit;
	}

	function send_im(){
		//send im to profile_id
		if(!$this->data){ print "ERROR: no data received"; exit; }
		$this->loadModel('Message');
		$mod = array('Message'=>array(
			"message"=>$this->data['IMMSG'],
			"profile_id"=>$this->SProfile['Profile']['id'],
			"to_id"=>$this->data['IMFriendID'],
			"type"=>"im",
			"sent"=>DboSource::expression('NOW()')
			));

		if($this->Message->save($mod)){
			print "Success"; exit;
		}
		print "ERROR Sending IM: ".print_r($mod,TRUE);

		exit;
	}
	function get_im($pID){
		//fetch all recent im's from profile $pID to users current profile
		//pID of 0 means get a list of any new ims
		$ims = array();
		$this->loadModel('Message');
		$options = array(
			'order'=>array('Message.sent'=>'DESC'),
			'conditions'=>array(
				'to_id'=>$this->SProfile['Profile']['id'],
				'type'=>'im',
				'sent >'=>date('Y-m-d', strtotime("-1 day"))
			 )
		);
		if($pID == 0){ //get list of profiles with new messages to this user
			$options['conditions']['read'] = 0;
			unset($options['conditions']['sent']);
			unset($options['order']);
			$options['fields']= array('Message.profile_id','COUNT(Message.id) as Num');
			$options['group'] = 'Message.profile_id';
			$ims = $this->Message->find('all',$options);
			
		}
		else {
			//$options['conditions']['profile_id'] = $pID ;
			unset($options['conditions']['to_id']);
			$options['conditions']['OR'] = array(
				array(
					'AND'=>array(
						'Message.profile_id'=>$pID,
						'Message.to_id'=> $this->SProfile['Profile']['id']
						)),
				array(
					'AND'=>array(
						'Message.profile_id'=>$this->SProfile['Profile']['id'],
						'Message.to_id'=>$pID
						)));
			$ims = $this->Message->find('all',$options);
			$setread = 'UPDATE messages SET messages.read = 1 WHERE messages.profile_id = '.$pID
				.' AND messages.to_id = '.$this->SProfile['Profile']['id'];
			$this->Message->query($setread);
			

		}
		
		

		$imstr = json_encode($ims);
		print $imstr; exit;
	}
	function send_chat(){
		//send chat message to specified center/network chatroom
		if(!$this->request->data){ print "Cannot add message. No data received"; exit; }
		//print_r($this->request->data); exit;
		$newDat = array('Chat'=>array());
		$newDat['Chat']['profile_id'] = $this->SProfile['Profile']['id'];
		$newDat['Chat']['from_name'] = $this->SProfile['Profile']['name'];
		$newDat['Chat']['message'] = $this->request->data['msg'];
		if($this->request->data['type']=='center'){
			$newDat['Chat']['center_id'] = $this->request->data['cid'];
		}
		else { $newDat['Chat']['network_id'] = $this->request->data['cid']; }
		$this->loadModel('Chat');
		if($this->Chat->save($newDat)){
			print "success";
		}
		else { print "Error: could not update chat"; }
		exit;
	}
	function get_chat($chatstr){
		//get recent chat room messages from $type(network,center) with id $cID
		$chatType = substr($chatstr,0,3);
		$chatID = substr($chatstr,3);
		$chats = array($chatType,$chatID);
		$this->loadModel('Chat');
		$opts = array('order'=>array('Chat.date'=>'DESC'),'limit'=>25,'conditions'=>array());
		if($chatType=='cen'){
			$opts['conditions']['Chat.center_id'] = $chatID;
		}
		else if($chatType=='net'){
			$opts['conditions']['Chat.network_id'] = $chatID;
		}
		else {
			$chats = array('Error: invalid chat type'); print $chats; exit;
		}
		$chats = $this->Chat->find('all',$opts);

		$chatstr = json_encode($chats);
		print $chatstr; exit;
	}

	function load_messages(){
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
		$options['conditions'] = array('ProfileT.user_id'=>$this->user['id'],
			'OR' =>array(array('Message.type'=>'message'),array('Message.type'=>'system'))
			);
		$messages = $this->Message->find('all',$options);
		//unset($options['conditions']['Message.type']);
		unset($options['conditions']['OR']);
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
		$this->loadModel('Wave');
		$waves = $this->Wave->find('all',$options);

		//print_r($this->user); print "<br><Br>";
		//print_r($requests);
		//exit;
		$this->set('waves',$waves); $this->set('messages',$messages);
		return;
	}

	function show_friends($sname){
		//print "SHOW FRIENDS"; exit;
		$this->load_messages();
		$this->load_centers();
		$this->loadModel('Friend');
		$options = array(
			'fields'=>array('Friend.*','Profile.*','ProfileT.*'),
			'conditions'=>array(
				'Friend.sport_id'=>$this->sport['Sport']['id'],
				'OR'=>array(
				'Profile.user_id'=>$this->user['id'],
				'ProfileT.user_id'=>$this->user['id']
				)),
			'joins'=>array(
				array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = Friend.profile2_id',
		        )
		    )
				));
		$friends = $this->Friend->find('all',$options);
		$this->set('sport',$this->sport);
		$this->set('friends',$friends);
		$this->layout = 'Sport';
		$this->render("friends");
	}
	//generate list of friends
	function set_friends($sname){
		$this->loadModel('Friend');
		$options = array(
			'fields'=>array('Friend.*','Profile.*','ProfileT.*'),
			'conditions'=>array(
				'Friend.sport_id'=>$this->sport['Sport']['id'],
				'OR'=>array(
				'Profile.user_id'=>$this->user['id'],
				'ProfileT.user_id'=>$this->user['id']
				)),
			'joins'=>array(
				array('table' => 'profiles',
		        'alias' => 'ProfileT',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'ProfileT.id = Friend.profile2_id',
		        )
		    )
				));
		$friends = $this->Friend->find('all',$options);
		return $friends;
		
	}


	//Process/Display Search
	function show_search($sname,$svalue){
		$this->layout = 'Sport';
		$this->set('sname',$sname);
		$this->set('stype',$svalue);
		$this->load_messages();
		$this->load_centers();

		//TODO:: DISTANCE SEARCH VIA ZIP
		if($this->data){
			$this->loadModel('Sport');
			$sp = $this->Sport->find('first',array('conditions'=>array('name'=>$this->data['sport_name'])));
			$this->set('stype',$this->data['searchType']);
			if($this->data['searchType']=='players'){

				$this->loadModel("Profile");
				$sfields = array('Profile.name','Profile.hometown','Profile.level','Profile.zip');
				$sfieldnames = array('Name','Hometown','Level','Zip');
				$this->set('sfields',$sfieldnames);
				if(strlen($this->data['playerName'])<2 ){
					$sresults = $this->Profile->find('all',
						array('conditions'=>array('Profile.sport_id'=>$sp['Sport']['id'])));
					//print "No name<br>"; print_r($sresults); exit;
				}
				else {
					$sresults = $this->Profile->find('all',
						array('conditions'=>array('Profile.sport_id'=>$sp['Sport']['id'],
						'Profile.name LIKE'=>'%'.$this->data['playerName'].'%')));
				}
				$this->set('sresults',$sresults);
			}
			else if($this->data['searchType']=='centers'){
				$this->loadModel("Center");
				$sfields = array("Name","Description","City","State");
				$this->set('sfields',$sfields);
				if(strlen($this->data['centerName'])<2 ){
					$sresults = $this->Center->find('all',
						array('conditions'=>array('Center.sport_id'=>$sp['Sport']['id'])));
					//print "No name<br>"; print_r($sresults); exit;
				}
				else {
					$sresults = $this->Center->find('all',
						array('conditions'=>array('Center.sport_id'=>$sp['Sport']['id'],
						'Center.name LIKE'=>'%'.$this->data['centerName'].'%')));
				}
				$this->set('sresults',$sresults);

				
			}
			else if($this->data['searchType']=='networks'){
				$this->loadModel("Network");
				$this->set('sfields',array("Name","Zip"));
				if(strlen($this->data['networkName'])<2 ){
					$sresults = $this->Network->find('all',
						array('conditions'=>array('Network.sport_id'=>$sp['Sport']['id'],
						'Network.approved'=>1)));
					//print "No name<br>"; print_r($sresults); exit;
				}
				else {
					$sresults = $this->Network->find('all',
						array('conditions'=>array('Network.sport_id'=>$sp['Sport']['id'],
						'Network.approved'=>1,
						'Network.name LIKE'=>'%'.$this->data['networkName'].'%')));
				}
				$this->set('sresults',$sresults);
				
			}
		}

		$this->render('search');
	}


	//PROFILE REGISTRATION
	function register($sname){
		$this->layout = 'Sport';
		
		$this->set('levels',$this->levelsa);
		$this->set('sname',$sname);
		$this->render('register');
		//print "Register for: ".$sname; exit;
	}

	function doregister(){
		$this->loadModel('Sport'); $this->loadModel('Profile');
		$sconds = array('Sport.name'=>$this->data['sport_name']);
		$sport = $this->Sport->find('first',array('conditions'=>$sconds));
		$modded = $this->data;
		//$modded['Sport'] = array('id'=>$sport['Sport']['id']);
		$modded['Profile']['sport_id'] = $sport['Sport']['id'];
		$modded['Profile']['user_id'] = $this->user['id'];
		//$modded['User'] = $this->user;
		//print "CREATING PROFILE<br>\n";
		//print "User: "; print_r($this->user); print "<br><br>";
		//print_r($sport); print "<br><br>";
		//print_r($modded); print "<br><br>";
		$this->Profile->create();

		if($this->Profile->save($modded)){
			//print "PROFILE CREATED";
			$this->redirect('/'.$sport['Sport']['tag'].'/profile');
		}
		else { //print "COULD NOT CREATE PROFILE"; 
			$this->redirect('/'.$sport['Sport']['tag'].'/register');
		}
		//print "<br><br><br>";
		//print_r($this->Profile);

		exit;
	}

	//CENTER FUNCTIONS ----------------------

	function center_create($sname){
		$this->load_messages();
		$this->load_centers();
		$this->layout = 'Sport';
		$this->set('sname',$sname);
		$this->render('center_register');
	}
	function center_docreate($sname){
		if(!$this->data){ $this->redirect('/tennis/centers/create'); return;}
		$this->loadModel('Center');
		$modded = $this->data;
		$modded['Center']['owner'] = $this->user['id'];
		$modded['Center']['sport_id'] = 1;
		$this->Center->create();
		if($this->Center->save($modded)){
			$this->Session->setFlash('Center Created');
			$this->redirect('/tennis/centers/'.$this->Center->id);
		}
		else {
			$this->Session->setFlash('Unable to create center. Please check you have entered valid information and try again');
			$this->redirect('/tennis/centers/create');
		}
	}
	function center_view($sname,$cname){
		if(!$cname){ $this->Session->setFlash('Missing Center ID'); $this->redirect('/tennis'); return; }
		$this->load_messages();
		$this->load_centers();
		$this->loadModel('Center');
		$sconds = array('Center.id'=>$cname);
		$sprof = $this->Center->find('first',array('conditions'=>$sconds));
		if(!$sprof){ $this->Session->setFlash('Invalid Center ID'); $this->redirect('/tennis'); return; }
		
		//load courts
		$this->loadCourts($sprof['Center']['id']);

		$this->layout = 'Sport';
		$this->set('prof',$sprof);
		$this->set('sname',$sname);
		$this->render('center');	
	}

	function center_edit($sname,$cname){
		if(!$cname){ $this->Session->setFlash('Missing Center ID'); $this->redirect('/tennis'); return; }
		$this->load_messages();
		$this->load_centers();
		$this->loadModel('Center');
		$sconds = array('Center.id'=>$cname);
		$sprof = $this->Center->find('first',array('conditions'=>$sconds));
		if(!$sprof){ $this->Session->setFlash('Invalid Center ID'); $this->redirect('/tennis'); return; }
		if($sprof['Center']['owner']!= $this->user['id']){ $this->Session->setFlash('Unable to Edit this Center'); $this->redirect('/tennis'); return; }
		
		if($this->request->data){
			if(isset($this->request->data['Center']['profile_pic'])){
					//just change profile pic
					$fileName = "center_".$this->user['id'].".";
					if(substr($this->request->data['Center']['profile_pic']['name'],-3)=="jpg"){
						$fileName .= "jpg";
					}
					else if(substr($this->request->data['Center']['profile_pic']['name'],-3)=="png"){
						$fileName .= "png";
					}
					else if(substr($this->request->data['Center']['profile_pic']['name'],-3)=="gif"){
						$fileName .= "gif";
					}
					else if(substr($this->request->data['Center']['profile_pic']['name'],-4)=="jpeg"){
						$fileName .= "jpeg";
					}
					else { print "Error: invalid file type for profile pic. Requires jpeg,jpg,png or gif"; exit; }
					$filePath = WWW_ROOT.'img/profiles/'.$fileName;
					move_uploaded_file($this->data['Center']['profile_pic']['tmp_name'], $filePath);
					$newDat = array('Center'=>array());
					$newDat['Center']['profile_pic'] = $fileName;
					$this->Center->id = $cname;
					if($this->Center->save($newDat)){
						$this->set('editmsg','Updated Profile Picture!');
					}
					else { $this->set('editmsg','Error updating profile'); }

					
			} else {
				//update center details
				$this->Center->id = $cname;
				if($this->Center->save($this->request->data)){
					$this->set('editmsg','Center Updated');
					$sprof = $this->Center->findById($cname);
				}
				else { $this->set('editmsg','Error updating center'); }
			}
		}

		//load courts
		$this->loadCourts($sprof['Center']['id']);
		$this->request->data = $sprof;

		$this->layout = 'Sport';
		$this->set('prof',$sprof);
		$this->set('sname',$sname);
		$this->render('center_edit');	
	}
	function loadCourts($cID,$doReturn=false){
		//load court data for all courts at center $cID
		$this->loadModel('Court');
		$courts = $this->Court->find('all',array('conditions'=>array(
			'center_id'=>$cID)));
		if($courts){
			if($doReturn){ return $courts; }
			$this->set('courts',$courts); return;
		}

		return false;
	}

	//parse post data to process various center updates
	function center_update($sname){
		if(!$this->data){ print "Error: no update data received"; exit; }
		if(!isset($this->data['center_id'])){ print "Error: no center specified"; exit;}
		$this->loadModel('Center');

		//check user can update this center
		$sconds = array('Center.id'=>$this->data['center_id'],
			'owner'=>$this->user['id']);
		$sprof = $this->Center->find('first',array('conditions'=>$sconds));
		if(!$sprof){ print "Error: Center not found or unable to be updated"; exit;}
		
		$action = (isset($this->data['action'])) ? $this->data['action']: '';
		switch($action){
			case 'add_court':
				$this->loadModel('Court');
				$mod = array('Court'=>array(
					'center_id'=>$sprof['Center']['id'],
					'name'=>$this->data['court_name'],
					'description'=>$this->data['court_description']
					));
				if($this->Court->save($mod)){ print "Court saved!"; exit; }
				print "Error: unable to save court";
				exit; break;
			case 'get_reservations':
				$this->loadModel('Reservation');
				$opts = array('order'=>array('Reservation.start'=>'DESC'),'conditions'=>array(
					'Reservation.center_id'=>$sprof['Center']['id'],
					'Reservation.approved'=>'0'));
				
				$rs = $this->Reservation->find('all',$opts);
				if($rs){
					print json_encode($rs); exit;
					//generate html lis
					$rshtml = '<ul>';
					foreach($rs as $reserv){
						$rshtml .= "<li>".$reserv['Reservation']['start']." to ".$reserv['Reservation']['stop']." <b>Court ".$reserv['Reservation']['court_id'].
							"</b></li>\n";
					}
					print $rshtml . "</ul>\n"; exit;
				}
				print "Error: unable to find reservations";
				exit; break;

		}


		print "Updating center ".$this->data['center_id']."<br>\n".print_r($this->data,TRUE); exit;
	}

	function center_viewall($sname){
		print "VIEW ALL CENTERS"; exit;
	}


	//PHOTO AND VIDEO ALBUM Functions
	function photos($sname,$svalue,$sID){
		//expect sport name, type(network/center/profile), and id
		$editmsg = null;
		$opts = array('conditions'=>array());
		if($this->request->data){
			$newDat = $this->request->data; $newDat['Photo']['user_id'] = $this->user['id'];
		}
		if($svalue=='profile'){
			$opts['conditions']['Photo.profile_id']=$sID;
			if($sID==$this->SProfile['Profile']['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Photo']['profile_id'] = $sID; }
			$this->set('aname',$this->SProfile['Profile']['name']);
		}
		else if($svalue=='center'){
			$opts['conditions']['Photo.center_id']=$sID;
			$this->loadModel('Center');
			$center = $this->Center->findById($sID);
			if(!$center){ print "Invalid center"; exit; }
			$this->set('center',$center);
			$this->set('aname',$center['Center']['name']);
			if($center['Center']['owner']==$this->user['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Photo']['center_id'] = $sID; }
		}
		else if($svalue=='network'){
			$opts['conditions']['Photo.network_id']=$sID;
			$this->loadModel('Network');
			$network = $this->Network->findById($sID);
			if(!$network){ print "Invalid network"; exit; }
			$this->set('network',$network);
			$this->set('aname',$network['Network']['name']);
			if($network['Network']['user_id']==$this->user['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Photo']['network_id'] = $sID; }
		}
		else if($svalue=='user'){
			//user is uploading a photo not specifically for a profile
			$this->set('aname','you');
			if($sID!=$this->user['id']){ print "Invalid user"; exit; }
			$opts['conditions']['Video.user_id']=$sID;
		}
		else {
			print "Invalid album type"; exit;
		}
		$this->layout = 'Sport';
		$this->loadModel('Photo');
		if($this->request->data){
			//upload new video or edit current one
			//$newDat = $this->request->data; $newDat['Photo']['user_id'] = $this->user['id'];
			if(!$this->request->data['Photo']['file'] || $this->request['Photo']['file']['error']){
				$editmsg = "Error: Unable to upload photo";
			}
			else {
				$fileName = $this->user['id'].'_'.$this->data['Photo']['file']['name'];
				$filePath = WWW_ROOT.'img/user_photos/'.$fileName;
				move_uploaded_file($this->data['Photo']['file']['tmp_name'], $filePath); 
				$newDat['Photo']['file'] = $fileName;
				$newDat['Photo']['caption'] = $this->request->data['Photo']['caption'];
				if($this->Photo->save($newDat)){
					$editmsg = 'PHOTO Saved!';
				}
				else {
					$editmsg = 'ERROR SAVING PHOTO.. '.print_r($this->request->data,1)."<br>WEBROOT:".WWW_ROOT;
				}
		
			}
		}
		$photos = $this->Photo->find('all',$opts);
		$this->set('photos',$photos);
		$this->set('editmsg',$editmsg);
		$this->set('sprof',$this->SProfile);
		$this->render('photo');
	}

	
	function videos($sname,$svalue,$sID){
		//expect sport name, type(network/center/profile), and id
		$editmsg = null;
		$opts = array('conditions'=>array());
		if($this->request->data){
			$newDat = $this->request->data; $newDat['Video']['user_id'] = $this->user['id'];
		}
		if($svalue=='profile'){
			$opts['conditions']['Video.profile_id']=$sID;
			if($sID==$this->SProfile['Profile']['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Video']['profile_id'] = $sID; }
			$this->set('aname',$this->SProfile['Profile']['name']);
		}
		else if($svalue=='center'){
			$opts['conditions']['Video.center_id']=$sID;
			$this->loadModel('Center');
			$center = $this->Center->findById($sID);
			if(!$center){ print "Invalid center"; exit; }
			$this->set('center',$center);
			$this->set('aname',$center['Center']['name']);
			if($center['Center']['owner']==$this->user['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Video']['center_id'] = $sID; }
		}
		else if($svalue=='network'){
			$opts['conditions']['Video.network_id']=$sID;
			$this->loadModel('Network');
			$network = $this->Network->findById($sID);
			if(!$network){ print "Invalid network"; exit; }
			$this->set('network',$network);
			$this->set('aname',$network['Network']['name']);
			if($network['Network']['user_id']==$this->user['id']){ $this->set('isowner',1); }
			if($this->request->data){ $newDat['Video']['network_id'] = $sID; }
		}
		else if($svalue=='user'){
			//user is uploading a photo not specifically for a profile
			$this->set('aname','you');
			if($sID!=$this->user['id']){ print "Invalid user"; exit; }
			$opts['conditions']['Video.user_id']=$sID;
		}
		else {
			print "Invalid album type"; exit;
		}
		$this->layout = 'Sport';
		$this->loadModel('Video');
		if($this->request->data){
			//upload new video or edit current one
			if($this->Video->save($newDat)){
				$editmsg = 'Video Added!';
			}
			else { $editmsg = 'Error: unable to add video'; }

		}

		$videos = $this->Video->find('all',$opts);
		$this->set('videos',$videos);
		$this->set('editmsg',$editmsg);
		$this->set('type',$svalue);
		$this->set('sprof',$this->SProfile);
		$this->render('video');
	}


	//VIEW PROFILE
	function profile($sname,$svalue){
		$this->layout = 'Sport';
		$this->loadModel('Profile');
		$this->load_messages();
		$this->load_centers();
		$sid = 1; //preset tennis a 1 for now
		if(!$svalue || $svalue == 'me' || $svalue == 'edit'){
			$this->set('isself',1); //view is looking at users own profile

			$prof = $this->Profile->find('first',array(
				'conditions'=>array(
					'sport_id'=>$sid,
					'user_id'=>$this->user['id'])));

			if($svalue == 'edit' && $this->request->data){

				if(isset($this->request->data['Profile']['profile_pic'])){
					//just change profile pic
					$fileName = "profile_".$this->user['id'].".";
					if(substr($this->request->data['Profile']['profile_pic']['name'],-3)=="jpg"){
						$fileName .= "jpg";
					}
					else if(substr($this->request->data['Profile']['profile_pic']['name'],-3)=="png"){
						$fileName .= "png";
					}
					else if(substr($this->request->data['Profile']['profile_pic']['name'],-3)=="gif"){
						$fileName .= "gif";
					}
					else if(substr($this->request->data['Profile']['profile_pic']['name'],-4)=="jpeg"){
						$fileName .= "jpeg";
					}
					else { print "Error: invalid file type for profile pic. Requires jpeg,jpg,png or gif"; exit; }
					$filePath = WWW_ROOT.'img/profiles/'.$fileName;
					move_uploaded_file($this->data['Profile']['profile_pic']['tmp_name'], $filePath);
					$newDat = array('Profile'=>array());
					$newDat['Profile']['profile_pic'] = $fileName;
					$this->Profile->id = $prof['Profile']['id'];
					if($this->Profile->save($newDat)){
						$this->set('editmsg','Updated Profile Pic!');
					}
					else { $this->set('editmsg','Error updating profile'); }

					
				}
				else { 

					$this->Profile->id = $prof['Profile']['id'];
					$newdat = $this->request->data;
					$newdat['Profile']['user_id'] = $this->user['id'];
					$newdat['Profile']['sport_id'] = $sid;
					if($this->Profile->save($newdat)){
						$this->set('editmsg','Profile Updated!');
					}
					else { $this->set('editmsg','Error: Unable to update profile'); }
					$prof = $this->Profile->findById($prof['Profile']['id']);
				}
				}
			
			
			if($svalue=='edit'){ 
				$this->set('isedit',1);
				$this->request->data = $this->Profile->findById($prof['Profile']['id']);
			}
		}
		else {
			$this->set('isself',0);
			$prof = $this->Profile->find('first',array(
			'conditions'=>array(
				'Profile.id'=>$svalue)));
		}
		$this->set('prof',$prof);
		$this->set('sname',$sname);
		$this->render('profile');
		//print "Profile for: ".$sname; exit;
	}
	function profileStatus($sname,$svalue){
		if(!$this->data){ print "Missing status data. Status not changed."; exit;}
		//print "Status changing to ".$this->data['newStatus']; exit;
		$this->loadModel('Profile');
		$prof = $this->Profile->find('first',array('conditions'=>array(
			'user_id'=>$this->user['id'],
			'sport_id'=>$this->sport['Sport']['id'])));
		if(!$prof){ print "Unable to find profile data. Cannot update status at this time."; exit; }
		$this->Profile->id = $prof['Profile']['id'];
		$mdat = array('Profile'=>array('status'=>$this->data['newStatus']));
		if($this->Profile->save($mdat)){
			print "Profile status changed to: ".$this->data['newStatus']; exit;
		}
		else { print "Error saving profile data"; exit; }
		//print_r($prof); exit;
	}

	//NETWORK FUNCTIONS
	/*********************************       ***********/

	function net_join(){
		//add user to network
	}

	function net_view($nID){
		$nID = (int)$nID;
		if(!is_int($nID)){ 
			print "INVALID ID ".$nID; exit;
			$this->redirect('/'.$this->sname); }
		$this->loadModel('Network');
		$net = $this->Network->find('first',array('conditions'=>array(
			'sport_id'=>$this->sport['Sport']['id'],
			'id'=>$nID)));
		if(!$net){ print "Invalid network ID"; exit; }
		$this->set('prof',$net);
		$this->layout = 'Sport';
		$isowner = 0;
		if($net['Network']['user_id']==$this->user['id']){
			$isowner = 1;
		}
		$this->set('isowner',$isowner);

		$this->render('network');
	}
	function net_edit($nID){
		$nID = (int)$nID;
		if(!is_int($nID)){ 
			print "INVALID ID ".$nID; exit;
			$this->redirect('/'.$this->sname); }
		$this->loadModel('Network');
		$net = $this->Network->find('first',array('conditions'=>array(
			'sport_id'=>$this->sport['Sport']['id'],
			'id'=>$nID)));
		if(!$net){ print "Invalid network ID"; exit; }

		if($this->request->data){
			if(isset($this->request->data['Network']['profile_pic'])){
					//just change profile pic
					$fileName = "network_".$this->user['id'].".";
					if(substr($this->request->data['Network']['profile_pic']['name'],-3)=="jpg"){
						$fileName .= "jpg";
					}
					else if(substr($this->request->data['Network']['profile_pic']['name'],-3)=="png"){
						$fileName .= "png";
					}
					else if(substr($this->request->data['Network']['profile_pic']['name'],-3)=="gif"){
						$fileName .= "gif";
					}
					else if(substr($this->request->data['Network']['profile_pic']['name'],-4)=="jpeg"){
						$fileName .= "jpeg";
					}
					else { print "Error: invalid file type for profile pic. Requires jpeg,jpg,png or gif"; exit; }
					$filePath = WWW_ROOT.'img/profiles/'.$fileName;
					move_uploaded_file($this->data['Network']['profile_pic']['tmp_name'], $filePath);
					$newDat = array('Network'=>array());
					$newDat['Network']['profile_pic'] = $fileName;
					$this->Network->id = $net['Network']['id'];
					if($this->Network->save($newDat)){
						$this->set('editmsg','Updated Profile Picture!');
					}
					else { $this->set('editmsg','Error updating profile'); }

					
			}
			else {
			//update network

				$this->Network->id = $net['Network']['id'];
				if($this->Network->save($this->request->data)){
					$this->set('editmsg','Network updated!');
				} else { $this->set('editmsg','Error updating network!'); }
				$net = $this->Network->findById($net['Network']['id']);
			}
			

		}
		$this->request->data = $net;

		$this->set('prof',$net);
		$this->layout = 'Sport';
		if($net['Network']['user_id']!=$this->user['id']){
			print "Error: You do not have access to this page"; exit;
		}
		$this->set('isowner',1);
		$this->set('isedit',1);

		$this->render('network');
		
	}

	function comp_search(){
		//return ajax/html list of comp based on post params
		$this->loadModel('League');
		$opts = array('conditions'=>array());
		$opts['order']=array('League.start'=>'DESC');
		if(isset($this->data)){
			if(isset($this->data['center_id'])){
				$opts['conditions']['League.center_id'] = $this->data['center_id'];
			}
			if(isset($this->data['network_id'])){
				$opts['conditions']['League.network_id'] = $this->data['network_id'];
			}
		}
		$comps = $this->League->find('all',$opts);
		if(isset($this->data)&& isset($this->data['json'])){
			$compstr = json_encode($comps);
			print $compstr; exit;
		}
		$chtml = "<ul>\n";
		foreach($comps as $comp){
			$chtml .= "<li><a href='/getxp/".$this->sname."/league/"
				.$comp['League']['id']."'><b>".$comp['League']['name']."</b> starts ".
				$comp['League']['start']."</li>\n";
		}
		$chtml .= "</ul>\n";
		print $chtml; exit;
		//print "Leagues:<br>".print_r($comps,TRUE); exit;
	}

	function comp_create(){
		$this->layout = 'Sport';
		if(!$this->data){ print "Error: missing data to create League"; exit;}
		if(isset($this->data['action'])){
			//no post data - display creation form
			if(isset($this->data['network_id'])){ 
				$this->set('network_id',$this->data['network_id']); }
			if(isset($this->data['center_id'])){
				$this->set('center_id',$this->data['center_id']); 
			}
			$this->render('league_register');
			return;
		}
		//process post data and create new league
		$this->loadModel('League');
		//print 'CREATING LEAGUE!<br>'.print_r($this->data,TRUE)."<br><br>"; 
		$mod = $this->data;
		$mod['League']['profile_id'] = $this->SProfile['Profile']['id'];
		if($this->League->save($mod)){
			$this->redirect('/'.$this->sname.'/league/'.$this->League->id);
		}
		print "ERROR COULD NOT SAVE!";
		exit;
	}
	function comp_join($cID){
		$cID = (int)$cID;
		$this->loadModel('Competitor');

		$mod = array('Competitor'=>array(
			'profile_id'=>$this->SProfile['Profile']['id'],
			'player_name'=>$this->SProfile['Profile']['name'],
			'league_id'=>$cID));
		if($this->Competitor->save($mod)){
			print "Entry request received. Good luck!"; exit;
		}
		print "Error submitting entry request";
		exit;

		//print "Joining League $cID with profile ".$this->SProfile['Profile']['id']; exit;
	}
	function comp_view($cID){
		$cID = (int)$cID;
		if(!is_int($cID) || $cID < 1){ print "Error: Invalid league ID"; exit; }
		$this->loadModel('League');
		$opts = array('conditions'=>array(
			'League.id'=>$cID));
		$league = $this->League->find('first',$opts);
		if(!$league){ print "Error loading League $cID"; exit; }
		//print_r($league); exit;
		$comp = $league;
		if($comp['Center']['id']){$comp['League']['center']=$comp['Center']; }
		if($comp['Network']['id']){$comp['League']['net']=$comp['Network']; }
		if($comp['Profile']['id']){ $comp['League']['host_name'] = $comp['Profile']['name']; }
		$this->set('comp',$comp['League']);
		$this->loadModel("Competitor");
		$opts['conditions'] = array( 
			'profile_id'=>$this->SProfile['Profile']['id'],
			'league_id'=>$cID );

		$entry = $this->Competitor->find('first',$opts);
		if($entry){ $this->set('entry',$entry); }
		$this->layout = 'Sport';
		$this->render('league');
	}

	//handle various ajax calls
	function parse_ajax($svalue){
		if($svalue=='addReservation'){
			//add reservation request for specified court
			if(!$this->data){ print "Error: missing reservation data"; exit; }
			$this->loadModel('Reservation');
			$mod = $this->data;
			if(!isset($mod['Reservation'])){ $mod['Reservation']=array(); }
			$mod['Reservation']['center_id'] = $this->data['center_id'];
			$mod['Reservation']['court_id'] = $this->data['court_id'];
			$mod['Reservation']['profile_id'] = $this->SProfile['Profile']['id'];
			$mod['Reservation']['comment'] = $this->data['reservationComment'];
			$rDate = split('/',$this->data['date']);
			$mDate = $rDate[2].'-'.$rDate[0].'-'.$rDate[1];
			$sTime = ($this->data['startAM']=='am')? $this->data['startHour'] : $this->data['startHour']+12 ;
			$sTime .= ':'.$this->data['startHalf'].':00';
			$eTime = ($this->data['endAM']=='am')? $this->data['endHour'] : $this->data['endHour']+12 ;
			$eTime .= ':'.$this->data['endHalf'].':00';
			$mod['Reservation']['start'] = $mDate.' '.$sTime;
			$mod['Reservation']['stop'] = $mDate.' '.$eTime;
			//print_r($mod); exit;

			//print "Adding reservation for: ".print_r($this->mod,true); exit;
			if($this->Reservation->save($mod)){
				print "Reservation request sent. You will receive mail if it approved"; exit;
			}
			print "Error: Unable to save your reservation";
			exit;
		}
		if($svalue=='approveReservation'){
			if(!$this->request->data){ print "Error: missing request data"; }
			$this->loadModel('Reservation');
			if( !isset($this->data['rID'])  ){
				print "ERROR: invalid reservation."; exit; }
			$reserv = $this->Reservation->findById($this->request->data['rID']);
			if(!$reserv){ print "Invalid reservation ".$this->request->data['rID']; exit;}
			$this->loadModel('Center');
			$center = $this->Center->findById($reserv['Reservation']['center_id']);
			if(!$center){ print "ERROR: Invalid center #".$reserv['Reservation']['center_id']; exit; }
			if($center['Center']['owner']!= $this->user['id']){
				print "Error: Invalid permission to authorize this request"; exit; 
			}
			$this->Reservation->id = $reserv['Reservation']['id'];
			$newDat = array('Reservation'=>array('approved'=>1));
			if($this->Reservation->save($newDat)){
				print "Success! Reservation approved";
				//TO-DO: Add message to reservation sender that it has been approved
				$this->loadModel('Message');
				$newDat = array('Message'=>array(
					'message'=>'Your court reservation for '.$center['Center']['name'].
						'at '.$reserv['Reservation']['start'].' has been approved',
					'profile_id'=>0,
					'to_id'=>$reserv['Reservation']['profile_id'],
					'type'=>'system'
					));
				$this->Message->save($newDat);
				exit;
			}
			print "Error: Could not update reservation status"; exit;
			
		}
		else if($svalue=="fetchReservations"){
			if(!$this->request->data){ print "Error: missing request data"; }
			$rDate = $this->request->data['date'];
			$sDate = $rDate . ' 00:00:01';
			$eDate = $rDate . ' 23:59:59';
			$opts = array('order'=>array('Reservation.start'=>'ASC'),'conditions'=>array());
			$this->loadModel('Reservation');
			$opts['conditions']['Reservation.start >'] = $sDate;
			$opts['conditions']['Reservation.stop <'] = $eDate;
			$opts['conditions']['Reservation.center_id'] = $this->request->data['center_id'];
			$opts['conditions']['Reservation.court_id'] = $this->request->data['court_id'];
			//print_r($opts); exit;
			//$reservs = $this->Reservation->find('all',$opts);
			/*$reservs = $this->Reservation->find('all',array('conditions'=>array(
				'Reservation.court_id'=> $this->request->data['court_id'],
				'Reservation.center_id'=>$this->request->data['center_id'],
				'Reservation.start >=' => $sDate
				))); */
			$rQuery = 'SELECT * FROM reservations WHERE reservations.court_id = '.$this->request->data['court_id'].
				' AND reservations.center_id = '.$this->request->data['center_id'].
				' AND reservations.start > "'.$sDate.'"'.
				' AND reservations.stop < "'.$eDate.'"';
			if(isset($this->request->date['approved'])){
				$rQuery .= ' AND reservations.approved = '.$this->request->data['approved'];
			}
			$reservs = $this->Reservation->query($rQuery);
			//if(!$reservs){ print "ERROR: unable to query<br>".print_r($reservs,true)."<br>"
			//	.print_r($rQuery,true); exit; }
			print json_encode($reservs); 
			exit;
		}
		else if(substr($svalue,0,3)=='com'){
			//fetch comments for specific profile/center/network/tourn
			$comwhat = substr($svalue,3);
			if(strlen($comwhat)<4){ print "Unable to fetch comments: Invalid type or id"; exit; }
			$comtype = substr($comwhat,0,3); $comid = substr($comwhat,3);
			$this->loadModel('Comment');

			//check if submitting comment
			if($this->request->data){
				$newDat = $this->request->data;
			}

			$opts['conditions'] = array();
			if($comtype=='pro'){ $opts['conditions']['profile_id'] = $comid;
				if($this->request->data){ $newDat['Comment']['profile_id'] = $comid; } }
			else if($comtype=='cen'){ $opts['conditions']['center_id'] = $comid; 
				if($this->request->data){ $newDat['Comment']['center_id'] = $comid; } }
			else if($comtype=='net'){ $opts['conditions']['network_id'] = $comid; 
				if($this->request->data){ $newDat['Comment']['network_id'] = $comid; } }
			else { print "Invalid comment type"; exit; }

			if($this->request->data){
				$newDat['Comment']['from_id'] = $this->SProfile['Profile']['id'];
				$newDat['Comment']['poster_name'] = $this->SProfile['Profile']['name'];
				if($this->Comment->save($newDat)){
					print "Comment saved!";
				}
				else { print "Error: Unable to save comment at this time"; }
				exit;
			}
			
			$comments = $this->Comment->find('all',$opts);
			print json_encode($comments); exit;
			
		}
		else if(substr($svalue,0,3)=='bul'){
			//fetch bulletins for profile/center/network
			$comtype = substr($svalue,3,3); $comid = substr($svalue,6);
			$this->loadModel('Bulletin');
			if($this->request->data){
				if($this->Bulletin->save($this->request->data)){
					print "Success";
				}
				else { print "Error: could not save bulletin"; }
				exit;
			}

			if(!$comid){ print "Error: no id given"; exit; }
			
			$opts = array('order'=>array('Bulletin.posted'=>'DESC'),'conditions'=>array());
			if($comtype=='cen'){
				$opts['conditions']['Bulletin.center_id']=$comid;
			}
			else if($comtype=='net'){
				$opts['conditions']['Bulletin.network_id']=$comid;
			}
			else if($comtype=='pro'){
				$opts['conditions']['Bulletin.profile_id']=$comid;
			}
			else { print "Invalid bulletin type"; exit; }
			$bulls = $this->Bulletin->find('all',$opts);

			print json_encode($bulls); exit;

		}



		print "Invalid request";
		exit;
	}

	//load profile based on sport and user id
	function get_profile($sname,$uid){
		if(!$uid || $uid == 0){ $uid = 1; }
	}


	//TOURNAMENT FUNCTIONS
	function tourn_view($tID){
		$tID = (int)$tID;
		if(!is_int($tID)){ print "Invalid tournament $tID"; exit; }

		$this->loadModel('Tournament');
		$opts = array('conditions'=>array(
			'Tournament.id'=>$tID));
		$opts['joins'] = array(
				array('table' => 'centers',
		        'alias' => 'Center',
		        'type' => 'LEFT',
		        'conditions' => array(
		            'Center.id = Tournament.center_id',
		        )
		    ));
		 $tourns = $this->Tournament->find('first',$opts);
		 if(!$tourns){ print "Unable to find tournament $tID"; exit; }
		// print_r($tourns); exit;
		

		 $this->set('tourn',$tourns);
		 if(isset($tourns['Center'])){ $this->set('location',$tourns['Center']); }
		 if(isset($tourns['Network'])){ $this->set('location',$tourns['Network']); }

		 //find competitors and matches
		 $this->loadModel('Competitor');
		 $competitors_approved = $this->Competitor->find('all',array('conditions'=>array(
			 'tournament_id'=>$tID,
			 'approved'=>1)));
		$competitors_pending = $this->Competitor->find('all',array('conditions'=>array(
			 'tournament_id'=>$tID,
			 'approved'=>0)));
		$this->loadModel('Match');
		$matches = $this->Match->find('all',array('conditions'=>array(
			'tournament_id'=>$tID)));
		$this->set('matches',$matches);
		$this->set('competitors_approved',$competitors_approved);
		$this->set('competitors_pending',$competitors_pending);

		if($this->data && isset($this->data['action']) ){
			if($this->data['action']=='startTournament'){
				$this->tourn_start($tourns,$competitors_approved); //start tournament
			}
		}
		if($tourns['Tournament']['registration']=='closed'){
			//fetch list of matches
			$this->loadModel('Match');
			$matches = $this->Match->find('all',array('conditions'=>array(
				'tournament_id'=>$tID),
				'order'=>array(
					'round'=>'ASC','time'=>'ASC')));
			if($matches){ $this->set('matches',$matches); }
		}

		$this->layout = 'Sport';
		$this->render('tournament');
		return;
	}
	function tourn_start($tourn,$teams){
		//start tournament-- close registration, generate brackets and pools and add matches 
		//to db
		$this->set('tourn_message','Starting Tournament..'.$tourn['Tournament']['name']);

		$msg = 'Starting Tournament..'.$tourn['Tournament']['name']."<br>";
		switch($tourn['Tournament']['format']){
			case "single elim":
				$msg .= "Single Elimination with ".$tourn['Tournament']['max_teams']." teams<br>";
				$mCount = 1; 
				$mRount = 1;
				$this->loadModel('Match');
				$matches = array();
				while($mCount <= $tourn['Tournament']['max_teams']){
					$msg .= "Match ".(($mCount+1)/2)." : ($mCount) ".
					$teams[$mCount-1]['Competitor']['player_name']." vs ";
					$mod = array('Match'=>array(
						'tournament_id'=>$tourn['Tournament']['id'],
						'round'=>1,
						'p1_name'=>$teams[$mCount-1]['Competitor']['player_name']
						));
					if($teams[$mCount-1]['Competitor']['profile_id']){ 
						$mod['Match']['p1_id']= $teams[$mCount-1]['Competitor']['profile_id']; }
					
					if($mCount <  $tourn['Tournament']['max_teams']){
						$mod['Match']['p2_name']=$teams[$mCount]['Competitor']['player_name'];
						if($teams[$mCount]['Competitor']['profile_id']){ 
						$mod['Match']['p2_id']= $teams[$mCount]['Competitor']['profile_id']; }
						$msg .= "(".($mCount+1).") ".
						$teams[$mCount]['Competitor']['player_name']." .. ";
					} else { $msg .= " BYE .. "; $mod['Match']['p2_name']='BYE';}
					$mCount +=2;
					array_push($matches,$mod);
					//$msg .= '<blockquote>'.print_r($mod,TRUE)."</blockquote><br>\n";
					$this->Match->create($mod);
					if($this->Match->save()){
						$msg .= "SAVED AS #".$this->Match->id." !<br>\n";
					} else { $msg .= "Error: unable to save match<br>\n"; }
				}
				$this->Tournament->id = $tourn['Tournament']['id'];
				$this->Tournament->set('registration','closed');
				$this->Tournament->save();
				$tourn['Tournament']['registration']= 'closed';
				$this->set('tourn',$tourn);
				$this->set('matches',$matches);


				break;
			case "double elim":
				//starts same as single elim
				$msg .= "Double Elimination with ".$tourn['Tournament']['max_teams']." teams<br>";
				$mCount = 1; 
				$mRount = 1;
				$this->loadModel('Match');
				$matches = array();
				while($mCount <= $tourn['Tournament']['max_teams']){
					$msg .= "Match ".(($mCount+1)/2)." : ($mCount) ".
					$teams[$mCount-1]['Competitor']['player_name']." vs ";
					$mod = array('Match'=>array(
						'tournament_id'=>$tourn['Tournament']['id'],
						'round'=>1,
						'p1_name'=>$teams[$mCount-1]['Competitor']['player_name']
						));
					if($teams[$mCount-1]['Competitor']['profile_id']){ 
						$mod['Match']['p1_id']= $teams[$mCount-1]['Competitor']['profile_id']; }
					
					if($mCount <  $tourn['Tournament']['max_teams']){
						$mod['Match']['p2_name']=$teams[$mCount]['Competitor']['player_name'];
						if($teams[$mCount]['Competitor']['profile_id']){ 
						$mod['Match']['p2_id']= $teams[$mCount]['Competitor']['profile_id']; }
						$msg .= "(".($mCount+1).") ".
						$teams[$mCount]['Competitor']['player_name']." .. ";
					} else { $msg .= " BYE .. "; $mod['Match']['p2_name']='BYE';}
					$mCount +=2;
					array_push($matches,$mod);
					//$msg .= '<blockquote>'.print_r($mod,TRUE)."</blockquote><br>\n";
					$this->Match->create($mod);
					if($this->Match->save()){
						$msg .= "SAVED AS #".$this->Match->id." !<br>\n";
					} else { $msg .= "Error: unable to save match<br>\n"; }
				}
				$this->Tournament->id = $tourn['Tournament']['id'];
				$this->Tournament->set('registration','closed');
				$this->Tournament->save();
				$tourn['Tournament']['registration']= 'closed';
				$this->set('tourn',$tourn);
				$this->set('matches',$matches);

				break;
			case "round robin":
				//generate matches for round robin tournament
				$msg .= "Round Robin Tournament with ".$tourn['Tournament']['max_teams']." teams in ".$tourn['Tournament']['pools']." pools<br>";
				$mCount = 1; 
				$mRount = 1;
				$this->loadModel('Match');
				$matches = array();
				$mPerP = intval($tourn['Tournament']['max_teams'] / $tourn['Tournament']['pools'] );
				$modP1 = $tourn['Tournament']['max_teams'] % $tourn['Tournament']['pools'] ;
				$msg .= "Divided into pools of $mPerP with $modP1 extra in pool 1<br>";
				$mCount = 0;
				$mPoolC = 1;
				//$msg  .= "teams: ".print_r($teams,TRUE)."<br>";
				while($mPoolC <= $tourn['Tournament']['pools'] ){
					$mTotal = $mPerP;
					if($mPoolC ==1){ $mTotal += $modP1; }
					$msg .= "Generating matches for $mTotal competitors in pool $mPoolC..<br>";
					$mCount = 0;
					while($mCount < $mTotal){
						$mod = array('Match'=>array(
						'tournament_id'=>$tourn['Tournament']['id'],
						'round'=>1,
						'pool'=>$mPoolC,
						'p1_name'=>$teams[$mCount]['Competitor']['player_name']
						));
						if(isset($teams[$mCount]['Competitor']['player_id'])){ $mod['Match']['p1_id'] = $teams[$mCount]['Competitor']['profile_id']; }
						if($mCount >= $mTotal -1){
							//bye!
							$mod['Match']['p2_name'] = 'BYE';
						}
						else {
							$mod['Match']['p2_name'] = $teams[$mCount+1]['Competitor']['player_name'] ;
							if(isset($teams[$mCount+1]['Competitor']['player_id'])){ 
								$mod['Match']['p2_id'] = $teams[$mCount+1]['Competitor']['profile_id']; }
						}
						$msg .= "Saving match: ".$mod['Match']['p1_name']." vs ".$mod['Match']['p2_name'].".. ";
						//if($this->Match->save()){
						if(1){
						$msg .= "SAVED AS #".$this->Match->id." !<br>\n";
						$msg .= print_r($mod,TRUE)."<br><hr>";
						}
					}
				}
				/*
				$this->Tournament->id = $tourn['Tournament']['id'];
				$this->Tournament->set('registration','closed');
				$this->Tournament->save();
				$tourn['Tournament']['registration']= 'closed'; */
				$this->set('tourn',$tourn);
				$this->set('matches',$matches);
				break;
		}

		$this->set('tourn_message',$msg);
	}

	function tourn_create(){
		$this->layout = 'Sport';
		if(!isset($this->data['center_id'])){ print "Missing center ID"; exit; }
		$this->set('center_id',$this->data['center_id']);
		if(!isset($this->data['Tournament'])){
			//display tournament creation form
			$this->render('tournament_register');
			return;
		}
		//parse form data
		$mod = $this->data ;
		if(isset($this->data['center_id'])){
			$mod['Tournament']['center_id'] = $this->data['center_id'];
		}
		if(isset($this->data['network_id'])){
			$mod['Tournament']['network_id'] = $this->data['network_id'];
		}
		$mod['Tournament']['host_id'] = $this->user['id'];
		$this->loadModel('Tournament');
		if($this->Tournament->save($mod)){
			$tID = $this->Tournament->id ;
			print "Tournament saved as #$tID";

			exit;
		}


		print "ERROR: UNABLE TO SAVE TOURNAMENT<br>\n";
		print "Parsing form data..<br>".print_r($this->data,TRUE); exit;
		return;
	}

	function tourn_ajax(){
		//parse ajax queries for tournaments
		if(!$this->data){ print "Error: no data received"; exit; }
		if(!isset($this->data['action'])){ print "Error: no action received"; exit; }
		switch($this->data['action']){
			case "list":
				$opts = array('conditions'=>array());
				$opts['order']= array('starts'=>'DESC');
				$this->loadModel('Tournament');
				if(isset($this->data['center_id'])){ $opts['conditions']['center_id'] = $this->data['center_id']; }
				if(isset($this->data['network_id'])){ $opts['conditions']['network_id'] = $this->data['network_id']; }
				if(isset($this->data['host_id'])){ $opts['conditions']['host_id'] = $this->data['host_id']; }

				$tourns = $this->Tournament->find('all',$opts);
				if(!isset($this->data['return_type']) || $this->data['return_type']=='json'){
					print json_encode($tourns); exit; }
				$thtml = "<ul>\n";
				foreach($tourns as $tourn){
					$thtml .= "<li><b><a href='/getxp/".$this->sname."/tournament/".
						$tourn['Tournament']['id']."'>".$tourn["Tournament"]["name"]."</a></b> (".
						$tourn['Tournament']['registration'].") ".$tourn['Tournament']['format'].
						" - starts on ".$tourn["Tournament"]['starts']."</li>\n";
				}
				$thtml .= "</ul>\n"; print $thtml;
				exit; break;
			case "approveCompetitor":
				if(!isset($this->data['competitor_id'])){ print "Error: missing competitor ID"; exit;}
				//print "Approving competitor ".$this->data['competitor_id'];
				$this->loadModel('Competitor');
				$this->Competitor->id = $this->data['competitor_id'];
				$this->Competitor->set('approved',1);
				if($this->Competitor->save()){ print "Competitor Approved"; }
				else { print "Error: unable to approve competitor wit that id"; }
				exit; break;
			case "denyCompetitor":
				if(!isset($this->data['competitor_id'])){ print "Error: missing competitor ID"; exit;}
				print "Competitor ".$this->data['competitor_id']." denieed";
				exit; break;
			case "addCompetitor": //add manual competitor - not a profile
				
				$this->loadModel('Competitor');
				$mod = array('Competitor'=>array(
					'tournament_id'=>$this->data['tournament_id'],
					'player_name'=>$this->data['addCompetitorName'],
					'approved'=>1
					));
				//print_r($mod); exit;
				if($this->Competitor->save($mod)){
					print "Succesfully added new competitor: ".$this->data['addCompetitorName'];
				}
				else {
					print "ERROR: unable to add competitor:<br>".print_r($this->data,TRUE);
				}
				exit; break;
			case "setMatchScore":
				if(!$this->data){ print "Cannot set score - no data received"; exit; }
				if(!$this->data['winner'] ){ print "Cannot set score - must specify winner"; exit;}
				//print "Setting match score - <br>".print_r($this->data,TRUE);
				$this->loadModel('Match');
				$this->Match->id = $this->data['match_id'];
				//print "Setting score for match ".$this->data['match_id']."<br>\n";
				$this->Match->set(array(
					'winner'=>$this->data['winner'],
					'p1_score'=>$this->data['score1'],
					'p2_score'=>$this->data['score2']
					)) ;
				if($this->Match->save()){
					print "Score updated";
				}
				else { print "Unable to update match score"; }
				exit; break;
			case "newRound":
				//generate new round of matches
				if(!$this->data){ print "No data received"; exit; }
				if(!isset($this->data['round_id'])){ print "No Round specified"; exit; }
				if(!isset($this->data['tourn_id'])){ print "No Tournament specified"; exit; }

				$this->loadModel('Tournament');
				$tourn = $this->Tournament->find('first',array('conditions'=>array(
					'id'=>$this->data['tourn_id'] )));
				if(!$tourn){
					print "Error: unable to find tournament with id ".$this->data['tourn_id']; exit;
				}
				$isDouble = ($tourn['Tournament']['format'] == 'double elim') ? true : false;

				//print "Generating new round..<br>".print_r($this->data,TRUE); exit;
				$this->loadModel('Match');
				$matches = $this->Match->find('all',array(
					'conditions'=>array(
						'tournament_id'=>$this->data['tourn_id'],
						'round'=>($this->data['round_id']-1)),
					'order'=>array('pool'=>'ASC','id'=>'ASC')
					));
				if(!$matches){ print "Unable to find previous round match data"; exit; }
				$prevMatches = count($matches);
				
				$mCount = 0;
				$curPool = $matches[0]['Match']['pool'];
				$isFirstRnd = false;
				if($isDouble && $this->data['round_id']==2){
					//first round ina  double elim - build losers list for 2nd pool
					$isFirstRnd = true;
					$losers = array();
				 }
				if($isDouble && $prevMatches == 2){
					//2 matches left on a double means winners bracket champ fights losers bracket champ for last match
					print "Setting up final match..<br>";
					$mod = array('Match'=>array(
						'tournament_id'=>$this->data['tourn_id'],
						'round'=>$this->data['round_id']));
					if($matches[0]['Match']['winner']==1){
						$mod['Match']['p1_name'] = $matches[0]['Match']['p1_name'];
						if($matches[0]['Match']['p1_id']){ $mod['Match']['p1_id'] = $matches[0]['Match']['p1_id']; }
					}
					else { 
						$mod['Match']['p1_name'] = $matches[0]['Match']['p2_name'];
						if($matches[0]['Match']['p2_id']){ $mod['Match']['p1_id'] = $matches[0]['Match']['p2_id']; }
					}
					if($matches[1]['Match']['winner']==1){
						$mod['Match']['p2_name'] = $matches[1]['Match']['p1_name'];
						if($matches[1]['Match']['p1_id']){ $mod['Match']['p2_id'] = $matches[1]['Match']['p1_id']; }
					}
					else { 
						$mod['Match']['p2_name'] = $matches[1]['Match']['p2_name'];
						if($matches[1]['Match']['p2_id']){ $mod['Match']['p2_id'] = $matches[1]['Match']['p2_id']; }
					}
					print $mod['Match']['p1_name']." vs ".$mod['Match']['p2_name'].".. ";
					$this->Match->create();
					if( $this->Match->save($mod)){
						print "Saved!<br>\n";
					}
					else { print "Error! Unable to save match"; exit; }
					exit;

				}

				while($mCount < $prevMatches){
					$mod = array('Match'=>array(
						'tournament_id'=>$this->data['tourn_id'],
						'round'=>$this->data['round_id']));
					if(isset($matches[$mCount]['Match']['pool']) && $matches[$mCount]['Match']['pool'] != $curPool){
						$curPool = $matches[$mCount+1]['Match']['pool'];
						print "Matching pool $curPool..<br>";
					}
					if($curPool){ $mod['Match']['pool'] = $curPool; }
					$w1 = $matches[$mCount]['Match']['winner'];
					$w1_name = null; $w1_id = null;
					$l1_name = null; $l1_id = null;
					if($w1==1){
						$w1_name = $matches[$mCount]['Match']['p1_name'];
						$mod['Match']['p1_name'] = $w1_name;
						if($matches[$mCount]['Match']['p1_id']){
							$w1_id = $matches[$mCount]['Match']['p1_id'];
							$mod['Match']['p1_id'] = $w1_id;
							if($isDouble && isset($matches[$mCount]['Match']['pool']) ){ 
								$mod['Match']['pool'] == $matches[$mCount]['Match']['pool'];}
						}
						if($isDouble && $isFirstRnd){
							$lost = array('name'=>$matches[$mCount]['Match']['p2_name']);
							if($matches[$mCount]['Match']['p2_id']){ $lost['id'] = $matches[$mCount]['Match']['p2_id']; }
							array_push($losers, $lost );
						}
					}
					else if($w1==2){
						$w1_name = $matches[$mCount]['Match']['p2_name'];
						$mod['Match']['p1_name'] = $w1_name;
						if($matches[$mCount]['Match']['p2_id']){
							$w1_id = $matches[$mCount]['Match']['p2_id'];
							$mod['Match']['p1_id'] = $w1_id;
						}
						if($isDouble && $isFirstRnd){
							$lost = array('name'=>$matches[$mCount]['Match']['p1_name']);
							if($matches[$mCount]['Match']['p2_id']){ $lost['id'] = $matches[$mCount]['Match']['p1_id']; }
							array_push($losers, $lost );
						}
					}
					if($mCount >= $prevMatches-1){ //uneven number, this match gets a bye
						$mod['Match']['p2_name'] = 'BYE';
					}
					else if(isset($matches[$mCount+1]['Match']['pool']) && $matches[$mCount+1]['Match']['pool'] != $curPool) {
						//next pool, set current match to bye and set mcount -1 so it only iterates once
						$mod['Match']['p2_name'] = 'BYE';
						$curPool = $matches[$mCount+1]['Match']['pool'];

						$mCount -=1 ;
						print "Pool changed - setting to bye ".$curPool."  mCount:$mCount<br>";
					}
					else {
						
						$w2 = $matches[$mCount+1]['Match']['winner'];
						$w2_name = null; $w2_id = null;
						if($w2==1){
							$w2_name = $matches[$mCount+1]['Match']['p1_name'];
							$mod['Match']['p2_name'] = $w2_name;
							if($matches[$mCount+1]['Match']['p1_id']){
								$w2_id = $matches[$mCount+1]['Match']['p1_id'];
								$mod['Match']['p2_id'] = $w2_id;
							}
							if($isDouble && $isFirstRnd){
								$lost = array('name'=>$matches[$mCount+1]['Match']['p2_name']);
								if($matches[$mCount+1]['Match']['p2_id']){ $lost['id'] = $matches[$mCount+1]['Match']['p2_id']; }
								array_push($losers, $lost );
							}
						}
						else if($w2==2){
							$w2_name = $matches[$mCount+1]['Match']['p2_name'];
							$mod['Match']['p2_name'] = $w2_name;
							if($matches[$mCount+1]['Match']['p2_id']){
								$w2_id = $matches[$mCount+1]['Match']['p2_id'];
								$mod['Match']['p2_id'] = $w2_id;
							}
							if($isDouble && $isFirstRnd){
								$lost = array('name'=>$matches[$mCount+1]['Match']['p1_name']);
								if($matches[$mCount+1]['Match']['p2_id']){ $lost['id'] = $matches[$mCount+1]['Match']['p1_id']; }
								array_push($losers, $lost );
							}
						}
					}
					$mCount +=2 ;
					print "Saving match.. ".$mod['Match']['p1_name']." vs ".$mod['Match']['p2_name']." .. ";
					//print "<br>Losers pool: <br>".print_r($losers,TRUE);
					//debug - exit after first match making
					//exit;

					$this->Match->create();
					//if( $this->Match->save($mod)){
					if(1){
						print "Saved!<br>\n";
					}
					else { print "Error! Unable to save match"; exit; }
					//print "Match generated..<br>".print_r($mod,TRUE)."<br>\n";
				}

				if($isDouble && $isFirstRnd){
					print "<b>Generating Pool 2</b>.. <br>".print_r($losers,TRUE)."<br>";

					$lCount = 0;
					while($lCount < count($losers) ) {
						$this->Match->create();
						$lmod = array('Match'=>array(
						'tournament_id'=>$this->data['tourn_id'],
						'pool'=>2,
						'round'=>$this->data['round_id']));
						$lmod['Match']['p1_name']= $losers[$lCount]['name'];
						if(isset($losers[$lCount]['id'])){ $lmod['Match']['p1_id'] = $losers[$lCount]['id']; }
						if($lCount < count($losers)-1){
							$lmod['Match']['p2_name']= $losers[$lCount+1]['name'];
							if(isset($losers[$lCount+1]['id'])){ $lmod['Match']['p2_id'] = $losers[$lCount+1]['id']; }
						}
						else {
							$lmod['Match']['p2_name']= 'BYE';
						}
						print "Saving Pool2 Match: ".$lmod['Match']['p1_name']. " vs ".$lmod['Match']['p2_name']." .. ";
						if( $this->Match->save($lmod)){
							print "Saved!<br>\n";
						}
						else { print "Error! Unable to save match"; exit; }

							$lCount +=2; 
						}
				}

				exit; break;
			case "joinTourn":
				//add users profile to tournament roster
				if(!$this->data ||!isset($this->data['tourn_id'])){ print "Cannot join - did not receive data"; exit; }
				$mod = array('Competitor'=>array(
					'tournament_id'=>$this->data['tourn_id'],
					'profile_id'=>$this->SProfile['Profile']['id'],
					'player_name'=>$this->SProfile['Profile']['name'],
					'approved'=>0));
				//print "Adding competitor to list..<br>".print_r($mod,TRUE);
				$this->loadModel('Competitor');
				if($this->Competitor->save($mod)){
					print "Request added!"; exit;
				}
				print "Error: unable to add request";

				exit; break;

		}
		exit;
	}


	//same thing as home
	function display(){
		
		if($this->user){ $this->redirect('/home'); }
		//if($this->Session){ print_r($this->Session); exit; }
		$this->layout = 'Basic';
		$this->render('home');

	}
	//front login/register page
	function front(){
		
	}

}
