<?php

class Post extends \Eloquent {

	// Add your validation rules here
	public static $rules = [

	 'text' => 'required',
	  'title' => 'required',
	 'image' => 'mimes:jpeg,bmp,png',
	 'link' => 'required|url',
	'lk_imageurl'=>'required|url'
	];
	public static $rules_validate = [

	 'text' => 'required',
	  'title' => 'required',
	 'image' => 'required',
	 'link' => 'required|url'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	//protected $visible = array('title', 'text');

}
