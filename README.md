# Mini CRM

Міні-CRM на Laravel 12 для збору заявок із сайту через iframe-віджет, з REST API, Blade-адмінкою, ролями через Spatie Permission, файлами через Spatie Media Library, Swagger-документацією та Feature-тестами.

## Стек

- PHP 8.4 у Docker-оточенні
- Laravel 12
- MySQL 8
- Redis
- Spatie Laravel Permission
- Spatie Laravel Media Library
- L5-Swagger

## Швидкий запуск через Docker

1. Скопіюйте конфіг:

```bash
cp .env.example .env
```

2. Підніміть контейнери:

```bash
docker compose up -d --build
```

3. Встановіть залежності, згенеруйте ключ, запустіть міграції, сідер і симлінк для файлів:

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
docker compose exec app php artisan l5-swagger:generate
```

4. Відкрийте застосунок:

- Віджет: `http://localhost:8080/widget`
- Альтернативний маршрут віджета: `http://localhost:8080/feedback-widget`
- Логін менеджера: `http://localhost:8080/login`
- Swagger UI: `http://localhost:8080/api/documentation`

## Локальний запуск без Docker

Потрібно: PHP 8.4+, Composer, MySQL, Redis.

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan l5-swagger:generate
php artisan serve
```

## Тестові дані

- Менеджер:
  - Email: `manager@mini-crm.test`
  - Пароль: `password`
- Seeder створює:
  - 6 клієнтів
  - 12 заявок
  - вкладення для частини заявок
- Ролі:
  - `manager`
  - `admin`
- Permissions:
  - `tickets.view`
  - `tickets.update`

## Віджет через iframe

```html
<iframe
    src="http://localhost:8080/widget"
    width="100%"
    height="760"
    style="border:0; max-width:720px;"
    loading="lazy"
></iframe>
```

## API

### Створення заявки

`POST /api/tickets`

Приклад через `curl`:

```bash
curl -X POST http://localhost:8080/api/tickets \
  -H "Accept: application/json" \
  -F "name=Olena Koval" \
  -F "phone=+380501234567" \
  -F "email=olena@example.com" \
  -F "subject=Потрібна консультація" \
  -F "message=Опишіть вашу задачу" \
  -F "attachment=@/absolute/path/to/file.pdf"
```

### Статистика заявок

`GET /api/tickets/statistics`

Приклад:

```bash
curl -H "Accept: application/json" http://localhost:8080/api/tickets/statistics
```

Відповідь:

```json
{
  "data": {
    "day": 2,
    "week": 4,
    "month": 5
  }
}
```

## Адмін-частина

- Логін по email/паролю
- Доступ тільки для ролей `manager` або `admin`
- Список усіх заявок
- Фільтрація за:
  - датою
  - статусом
  - email
  - телефоном
- Перегляд деталей заявки
- Завантаження прикріплених файлів
- Зміна статусу заявки

## Базові правила бізнес-логіки

- Телефон валідовано у форматі E.164
- Не більше однієї заявки на добу з одного email або номера телефону
- Файли прикріплюються до заявки тільки через Spatie Media Library
- Статистика заявок рахується Eloquent scopes + Carbon

## Тести

```bash
php artisan test
```

Покриті сценарії:

- створення заявки через API
- денний ліміт по email/телефону
- статистика за день/тиждень/місяць
- логін менеджера
- рольовий доступ до адмінки
- фільтрація заявок
- оновлення статусу
- скачування вкладення

## Swagger

Після генерації документація доступна на:

- `GET /api/documentation`

Повторна генерація:

```bash
php artisan l5-swagger:generate
```

## Архітектурні нотатки

Окремий файл з поясненням архітектурних рішень: [ARCHITECTURE.md](ARCHITECTURE.md)
