<?php

	date_default_timezone_set("America/Sao_Paulo");
	setlocale(LC_ALL, 'pt_BR');
	

	include "sql/conect.php";
	include "inc/msg.php";
	

	
	if(isset($_GET["dialog"])){
		
		switch($_GET["dialog"]){
			
			case "erro_logar":
				erro("Erro ao tentar logar");
			break;	
			
			case "erro_vazio":
				erro("Campo vazio, tente novamente");
			break;
			case "erro_cadastro":
				erro("Erro ao cadastrar, tente novamente");
			break;
			case "erro_chamado":
				erro("Erro ao salvar chamado, tente novamente");
			break;
			case "erro_nova_loja":
				erro("Erro ao cadastrar nova loja");
			break;
			case "loja_removida":
				info("Loja removida");
			break;
			case "erro_editar_loja":
				info("Erro ao editar loja");
			break;
		}
		
	}
	
	
		
	if(isset($_SESSION["id"])){
		
		$cod = $_SESSION["id"];
		$sql = "SELECT * FROM tb_login WHERE lg_id='$cod'";
		$res = mysqli_query($con, $sql) or die (mysqli_error($con));
		
		while($reg = mysqli_fetch_array($res)){
			
			$usuario = $reg['lg_usuario'];
			
			
		}
		
	}
	
	

