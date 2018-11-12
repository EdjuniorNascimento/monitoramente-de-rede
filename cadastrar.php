<?php

	include "sql/conect.php";
	

	if($_POST['clogin']!='' || $_POST['csenha']!=''){

		$login = $_POST['clogin'];
		$senha = $_POST['csenha'];
		
		$sql_i = "INSERT INTO tb_login VALUES (NULL, '$login', '$senha')";
		$res = mysqli_query($con, $sql_i) or die (mysqli_error($con));
		
		if($res){
			
			$sql="SELECT * FROM tb_login WHERE lg_usuario='$login' AND lg_senha='$senha'";
			$result = mysqli_query($con, $sql) or die (mysqli_error($con));
			$linha=mysqli_affected_rows($con);
			
			if($linha>0){
				
				while($reg=mysqli_fetch_array($result)){
					
					$num=rand(100000, 900000);
					
					$_SESSION["session"]=$num;
					
					mysqli_close($con);
					
				}
				
				
				
			}else{
					
					mysqli_close($con);
					session_destroy();
					
			}
				
			header('Location:index.php');
			
		}else{
		
			header('Location:index.php?dialog=erro_cadastro');
			mysqli_close($con);
			session_destroy();
		}
		
		
	}else{
		
		header('Location:index.php?dialog=erro_vazio');
		
	}




?>