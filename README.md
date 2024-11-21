# Zadanie rekrutacyjne

Aplikacja jest małym systemem pozwalającym dodawać faktury (Invoice) do kontrahentów (User). System jest w początkowej fazie rozwoju i pozwala na uruchomienie dwóch poleceń z CLI.

```bash
# tworzenie faktury dla użytkownika user@example.com na kwotę 125,00 zł
bin/console app:invoice:create user@example.com 12500

# pobieranie identyfikatorów faktur, które mają status "new" i ich kwota jest większa od 100,00 zł
bin/console app:invoice:get-by-status-and-amount new 10000
```

## Istniejące założenia biznesowe

- Kwota faktury musi być większa od 0
- Kwoty zapisywane są w groszach

## Do zrobienia

- [x] Rozbuduj encję User dodając do niej parametr pozwalający określić aktywność użytkownika (aktywny/nieaktywny). Stwórz migrację, która utworzy nową kolumnę w bazie danych.
- [x] Dodaj możliwość tworzenia nowego użytkownika z poziomu CLI. Argumentem jest e-mail. Nowy użytkownik powinien utworzyć się ze statusem nieaktywny.
- [x] Po utworzeniu użytkownika powinien zostać wysłany e-mail do tego użytkownika z komunikatem "Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h" - chodzi o wykorzystanie interfejsu \App\Common\Mailer\MailerInterface - nie trzeba tworzyć rzeczywistej wysyłki maila.
- [x] Wprowadź do systemu założenie biznesowe pozwalające tworzyć faktury tylko dla aktywnych użytkowników i napisz testy udowadniające, że tak jest.
- [x] W systemie jest błąd. CLI Command "app:invoice:get-by-status-and-amount" z jakiegoś powodu zwraca wszystkie nowe faktury ignorując argument statusu i kwoty. Znajdź przyczyny i rozwiąż ten problem
- [x] Stwórz CLI Command do pobierania e-maili nieaktywnych użytkowników.

## Uruchomienie aplikacji

Aplikacja posiada konfigurację obrazów docker'owych

### Plik konfiguracyjny .env

Zmień nazwę pliku `.env.example` na `.env`.

```bash
# zbudowanie obrazu i uruchomienie kontenera aplikacji
docker-compose up -d

# lista uruchomionych kontenerów, na liście jest CONTAINER ID
docker ps

# wejście do bash kontenera
docker exec -it {CONTAINER ID} bash
```

### Testy

```bash
bin/phpunit tests/Unit/
```
