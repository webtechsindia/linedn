<?php

class FacebookController extends BaseController {
	protected $facebook ;
	public $config = array(
			 'appId'  => '790702710969266',
			  'secret' => '0274a122ced250e25a36531f4ab781eb',
			);
		

	public function readpost(){
		$this->facebook = new Facebook($this->config);
		$facebookdetails = User::find(9);
		$this->facebook->setAccessToken($facebookdetails->access_token);
		$feeds			=	$this->facebook->api('277700802417164/feed?&limit=5', 'GET');
		$facebookfeeds	=	array();
		foreach($feeds['data'] as $key=>$val){
			$facebookfeeds['text']		=	$val['message'];
			$facebookfeeds['social_id']	=	$val['from']['id'];
			$facebookfeeds['fb_id']		=	$val['id'];
			$facebookfeeds['image']		=	"";
			$facebookfeeds['social_type']	=	2;
			if(isset($val['picture'])){
				$extensions = array(
				    1 => ".gif",
				    2 => ".jpg",
				    3 => ".png",
				);
			  	$randomname						=	substr(md5(rand()), 0, 11);
			  	$imagetype  					= 	exif_imagetype($val['picture']);
			  	$filename 						=	$randomname.$extensions[$imagetype];
			  	copy($val['picture'],public_path()."/upload/".$filename);
			  	$facebookfeeds['image']			=	$filename;
			}
			 $rules = [
				 'text' => 'required',
				 'fb_id' => 'required|unique:posts',
				 'social_id'=>'required',
				];
				$validator = Validator::make($facebookfeeds,$rules);
				if (!$validator->fails())
				{
					DB::table('posts')->insert(
						     $facebookfeeds
						);
				}
		}
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
						$userdetails->access_token		=	$access;
						$userdetails->save();
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
		  'scope' => 'read_stream, friends_likes, email,publish_actions,user_groups,publish_actions'
		  
		);
		 $fblink = $facebook->getLoginUrl($params);
		 return View::make('login.facebook',compact('fblink'));
		}

	}

	public function facebookpost($id){
		$post = Post::find($id);
		$user  			= 	User::find(8);
		
		$this->facebook = new Facebook(array(
			 'appId'  => '790702710969266',
			  'secret' => '0274a122ced250e25a36531f4ab781eb',
			)
		);
		
		$attachment = array(
						'caption' => $post->title,
						'message' => $post->title,
						 'description' => $post->text,
			             'name' => $post->title,
			            'link' => $post->link,
			          	'picture'=> 'http://www.picturesnew.com/media/images/image-background.jpg',
						'access_token'=>'790702710969266|0274a122ced250e25a36531f4ab781eb'
				    );
		$this->facebook->signeduser = $user->social_id;
		$this->facebook->api('/618667244856279/links?uid='.$user->social_id, 'POST', $attachment);

		$post->fb_status = 1;
		$post->save();

	}


}