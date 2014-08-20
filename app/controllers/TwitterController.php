<?php

class TwitterController extends BaseController {
	
	protected $twitter ;

	 public function gettwLoginLink(){
	        $this->twitter = new TwitterOAuth('QsAk6xZNLibwUocWUobNHEWNw', 'JQhQgeBGjAeXeUMmbGDx2ZWuGCR9M2NTAWTWY5P7gL0FqJPchu');
	        $temporary_credentials = $this->twitter->getRequestToken('http://linden.com/tw/access');
	        $value = Session::put('twitdetails', $temporary_credentials);
	        $url =  $this->twitter->getAuthorizeURL($temporary_credentials);
	        return View::make('login.twitter',compact('url'));
        }


        function getTWAccessToken(){
            $oauth_verifier         =       Input::get('oauth_verifier');
            $data                           =       Session::get('twitdetails');
            $newtwitter             =       new TwitterOAuth('QsAk6xZNLibwUocWUobNHEWNw', 'JQhQgeBGjAeXeUMmbGDx2ZWuGCR9M2NTAWTWY5P7gL0FqJPchu',$data['oauth_token'],$data['oauth_token_secret']);
            $token_credentials      =       $newtwitter->getAccessToken($_GET['oauth_verifier']);
    		$connection                     = new TwitterOAuth('QsAk6xZNLibwUocWUobNHEWNw', 'JQhQgeBGjAeXeUMmbGDx2ZWuGCR9M2NTAWTWY5P7gL0FqJPchu', $token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);
            $data = $connection->get('account/verify_credentials');   
            $rules = [
			 'name' => 'required',
			 'social_id' => 'required|unique:users',
			 'access_token'=>'required'
			];
			$insertarray = array(
				'name'=>$data->name,
				'social_id'=>$data->id,
				'login_type'=>3,
				'access_token'=>serialize($token_credentials),
				);
			$validator = Validator::make($insertarray,$rules);

			if ($validator->fails())
			{
				if($validator->messages()->get('social_id')){

					$userdetails = User::where('social_id', '=', $data->id)->first();
					$userdetails->access_token = serialize($token_credentials);
					$userdetails->save();
					if(Auth::loginUsingId($userdetails->id)){
						return Redirect::to('post');
					}
				}else{
					return Redirect::to('error')->withErrors($validator);
				}
			}else{
				$user = User::create($insertarray);
				if(Auth::loginUsingId($user->id)){
						return Redirect::to('/post');
				}
			}	
		}


	function readpost(){
		$user  = User::find(2);
		$tokens=	unserialize($user->access_token);
		
		$connection                     = new TwitterOAuth('QsAk6xZNLibwUocWUobNHEWNw', 'JQhQgeBGjAeXeUMmbGDx2ZWuGCR9M2NTAWTWY5P7gL0FqJPchu',  $tokens['oauth_token'],$tokens['oauth_token_secret']);
        $data = $connection->get('account/verify_credentials');   
        $timeline 				=	$connection->get('statuses/user_timeline');		
		echo "<pre>";
		print_r($timeline);
		exit;
	}         

	function twitterinpost($id){
		$post = Post::find($id);
		$oauth_token 	=	'2298174770-Do6vs7II6cUuzs7jWcuhJiXFD8AhXqEnPqvgZPq';
		$oauth_token_secret = '35TQfKJLeuMlirWlcYgeXK7iOPhZm8F7VPAXSjSbvpfhT';

		$connection 			= new TwitterOAuth('9PviV7LT5g6rpuKxjpk9n6n6A', 'GwPfUrHYtbPm0hBZ99mCYjPpuOYSi8hA11el7LKJdNciljfopM', $oauth_token,$oauth_token_secret);
		$connection->post('statuses/update', array('status' =>"asdasdasd"));		


	}


}