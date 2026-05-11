# 🎓 Vysoká škola ZKOM – Informační systém

## 📌 Popis projektu
Webová aplikace pro komplexní správu obsahu vysoké školy. Umožňuje administraci studentů, vysokých škol, uživatelských účtů a komunikaci přes kontaktní formulář.

Projekt je postaven na moderním PHP s důrazem na **objektově orientované programování (OOP)**, bezpečnost a čistotu kódu.

---

## ⚙️ Funkcionalita
*   **Autentizace a autorizace:** Bezpečné přihlášení s rozlišením rolí (User, Admin vs. SuperAdmin).
*   **Správa dat (CRUD):** Plná administrace studentů (včetně nahrávání fotografií), škol a uživatelů.
*   **Dynamický obsah:** Flexibilní správa obsahu webu.
*   **Komunikace:** Kontaktní formulář pro odesílání e-mailů pomocí PHPMailer.
*   **Zabezpečená administrace:** Oddělená část `/admin` s vylepšeným přesměrováním a kontrolou přístupu.

---

## 🧱 Technologie
*   **PHP 8.x (OOP):** Využití jmenných prostorů a čisté architektury tříd.
*   **MySQL / MariaDB:** Databáze s kódováním `utf8mb4` pro plnou podporu diakritiky.
*   **Frontend:** HTML5, CSS3 (Bootstrap 5), JavaScript.
*   **Composer:** Správa závislostí (PHPMailer atd.).

---

## 🚀 Instalace

### 1. Klonování projektu
```bash
git clone https://github.com/tvuj-repozitar.git
cd projekt
```

### 2. Nastavení serveru
*   PHP >= 8.0
*   MySQL / MariaDB
*   Web server (Apache / Nginx)

### 3. Instalace závislostí
```bash
composer install
```

---

## 🗄️ Databáze

### Import databáze
1.  Vytvoř novou databázi (např. `vszkom`).
2.  Importuj aktuální soubor: `vszkom_DB.sql`.

### Nastavení připojení
Uprav přihlašovací údaje v souboru `classes/Database.php` (nebo v `.env`, pokud jej používáš):
```php
DB_HOST=localhost
DB_NAME=vszkom
DB_USER=root
DB_PASS=heslo
```

---

## 🔑 Přístupové údaje (testovací)
| Role | Email | Heslo |
| :--- | :--- | :--- |
| **Super Admin** | `zkom@zkom.cz` | `heslo123` |

*(Doporučeno změnit ihned po první instalaci!)*

---

## 📁 Struktura projektu
```text
/admin      → administrační rozhraní a kontrola přístupu
/assets     → statické soubory (CSS, JS, obrázky, formuláře)
/classes    → PHP třídy (StudentsDB, UserDB, PhotoDB, Auth, atd.)
/uploads    → úložiště pro nahrané fotografie studentů
/vendor     → externí knihovny spravované Composerem
index.php   → hlavní vstupní bod aplikace
login.php   → přihlašovací formulář
```

---

## 🔒 Bezpečnost a refaktoring
Aplikace prošla procesem "profi úklidu", který zahrnuje:
*   **Prepared Statements:** Ochrana proti SQL injection u všech dotazů.
*   **Error Handling:** Chyby se již nevypisují přímo, ale využívají třídu `LogError` pro tichý zápis.
*   **Zpřísněná Validace:** Kritické operace (mazání uživatelů) vyžadují oprávnění `requireSuperAdmin()`.
*   **Bezpečné přesměrování:** Ochrana adresářové struktury pomocí hlaviček v `index.php` souborech.

---

## 📄 Licence
Tento projekt je určen pro studijní účely.

## 👨‍💻 Autor
Zdeněk Komárek

---

### Poznámka:
Vždy mluvíme česky a to vždy. Pokud potřebuješ v README něco změnit nebo doplnit specifické technické detaily, stačí napsat!