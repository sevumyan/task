# Task Tracker API

REST API для управления задачами команды разработчиков, построенное на Laravel с использованием CQRS архитектуры и типизированных исключений.

## 🚀 Особенности

- **CQRS архитектура** - разделение команд и запросов
- **Типизированные исключения** - все операции с моделями через `Model::query()` с проверками
- **DTO паттерн** - строгая типизация данных
- **Queue Jobs** - асинхронные уведомления менеджерам
- **Автоматическая бизнес-логика** - high priority задачи, автоназначение менеджеров
- **Валидация** - через FormRequest классы
- **API Resources** - консистентное форматирование ответов

## 📋 Функциональность

### API Endpoints
- `GET /api/tasks` - список задач с фильтрацией и пагинацией
- `POST /api/tasks` - создание задач с автоматической логикой
- `GET /api/tasks/{id}` - детали задачи с комментариями
- `PUT /api/tasks/{id}/status` - изменение статуса с уведомлениями
- `POST /api/tasks/{id}/comments` - добавление комментариев

### Artisan команды
- `php artisan tasks:check-overdue` - проверка просроченных задач (>7 дней)

### Бизнес-правила
- **High priority** задачи автоматически получают статус `in_progress`
- **Автоназначение менеджера** при отсутствии `user_id`
- **Автокомментарий** при смене статуса на `completed`
- **Запрет комментариев** к `cancelled` задачам
- **Уведомления менеджерам** при создании high priority задач и смене статусов

### Клонирование репозитория
```bash
git clone git@github.com:sevumyan/task.git
cd task
```

### Установка зависимостей
```bash
composer install
```

### Настройка окружения
```bash
# Скопировать конфигурационный файл
cp .env.example .env

# Сгенерировать ключ приложения
php artisan key:generate
```

### Настройка базы данных
Отредактируйте `.env` файл:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_tracker
DB_USERNAME=user
DB_PASSWORD=password
```

### Миграции и сидеры
```bash
# Создать базу данных
php artisan migrate

# Заполнить тестовыми данными (опционально)
php artisan db:seed
```

### Настройка очередей
```bash
# Создать таблицы для очередей
php artisan queue:table
php artisan migrate

# Запустить обработчик очередей (в отдельном терминале)
php artisan queue:work
```

## 🏃‍♂️ Запуск

### Сервер разработки
```bash
php artisan serve
```
API будет доступно по адресу: http://localhost:8000/api

### Фоновые задачи
```bash
# В отдельном терминале
php artisan queue:work
```

## 📊 Тестовые данные

После выполнения `php artisan db:seed` создаются:

**Пользователи:**
- John Manager (manager@example.com) - Менеджер
- Alice Developer (developer@example.com) - Разработчик  
- Bob Tester (tester@example.com) - Тестировщик

**Задачи:**
- "Urgent Bug Fix Required" (high priority, in_progress)
- "Update Documentation" (normal priority, new)
- "Legacy Code Refactoring" (low priority, in_progress, просрочена)
- "Setup Testing Environment" (normal priority, completed)

## 🧪 Тестирование

### Примеры API запросов

#### Получить список задач
```bash
curl "http://localhost:8000/api/tasks"
```

#### Создать задачу с высоким приоритетом
```bash
curl -X POST "http://localhost:8000/api/tasks" \
     -H "Content-Type: application/json" \
     -d '{
         "title": "Critical Security Fix",
         "description": "Fix SQL injection vulnerability", 
         "priority": "high"
     }'
```

#### Изменить статус задачи
```bash
curl -X PUT "http://localhost:8000/api/tasks/1/status" \
     -H "Content-Type: application/json" \
     -d '{
         "status": "completed",
         "user_id": 2
     }'
```

### Проверка просроченных задач
```bash
# Показать просроченные задачи без изменений
php artisan tasks:check-overdue --dry-run

# Обработать просроченные задачи
php artisan tasks:check-overdue
```

## 🏗️ Архитектура

### CQRS Pattern
```
app/Http/Services/Task/
├── Command/           # Команды (создание, обновление)
│   ├── CreateTaskCommand.php
│   ├── UpdateTaskStatusCommand.php
│   └── CreateTaskCommentCommand.php
├── Query/            # Запросы (чтение данных)
│   ├── IndexTaskQuery.php
│   └── ShowTaskQuery.php
└── TaskService.php   # Единая точка входа
```

### Типизированные исключения
```
app/Exceptions/
├── Task/
│   ├── TaskNotFoundException.php
│   ├── TaskCreationFailedException.php
│   ├── TaskUpdateFailedException.php
│   ├── TaskCommentCreationFailedException.php
│   └── InvalidTaskStatusException.php
└── User/
    ├── UserNotFoundException.php
    └── ManagerNotFoundException.php
```

### DTO Pattern
```
app/Http/Services/Task/*/Dto/
├── CreateTaskDto.php
├── UpdateTaskStatusDto.php
├── CreateTaskCommentDto.php
├── IndexTaskDto.php
└── ShowTaskDto.php
```

## 📝 API Документация

### Коды ответов
- **200** - Успешный запрос
- **201** - Ресурс создан
- **400** - Нарушение бизнес-правил
- **404** - Ресурс не найден
- **422** - Ошибка валидации

### Структура ошибок
```json
{
    "message": "Task with ID 999 not found",
    "type": "E_TASK",
    "code": "E_TASK_NOT_FOUND",
    "status": 404
}
```

### Фильтрация задач
- `status` - new, in_progress, completed, cancelled
- `priority` - high, normal, low  
- `user_id` - ID пользователя
- `page` - номер страницы
- `per_page` - количество на странице

## 🔧 Разработка

### Структура проекта
- `app/Cqrs/` - CQRS архитектура
- `app/Exceptions/` - Типизированные исключения
- `app/Http/Services/` - Бизнес-логика
- `app/Jobs/` - Фоновые задачи
- `app/Console/Commands/` - Artisan команды
