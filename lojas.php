<?php
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');


if(isset($_GET['acao'])){
	
	switch($_GET['acao']){
		
		
		case 'listar':
			listar();
		break;	
		
		case 'info':
			mostrar_info($_GET['lj']);
		break;
		
		case 'mostrar_loja':
			mostrar_loja($_GET['lj']);
		break;
		
		case 'editar_loja':
			editar_loja($_GET['lj']);
		break;
		
		case 'pesquisa':
			pesquisar($_GET['tx_pesquisa']);
		break;
		
		case 'verificar_existe':
			verificar_existe($_GET['lj']);
		break;
		
	}
	
}


if(isset($_POST['acao'])){
	
	switch($_POST['acao']){
		
		case 'novo_chamado':
		
			$ch_lj_id = $_POST['ch_lj_id'];
			$ch_login = $_POST['ch_login'];
			$ch_protocolo = $_POST['ch_protocolo'];
			$ch_atendente = $_POST['ch_atendente'];
			$ch_data_prev = $_POST['ch_data_prev'];
			$ch_hora_prev = $_POST['ch_hora_prev'];
			$ch_obs = $_POST['ch_obs'];
			$status = $_POST['status'];
			
		
		
			//echo "resultado2: $ch_lj_id, $ch_login, $ch_protocolo, $ch_atendente, $ch_data_prev, $ch_hora_prev, $ch_obs";
			novo_chamado($ch_lj_id, $ch_login, $ch_protocolo, $ch_atendente, $ch_data_prev, $ch_hora_prev, $ch_obs, $status);
			
		break;
		
		
		case 'editar_chamado':
		
			include "sql/conect_sem_session.php";
			
			$ch_lj_id = $_POST['ch_lj_id'];
			$ch_login = $_POST['ch_login'];
			$ch_protocolo = $_POST['ch_protocolo'];
			$ch_atendente = $_POST['ch_atendente'];
			$ch_data_prev = $_POST['ch_data_prev'];
			$ch_hora_prev = $_POST['ch_hora_prev'];
			$ch_obs = $_POST['ch_obs'];
			$status = $_POST['status'];
			
			$sql_delete = "DELETE FROM `tb_chamado` WHERE `ch_lj_id` = '$ch_lj_id'";
			$resp = mysqli_query($con, $sql_delete) or die (mysqli_error($con));
			
			if($resp){
				novo_chamado($ch_lj_id, $ch_login, $ch_protocolo, $ch_atendente, $ch_data_prev, $ch_hora_prev, $ch_obs, $status);
			}
			
		break;
		
		case 'excluir_loja':
		
			$lj_loja = $_POST['lj_loja'];
			
			excluir_loja($lj_loja);
			
		break;
		
		
	}

}


function excluir_loja($lj_loja){
	
	
	include "sql/conect_sem_session.php";
	
	$sql_delete = "DELETE FROM `tb_lojas` WHERE `lj_loja` = '$lj_loja'";
	$resp = mysqli_query($con, $sql_delete) or die (mysqli_error($con));
	
	excluir_chamado($lj);

}


function listar(){
	
	
	include "sql/conect_sem_session.php";
	
	$sql = "SELECT * FROM tb_lojas ORDER BY `lj_status`='Normal', `lj_loja`";
	$res = mysqli_query($con, $sql) or die (mysqli_error($con));

	while ($reg = mysqli_fetch_array($res)){
		
		$lj = $reg['lj_loja'];
		$cidade = $reg['lj_cidade'];
		$status = $reg['lj_status'];
		$servidor = $reg['lj_servidor'];
		$circuito = $reg['lj_circuito'];
		$lat = $reg['lj_lat'];
		$lng = $reg['lj_lng'];
		
		if( $reg['lj_tempo_fora'] != NULL){
			
			
			$dataFuturo = "".date("d-m-Y H:i:s");
			$dataAtual = $reg['lj_tempo_fora'];

			$date_time  = new DateTime($dataAtual);
			$diff       = $date_time->diff( new DateTime($dataFuturo));
			$lj_tempo_fora = $diff->format('%dd %Hh %im %ss');
			//$lj_tempo_fora = $diff->format('%Y ano(s), %m mês(s), %d dia(s), %H hora(s), %i minuto(s) e %s segundo(s)');
			
			
			
		}else{$lj_tempo_fora = "-";}
		
		
		if($status == "Normal"){
			echo '
			
			<tr id="'.$lj.'" class="lojas" onclick="mostrar_info(this)">
				
				<td>
					'.$lj.'
				</td>
				<td>
					'.$cidade.'
				</td>
				<td>
					'.$status.'
				</td>
				<td>
					'.$lj_tempo_fora.'
				</td>

			</tr>
		
			<script type="text/javascript"> 
				add_marca('.$lat.' , '.$lng.', "Loja'.$lj.' - '.$cidade.'", "","on"); 
			</script>';
			
			
			
		}else{
			 
			echo '
			
			<tr id="'.$lj.'" class="lojas" onclick="mostrar_info(this)" style="background:#FF7070">
				
				<td>
					<div id="pinguelo_'.$lj.'" class="pinguelo masc"></div>
					'.$lj.'
				</td>
				<td>
					'.$cidade.'
				</td>
				<td>
					'.$status.'
				</td>
				<td>
					'.$lj_tempo_fora.'
				</td>

			</tr>
			
			<script type="text/javascript"> 
				add_marca('.$lat.' , '.$lng.', "Loja'.$lj.' - '.$cidade.'", "","off");
			</script>
			
			';


		}	
	}

}


