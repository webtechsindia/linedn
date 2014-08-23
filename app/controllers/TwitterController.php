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
        $timeline 				=	$connection->get('statuses/user_timeline',array('count'=>5));		
		$twitterdata 				=	array();

		foreach($timeline as $key=>$val){
			 $twitterdata['text']				=	$val->text;
			 $twitterdata['social_id']	=	$val->user->id;
			 $twitterdata['social_type']		=	3;
			 $twitterdata['tw_id']			=	$val->id;
			$twitterdata['image']			=	"";	
			 if(isset($val->extended_entities->media[0]->media_url)){
			  	$extensions = array(
				    1 => ".gif",
				    2 => ".jpg",
				    3 => ".png",
				);
			  	$randomname						=	substr(md5(rand()), 0, 7);
			  	$imagetype  					= 	exif_imagetype($val->extended_entities->media[0]->media_url);
			  	$filename 						=	$randomname.$extensions[$imagetype];
			  	copy($val->extended_entities->media[0]->media_url,public_path()."/upload/".$filename);
			  	$twitterdata['image']			=	$filename;
			}			
			 $rules = [
			 'text' => 'required',
			 'tw_id' => 'required|unique:posts',
			 'social_id'=>'required',
			];

			$validator = Validator::make($twitterdata,$rules);
			if (!$validator->fails())
			{
				DB::table('posts')->insert(
					     $twitterdata
					);
			}
			
		}

		 
	}

	function twitterinpost($id){
		$post 			= 	Post::find($id);
		$user  			= 	User::find(2);
		$tokens 		=	unserialize($user->access_token);
		$connection    	= 	new TwitterOAuth('QsAk6xZNLibwUocWUobNHEWNw', 'JQhQgeBGjAeXeUMmbGDx2ZWuGCR9M2NTAWTWY5P7gL0FqJPchu',  $tokens['oauth_token'],$tokens['oauth_token_secret']);
       	$image_path		=		public_path()."/upload/".$post->image;

		$handle 		= fopen($image_path,'rb');
		$image     	= fread($handle,filesize($image_path));
		fclose($handle);

		$params 		= array(
							 'media[]' => "{$image};type=image/jpeg;filename={$image_path}",
							 'status'  => $post->text
					 		);

		$post= $connection->post('statuses/update_with_media',$params,true);
    	$connection->post('statuses/update_with_media', $params);		
    	$timeline 				=	$connection->get('statuses/user_timeline',array('count'=>1));	
    	Post::where('id', '=', $id)->update(array('tw_id' => $timeline[0]->id));
    	}


}