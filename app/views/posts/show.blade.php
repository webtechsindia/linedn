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
                        <p> {{$post->title}}</p> 
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Text</label>
                        <p>{{$post->text}}</p>   
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">image</label>
                        <img width="300" height="300" src="{{URL::to('/').'/upload/'.$post->image}}" />
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">link</label>
                        <p>{{$post->link}}</p>
                    </div>
                </div><!-- /.box-body -->

            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>
@stop
