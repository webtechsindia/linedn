<?php

class FacebookController extends BaseController {
	protected $facebook ;
	public $config = array(
			 'appId'  => '790702710969266',
			  'secret' => '0274a122ced250e25a36531f4ab781eb',
			);
		

	public function facebookpost($id){
		$post = Post::find($id);
		$this->facebook = new Facebook($this->config);
		$FBaccessToken = "CAAFYZB2kH6V4BADhqN2AQoAZAhYgBclWsRVFSKVcPqVsUXPC3KQtf7GaLF5ISyRqdz3PpnIRwj9h8WPP7SHBZByeE7gKGeFsxUToVWLTFF11lilzfkCdzaZA8pgssVPhOEtT9ZBKxD9uy74xkOAg5D8wTNvsFHxrCP0umS6RyhQA1RuOhCI36";
		$attachment = array(
						'caption' => $post->title,
						'message' => $post->title,
						 'description' => $post->text,
			             'name' => $post->title,
			            'link' => $post->link,
			          	'picture'=> 'http://www.picturesnew.com/media/images/image-background.jpg',
						'access_token'=>$FBaccessToken
				    );
		$this->facebook->api('/618667244856279/links', 'POST', $attachment);
		$post->fb_status = 1;
		$post->save();
	}

	
	public function readpost(){
		

		$this->facebook = new Facebook($this->config);
		$facebookdetails = User::find(1);
		$this->facebook->setAccessToken($facebookdetails->access_token);
		echo "<pre>";
		$feeds			=	$this->facebook->api('277700802417164/feed', 'GET');
		print_r($feeds);
		exit;
	}





	public function getFBaccessToken(){
		$facebook = new Facebook($this->config);
		$user = $facebook->getUser();
		if($user){
			$access  	= $facebook->getAccessToken();
			$user   	= $facebook->api('/me');
			$insertarray =  array(
									'name'=>$user['first_name'],
									'gender'=>($user['gender']=="male")?1:2,
									'email'=>$user['email'],
									'social_id'=>$user['id'],
									'access_token'=>$access,
									'login_type'=>2
								);

		 $rules = [
			 'name' => 'required',
			 'email' => 'required|email|unique:users',
			 'social_id'=>'required|unique:users'
			];

		$validator = Validator::make($insertarray,$rules);
		if ($validator->fails())
		{
			if($validator->messages()->get('email'))
			{

				$userdetails = User::whereRaw("email ='".trim($insertarray['email']) ."' and social_id=".$user['id'] )->first();
				if($userdetails)
				{
					if($userdetails->login_type==2){
						if(Auth::loginUsingId($userdetails->id)){
							return Redirect::to('post');
						}

					}else{
						switch($userdetails->login_type){
							case 1:
								die("you did not use facebook login");
							break;
							case 3:
								die("twitter ");
							break;
							case 4:
								die("linkedin");
							break;

						}

					}
				}	
				

			}
			//return Redirect::to('error')->withErrors($validator);

		}else{
			User::create($insertarray);
			return Redirect::to('post');
		}			


		}else{

			$params = array(
		  'scope' => 'read_stream, friends_likes, email'
		  
		);
		 $fblink = $facebook->getLoginUrl($params);
		 return View::make('login.facebook',compact('fblink'));
		}

	}




}