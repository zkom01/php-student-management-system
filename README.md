# 🎓 Vysoká škola ZKOM – Webová aplikace

Studentský webový projekt simulující informační systém fiktivní vysoké školy **ZKOM** (Základní komunikace a management). Aplikace umožňuje správu studentů, uživatelských účtů a galerie fotek s plnohodnotným admin rozhraním, autentizací a odesíláním e-mailů.

---

## 📋 Obsah

1. [Technologie](#1-technologie)
2. [Struktura projektu](#2-struktura-projektu)
3. [Databázové schéma](#3-databázové-schéma)
4. [Architektura tříd (classes/)](#4-architektura-tříd-classes)
5. [Funkcionality](#5-funkcionality)
6. [Systém rolí a oprávnění](#6-systém-rolí-a-oprávnění)
7. [JavaScript moduly](#7-javascript-moduly)
8. [Instalace a nastavení](#8-instalace-a-nastavení)
9. [Bezpečnostní opatření](#9-bezpečnostní-opatření)

---

## 1. Technologie

| Vrstva | Technologie |
|---|---|
| Backend | PHP 8.x |
| Databáze | MariaDB 11.4 / MySQL |
| Databázová vrstva | PDO (prepared statements) |
| Frontend | HTML5, CSS3, vanilla JavaScript |
| E-mail | PHPMailer (SMTP/SSL) |
| Session management | PHP Sessions |

---

## 2. Struktura projektu

```
vysoka_skola_zkom/
│
├── index.php                  # Úvodní stránka (přesměruje přihlášeného uživatele)
├── login.php                  # Přihlášení + registrace (přepínání formulářů)
├── contact.php                # Kontaktní formulář s odesíláním e-mailu přes PHPMailer
├── vszkom_DB.sql              # SQL dump – kompletní schéma databáze s testovacími daty
│
├── admin/                     # Chráněná admin sekce (vyžaduje přihlášení)
│   ├── index_admin.php        # Dashboard přihlášeného uživatele
│   ├── all_students.php       # Seznam všech studentů s vyhledáváním
│   ├── one_student.php        # Detail studenta
│   ├── add_student.php        # Formulář pro přidání studenta
│   ├── edit_student.php       # Formulář pro úpravu studenta
│   ├── delete_student.php     # Smazání studenta (POST akce)
│   ├── all_users.php          # Seznam uživatelů (admin+)
│   ├── one_user.php           # Detail uživatele (admin+)
│   ├── add_user.php           # Přidání uživatele (admin+)
│   ├── edit_user.php          # Úprava uživatele (admin+)
│   ├── delete_user.php        # Smazání uživatele (super_admin)
│   ├── photos.php             # Galerie fotek přihlášeného uživatele
│   ├── upload_photo.php       # Nahrání fotky (POST akce)
│   ├── delete_photo.php       # Smazání fotky (POST akce)
│   ├── after_login.php        # Zpracování přihlašovacího formuláře
│   ├── after_registration.php # Zpracování registračního formuláře
│   └── log_out.php            # Odhlášení (zničení session)
│
├── assets/                    # Sdílené komponenty (chráněno .htaccess)
│   ├── configDB.php           # Konfigurace připojení k DB a SMTP
│   ├── header.php             # Hlavička pro veřejné stránky
│   ├── header_admin.php       # Hlavička pro admin sekci
│   ├── footer.php             # Patička
│   ├── flash_message.php      # Zobrazení session flash zpráv
│   ├── email_template.php     # HTML šablona e-mailu (placeholder systém)
│   ├── form_login.php         # Formulář přihlášení
│   ├── form_registration.php  # Formulář registrace
│   ├── form_student.php       # Formulář studenta (add/edit)
│   ├── form_user.php          # Formulář uživatele (edit)
│   ├── form_add_user.php      # Formulář pro přidání uživatele adminem
│   ├── form_contact.php       # Kontaktní formulář
│   └── form_photo.php         # Formulář pro nahrání fotky
│
├── classes/                   # PHP třídy (chráněno .htaccess)
│   ├── Auth.php               # Autentizace a kontrola rolí
│   ├── Database.php           # PDO připojení k databázi
│   ├── CollegeDB.php          # Operace s tabulkou kateder
│   ├── StudentsDB.php         # CRUD operace se studenty
│   ├── UserDB.php             # CRUD operace s uživateli + autentizace
│   ├── PhotoDB.php            # Správa fotek (DB + disk)
│   ├── LogError.php           # Logování chyb do souborů
│   └── Url.php                # Přesměrování a flash zprávy
│
├── css/                       # Styly rozdělené po komponentách
│   ├── general.css            # Globální reset a základní styly
│   ├── header.css             # Navigace a hlavička
│   ├── footer.css             # Patička
│   ├── form.css               # Formuláře
│   ├── buttons.css            # Tlačítka
│   ├── index.css              # Úvodní stránka
│   ├── all_students.css       # Seznam studentů
│   ├── one_student.css        # Detail studenta
│   ├── photos.css             # Galerie fotek
│   ├── message.css            # Flash zprávy
│   ├── warning_text.css       # Varovné texty
│   └── query/                 # Media queries (responzivní design)
│       ├── header_query.css
│       └── form_query.css
│
├── js/                        # Klientské JavaScript skripty
│   ├── header.js              # Hamburger menu (mobilní navigace)
│   ├── filter_all_students.js # Live filtrování seznamu studentů
│   ├── search.js              # Vyhledávání
│   ├── password_checker.js    # Validace shody hesel v reálném čase
│   ├── chose_file.js          # Zobrazení názvu vybraného souboru
│   └── switchForms.js         # Přepínání login/registrace formulářů
│
├── img/                       # Statické obrázky a ikony
├── errors/                    # Logovací soubory (generuje se za provozu)
│   ├── db_errors.log
│   └── oneStudent_errors.log
│
└── vendor/
    └── PHPMailer/             # Knihovna pro odesílání e-mailů přes SMTP
```

---

## 3. Databázové schéma

Databáze `vszkom_DB` obsahuje 4 tabulky:

```
┌──────────┐        ┌─────────┐        ┌───────────┐
│ college  │        │ student │        │   user    │
├──────────┤        ├─────────┤        ├───────────┤
│ id (PK)  │◄───────│ id (PK) │        │ id (PK)   │
│ name     │        │first_name        │first_name │
└──────────┘        │second_name       │second_name│
                    │ age     │        │ email     │
                    │ life    │        │ heslo     │
                    │college_id (FK)   │ role      │
                    └─────────┘        └─────┬─────┘
                                             │
                                      ┌──────▼──────┐
                                      │    image    │
                                      ├─────────────┤
                                      │ image_id(PK)│
                                      │ user_id (FK)│
                                      │ image_name  │
                                      └─────────────┘
```

### Tabulky

| Tabulka | Popis | Klíčové vazby |
|---|---|---|
| `user` | Uživatelé systému s rolemi | – |
| `student` | Studenti školy | `college_id → college.id` (SET NULL on delete) |
| `college` | Katedry a fakulty (12 záznamů) | – |
| `image` | Fotky uživatelů | `user_id → user.id` (CASCADE on delete/update) |

### Role uživatelů (`user.role`)

| Role | Popis |
|---|---|
| `user` | Základní přihlášený uživatel – může spravovat vlastní fotky |
| `admin` | Přístup ke správě studentů a uživatelů |
| `super_admin` | Plný přístup včetně mazání uživatelů |

### Katedry (výchozí data)

| Zkratka | Název |
|---|---|
| KSM | Katedra strategického managementu |
| IIK | Institut interpersonální komunikace |
| FDM | Fakulta digitálního marketingu |
| KOP | Kolej organizační psychologie |
| KKR | Katedra krizového řízení |
| UMS | Ústav mediálních studií |
| FPV | Fakulta projektového vedení |
| KRV | Kolej rétoriky a vyjednávání |
| KLZ | Katedra lidských zdrojů |
| IFK | Institut firemní kultury |
| FDAO | Fakulta datové analytiky v obchodu |
| KEP | Katedra etiky v podnikání |

---

## 4. Architektura tříd (classes/)

Projekt používá vlastní OOP vrstvu. Všechny třídy jsou uloženy v `classes/` a chráněny `.htaccess` před přímým přístupem z prohlížeče.

### `Database`
Zapouzdřuje inicializaci PDO připojení. Při selhání zapíše chybu do logu a přesměruje uživatele.

```php
$dbClass = new Database();
$conn = $dbClass->connectionDB();
```

### `Auth`
Statické metody pro ochranu stránek. Každá admin stránka začíná jedním z těchto volání:

```php
Auth::requireLogin();      // vyžaduje přihlášení
Auth::requireAdmin();      // vyžaduje roli admin nebo super_admin
Auth::requireSuperAdmin(); // vyžaduje roli super_admin
```

### `StudentsDB`
CRUD operace nad tabulkou `student`. Všechny metody jsou statické a přijímají `$conn` jako první parametr.

| Metoda | Popis |
|---|---|
| `allStudents($conn)` | Vrátí všechny studenty seřazené dle příjmení (LEFT JOIN s katedrou) |
| `getOneStudent($conn, $id)` | Vrátí data jednoho studenta včetně názvu katedry |
| `addStudent($conn, ...)` | Přidá nového studenta |
| `editStudent($conn, ...)` | Upraví existujícího studenta |
| `deleteStudent($conn, $id)` | Smaže studenta podle ID |

### `UserDB`
Správa uživatelů včetně autentizace. Hesla jsou ukládána přes `password_hash()` a ověřována přes `password_verify()`.

| Metoda | Popis |
|---|---|
| `addUser($conn, ...)` | Registrace nového uživatele |
| `checkUser($conn, $email, $heslo)` | Přihlášení – ověří e-mail a hash hesla |
| `checkUserbyEmail($conn, $email)` | Kontrola duplicity e-mailu při registraci |
| `infoUser($conn, $id)` | Načte základní údaje uživatele |
| `allUser($conn)` | Seznam všech uživatelů (bez hesel) |
| `editUser($conn, ...)` | Úprava údajů uživatele |
| `deleteUser($conn, $id)` | Smazání uživatele |

### `PhotoDB`
Správa fotek – kombinuje databázové záznamy se soubory na disku.

| Metoda | Popis |
|---|---|
| `addImg($conn, $user_id, $image_name)` | Uloží záznam o fotce do DB |
| `allImgByUser($conn, $user_id)` | Vrátí všechny fotky daného uživatele |
| `getOneImage($conn, $image_id)` | Načte data jedné fotky |
| `deleteImg($conn, $image_id, $image_path)` | Smaže záznam z DB i soubor z disku; odstraní prázdný adresář |

### `CollegeDB`
Jednoduchá třída pro načtení seznamu kateder (používá se v select boxech formulářů).

```php
$colleges = CollegeDB::allColleges($conn); // vrátí [['id' => 1, 'name' => '...'], ...]
```

### `LogError`
Systémové logování chyb do souborů v `errors/`. Každý záznam obsahuje timestamp, IP adresu a URL.

```php
LogError::logError("Popis chyby", 'db_errors'); // zapíše do errors/db_errors.log
```

### `Url`
Pomocná třída pro přesměrování a flash zprávy (ukládané do `$_SESSION`).

```php
Url::flashMessage("Uloženo úspěšně.", "success"); // typy: success / error
Url::redirectUrl("./admin/index_admin.php");
```

---

## 5. Funkcionality

### 🔐 Autentizace
- Registrace s kontrolou duplicity e-mailu a hashováním hesla (`PASSWORD_DEFAULT`)
- Přihlášení s `session_regenerate_id()` pro prevenci Session Fixation útoku
- Odhlášení zničením session (`session_destroy()`)
- Přepínání formulářů login/registrace bez načítání stránky (JavaScript)

### 👥 Správa studentů
- Výpis všech studentů s live filtrováním podle jména (JavaScript)
- Detail studenta s přiřazenou katedrou
- Přidání, úprava a smazání studenta
- Výběr katedry přes dynamicky plněný `<select>` z databáze

### 👤 Správa uživatelů _(admin+)_
- Výpis všech uživatelů
- Přidání uživatele s přiřazením role
- Úprava jména, e-mailu a role
- Smazání uživatele _(pouze super_admin)_

### 🖼️ Galerie fotek
- Každý přihlášený uživatel může nahrávat vlastní fotky
- Fotky jsou ukládány do `uploads/{user_id}/` s unikátním názvem (`uniqid()`)
- Podporované formáty: `jpg`, `jpeg`, `gif`, `webp`, `png`
- Maximální velikost souboru: **9 MB**
- Při smazání fotky se odstraní i soubor z disku; po smazání poslední fotky se smaže i složka uživatele

### ✉️ Kontaktní formulář
- Odesílání e-mailů přes **PHPMailer** (SMTP/SSL, port 465)
- HTML šablona e-mailu se systémem placeholderů (`{{name}}`, `{{email}}`, `{{message}}`, `{{year}}`)
- Validace e-mailu přes `filter_var(..., FILTER_VALIDATE_EMAIL)`
- BCC kopie na administrátorský e-mail
- Prevence opakovaného odeslání při refresh (Post/Redirect/Get vzor)

---

## 6. Systém rolí a oprávnění

| Akce | `user` | `admin` | `super_admin` |
|---|:---:|:---:|:---:|
| Přihlášení a přístup do admin sekce | ✅ | ✅ | ✅ |
| Vlastní galerie fotek | ✅ | ✅ | ✅ |
| Přidání / úprava / smazání studenta | ❌ | ✅ | ✅ |
| Zobrazení seznamu uživatelů | ❌ | ✅ | ✅ |
| Přidání / úprava uživatele | ❌ | ✅ | ✅ |
| Smazání uživatele | ❌ | ❌ | ✅ |

Ochranu zajišťuje třída `Auth` volaná vždy na začátku každého admin souboru. Při neoprávněném přístupu dojde k přesměrování s flash zprávou.

---

## 7. JavaScript moduly

| Soubor | Funkce |
|---|---|
| `header.js` | Hamburger menu pro mobilní navigaci |
| `switchForms.js` | Přepínání formulářů login ↔ registrace na `login.php` |
| `password_checker.js` | Validace shody hesel v reálném čase – blokuje tlačítko submit dokud se hesla neshodují |
| `filter_all_students.js` | Live DOM filtrování seznamu studentů – neprovádí HTTP požadavek, pracuje s již načtenými daty |
| `search.js` | Obecné vyhledávání |
| `chose_file.js` | Zobrazení názvu vybraného souboru v custom file inputu |

---

## 8. Instalace a nastavení

### Požadavky
- PHP 8.0+
- MariaDB 10.x / MySQL 5.7+
- Webový server s podporou PHP (Apache / Nginx)
- SMTP přístup pro odesílání e-mailů

### Postup

**1. Import databáze**
```bash
mysql -u uzivatel -p < vszkom_DB.sql
```

**2. Konfigurace připojení**

Upravte soubor `assets/configDB.php`:
```php
define('DB_HOST', 'localhost');       // adresa databázového serveru
define('DB_NAME', 'vszkom_DB');       // název databáze
define('DB_USER', 'váš_uživatel');    // uživatelské jméno
define('DB_PASS', 'vaše_heslo');      // heslo

define('SMTP_PASS', 'smtp_heslo');    // heslo pro SMTP odesílání e-mailů
```

> ⚠️ **Důležité:** Soubor `configDB.php` obsahuje citlivá data. Nikdy ho nenahrávejte do veřejného repozitáře. Přidejte ho do `.gitignore`.

**3. SMTP konfigurace**

V souboru `contact.php` upravte nastavení PHPMaileru:
```php
$mail->Host     = 'smtp.váš-poskytovatel.cz';
$mail->Username = 'váš@email.cz';
$mail->Port     = 465;  // SSL
```

**4. Oprávnění adresáře pro nahrávání fotek**
```bash
mkdir uploads
chmod 777 uploads
```

**5. Výchozí přihlašovací údaje**

Po importu SQL souboru jsou k dispozici tyto účty:

| E-mail | Role |
|---|---|
| `zkom@zkom.cz` | `super_admin` |
| `edzk@seznam.cz` | `admin` |

> Hesla jsou hashována v databázi. Pro zjištění výchozích hesel kontaktujte autora projektu, nebo si vytvořte nový účet přes registraci a změňte mu roli přes phpMyAdmin.

---

## 9. Bezpečnostní opatření

| Opatření | Implementace |
|---|---|
| SQL Injection | PDO Prepared Statements s `bindValue()` ve všech dotazech |
| Session Fixation | `session_regenerate_id(true)` při přihlášení, odhlášení i neúspěšném pokusu |
| Přímý přístup ke třídám | `.htaccess` v `classes/` a `assets/` blokuje přímý HTTP přístup |
| Hesla | `password_hash(PASSWORD_DEFAULT)` + `password_verify()` |
| XSS | `htmlspecialchars()` při vkládání uživatelského obsahu do e-mailu |
| Upload validace | Kontrola přípony (`pathinfo()`), MIME typu (`getimagesize()`), velikosti (max 9 MB) |
| Autorizace | Každá chráněná stránka volá `Auth::requireLogin()` / `Auth::requireAdmin()` / `Auth::requireSuperAdmin()` |
| Logování chyb | Technické detaily výjimek se ukládají do `errors/*.log`, uživateli se zobrazuje neutrální zpráva |

---

## 📌 Poznámky

- Projekt je navržen jako školní/portfóliová aplikace – před nasazením do produkce doporučujeme provést bezpečnostní audit a přidat CSRF ochranu formulářů.
- Adresář `errors/` se vytváří automaticky při prvním výskytu chyby.
- Flash zprávy jsou uloženy v session a zobrazeny komponentou `assets/flash_message.php` vždy na začátku `<main>`.

---

*Projekt Vysoká škola ZKOM – webová aplikace v PHP s PDO a MariaDB.*