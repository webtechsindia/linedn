<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/AdminLTE.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
  <body class="bg-black">

	 <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form action="auth" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="User ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                 </div>
                <div class="footer">     

                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  
                   <a href="/facebook"  class="btn bg-olive btn-block">Facebook</a>
                    <a href="/twitter" class="btn bg-olive btn-block">Twitter</a>
                    <a href="/linkedin" class="btn bg-olive btn-block">linkedin</a>
                </div>
             

            </form>
            <div class="margin text-center">
                
            </div>	
		</div>
</body>
</html>