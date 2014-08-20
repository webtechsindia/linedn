@extends('homepage')

@section('content')



<div class="header">Error</div>
            <form action="auth" method="post">
                <div class="body bg-gray">
                     <p class="error">error with login</p>
                 </div>   

            </form>
        <div class="margin text-center">
             <a href="/resetpassword" class="btn bg-olive btn-block">forget password</a>
        </div>  

@stop