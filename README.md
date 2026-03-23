# Promotion App

Laravel 13 + Filament alapú promóciókezelő rendszer.

## Fő funkciók

### Felhasználói oldal
- Regisztráció / bejelentkezés / kijelentkezés - Dashboard-on keresztül elérhető profile - Update-elhető személyes adatok
- Validációk: Név, email, Jelszó és confirm, Telefonszám formátum, Bankszámlaszám formátum.
- Dashboard aktív promóciók listájával
- Promóció részletek megtekintése
- Feltöltés (submission) létrehozása dokumentummal és terméklistával - Adminok az admin-panelen keresztül tudnak
termékeket kezelni (hozzáadni, törölni, edit) - Így egy termék több promóciónál is felhasználható - A rendszer figyeli a beállított időintervallumokat is (Promóció kezdete - vége és feltöltés időszak kezdete - vége - Egy felhasználó egy promócióra csak egyszer jogosult (egy feltöltés engedélyezett) - A rendszer az előre beállított termékek ára alapján számolja a visszafizetés összegét. Státuszok - Feltöltve (submitted), Ellenőrzés alatt (under_review), Elfogadva (approved), Elutasítva (rejected), Fellebbezve (appeald), Kifizetve (paid).
- Saját feltöltések listája és részletei
- Törlés csak `submitted` (Feltöltve) státuszban
- Fellebbezés csak `rejected` (Elutasítva) státuszban, **egyszer** (`appeald_at` alapján)
- Kapcsolatfelvételi űrlap (név/e-mail/telefon automatikusan töltve, nem szerkeszthető)
- Kapcsolatfelvételhez két külön tábla lett létrehozva (contact_messages és answers) -  Így az adminok láthatjék a bejövő
üzeneteket és az admin-panelből tudnak válaszolni azokra (státuszok: Beérkezett és megválaszolt, ezekre szűrni is lehet).

**Feltöltés státuszok**: Feltöltve (submitted), Ellenőrzés alatt (under_review), Információ szükséges (need_data), Elfogadva (approved), Elutasítva (rejected), Fellebbezve (appeald), Kifizetve (paid).
- Email: `admin@example.com`
- Jelszó: `Admin1`

- Amikor egy submission need_data statuszba kerül az Adminnak lehetősége van üzenetet küldeni. A felhasználó email-ben értesül a státusz változásról. Az adott submission-t megnyitva a felhasználó látja az üzenetet és egy info-box adja az információt mit is kell tennie (Update-elni a releváns adatot a profilon keresztül majd rányomni a frissítve gombra). Az Adminok tudnak szűrni az updated statusz-ra is, hogy ismét ellenőrzés alatt státuszba kerülhessen.

- Telepítés (setup)

- Követelmények
PHP >= 8.3
Composer
Adatbázis (MySQL / PostgreSQL / SQLite)

```bash
git clone https://github.com/RoTTeN3013/promotion-app.git
cd promotion-app

composer install
php artisan key:generate
```

- Adatbázis migráció és seed

- .env file-ban az adatbázis csatlakozáshoz szükséges adatok megadása után:

```bash
php artisan migrate --seed
```

- Szerver elindítása

```bash
php artisan serve 
```

- MAIL config példa (.env) - Google smtp

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

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
