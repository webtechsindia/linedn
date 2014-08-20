<?php

class Post extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
	 'title' => 'required',
	 'text' => 'required',
	 'image' => 'mimes:jpeg,bmp,png',
	 'link' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

	//protected $visible = array('title', 'text');

}