function mostrar_info($lj){

	include "sql/conect.php";
	
	
	$sql = "SELECT * FROM tb_lojas WHERE lj_loja = '$lj' ";
	$res = mysqli_query($con, $sql) or die (mysqli_error($con));
	
	
	while ($reg = mysqli_fetch_array($res)){
		
		$cidade = $reg['lj_cidade'];
		$status = $reg['lj_status'];
		$servidor = $reg['lj_servidor'];
		$circuito = $reg['lj_circuito'];
		
				
		echo '
		
		
			<span id="'.$lj.'" style="font-size:18px; color:white; cursor:pointer;"  onclick="mostrar_loja(this)" >Loja '.$lj.' - '.$cidade.'</span></br>
			<span style="color:#aaa">('.$circuito.')</span></br></br>
			
		';
		
		
		switch($status){
			
			case "Normal"://normal
			
				 echo '
					<img src="img/ic_action_monitor.png"></img></br>
					<span>Funcionamento Normal</span></br></br>
				 ';
				 
			break;
			
			case "Sem Contato"://Sem Contato
			
				 echo '
					<img src="img/ic_action_warning.png"></img></br>
					<span> Sem contato com o '.$servidor.' entre em contato com suporte para verificar o motivo</span></br></br>
					
				 ';
				 if(isset($_SESSION["id"])){echo '<button id="'.$lj.'" class="botao_inf_reparo" onclick="exibir_selecao(this)">Informar reparo</button>';}
				 
			break;
			
			case "Sem internet"://sem internet
			
				 echo '
					<img src="img/ic_action_clock.png"></img></br>
					<span> Sem internet no '.$servidor.'</span></br></br>
					
				 ';
				 
				if(isset($_SESSION["id"])){echo '<button id="'.$lj.'" class="botao_inf_reparo" onclick="editar_chamado(this)">Editar</button>';}
				 
			 
				 $regi = info_chamado($lj);
				 
				echo '
					</br></br>
					<select style="display:none" id="selec_prob" name="selec_prob" onchange="selecao_problema()">
					
						<option value=""> </option>
					
						<option value="sem_internet" selected="selected">Sem Internet</option>
						<option value="sem_energia">Sem Energia</option>
						<option value="suporte">Suporte</option>
						
					</select>
				
				
					<div id="win_chamado" style="text-align:left;" >
						</br><table style="color:white;">
							<tr><td>LOGIN:</td><td><input id="ch_protocolo" type="text" style="width:100%" maxlength="15" readonly="true" disabled="disabled" value="'.$regi['ch_login'].'"></input></td></tr>
							<tr><td>PROTOCOLO:</td><td><input id="ch_protocolo" type="text" style="width:100%" maxlength="15" disabled="disabled" value="'.$regi['ch_protocolo'].'"></input></td></tr>
							<tr><td>ATENDENTE:</td><td><input id="ch_atendente" type="text" style="width:100%" maxlength="20" disabled="disabled" value="'.$regi['ch_atendente'].'"></input></td></tr>
							<tr><td>PREVISÃO:</td><td><input id="ch_data_prev" type="date" style="width:65%" disabled="disabled" value="'.$regi['ch_data_prev'].'"></input><input id="ch_hora_prev" type="time" style="width:35%" disabled="disabled" value="'.$regi['ch_hora_prev'].'"></input></td></tr>
							<tr><td>OBS:</td><td><textarea rows="5" id="ch_obs" type="text" style="width:100%" maxlength="255" disabled="disabled">'.$regi['ch_obs'].'</textarea></td></tr>
						</table>
					</div>

				';
				 
				 
			break;
			
			case "Sem energia"://sem energia
			
				 echo '
					<img src="img/ic_action_plug.png"></img></br>
					<span> Sem energia na loja</span></br></br>
					
				 ';
				if(isset($_SESSION["id"])){echo '<button id="'.$lj.'" class="botao_inf_reparo" onclick="editar_chamado(this)">Editar</button>';}
				 
			 
				 $regi = info_chamado($lj);
				 
				echo '
					</br></br>
					<select style="display:none" id="selec_prob" name="selec_prob" onchange="selecao_problema()">
					
						<option value=""> </option>
					
						<option value="sem_internet">Sem Internet</option>
						<option value="sem_energia" selected="selected">Sem Energia</option>
						<option value="suporte">Suporte</option>
						
					</select>
				
				
					<div id="win_chamado" style="text-align:left;" >
						</br><table style="color:white;">
							<tr><td>LOGIN:</td><td><input id="ch_protocolo" type="text" style="width:100%" maxlength="15" readonly="true" disabled="disabled" value="'.$regi['ch_login'].'"></input></td></tr>
							<tr><td>PREVISÃO:</td><td><input id="ch_data_prev" type="date" style="width:65%" disabled="disabled" value="'.$regi['ch_data_prev'].'"></input><input id="ch_hora_prev" type="time" style="width:35%" disabled="disabled" value="'.$regi['ch_hora_prev'].'"></input></td></tr>
							<tr><td>OBS:</td><td><textarea rows="5" id="ch_obs" type="text" style="width:100%" maxlength="255" disabled="disabled">'.$regi['ch_obs'].'</textarea></td></tr>
						</table>
					</div>

				';

				 
			break;
			
			case "Suporte"://em manutenção
			
				 echo '
					<img src="img/ic_action_gear.png"></img></br>
					<span>Está sendo realizado manutenção em/no '.$servidor.'</span></br></br>
					
				 ';
				if(isset($_SESSION["id"])){echo '<button id="'.$lj.'" class="botao_inf_reparo" onclick="editar_chamado(this)">Editar</button>';}
				 
			 
				 $regi = info_chamado($lj);
				 
				echo '
					</br></br>
					<select style="display:none" id="selec_prob" name="selec_prob" onchange="selecao_problema()">
					
						<option value=""> </option>
					
						<option value="sem_internet">Sem Internet</option>
						<option value="sem_energia">Sem Energia</option>
						<option value="suporte" selected="selected">Suporte</option>
						
					</select>
				
				
					<div id="win_chamado" style="text-align:left;" >
						</br><table style="color:white;">
							<tr><td>LOGIN:</td><td><input id="ch_protocolo" type="text" style="width:100%" maxlength="15" readonly="true" disabled="disabled" value="'.$regi['ch_login'].'"></input></td></tr>
							<tr><td>PREVISÃO:</td><td><input id="ch_data_prev" type="date" style="width:65%" disabled="disabled" value="'.$regi['ch_data_prev'].'"></input><input id="ch_hora_prev" type="time" style="width:35%" disabled="disabled" value="'.$regi['ch_hora_prev'].'"></input></td></tr>
							<tr><td>OBS:</td><td><textarea rows="5" id="ch_obs" type="text" style="width:100%" maxlength="255" disabled="disabled">'.$regi['ch_obs'].'</textarea></td></tr>
						</table>
					</div>

				';
			
				 
			break;
			
		}
		
		
		if(isset($_SESSION["id"])){
			echo '
				</br></br>
				<select style="display:none" id="selec_prob" name="selec_prob" onchange="selecao_problema()" >
				
					<option value=""> </option>
				
					<option value="sem_internet">Sem Internet</option>
					<option value="sem_energia">Sem Energia</option>
					<option value="suporte">Suporte</option>
					
				</select>
				
				<div id="win_chamado" style="text-align:left;"></div>
				
			
			';
		}
	
	
	}
	
}


