@extends('admin')

@section('content')
<div class="row">
                        <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Quick Example</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data">
                <div class="box-body">
                   
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input type="text" class="form-control" name="title" value="{{$post->title}}"> 
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Text</label>
                        <textarea  name='text' class="form-control">{{$post->text}}</textarea>   
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">image</label>
                        <input type="file" name='image' value="{{$post->image}}" class="form-control" id="exampleInputEmail1" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">link</label>
                        <input type="text" name='link' value="{{$post->link}}" class="form-control" id="exampleInputEmail1" placeholder="Enter link">
                    </div>
			<div class="form-group">
                        <label for="exampleInputEmail1">linkedin img URL</label>
                        <input type="text" name='lk_imageurl' value="{{$post->lk_imageurl}}" class="form-control" id="exampleInputEmail1" placeholder="Linkedin image should be from the domain as the link given above">
                    </div>
                    <div style="display:none" class="form-group">
                    	
                    	<input type="text" name="id" value="{{$post->id}}"> 
                    </div>	
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>





@stop
