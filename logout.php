<?php

//Sair do sistema

	include "sql/conect.php";
	unset($_SESSION[""]);
	session_destroy();
	mysqli_close($con);
	header("Location:index.php");

?>