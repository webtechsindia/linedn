<?php

class PostsController extends \BaseController {

	/**
	 * Display a listing of posts
	 *
	 * @return Response
	 */
	protected $facebook ;
	protected $linkedin;
	protected $twitter;


	public function __construct(FacebookController $facebook,TwitterController $twitter,LinkedinController $linkedin){
		$this->facebook = $facebook;
		$this->twitter = $twitter;
		$this->linkedin = $linkedin;
	}


	public function index()
	{
		$posts = Post::all();
		return View::make('posts.index', compact('posts'));
	}

	public function readstream(){
		//$this->facebook->readpost();;
		//$this->twitter->readpost();
		$this->linkedin->readpost();

	}

	


	/**
	 * Show the form for creating a new post
	 *
	 * @return Response
	 */
	public function create()
	{

		if(Input::get()){
			$validator = Validator::make(Input::all(),Post::$rules);
			if (!$validator->fails())
				{
					$file  = Input::file('image');			
					$rand = str_random(12);
					$filename = $file->getClientOriginalName();
					$extension =$file->getClientOriginalExtension(); 
					$upload_success = $file->move(public_path()."/upload",$filename."_".$rand.".".$extension);	
					$post = new Post;
					$post->title  = Input::get('title');
					$post->text  = Input::get('text');
					$post->image  =$filename."_".$rand.".".$extension;
					$post->link  = Input::get('link');
					if($post->save()){
						return Redirect::to('/index');
					}else{
						die('error');
					}
				}else{
					print_r( $validator->messages());
				}	
		}

		return View::make('posts.create');
	}



	/**
	 * Store a newly created post in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Post::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Post::create($data);

		return Redirect::route('posts.index');
	}

	/**
	 * Display the specified post.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$post = Post::findOrFail($id);

		return View::make('posts.show', compact('post'));
	}

	/**
	 * Show the form for editing the specified post.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$post = Post::find($id);

		return View::make('posts.edit', compact('post'));
	}
	
	

	public function share($id){
			//var_dump($this->facebook);
			$test = app::make('FacebookCon');
			
		 // $this->facebookpost($id);
		 // $this->linkedinpost($id);
		 // $this->twitterinpost($id);
		
	}






	/**
	 * Update the specified post in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$post = Post::findOrFail($id);

		if(Input::all()){
			$validator = Validator::make(Input::all(),Post::$rules);
			if (!$validator->fails())
				{
				
					if(Input::file('image')){
						$file  = Input::file('image');			
						$rand = str_random(12);
						$filename = $file->getClientOriginalName();
						$extension =$file->getClientOriginalExtension(); 
						$upload_success = $file->move(public_path()."/upload",$filename."_".$rand.".".$extension);	
					}
					$post = Post::find(Input::get('id'));
					$post->title  = Input::get('title');
					$post->text  = Input::get('text');
					if(isset($upload_success))
					$post->image  =$filename."_".$rand.".".$extension;
					$post->link  = Input::get('link');
					if($post->save()){
						return Redirect::to('/index');
					}else{
						die('error');
					}
				}else{
					print_r( $validator->messages());
				}	
			
		}else{
			return View::make('posts.edit', compact('post'));
		}		

		
	}

	/**
	 * Remove the specified post from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Post::destroy($id);

		return Redirect::route('posts.index');
	}

}
