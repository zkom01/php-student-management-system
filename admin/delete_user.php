<?php
    session_start(); // spustíme session pro ukládání hlášek o úspěchu nebo chybě
    require '../classes/Auth.php';
    require '../classes/Database.php';
    require '../classes/UserDB.php';
    require '../classes/Url.php';
    require '../classes/PhotoDB.php';

    Auth::requireSuperAdmin();

    $dbClass = new Database();
    $conn = $dbClass->connectionDB();
    
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // získáme a validujeme ID uživatele z URL


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
    
    $oneUser = UserDB::infoUser($conn, $id);
    $all_images = PhotoDB::allImgByUser($conn, $id);


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        foreach ($all_images['data'] as $image) {
            $image_id = $image['image_id'];
            $user_id = $image['user_id'];
            $image_name = $image['image_name'];

            // Sestavení cesty k souboru
            $image_path = "../uploads/" . $user_id . "/" . $image_name;

            // Volání vaší existující metody
            PhotoDB::deleteImg($conn, $image_id, $image_path);
        }

        $result = UserDB::deleteUser($conn, $id); // zavoláme funkci pro smazání uživatele z databáze a uložíme výsledek do proměnné $result
        
        Url::flashMessage($result,'error');
        Url::redirectUrl("../admin/all_users.php"); // přesměrujeme na stránku se seznamem uživatelů
        exit(); // ukončíme skript, aby se zabránilo dalšímu vykonávání kódu po přesměrování
    }
?>

<?php 
    $pageTitle = "Smazání uživatele"; // Tady definujete název stránky, který se zobrazí v záložce prohlížeče
    require '../assets/header_admin.php'; ?>

<main>
    <section class='main_heading'>
        <h1>Smazání uživatele</h1>
    </section>
 
    <section class="one_student_card">
            <div class="text_container">
                <?php if (is_array($oneUser) && $oneUser['success']):?>
                <?php $u = $oneUser['data']; // Pomocná proměnná pro kratší kód ?>
            
                <h2><?= htmlspecialchars($u['first_name']) . " " . htmlspecialchars($u['second_name']) ?></h2>
                <p>E-mail: <?= htmlspecialchars($u['email']) ?></p>
                <p>Role: <?= htmlspecialchars($u['role'] ?? 'uživatel') ?></p>
            </div>
            
            <form method="post">
                <section class="buttons-container">
                    <a href="../admin/one_user.php?id=<?= $id ?>" class="btn btn-primary">Ne, zpět</a>
                    <button type="submit" class="btn btn-secondary">Ano, smazat</button>
                </section>
            </form>
            
            
        <?php else: ?>
            <h2><?= htmlspecialchars($oneUser['message']) ?></h2>
                <section class="buttons-container">
                    <a href="../admin/all_users.php" class="btn btn-primary">Zpět na seznam uživatelů</a>
                <section>
        <?php endif ?>
    </section>

</main>

<?php require '../assets/footer.php'; ?>
