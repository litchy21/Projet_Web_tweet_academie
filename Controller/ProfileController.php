<?php
require_once('Controller/Controller.php');
require_once('Model/Profile.php');
require_once ('Model/User.php');
require_once("Model/Search.php");

class ProfileController extends Controller {

	public function index($show, $fullName, $displayName, $mail, $theme, $registrationDate){
		return $this->view('profile', [
            'show' => $show,
			'fullName' => $fullName,
			'displayName' => $displayName,
			'mail' => $mail,
			'theme' => $theme,
			'registrationDate' => $registrationDate,
            // 'idUrlAvatar' => $_SESSION['idUrlAvatar'],
        ]);
	}
    public function updatemail($updatemail, $mail){
        $profile = new Profile();
        $profile->updatemail($updatemail, $mail);
    }
    public function updatepassword($updatepassword, $mail){
        $profile = new Profile();
        $profile->updatepassword($updatepassword, $mail);
    }
    // public function addAvatar($avatar, $mail){
    //     $profile = new Profile();
    //     $profile->addAvatar($avatar, $mail);
    // }
}

$profileController = new ProfileController();
$profile = new Profile();
$user = new User();
// if(isset($_POST['upload'])){
//     $profileController->addAvatar($_FILES['avatar'], $_SESSION['mail']);
//         $user->getInfos($_SESSION['mail']);
//     }

/*if (isset($_POST['avatar'])) { 
} else{
   echo "La photo n'as pas pu être téléchargée !";
}

if(isset($_POST['submit'])){
    $file = $_FILES['avatar'];
    $fileName = $_FILES['avatar']['name'];
}
if(isset($_FILES['avatar']) AND !empty($_FILES['avatar'])){
    $tailleMax = 2097152;
    $extensionValides = array('jpg', 'jpeg', 'gif', 'png');
    if ($_FILES['avatar']['size'] <= $tailleMax){
       $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        $profileController->addAvatar($_POST['avatar'], $_SESSION['mail']);
        $user->getInfos($_SESSION['mail']);
   }
}*/

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

if (isset($_POST['updatemail'])) {
    if (!empty($_POST['updatemail'])) {
        if (filter_var($_POST['updatemail'], FILTER_VALIDATE_EMAIL)) {
            if ($profile->checkmail($_POST['updatemail']) == true) {
                $profileController->updatemail($_POST['updatemail'], $_SESSION['mail']);
                $user->getInfos($_POST['updatemail']);
            }
        }
        else{
            echo "Email incorrect!";
            return false;
        }
    }else{
        echo "Veuillez rentrer une adresse mail";
        return false;
    }
}

if (isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['confirm_pass'])) {
    if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
        if (hash('ripemd160', $_POST['old_pass']) == $_SESSION['password']) {
            if ($_POST['new_pass'] == $_POST['confirm_pass']) {

                $_POST['new_pass'] = hash('ripemd160', $_POST['new_pass']);
                $profileController->updatepassword($_POST['new_pass'], $_SESSION['mail']);
                $user->getInfos($_SESSION['mail']);
            }
            else{
                echo "les deux mot de passe ne correspondent pas !!!";
                return false;
            }
        }
        else{
            echo "Vous avez mis un mauvais mot de passe";
            return false;
        }  
    }
    else{
        echo "Veuillez rentrer un mot de passe";
        return false;
    }
}

if (isset($_GET['displayName']) &&  $_GET['displayName'] != null) {
    $profile = $user->getUserInfos($_GET['displayName']);
    if (isset($profile[0]['fullName'])) {
         $show = false;
        $profileController->index($show, $profile[0]['fullName'], $profile[0]['displayName'], $profile[0]['mail'], $profile[0]['theme'], $profile[0]['registrationDate']);
    }else{
        $show = true;
        $profileController->index($show, $_SESSION['fullName'], $_SESSION['displayName'], $_SESSION['mail'], $_SESSION['theme'], $_SESSION['registrationDate']);
    }
}else{
    $show = true;
    $profileController->index($show, $_SESSION['fullName'], $_SESSION['displayName'], $_SESSION['mail'], $_SESSION['theme'], $_SESSION['registrationDate']);
}

?>