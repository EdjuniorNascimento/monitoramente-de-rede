<?php
	include "sql/conect.php";
	
	
	
	
	if($_POST['login']!='' || $_POST['senha']!=''){
		
		$login=$_POST['login'];
		$senha=$_POST['senha'];
		
		
		
		
		$sql="SELECT * FROM tb_login WHERE lg_usuario='$login' AND lg_senha='$senha'";
		$result = mysqli_query($con, $sql) or die (mysqli_error($con));
		$linha=mysqli_affected_rows($con);
		
		if($linha>0){
			
			while($reg=mysqli_fetch_array($result)){
				
				$_SESSION["id"]=$reg['lg_id'];
				
				header('Location:index.php');
				
				mysqli_close($con);
			}
			header('Location:index.php');
		}else{
				
				header('Location:index.php?dialog=erro_logar');
				mysqli_close($con);
				session_destroy();
				
		}
		
	}else{
		
		header('Location:index.php?dialog=erro_vazio');
		
	}




?>