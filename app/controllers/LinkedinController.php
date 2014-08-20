<?php

class LinkedinController extends BaseController {
	
	protected $linkedin ;

	public function getlkLoginLink()
	{
		$linkind = array('api_key' => '75d1x7seww8259','api_secret' =>'trjzhhAKKucC5oN0','callback_url'=>'http://linden.com/lk/access');
		$linked = new LinkedIn\LinkedIn($linkind);
		 $url = $linked->getLoginUrl(
								  array(
								    $linked::SCOPE_BASIC_PROFILE, 
								    $linked::SCOPE_EMAIL_ADDRESS, 
								    $linked::SCOPE_NETWORK,
								    $linked::SCOPE_READ_WRTIE_UPDATES
								  )
								);
		  return View::make('login.linkedin',compact('url'));
	}
	public function getLkAccessToken(){
		$code 	=	Input::get('code');
		//$post = Post::find($id);
		$linkind = array('api_key' => '75d1x7seww8259','api_secret' =>'trjzhhAKKucC5oN0','callback_url'=>'http://linden.com/lk/access');
		$this->linkedin = new LinkedIn\LinkedIn($linkind);
		$access = $this->linkedin->getAccessToken($code);
		$this->linkedin->setAccessToken($access);
		$data = $this->linkedin->get('/people/~:(first-name,id,email-address)')	;	

		  $rules = [
			 'name' => 'required',
			 'social_id' => 'required|unique:users',
			 'access_token'=>'required',
			 'email'=>'required|unique:users',
			];

			$insertarray = array(
				'name'=>$data['firstName'],
				'social_id'=>$data['id'],
				'login_type'=>4,
				'email'=>$data['emailAddress'],
				'access_token'=>$access,
				);

			$validator = Validator::make($insertarray,$rules);
			if ($validator->fails())
			{
				if($validator->messages()->get('social_id')){
					$userdetails = User::where('social_id', '=', $data['id'])->first();
					if(Auth::loginUsingId($userdetails->id)){
						return Redirect::to('post');
					}
				}
				else if($validator->messages()->get('email')){
					$userdetails = User::where('email', '=', $data['emailAddress'])->first();
						return Redirect::to('error')->withErrors($validator);
					}
				else{
					return Redirect::to('error')->withErrors($validator);
					}
			}else{
				$user = User::create($insertarray);
				if(Auth::loginUsingId($user->id)){
						return Redirect::to('/post');
				}
			}
	}
	public function readpost(){
		$linkind = array('api_key' => '75d1x7seww8259','api_secret' =>'trjzhhAKKucC5oN0','callback_url'=>'http://linden.com/lk/access');
		$this->linkedin = new LinkedIn\LinkedIn($linkind);
		$user  = User::find(5);
		$tokens=	$user->access_token;
		$this->linkedin->setAccessToken($tokens);
		$feeds 	=	$this->linkedin->get('/people/~/network/updates');	
		print_r($feeds);
	}
	public function linkedinpost($id)
	{
		$post = Post::find($id);
		$linkind = array('api_key' => '75d1x7seww8259','api_secret' =>'trjzhhAKKucC5oN0','callback_url'=>'http://linden.com/lk/access');
		$this->linkedin = new LinkedIn\LinkedIn($linkind);
		$lkaccess = "AQUZU1F4YPAbjBnGppQ0EuRpr_qZ1guCHt3c3WVMAjKzY3Z_QsFUD4dZmAsIO6GeeNiczf3hpO9lXOfLIpAaIr9MW9gm8HzWgjy-nDjTPn53l24HmZ52Eog_GHu7I5qsOrMGaFjCY1o1L29NBg_xSL6tY8AFPQZS2THZo6hEVGNjA3b73QQ";
		$this->linkedin->setAccessToken($lkaccess);
		$this->linkedin->post('/people/~/shares',array('comment'=>'testing','visibility'=>array('code'=>'anyone'),'content'=>array('title'=>$post->title,'description'=>$post->text,'submitted-url'=>'http://linden.com/post/share/1','submitted_image_url'=>'http://kinlane-productions.s3.amazonaws.com/api-evangelist-site/building-blocks/bw-linkedin.png')));
			
	}

}