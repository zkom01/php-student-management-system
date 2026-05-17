<?php 
    session_start(); // spustíme session pro ukládání hlášek o úspěchu nebo chybě a kontrola přihlášení
    require './classes/Auth.php';
    require './classes/Url.php';

    if(Auth::isLoggedIn()){
        // Url::flashMessage("Jste stále přihlášen.", "success");
        Url::redirectUrl("./admin/index_admin.php"); 
    }

    $pageTitle = "Vysoká škola ZKOM"; // Tady definujete název stránky, který se zobrazí v záložce prohlížeče
    require './assets/header.php'; 
    
?>


<?php require 'assets/flash_message.php'; ?> <!-- přidáme soubor pro zobrazení hlášek -->

    <main class="index">
        <section>
            <img src="./img/logo.png" alt="">
            <h1>Vysoká škola ZKOM</h1>
            <h3>(Základní komunikace a management)</h3>
            <h2>Nepřihlášen</h2>
        </section>
    </main>

<?php require 'assets/footer.php';  ?> <!-- přidáme patičku stránky -->