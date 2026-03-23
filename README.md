# Promotion App

Laravel 13 + Filament alapú promóciókezelő rendszer.

## Fő funkciók

### Felhasználói oldal
- Regisztráció / bejelentkezés / kijelentkezés - Dashboard-on keresztül elérhető profile - Update-elhető személyes adatok
- Validációk: Név, email, Jelszó és confirm, Telefonszám formátum, Bankszámlaszám formátum.
- Dashboard aktív promóciók listájával
- Promóció részletek megtekintése
- Feltöltés (submission) létrehozása dokumentummal és terméklistával - Adminok az admin-panelen keresztül tudnak
termékeket kezelni (hozzáadni, törölni, edit) - Így egy termék több promóciónál is felhasználható - A rendszer figyeli a beállított időintervallumokat is (Promóció kezdete - vége és feltöltés időszak kezdete - vége - Egy felhasználó egy promócuóra csak egyszer jogosult (egy feltöltés engedélyezett) - A rendszer az előre beállított termélkek éára alapján számolja a visszafizetés összegét. Státuszok - Feltöltve (submitted), Ellenőrzés alatt (under_review), Elfogadva (approved), Elutasítva (rejected), Fellebbezve (appeald), Kifizetve (paid).
- Saját feltöltések listája és részletei
- Törlés csak `submitted` (Feltöltve) státuszban
- Fellebbezés csak `rejected` (Elutasítva) státuszban, **egyszer** (`appeald_at` alapján)
- Kapcsolatfelvételi űrlap (név/e-mail/telefon automatikusan töltve, nem szerkeszthető)
- Kapcsolatfelvételhez két külön tábla lett létrehozva (contact_messages és answers) -  Így az adminok láthatjék a bejövő
üzeneteket és az admin-panelből tudnak válaszolni azokra (státuszok: Beérkezett és megválaszolt, ezekre szűrni is lehet).

Adminok és Felhasználók szeparálva lettek adatbázisban is (jobb kezelhetőség, átláthatóság).

### Admin panel (Filament)
- Erőforrások kezelése: Adminok, Felhasználók, Termékek, Promóciók, Feltöltések, Exportok, Kapcsolati üzenetek, Válaszok
- A rendszer több promóciót is tud kezelni (ezek az admin felületen hozhatóak létre illetve editálhatóak)
- Kapcsolati üzenetek szűrése (dátum, státusz), válasz írása modalban
- Válaszok listája admin és dátum szűrőkkel
- Feltöltés státusz módosítás
- Export létrehozás és letöltés - ExportService.php felelős a logikáért OOP elv követése (Dependency Injection).

## E-mail működés

- **Kapcsolati üzenet válasz**: admin válasznál a felhasználó e-mailt kap (`ContactAnswerMail`)
- **Feltöltés státusz változás**: ha admin módosítja a státuszt, a felhasználó e-mailt kap (`SubmissionStatusChangedMail`)

Alapértelmezetten a `MAIL_MAILER=log`, tehát lokálisan a levelek a logba kerülnek (`storage/logs/laravel.log`).

## Validációk (főbb üzleti szabályok)

- Egy felhasználó egy promócióra csak egyszer tölthet fel
- Csak a kiválasztott promócióhoz rendelt termékeket lehet feltölteni
- Vásárlási dátum a promóció időszakán belül kell legyen
- Feltöltés csak a promóció upload időablakán belül lehetséges
- Fellebbezés csak elutasított státuszban és csak egyszer
- Filament űrlapoknál egyedi magyar hibaüzenetek beállítva

## Export működés

- Export az `approved` státuszú feltöltésekből készül
- Kimenet:
	- 100 sorig egy CSV
	- 100 sor felett ZIP, több CSV fájllal
- Az export fájl útvonala az `exports.file_path` mezőben tárolódik, így később újra letölthető admin felületről

## Telepítés (rövid útmutató)

### 1) Követelmények
- PHP 8.3+
- Composer
- MySQL/MariaDB

### 2) Projekt telepítése
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3) `.env` beállítás
Állítsd be legalább ezeket:
- `APP_URL`
- `DB_*` (kapcsolat, adatbázis, felhasználó, jelszó)
- `MAIL_*` (ha nem log mailert használsz)

### 4) Adatbázis + seed
```bash
php artisan migrate
php artisan db:seed
```

Seeder tartalom:
- `AdminSeeder`: admin fiók (admin@example.com - Admin1)
- `UserSeeder`: teszt user (user@example.com - User1)
- `ProductSeeder`: 100 termék
- `SubmissionSeeder`: 400 feltöltés (ha hiányzik, promóciókat is létrehoz)

### 5) Storage link
```bash
php artisan storage:link
```

### 6) Indítás
```bash
php artisan serve
```

## Bejelentkezési adatok seed után

### Admin (Filament)
- URL: `/admin`
- Email: `admin@example.com`
- Jelszó: `Admin1`

### Felhasználó
- URL: `/login`
- Email: `user@example.com`
- Jelszó: `User1`

## Hasznos parancsok

```bash
php artisan migrate:fresh --seed
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