?>
<html>
<head>
  <meta charset="utf-8">
  <title>MACAVI - Monitoramento da Rede</title>
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript" src="js/gmaps.js"></script>
  <script type="text/javascript" src="js/jquery-3.1.0.min.js" ></script>
  <script type="text/javascript" src="js/jquery.maskedinput.min.js" ></script>
  <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />

  <script type="text/javascript">
    var map;
    $(document).ready(function(){

		//Iniciando o Mapa
		map = new GMaps({
			div: '#map',
			lat: -5.5363404,
			lng: -40.7731445,
			zoom:7
		});	
	
		//Verificar se eu concigo unir esses dois
		listar();   
		auto_atualizacao();
	   
    });
	
	$(function(){
		//Mascarando formulario
		$("#lj_cep").mask("99999-999");
		$("#lj_cnpj").mask("99.999.999/9999-99");
		$("#lj_claro").mask("(99)99999-9999");
		$("#lj_fone").mask("(99)9999-9999");
		$("#lj_circuito").mask("aaa 9999999");
	});
	
	function logar(){
	
		$('.masc').hide();
	
		$('#mascara').css({'width':'100%', 'height':"100%", 'background-color':'#000'});
		$('#mascara').fadeIn(500);
		$('#mascara').fadeTo('fast',0.6);
	
		$('#login').show();
		
		$('#mascara').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
		$('#fechar_login').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
	
	}
		
	function cadastrar(){
	
		$('.masc').hide();
		
		$('#mascara').css({'width':'100%', 'height':"100%", 'background-color':'#000'});
		$('#mascara').fadeIn(500);
		$('#mascara').fadeTo('fast',0.6);
	
		$('#cadastrar').show();
		
		$('#mascara').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
		$('#fechar_cadastro').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
	
	}


	
	function auto_atualizacao(){

			listar();
			
			setTimeout(function(){auto_atualizacao();},60000); 

	}

	function add_loja(){
		
		map.setContextMenu({
        control: 'map',
        options: [{
          title: 'Add Loja',
          name: 'add_loja',
          action: function(e){
            console.log(e.latLng.lat());
            console.log(e.latLng.lng());
			mostrar_nova_loja();
			$("#lj_lat").val(e.latLng.lat());
			$("#lj_lng").val(e.latLng.lng());

            this.hideContextMenu();
          }
        }]
      });
	}
	
	function add_marca(lat, lng, ljcidade, html, status){
		
	
		if(status=="on"){
			
			image = "img/ic_macavi_25.png";
		
			map.addMarker({
				lat: lat,
				lng: lng,
				title: ljcidade,
				infoWindow: {
				content: html
				},
				icon: image
			});
			
		}else{
			
			image = "img/ic_macavi_25_red.png";
			
			map.addMarker({
				lat: lat,
				lng: lng,
				title: ljcidade,
				infoWindow: {
				content: html
				},
				icon: image
			});
			
			
			
		}
	}
	
	function listar(){
		
		$.get('lojas.php', {
			
			acao:'listar'
	
		}, function(retorno){
			
			$("#lista").html(retorno);
			
		});
		
		
	}
	
	
	
	function mostrar_nova_loja(){
		
		$('.masc').hide();
		
		$('#mascara').css({'width':'100%', 'height':"100%", 'background-color':'#000'});
		$('#mascara').fadeIn(500);
		$('#mascara').fadeTo('fast',0.6);
	
		$('#nova_loja').show();
		
		
		$('#mascara').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
		$('#fechar_novaloja').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		

		
	}

	function mostrar_loja(item){
	
		$('.masc').hide();
	
		$("#dados_loja").html('<img width="200px" src="img/loading.gif" alt="carregando"></img>');

			
		$.get('lojas.php', {
			
			acao:'mostrar_loja',
			lj: item.id
			
		}, function(retorno){
			
			$("#dados_loja").html(retorno);
			
		});

		$('#mascara').css({'width':'100%', 'height':"100%", 'background-color':'#000'});
		$('#mascara').fadeIn(500);
		$('#mascara').fadeTo('fast',0.6);

		$('#editar_loja').show();


		$('#mascara').click(function(ev){

			ev.preventDefault();
			$('.masc').hide();

		});

		$('#fechar_editarloja').click(function(ev){

			ev.preventDefault();
			$('.masc').hide();

		});



	}
	
	function mostrar_info(item){

		$('.masc').hide();
		
		var info = $('.info');
		//var pinguelo = $('#pinguelo_'+item.id+'');
		var dados = $('#dados');
		
		$('#mascara').css({'width':'100%', 'height':"100%", 'background-color':'transparent'});
		$('#mascara').show();
		
		//pinguelo.show();
		info.show();
		
		dados.html('<img width="200px" src="img/loading.gif" alt="carregando"></img>');
		
			
		$.get('lojas.php', {
			
			acao:'info',
			lj: item.id
			
		}, function(retorno){
			
			dados.html(retorno);
			
		});
		
		
		
		
		
		$('#mascara').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		
		});
		
		$('#fechar_info').click(function(ev){
		
			ev.preventDefault();
			$('.masc').hide();
		});
		
	}

	function editar_loja(item){
		
		$("#dados_loja input").removeAttr('readonly');
		
		$("#ed_lj_loja").attr('readonly','true');
		$("#ed_lj_lat").attr('readonly','true');
		$("#ed_lj_lng").attr('readonly','true');
		
		$("#bt_editar_lj").hide();
		$("#bt_salvar_lj").show();
			
		//Mascarando formulario
		$("#ed_lj_cep").mask("99999-999");
		$("#ed_lj_cnpj").mask("99.999.999/9999-99");
		$("#ed_lj_claro").mask("(99)99999-9999");
		$("#ed_lj_fone").mask("(99)9999-9999");
		$("#ed_lj_circuito").mask("aaa 9999999");
		
		
	}

	function salvar_editar_loja(){
		
		$("#form_editar_lj").submit();
		
	}
	
	function excluir_loja(lj_loja){
		
		$.post('lojas.php', {
		
			acao:'excluir_loja',
			lj_loja:lj_loja.id,
			
			
		}, function(retorno){
		
			window.location.reload();
		
		});
		
	}
	
	function chave_cadastro(){
		
		if($('#chave').val() =="999076960"){
			
			$('#form_cadastrar').show();
			
		}else{
			
			
			
		}
		
		
	}

	function editar_chamado(lj){
		
		
		if($(".botao_inf_reparo").text() !="Salvar" ){
			
			$(".botao_inf_reparo").text("Salvar");
			$("#selec_prob").show();
			$("#win_chamado tr td input").removeAttr("disabled","");
			$("#win_chamado tr td textarea").removeAttr("disabled","");
			
		}else{
			
			$.post('lojas.php', {
			
				acao:'editar_chamado',
				ch_lj_id:lj.id,
				ch_login:$("#ch_login").text(),
				ch_protocolo:$("#ch_protocolo").val(),
				ch_atendente:$("#ch_atendente").val(),
				ch_data_prev:$("#ch_data_prev").val(),
				ch_hora_prev:$("#ch_hora_prev").val(),
				ch_obs:$("#ch_obs").val(),
				status:$("#selec_prob").val(),
				
				
			}, function(retorno){
			
				mostrar_info(lj);
				listar();
			
			});

		}
		
		
	}
	
	function exibir_selecao(lj){
	
		if($(".botao_inf_reparo").text() !="Salvar" ){
			
			$(".botao_inf_reparo").hide();
			$(".botao_inf_reparo").text("Salvar");
			$("#selec_prob").show();
			
		}else{
			
			$.post('lojas.php', {
			
				acao:'novo_chamado',
				ch_lj_id:lj.id,
				ch_login:$("#ch_login").text(),
				ch_protocolo:$("#ch_protocolo").val(),
				ch_atendente:$("#ch_atendente").val(),
				ch_data_prev:$("#ch_data_prev").val(),
				ch_hora_prev:$("#ch_hora_prev").val(),
				ch_obs:$("#ch_obs").val(),
				status:$("#selec_prob").val(),
				
				
			}, function(retorno){
			
				mostrar_info(lj);
				listar();
			
			});

		}
	}
	
	function selecao_problema(){
		
		$("#win_chamado").empty();
		$(".botao_inf_reparo").hide();
		
		switch($("#selec_prob").val()){
			
			
			
			case "sem_internet":

				$("#win_chamado").append("</br><table>");
				$("#win_chamado").append("<tr><td>PROTOCOLO:</td><td><input id='ch_protocolo' type='text' style='width:100%' maxlength='15'></input></td></tr>");
				$("#win_chamado").append("<tr><td>ATENDENTE:</td><td><input id='ch_atendente' type='text' style='width:100%' maxlength='20'></input></td></tr>");
				$("#win_chamado").append("<tr><td>PREVISÃO:</td><td><input id='ch_data_prev' type='date'></input><input id='ch_hora_prev' type='time'></input></td></tr>");
				$("#win_chamado").append("<tr><td>OBS:</td><td><textarea cols='30' rows='5' id='ch_obs' type='text' style='width:100%' maxlength='255'></textarea></td></tr>");
				$("#win_chamado").append("</table>");
			
			
				$(".botao_inf_reparo").show();
			break;
			
			
			
			case "sem_energia":
			
				
				$("#win_chamado").append("</br><table>");
				$("#win_chamado").append("<tr style='display:none'><td>PROTOCOLO:</td><td><input id='ch_protocolo' type='text' style='width:100%' maxlength='15'></input></td></tr>");
				$("#win_chamado").append("<tr style='display:none'><td>ATENDENTE:</td><td><input id='ch_atendente' type='text' style='width:100%' maxlength='20'></input></td></tr>");
				$("#win_chamado").append("<tr><td>PREVISÃO:</td><td><input id='ch_data_prev' type='date'></input><input id='ch_hora_prev' type='time'></input></td></tr>");
				$("#win_chamado").append("<tr><td>OBS:</td><td><textarea cols='30' rows='5' id='ch_obs' type='text' style='width:100%' maxlength='255'></textarea></td></tr>");
				$("#win_chamado").append("</table>");
			
			
				$(".botao_inf_reparo").show();
	
			
			break;
			
			case "suporte":
				
				
				$("#win_chamado").append("</br><table>");
				$("#win_chamado").append("<tr style='display:none'><td>PROTOCOLO:</td><td><input id='ch_protocolo' type='text' style='width:100%' maxlength='15'></input></td></tr>");
				$("#win_chamado").append("<tr style='display:none'><td>ATENDENTE:</td><td><input id='ch_atendente' type='text' style='width:100%' maxlength='20'></input></td></tr>");
				$("#win_chamado").append("<tr><td>PREVISÃO:</td><td><input id='ch_data_prev' type='date'></input><input id='ch_hora_prev' type='time'></input></td></tr>");
				$("#win_chamado").append("<tr><td>OBS:</td><td><textarea cols='30' rows='5' id='ch_obs' type='text' style='width:100%' maxlength='255'></textarea></td></tr>");
				$("#win_chamado").append("</table>");
			
			
				$(".botao_inf_reparo").show();
			
			break;
			
		}
		
	}

	
	function gerenciador(){
		
		window.open("ping/auto.php", "modal", "width=500px, height=600px, top=50px, left=50%, scrollbars=no ");

	}

	function pesquisar(){
		
		pesquisa = $("#texto_pesquisa").val();
		if(pesquisa!=""){
			$.get('lojas.php', {
				
				acao:'pesquisa',
				tx_pesquisa:pesquisa
				
			}, function(retorno){
				
				$("#lista").html(retorno);
				
			});
		}else{
			
			listar();
			
		}
		
	}
	
	function verificar_existe(){
		
		valor = $("#lj_loja").val();
		
		if(valor!=""){
			
			$.get('lojas.php', {
				
				acao:'verificar_existe',
				lj:valor
				
			}, function(retorno){
				
				if(retorno){
					
					$("#lj_loja").css("border-color","transparent");
					$("#bt_salvar_nova_lj").removeAttr("disabled","");
					$("#alerta").hide();
				}else{
					$("#bt_salvar_nova_lj").attr("disabled","disabled");
					$("#lj_loja").css("border-color","red");
					$("#lj_loja").val("");
					$("#alerta").show();
					$("#alerta").text("Já existe uma loja com esse nome");
				}
				
			});
		}else{
			$("#bt_salvar_nova_lj").attr("disabled","disabled");
			$("#lj_loja").css("border-color","red");
			$("#alerta").show();
			$("#alerta").text("Campo obrigatório");
			
		}
		
	}
	
