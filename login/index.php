<?php
  include ("../connection.php");
  
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $erro=0;

    /* se for um registo */
    if($_POST['form']=="registo"){
      
      /* verifica se jรก existe o user */
      $sql = "SELECT email,password,username FROM user WHERE email='".mysqli_real_escape_string($db,$_POST['email'])."'";
    
      //echo $sql;
    
      if($result = mysqli_query($db,$sql) && isset($result['email'])){
        
        $erro=3;
      }
      else{ 
      
        /* contra o sql injection */
        $username= mysqli_real_escape_string($db,$_POST['username']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $password = sha1(mysqli_real_escape_string($db,$_POST['password']));
        
            
        $sql="INSERT INTO user(username,email,password) VALUES('".$username."','".$email."','".$password."')";
        
       //echo $sql;
		    //exit();
        
        $result=mysqli_query($db,$sql);
        
        /* se houver erro no insert */
        if(!$result){
          $erro=4;
        }
        else{
          
          session_start();
          $_SESSION['nome'] = $username;
          $_SESSION['email'] = $email;
     
		  
          $rememberme = isset($_POST['rememberme']) ? true : false;
          if ($rememberme){
          setcookie('username',$username, time() + 3600*24*30);
          }
          
          
          header('Location:..\main\index.php');
          exit;
        }
      }
    }
    
    /* se for login */
    if($_POST['form']=="login"){
      
     
        $email=mysqli_real_escape_string($db,$_POST['email']);
        $crypt_pass = sha1($_POST['password']); // cifra a password do form
        $found = false;
        $username = '';

       /* verifica a existencia do user e obtem a password para poder comparar com a password dada */
       $sql = "SELECT user_id,username,password,email FROM user WHERE email='$email'";
     
        $result = mysqli_query($db,$sql);
        if ($data = mysqli_fetch_array($result)){
        
            /* se as passwords forem iguais ?  então existe o utilizador */
           /* echo $crypt_pass."------>". $data['password'];
            exit();*/
            
            if ($crypt_pass == $data['password']){
                $found = true;
                $username = $data['username'];
                $email=$data['email'];
            }
        }
        else{
            $erro=1;
         }		
    
    
      if($found == false) // não existe user redirect para registo
      {
        $erro=1;
      }
		else // existe user então cria a sessão e redirect para a página inicial 
		{
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $rememberme = isset($_POST['rememberme']) ? true : false;
            if ($rememberme){
                setcookie('username',$username, time() + 3600*24*30);
			}  
		}
		
		$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
		$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
	  
	  
		header('Location:..\main\index.php');
		exit;
    }
    
  }
  else{
    session_start();
  }
  
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Project SAO Login</title>
    <!-- MDB icon -->
    <link rel="icon" href="img/logo.jpeg" type="image/x-icon"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="css/mdb.min.css" />
    <!-- Custom styles -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
      <!--Main Navigation-->
  <header>
    <style>
      #intro {
        background-image: url(https://mdbootstrap.com/img/new/fluid/city/008.jpg);
        height: 100vh;
      }

      /* Height for devices larger than 576px */
      @media (min-width: 992px) {
        #intro {
          margin-top: -58.59px;
        }
      }

      .navbar .nav-link {
        color: #fff !important;
      }
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
      <div class="container-fluid">
        <!-- Navbar brand -->
        <a class="navbar-brand nav-link" target="_blank" href="https://mdbootstrap.com/docs/standard/">
          <strong>MDB</strong>
        </a>
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarExample01"
          aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarExample01">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item active">
              <a class="nav-link" aria-current="page" href="../main/index.php">Home</a>
            </li>
          </ul>
          <ul class="navbar-nav d-flex flex-row">
            <!-- Icons -->
            <li class="nav-item me-3 me-lg-0">
              <a class="nav-link" href="https://twitter.com/kiko__2003_" rel="nofollow" target="_blank">
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <li class="nav-item me-3 me-lg-0">
              <a class="nav-link" href="https://github.com/Project-SAO" rel="nofollow" target="_blank">
                <i class="fab fa-github"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar -->

    <!-- Background image -->
    <div id="intro" class="bg-image shadow-2-strong">
      <div class="mask d-flex align-items-center h-100" style="background-color: rgba(0, 0, 0, 0.8);">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-md-8">
              <form action="index.php" method="POST" class="bg-white rounded shadow-5-strong p-5">

                <input type="hidden" name="form" value="login"/>

                <!-- Email input -->
                <div class="form-outline mb-4">
                  <input type="email" name="email" id="form1Example1" class="form-control" />
                  <label class="form-label" for="form1Example1">Email address</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                  <input type="password" name="password" id="form1Example2" class="form-control" />
                  <label class="form-label" for="form1Example2">Password</label>
                </div>

                <!-- 2 column grid layout for inline styling -->
                <div class="row mb-4">
                  <div class="col d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check">
                      <input class="form-check-input" name="checkbox" type="checkbox" value="" id="form1Example3" checked />
                      <label class="form-check-label" for="form1Example3">
                        Remember me
                      </label>
                    </div>
                  </div>

                  <div class="col text-center">
                    <!-- Simple link -->
                    <a href="#!">Forgot password?</a>
                  </div>
                </div>
				
			<div class="row">
				<div class="col-md-11 text-center">
                    <!-- Simple link -->
                    <a href="../registo/index.php">Don't have an account ? Register now.</a>
                </div>
				
				<div class="col-md-11 text-center">
                    <!-- Simple link -->
                    <a><br></a>
                </div>
			</div>
			
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Background image -->
  </header>
  <!--Main Navigation-->

  <!--Footer-->
  <footer class="bg-light text-lg-start">
    <hr class="m-0" />
    <div class="text-center py-4 align-items-center">
      <p>Follow Project SAO on social media</p>
      <a href="https://twitter.com/kiko__2003_" class="btn btn-primary m-1" role="button" rel="nofollow"
        target="_blank">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://github.com/Project-SAO" class="btn btn-primary m-1" role="button" rel="nofollow"
        target="_blank">
        <i class="fab fa-github"></i>
      </a>
    </div>

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
      © 2021 Copyright:
      <a class="text-dark" href="https://mdbootstrap.com/">Project SAO</a>
    </div>
    <!-- Copyright -->
  </footer>
  <!--Footer-->
    <!-- MDB -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript" src="js/script.js"></script>
</body>
</html>