function novo_chamado($ch_lj_id, $ch_login, $ch_protocolo, $ch_atendente, $ch_data_prev, $ch_hora_prev, $ch_obs, $status){
	
	include "sql/conect_sem_session.php";
	
	$sql = "INSERT INTO tb_chamado VALUES (NULL, '$ch_lj_id', '$ch_login', '$ch_protocolo', '$ch_atendente', '$ch_data_prev', '$ch_hora_prev', '$ch_obs')";
	$res = mysqli_query($con, $sql) or die (mysqli_error($con));
	

	switch($status){
			
			
		case "sem_internet":

			$sql_i =  "UPDATE `tb_lojas` SET `lj_status`= 'Sem internet' WHERE `lj_loja`='$ch_lj_id'";
			$res_i = mysqli_query($con, $sql_i) or die (mysqli_error($con));
			
		break;
		
		
		
		case "sem_energia":
		
			$sql_i =  "UPDATE `tb_lojas` SET `lj_status`= 'Sem energia' WHERE `lj_loja`='$ch_lj_id'";
			$res_i = mysqli_query($con, $sql_i) or die (mysqli_error($con));

		break;
		
		case "suporte":

			$sql_i =  "UPDATE `tb_lojas` SET `lj_status`= 'Suporte' WHERE `lj_loja`='$ch_lj_id'";
			$res_i = mysqli_query($con, $sql_i) or die (mysqli_error($con));

		break;
		
	}

	
	

	
}


