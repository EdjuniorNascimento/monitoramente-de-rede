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


	$salvarsql = "UPDATE `tb_lojas` SET `lj_loja`='$lj_loja', `lj_cidade`='$lj_cidade', `lj_estado`='$lj_estado', `lj_rua`='$lj_rua', `lj_numero`='$lj_numero', `lj_bairro`='$lj_bairro', `lj_cep`='$lj_cep', `lj_razao`='$lj_razao', `lj_cnpj`='$lj_cnpj', `lj_lat`='$lj_lat', `lj_lng`='$lj_lng', `lj_claro`='$lj_claro', `lj_fone`='$lj_fone', `lj_ip`='$lj_ip', `lj_servidor`='$lj_servidor', `lj_status`='Normal', `lj_tempo_fora`=NULL, `lj_circuito`='$lj_circuito' WHERE `lj_loja`='$lj_loja'";
	$resx=mysqli_query($con,$salvarsql) or die (mysqli_error($con));;
			
	if($resx){
		excluir_chamado($lj_loja);
		header('Location:index.php');
		
	}else{	
		header('Location:index.php/dialog=erro_editar_loja');
		mysqli_close($con);
		session_destroy();
	}	

	
function excluir_chamado($lj){
	
	include "sql/conect_sem_session.php";
	
	$sql_delete = "DELETE FROM `tb_chamado` WHERE `ch_lj_id` = '$lj'";
	$resp = mysqli_query($con, $sql_delete) or die (mysqli_error($con));
	
	if($resp){
		return true;
	}else{
		return false;
	}
}

	
	
	

?>