</script>

</head>

<body>

	
	<?php if(isset($_SESSION["id"])){ 
		
		echo "<span id='ch_login' style='display:none' >".$usuario."</span>";
		echo " <script type='text/javascript'>$(document).ready(function(){add_loja();});</script>";
		
		
		}?>	
		
	<table id="titulo">
		<tr>
			<td><img src="img/logo_macavi.png" ></img></td>
			<td><span style="font-size:30px;" >Monitoramento da Rede </span>
			<?php if(isset($_SESSION["id"])){ echo "<span style='font-size:25px; color:#ddd;' >(".$usuario.")   </span><a href='logout.php' style='color:#ddd;'>sair</a>";}?></td>
			<td align='right'>
			<?php if(isset($_SESSION["id"]) && $_SESSION["id"]==1){ echo "<button onclick='gerenciador();'>Gerenciador</button>";}?></td>
			</td>
			<td align="right"><img onclick="logar()" class="menu" src="img/ic_action_overflow.png" ></img></td>
		</tr>
	</table>
	
	
	<div style="width:98%;">
	
		<div id="map" ></div>

		<div id="visao" >
		
			<div style="background:rgb(55, 55, 55); padding:5px; text-align:center;">
				<input id="texto_pesquisa"type="text" style="width:80%; font-size:15px;" placeholder="Digite sua pesquisa..." onkeyup ="pesquisar()" ></input><img src="img/ic_action_search.png" style="cursor:pointer;" onclick="pesquisar()"></img>
			</div>
			
			<div class="info masc">
				<img id="fechar_info" class="fechar" src="img/ic_action_cancel.png"></img></br>
				<div id="dados"></div>
			</div>
			<div style="height:93%; overflow-y:auto;">
			<table id="lista" cellspacing="0"></table>
			</div>
		</div>
		
		

	</div>
	
	<div id="login" class="masc">
		<img id="fechar_login" class="fechar" src="img/ic_action_cancel.png"></img>
		
		<div style="margin:25%; text-align:center;" >
			
				<?php 
					
					if(isset($_SESSION["id"])){ 
						
						echo "
								<h2 style='color:white' >Você está logado como: '$usuario'</h2>
								<h3 style='color:white'> click <a  href='logout.php' style='color:white'>aqui</a> para sair</h3>
								
							";
								
								
					
					}else{ ?>
					<form  name="formulario" method="post" action="login.php">
						<span class="fonte" style="color:#fff">Login: </span><input type="text" name="login" class="fonte"></input></br></br>
						<span class="fonte" style="color:#fff">Senha: </span><input type="password" name="senha" class="fonte"></input></br></br>
						<input name="entrar" type="submit" value="Entrar" class="fonte"/>
						<span onclick="cadastrar()" style="text-decoration:none; cursor:pointer; color:white;">cadastrar</span>
					</form>
				<?php }?>
			
		</div>
		
	</div>
	
	<div id="cadastrar" class="masc">
		<img id="fechar_cadastro" class="fechar" src="img/ic_action_cancel.png"></img>
		<div style="padding:15%;" >
			<span class="fonte" style="color:#fff">Chave: </span>
			<input id="chave" class="fonte" style="width:100px" type="password"></input>
			<button style="padding:5px;	font-size:20px;	font-family:Roboto,helvetica,arial,sans-serif;" onclick="chave_cadastro()">ir</button></br></br>	

			<form id="form_cadastrar" method="post" action="cadastrar.php">
				
				<span class="fonte" style="color:#fff" >Login: </span><input type="text" name="clogin" class="fonte"></input></br></br>
				<span class="fonte" style="color:#fff" >Senha: </span><input type="password" name="csenha" class="fonte"></input></br></br>
				<input name="criar" style="width:250px;" type="submit" value="criar" class="fonte" />
			</form>
		</div>
	</div>
	
	<div id="nova_loja" class="masc">
		<img id="fechar_novaloja" class="fechar" src="img/ic_action_cancel.png"></img>
		
		
		<form style="margin:5%; text-align:center" name="formulario" method="post" action="nova_loja.php">
				
				<input type="text" id="lj_loja" name="lj_loja" class="fonte" placeholder="Nº" style="width:55px" maxlength="11" onblur="verificar_existe()" ></input>
				<div style="position:absolute; text-align:center; width:170px;"><span id="alerta" style="color:#FFA07A; font-size:10; display:none;"></span></div>
				<input type="text" name="lj_cidade" class="fonte" placeholder="Cidade" style="width:380px; text-transform:capitalize;" maxlength="50"></input>
				<select name="lj_estado" class="fonte" style="width:60px">
						<option value=""></option>
						<option value="CE">CE</option>
						<option value="PI">PI</option>
						
				</select>
				
				</br></br>
				
				<input type="text" name="lj_rua" class="fonte" placeholder="Rua" style="width:440px; text-transform:capitalize;" maxlength="50"></input>
				<input type="text" name="lj_numero" class="fonte" placeholder="Nº" style="width:60px" maxlength="11"></input>
				</br></br>
				
				<input type="text" name="lj_bairro" class="fonte" placeholder="Bairro" style="width:300px; text-transform:capitalize;" maxlength="20"></input>
				<input type="text" id="lj_cep" name="lj_cep" class="fonte" placeholder="CEP" style="width:200px" maxlength="10"></input>
				</br></br>
				
				<input type="text" name="lj_razao" class="fonte" placeholder="Razão" style="width:250px; text-transform:uppercase;" maxlength="60"></input>
				<input type="text" id="lj_cnpj" name="lj_cnpj" class="fonte" placeholder="CNPJ" style="width:250px" maxlength="18"></input>
				</br></br>
				
				<input type="text" id="lj_claro" name="lj_claro" class="fonte" placeholder="Claro Empresarial" style="width:250px;" maxlength="15"></input>
				<input type="text" id="lj_fone" name="lj_fone" class="fonte" placeholder="Telefone" style="width:250px;" maxlength="15"></input>
				</br></br>
				
				<input type="text" name="lj_servidor" class="fonte" placeholder="Nome do Servidor" style="width:300px; text-transform:capitalize;" maxlength="20"></input>
				<input type="text" name="lj_ip" class="fonte" placeholder="IP" style="width:200px" maxlength="20"></input>
				</br></br>
				
				<input type="text" id="lj_circuito" name="lj_circuito" class="fonte" placeholder="Circuito da OI" style="width:500; text-transform:uppercase;" maxlength="20"></input>
				</br></br>
				
				
				<input type="text" id="lj_lat" readonly="true" name="lj_lat" class="fonte" placeholder="latitude" style="width:250px;" maxlength="50"></input>
				<input type="text" id="lj_lng" readonly="true" name="lj_lng" class="fonte" placeholder="longitude" style="width:250px;" maxlength="50"></input>
				</br></br>
				
				<input id="bt_salvar_nova_lj" disabled="disabled" name="salvar" type="submit" value="Salvar" class="fonte"/>
			
		</form>
		
	</div>

	<div id="editar_loja" class="masc">
		<img id="fechar_editarloja" class="fechar" src="img/ic_action_cancel.png"></img>
		<div id="dados_loja" style="margin:5%; text-align:center" >
		</div>
		
	</div>
	
	<div style="background:black; color:white; font-size:14px; padding:5px; position:fixed; bottom:2px;">Por: Edjúnior Nascimento</div>
</body>
<div id="mascara" class="masc"></div>
</html>