function info_chamado($lj){
	
	include "sql/conect_sem_session.php";
	
	$sql = "SELECT * FROM tb_chamado WHERE ch_lj_id = '$lj' ";
	$result = mysqli_query($con, $sql) or die (mysqli_error($con));
	
	return mysqli_fetch_array($result);
	
}


function mostrar_loja($lj){
		
	include "sql/conect.php";
	
	$sql = "SELECT * FROM tb_lojas WHERE lj_loja = '$lj'";
	$res = mysqli_query($con, $sql) or die (mysqli_error($con));
	if(mysqli_affected_rows($con)){

		while ($reg = mysqli_fetch_array($res)){
			
			echo'
			<form id="form_editar_lj" method="post" action="editar_loja.php">
				
				<input type="text" readonly="true" id="ed_lj_loja" name="lj_loja" class="fonte" placeholder="Nº" style="width:55px" maxlength="11" value="'.$reg['lj_loja'].'"></input>
				<input type="text" readonly="true" name="lj_cidade" class="fonte" placeholder="Cidade" style="width:380px; text-transform:capitalize;" maxlength="50" value="'.$reg['lj_cidade'].'"></input>
				<select name="lj_estado" readonly="true" class="fonte" style="width:60px">
						<option value="" '; if($reg['lj_estado']==""){echo 'selected="selected"';} echo ' ></option>
						<option value="CE" '; if($reg['lj_estado']=="CE"){echo 'selected="selected"';} echo ' >CE</option>
						<option value="PI" '; if($reg['lj_estado']=="PI"){echo 'selected="selected"';} echo ' >PI</option>
						
				</select>
				
				
				</br></br>
				
				<input type="text" readonly="true" name="lj_rua" class="fonte" placeholder="Rua" style="width:440px; text-transform:capitalize;" maxlength="50" value="'.$reg['lj_rua'].'"></input>
				<input type="text" readonly="true" name="lj_numero" class="fonte" placeholder="Nº" style="width:60px" maxlength="11" value="'.$reg['lj_numero'].'"></input>
				</br></br>
				
				<input type="text" readonly="true" name="lj_bairro" class="fonte" placeholder="Bairro" style="width:300px; text-transform:capitalize;" maxlength="20" value="'.$reg['lj_bairro'].'"></input>
				<input type="text" id="ed_lj_cep" readonly="true" name="lj_cep" class="fonte" placeholder="CEP" style="width:200px" maxlength="10" value="'.$reg['lj_cep'].'"></input>
				</br></br>
				
				<input type="text" readonly="true" name="lj_razao" class="fonte" placeholder="Razão" style="width:250px; text-transform:uppercase;" maxlength="60" value="'.$reg['lj_razao'].'"></input>
				<input type="text" id="ed_lj_cnpj" readonly="true" name="lj_cnpj" class="fonte" placeholder="CNPJ" style="width:250px" maxlength="18" value="'.$reg['lj_cnpj'].'"></input>
				</br></br>
				
				<input type="text" id="ed_lj_claro" readonly="true" name="lj_claro" class="fonte" placeholder="Claro Empresarial" style="width:250px;" maxlength="15" value="'.$reg['lj_claro'].'"></input>
				<input type="text" id="ed_lj_fone" readonly="true" name="lj_fone" class="fonte" placeholder="Telefone" style="width:250px;" maxlength="15" value="'.$reg['lj_fone'].'"></input>
				</br></br>
				
				<input type="text" readonly="true" name="lj_servidor" class="fonte" placeholder="Nome do Servidor" style="width:300px; text-transform:capitalize;" maxlength="20" value="'.$reg['lj_servidor'].'"></input>
				<input type="text" readonly="true" name="lj_ip" class="fonte" placeholder="IP" style="width:200px" maxlength="20" value="'.$reg['lj_ip'].'"></input>
				</br></br>
				
				<input type="text" id="ed_lj_circuito" readonly="true" name="lj_circuito" class="fonte" placeholder="Circuito da OI" style="width:500; text-transform:uppercase;" maxlength="20" value="'.$reg['lj_circuito'].'"></input>
				</br></br>
				
				
				<input type="text" id="ed_lj_lat" readonly="true" name="lj_lat" class="fonte" placeholder="latitude" style="width:250px;" maxlength="50" value="'.$reg['lj_lat'].'"></input>
				<input type="text" id="ed_lj_lng" readonly="true" name="lj_lng" class="fonte" placeholder="longitude" style="width:250px;" maxlength="50" value="'.$reg['lj_lng'].'"></input>
				</br></br>
				
				<input id="salvar_edicao" name="salvar_edicao" type="submit" style="display:none" class="fonte"/>
				
			</form>
			'; 
			
			if(isset($_SESSION["id"])){ 
			
				echo '
				
				
				
				<button id="'.$_GET["lj"].'" class="fonte" onclick="excluir_loja(this);">Excluir</button>
				<button id="bt_editar_lj" class="fonte" onclick="editar_loja();">Editar</button>
				<button id="bt_salvar_lj" class="fonte" style="display:none" onclick="salvar_editar_loja()">Salvar</button>
				';
			
			}
			
		}
		
	}
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


function pesquisar($texto_pesquisa){
	
	include "sql/conect_sem_session.php";
	
	$sql = "SELECT * FROM tb_lojas WHERE `lj_loja` LIKE '%$texto_pesquisa%' OR `lj_cidade` LIKE '%$texto_pesquisa%' OR `lj_status` LIKE '%$texto_pesquisa%' OR `lj_ip` LIKE '%$texto_pesquisa%'";

	//$sql = "SELECT * FROM tb_lojas ORDER BY lj_status DESC";
	$res = mysqli_query($con, $sql) or die (mysqli_error($con));
	
	while ($reg = mysqli_fetch_array($res)){
		
		$lj = $reg['lj_loja'];
		$cidade = $reg['lj_cidade'];
		$status = $reg['lj_status'];
		$servidor = $reg['lj_servidor'];
		$circuito = $reg['lj_circuito'];
		$lat = $reg['lj_lat'];
		$lng = $reg['lj_lng'];
		
		if( $reg['lj_tempo_fora'] != NULL){
			
			
			$dataFuturo = "".date("d-m-Y H:i:s");
			$dataAtual = $reg['lj_tempo_fora'];

			$date_time  = new DateTime($dataAtual);
			$diff       = $date_time->diff( new DateTime($dataFuturo));
			$lj_tempo_fora = $diff->format('%dd %Hh %im %ss');
			//$lj_tempo_fora = $diff->format('%Y ano(s), %m mês(s), %d dia(s), %H hora(s), %i minuto(s) e %s segundo(s)');

			
			
			
		}else{$lj_tempo_fora = "-";}
		
		
		if($status == "Normal"){
			echo '
			
			<tr id="'.$lj.'" class="lojas" onclick="mostrar_info(this)">
				
				<td>
					<div id="pinguelo_'.$lj.'" class="pinguelo masc"></div>
					'.$lj.'
				</td>
				<td>
					'.$cidade.'
				</td>
				<td>
					'.$status.'
				</td>
				<td>
					'.$lj_tempo_fora.'
				</td>

			</tr>
		
			<script type="text/javascript"> 
				add_marca('.$lat.' , '.$lng.', "Loja'.$lj.' - '.$cidade.'", "","on"); 
			</script>';
			
			
			
		}else{
			 
			echo '
			
			<tr id="'.$lj.'" class="lojas" onclick="mostrar_info(this)" style="background:#FF7070">
				
				<td>
					<div id="pinguelo_'.$lj.'" class="pinguelo masc"></div>
					'.$lj.'
				</td>
				<td>
					'.$cidade.'
				</td>
				<td>
					'.$status.'
				</td>
				<td>
					'.$lj_tempo_fora.'
				</td>

			</tr>
			
			<script type="text/javascript"> 
				add_marca('.$lat.' , '.$lng.', "Loja'.$lj.' - '.$cidade.'", "","off");
			</script>
			
			';
		}	
	}
}


function verificar_existe($lj){
	
	include "sql/conect_sem_session.php";
	
	$sql="SELECT * FROM tb_lojas WHERE lj_loja='$lj'";
	$result = mysqli_query($con, $sql) or die (mysqli_error($con));
	$linha=mysqli_affected_rows($con);
	
	if($linha>0){
		
		echo false;
		
	}else{
		
		echo true;
	}
	
	
}
?>