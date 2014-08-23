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
		$linkininfeeds	=	Array();
		foreach($feeds['values'] as $key=>$val){
			$linkininfeeds['lk_id'] = $val['updateContent']['person']['currentShare']['id'];
			$linkininfeeds['text'] = $val['updateContent']['person']['currentShare']['comment'];
			$linkininfeeds['title'] = $val['updateContent']['person']['currentShare']['content']['title'];
			$linkininfeeds['social_id'] = $val['updateContent']['person']['currentShare']['author']['id'];
			$linkininfeeds['social_type'] = 4;
			$linkininfeeds['image']		=	"";
			if(isset($val['updateContent']['person']['currentShare']['content']['submittedUrl']))
			$linkininfeeds['link'] = $val['updateContent']['person']['currentShare']['content']['submittedUrl'];

			if(isset($val['updateContent']['person']['currentShare']['content']['submittedImageUrl'])){
				$extensions = array(
				    1 => ".gif",
				    2 => ".jpg",
				    3 => ".png",
				);
			  	$randomname						=	substr(md5(rand()), 0, 11);
			  	try{
				  	$imagetype  					= 	exif_imagetype($val['updateContent']['person']['currentShare']['content']['submittedImageUrl']);
				  	$filename 						=	$randomname.$extensions[$imagetype];
				  	copy($val['updateContent']['person']['currentShare']['content']['submittedImageUrl'],public_path()."/upload/".$filename);
				  	$linkininfeeds['image']			=	$filename;
				}catch(Exception $e){
					
				}  	
			}	


			 $rules = [
			 'text' => 'required',
			 'lk_id' => 'required|unique:posts',
			 'social_id'=>'required',
			];
			$validator = Validator::make($linkininfeeds,$rules);
			if (!$validator->fails())
			{
				DB::table('posts')->insert(
					     $linkininfeeds
					);
			}
		}
	}
	public function linkedinpost($id)
	{
		$linkind = array('api_key' => '75d1x7seww8259','api_secret' =>'trjzhhAKKucC5oN0','callback_url'=>'http://linden.com/lk/access');
		$this->linkedin = new LinkedIn\LinkedIn($linkind);
		$post 		= 	Post::find($id);
		$user  		= 	User::find(5);
		$tokens		=	$user->access_token;
		$this->linkedin->setAccessToken($tokens);
		$this->linkedin->post('/people/~/shares',array('comment'=>'testing','visibility'=>array('code'=>'anyone'),'content'=>array('title'=>$post->title,'description'=>$post->text,'submitted-url'=>$post->link,'submitted_image_url'=>$post->image)));
		$feeds 	=	$this->linkedin->get('/people/~/network/updates');
		$post->lk_id = $feeds['values'][0]['updateContent']['person']['currentShare']['id'];
		$post->save();
	}

}