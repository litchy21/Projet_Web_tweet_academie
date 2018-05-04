<?php
include "./Model/DB.php";
require_once("Model/Search.php");

class displayN extends DB {
	public function pseudo(){
		$this->query("SELECT displayName FROM user ORDER BY displayName");
		$this->execute();
		$req = $this->fetch_all();
		foreach ($req as $value) {
			echo "<option>". "@".$value["displayName"]."</option>";
		}

	}
	public function aa($destinataire, $message){
		$this->query("SELECT idUser FROM user WHERE displayName = :displayName ");
		$this->bind(':displayName', $destinataire);
		$this->execute();
		$destinataire = $this->fetch_all();
		$destinataire = $destinataire[0]['idUser'];
		$this->query("INSERT INTO message(idSender, idReceiver, msgContent) VALUES ('".$_SESSION['idUser']."','" . $destinataire . "','" . $_POST['message'] ."')");

		$this->execute();
	}
	public function seemessage($Me){
		$receiver = $this->query("SELECT
		 message.msgCreationDate AS creation, 
		 message.msgContent AS content, 
		 user.displayName AS pseudoReceiver 
		  FROM message 
			LEFT JOIN user ON user.idUser = message.idReceiver
			WHERE idReceiver = :idReceiver OR idSender = :idSender");
		$this->bind(':idReceiver', $Me);
		$this->bind(':idSender', $Me);
		$this->execute();
		$reqReceiver = $this->fetch_all();

		$sender = $this->query("SELECT
		 message.msgCreationDate AS creation, 
		 message.msgContent AS content, 
		 user.displayName AS pseudoSender 
		  FROM message 
			LEFT JOIN user ON user.idUser = message.idSender
			WHERE idReceiver = :idReceiver OR idSender = :idSender");
		$this->bind(':idReceiver', $Me);
		$this->bind(':idSender', $Me);
		$this->execute();
		$reqSender = $this->fetch_all();
		$tableau = "";
		$i = 0;
		foreach ($reqSender as $valueSender) {
			$receiver = $reqReceiver[$i]["pseudoReceiver"];
			$tableau .= "<tr><td>" . "@".$valueSender["pseudoSender"] . "</td><td>@" . $receiver ."</td><td>" . $valueSender['creation'] . "</td><td>" . $valueSender['content'] . "</td></tr>";
			$i++;
		}
		echo $tableau;
	}
}
$displayN = new displayN();

if (isset($_POST['search'])) {
	if ($_POST['search'][0] !== '@') {
		echo "La recherche doit être par pseudo valide.";
	}
	else {
		$displayN = new Search();
		$displayName = $displayN->search_member($_POST['search']);
		if ($displayName != null) {
			header("Location: index.php?controller=profile&displayName=".$displayName['displayName']);
		}else{
			echo "Cet utilisateur n'existe pas";
		}	
	}
}

if(isset($_POST["send_mess"])){
	if (isset($_POST["destinataire"], $_POST["message"])) {
		if(!empty($_POST["destinataire"]) && !empty($_POST["message"])){
			$destinataire = substr(htmlspecialchars($_POST["destinataire"]), 1);
			$message = htmlspecialchars($_POST["message"]);
			$displayN->aa($destinataire, $message);

			$error = "Votre message a bien été envoyé :) ";
		}
		else {
			$error = "Vous n'avez pas remplis tous les champs";
		}
	}
	else{
		$error = "Veuillez completer tous les champs";
	}
}


?>