<?php


	include ("./connection.php");

	/********************************************************************************************************************************** 
	*  desliga a variável de sessão (nome) e destrói a sessão anteriormente criada e redirecciona para a página principal (index.php) *
	**********************************************************************************************************************************/

	session_start();
	
	
	/* actualiza o ultimo logout na bd */
	
	$email=$_SESSION['email'];
	$sql="UPDATE user SET logout_time='".date("Y-m-d H:i:s")."' WHERE email='$email'";
			  
	
	  
	$result=mysqli_query($db,$sql);
	  
	/* se houver erro no insert */
	if(!$result){
	}
	
		 

	unset($_SESSION['username']);
	unset($_SESSION['email']);
	session_destroy();

	header("Location: ./main/index.php");
	exit;
?>
