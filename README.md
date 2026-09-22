# Piwniczka - System Zarządzania Domowymi Przetworami

Aplikacja oparta na frameworku **Symfony**, zaprojektowana do zarządzania zapasami w domowej spiżarni. Projekt łączy klasyczne operacje CRUD ze statystykami, historią zdarzeń i autorskim mechanizmem synchronizacji bazy danych offline.

## Stos Technologiczny
- **Backend:** Symfony 7, PHP 8, Doctrine ORM
- **Baza Danych:** SQLite (wybrana ze względu na środowisko "offline")
- **Frontend:** Twig, Bootstrap 5, Vanilla JavaScript, CSS

## Główne Funkcjonalności
* **Zarządzanie Zapasami (CRUD):** Dodawanie, edytowanie i modyfikowanie przetworów. Zastosowano mechanizm miękkiego usuwania (soft-delete) za pomocą modyfikacji statusu.
* **Agregacja Danych (Dashboard):** Kolorowy, blogowy pulpit wyświetlający aktualny stan spiżarni (QueryBuilder użyty m.in. do kwerend `GROUP BY`).
* **System Audytu (Dziennik Zdarzeń):** Zaawansowane logowanie wykorzystujące relację Wiele-do-Wielu (ManyToMany), pozwalające przypisać jedną notatkę z audytu (np. "Zrobiono dżemy owocowe") do wielu słoików jednocześnie.
* **Autorska Karuzela i Modale:** Moduł wyświetlania ostatnich zdarzeń napisany w czystym JavaScript (bez zewnętrznych bibliotek dla karuzeli) oraz wykorzystanie Symfony Flash Messages do obsługi wyskakujących okien po zapisie do bazy.

## Autorski system synchronizacji bazy (Git Sync)
Z uwagi na specyfikę środowiska (piwnica, telefony bez zasięgu, praca z plikiem binarnym `.db`), aplikacja posiada **własne komendy konsolowe** rozwiązujące problem konfliktów Gita dla baz SQLite:
* `php bin/console app:db-dump` - eksportuje lokalną bazę do czytelnego pliku tekstowego `dump.sql` ułatwiając commitowanie.
* `php bin/console app:db-load` - odtwarza działającą bazę `.db` na nowym urządzeniu (np. komputerze) bezpośrednio z pliku SQL.

## Instrukcja Uruchomienia

1. Sklonuj repozytorium i przejdź do folderu:
```bash
git clone <adres-repozytorium>
cd symfony-first
```

2. Zainstaluj niezbędne pakiety PHP:
```bash
composer install
```

3. Załaduj demonstracyjną strukturę bazy danych z dołączonego zrzutu (korzystając z CLI stworzonego w projekcie):
```bash
php bin/console app:db-load
```
*(W przypadku braku narzędzia sqlite3 w środowisku, wygeneruj czystą bazę komendą: `php bin/console doctrine:migrations:migrate`)*

4. Uruchom wbudowany serwer PHP (lub użyj Symfony CLI):
```bash
php -S localhost:8000 -t public
```

5. Aplikacja będzie dostępna pod adresem: [http://localhost:8000](http://localhost:8000)
