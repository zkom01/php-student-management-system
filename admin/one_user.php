    <?php
        session_start(); // pro přístup k $_SESSION
        require '../classes/Auth.php';
        require '../classes/Database.php';
        require '../classes/UserDB.php'; 
        require '../classes/Url.php';

        Auth::requireSuperAdmin();

        $dbClass = new Database();
        $conn = $dbClass->connectionDB();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // získáme a validujeme id z URL
        if (!$id || $id <= 0) {
            Url::flashMessage('Neplatné ID uživatele.', 'error');
            Url::redirectUrl('../admin/all_users.php');
            exit;
        }
        $oneUser = UserDB::infoUser($conn, $id);
        
        if ((!$oneUser['success'])) {
            Url::flashMessage($oneUser['message'],'error');
        }

    ?>

    <?php 
        $pageTitle = "Detail uživatele";
        require '../assets/header_admin.php'; ?> <!-- přidáme hlavičku stránky -->

    <?php require '../assets/flash_message.php'; ?> <!-- přidáme soubor pro zobrazení hlášek -->

    <main>
        <section  class='main_heading'>
            <h1>Informace o&nbsp;uživateli</h1>
        </section>

        <section class="one_student_card">
            <div class="text_container">
                <?php if (is_array($oneUser) && $oneUser['success']): ?>
                    <?php $u = $oneUser['data']; // Pomocná proměnná pro kratší kód ?>
                    
                    <h2><?= htmlspecialchars($u['first_name']) . " " . htmlspecialchars($u['second_name']) ?></h2>
                    <?php if ($u['role'] != "super_admin"):?>
                        <p>E-mail: <?= htmlspecialchars($u['email']) ?></p>
                    <?php endif ?>
                    <p>Role: <?= htmlspecialchars($u['role'] ?? 'uživatel') ?></p>
                    
                <?php endif ?>
            </div>
    
    <?php if (is_array($oneUser) && $oneUser['success']): ?>
        <section class="buttons-container">
            <?php if ($_SESSION['role_user_log_in'] === "super_admin" && $u['role'] != "super_admin"):?>
                <a href="edit_user.php?id=<?= $id ?>" class="btn btn-primary">Upravit uživatele</a>
                <a href="delete_user.php?id=<?= $id ?>" class="btn btn-secondary">Smazat uživatele</a>
            <?php endif ?>
            <a href="all_users.php" class="btn btn-primary">Seznam uživatelů</a>
        </section>
            <?php else: ?>
                <h2><?= htmlspecialchars($oneUser['message']) ?></h2>
                <section class="buttons-container">
                    <a href="all_users.php" class="btn btn-primary">Zpět na seznam uživatelů</a>
                </section>
            <?php endif ?>
        </section>

    </main>

    <?php require '../assets/footer.php'; ?> <!-- přidáme patičku stránky -->