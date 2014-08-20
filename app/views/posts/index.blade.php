@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
            <div class="box-header">
                <h3 class="box-title">Posts</h3>
                <div class="box-tools">
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Text</th>
                        <th>link</th>

                        <th>Facebook</th>
                        <th>Twitter</th>
                        <th>linkedin</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($posts as $post)
                    <tr>
                        <td>{{$post->id}}</td>
                        <td>{{$post->title}}</td>
                        <td>{{$post->text}}</td>
                        <td>{{$post->link}}</td>
                        <td>
                        	@if ($post->fb_status === 1)
                        	<span class="label label-success">Posted</span>
                        	@else
                        	<span class="label label-warning">Not posted</span>
     						@endif
                       	</td>
                        <td>
                        	@if ($post->tw_status === 1)
                        	<span class="label label-success">Posted</span>
                        	@else
                        	<span class="label label-warning">Not posted</span>
     						@endif
                       	</td>
                        <td>
                        	@if ($post->li_status === 1)
                        	<span class="label label-success">Posted</span>
                        	@else
                        	<span class="label label-warning">Not posted</span>
     						@endif
                       	</td>
                       	<td>
                   			<a href="post/update/{{$post->id}}">
                       			<button type="submit" class="btn btn-primary">Edit</button>
                       		</a>
                       	
                   			<a href="post/share/{{$post->id}}">
                       			<button type="submit" class="btn btn-primary">share</button>
                       		</a>
                       	</td>
                        
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
	</div>
</div>			

	









@stop