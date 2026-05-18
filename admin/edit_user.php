<?php
    session_start(); // spustíme session pro správu uživatelských relací
    require '../classes/Auth.php';
    require '../classes/Database.php';
    require '../classes/UserDB.php';
    require '../classes/Url.php';


    Auth::requireSuperAdmin();

    $dbClass = new Database();
    $conn = $dbClass->connectionDB();
    
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // získáme a validujeme ID uživatele z URL pro načtení jeho informací do formuláře

     $user_to_action = UserDB::infoUser($conn, $id);
    if ($user_to_action['data']['role'] === 'super_admin') {
        // Nepovolit akci ani jinému super_adminovi
        Url::flashMessage('Nedvolená operace.', 'error');
        Url::redirectUrl('../admin/all_users.php');
        exit;
    }

    if (!$id || $id <= 0) {
        Url::flashMessage('Neplatné ID uživatele.', 'error');
        Url::redirectUrl('../admin/all_users.php');
        exit;
    }
    $oneUser = UserDB::infoUser($conn, $id); // získáme informace o uživateli pro předvyplnění formuláře

    if (is_array($oneUser) && $oneUser['success']) { // pokud se nám podařilo získat informace o uživateli, uložíme je do proměnných pro předvyplnění formuláře
        $first_name = $oneUser['data']['first_name'];
        $second_name = $oneUser['data']['second_name'];
        $email = $oneUser['data']['email'];
        $role = $oneUser['data']['role'];

    } else {
        Url::redirectUrl("../admin/one_user.php?id=" . $id); // přesměrujeme na stránku s detaily uživatele
        exit; // ukončí skript, aby se zabránilo dalšímu vykonávání po přesměrování
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

         // získáme data z formuláře
        $first_name = $_POST['first_name'];
        $second_name = $_POST['second_name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $result = UserDB::editUser($conn, $id, $first_name, $second_name, $email, $role);

        if ($result) {
            Url::flashMessage($result,'success'); // Uložíme do session zprávu o úspěšném přidání studenta, aby se zobrazila na další stránce
            Url::redirectUrl("../admin/one_user.php?id=" . $id); // přesměrujeme na stránku s detaily studenta
            exit; // ukončí skript, aby se zabránilo dalšímu vykonávání po přesměrování
        }
    }
?>

<?php 
    $pageTitle = "Upravit uživatele"; // Tady definujete název stránky, který se zobrazí v záložce prohlížeče
    require '../assets/header_admin.php'; ?>

    <main>
        <section class='main_heading'>
            <h1>Upravit uživatele</h1>
        </section>

        <section class="add_form">
            <?php require '../assets/form_user.php'; ?>
        </section>
    </main>

<?php require '../assets/footer.php'; ?>