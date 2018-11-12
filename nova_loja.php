<?php

	include "sql/conect.php";


	$lj_loja = $_POST['lj_loja'] ;
	$lj_cidade = ucfirst($_POST['lj_cidade']);
	$lj_estado = $_POST['lj_estado'];
	$lj_rua = ucfirst($_POST['lj_rua']);
	$lj_numero = $_POST['lj_numero'];
	$lj_bairro = ucfirst($_POST['lj_bairro']);
	$lj_cep = $_POST['lj_cep'];
	$lj_razao = strtolower($_POST['lj_razao']);
	$lj_cnpj = $_POST['lj_cnpj'];
	$lj_lat = $_POST['lj_lat'];
	$lj_lng = $_POST['lj_lng'];	
	$lj_claro = $_POST['lj_claro'];
	$lj_fone = $_POST['lj_fone'];
	$lj_ip = $_POST['lj_ip'];
	$lj_servidor = ucfirst($_POST['lj_servidor']);
	$lj_circuito = strtolower($_POST['lj_circuito']);


	

	$salvarsql = "INSERT INTO `tb_lojas` VALUES ('$lj_loja', '$lj_cidade', '$lj_estado', '$lj_rua', '$lj_numero', '$lj_bairro', '$lj_cep', '$lj_razao', '$lj_cnpj', '$lj_lat', '$lj_lng', '$lj_claro', '$lj_fone', '$lj_ip', '$lj_servidor', 'Normal', NULL,'$lj_circuito')";
	$resx=mysqli_query($con,$salvarsql) or die (mysqli_error($con));;
			
	if($resx){

		header('Location:index.php');
		
	}else{	
		header('Location:index.php/dialog=erro_nova_loja');
		mysqli_close($con);
		session_destroy();
	}	

	
	
	
	

?>