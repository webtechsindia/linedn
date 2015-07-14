@extends('admin')

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
            <div class="box-header">
                <h3 class="box-title">Posts</h3>
                <div class="box-tools">
                     <a href="./readstream">
                                <button type="submit" class="btn pull-right btn-primary">Get Posts</button>
                    </a>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th width="100px">Title</th>
                        <th width="100px">Text</th>
                        <th width="100px">link</th>
                        <th>Facebook</th>
                        <th>Twitter</th>
                        <th>linkedin</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($posts as $post)
                    <tr>
                        <td>{{$post->id}}</td>
                        <td>{{str_limit($post->title, $limit = 30, $end = '...')}}</td>
                        <td>{{str_limit($post->text, $limit = 30, $end = '...')}}</td>
                        <td>{{str_limit($post->link, $limit = 30, $end = '...')}}</td>
                        <td>
                        	@if ($post->fb_id != "")
                        	<span class="label label-success">Posted</span>
                        	@else
                        	<span class="label label-warning">Not posted</span>
     						@endif
                       	</td>
                        <td>
                        	@if ($post->tw_id != "")
                        	<span class="label label-success">Posted</span>

                            @else
                        	   <span class="label label-warning">Not posted</span>
     						@endif
                            
                       	</td>
                        <td>
                        	@if ($post->lk_id != "")
                        	<span class="label label-success">posted</span>
                            @else
                        	   <span class="label label-warning">Not Posted</span>
     						@endif
                       	</td>
                       	<td>
                   			<a href="posts/view/{{$post->id}}">
                            <button type="submit" class="btn btn-primary">View</button>
                        </a>
                        <a href="posts/update/{{$post->id}}">
                       			<button type="submit" class="btn btn-primary">Edit</button>
                     		</a>
                        <a href="posts/share/{{$post->id}}">
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
