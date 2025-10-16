# 🧩 Backend Skill Test – Laravel Project

## 📘 Overview

This project is a **modular backend API** built using **Laravel 11+**, designed to demonstrate best practices in backend architecture.  
It covers database design, authentication, role-based permissions, API resource structure, middleware, service layer separation, notifications, queues, and caching.

---

## 🚀 Key Features

- **Authentication System** for Admins & Users (multi-guard)
- **Role & Permission Management** using [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)
- **Project / Task / Comment Management** APIs
- **Search & Filter Traits** for reusable Eloquent scopes
- **Task Assignment Service** with Notification & Email Queue
- **Activity Logging Middleware**
- **Response in JSON format**
- **Caching for performance optimization**

---

## 🧱 Project Architecture

```
app/
 ├── Http/
 │    ├── Controllers/
 │    │    ├── Api/
 │    │    │    ├── ProjectController.php
 │    │    │    ├── TaskController.php
 │    │    │    └── CommentController.php
 │    ├── Middleware/
 │    │    └── LogUserActivity.php
 │    └── Kernel.php
 │
 ├── Models/
 │    ├── Admin.php
 │    ├── User.php
 │    ├── Project.php
 │    ├── Task.php
 │    └── Comment.php
 │
 ├── Services/
 │    └── TaskAssignmentService.php
 │
 ├── Traits/
 │    └── CommonQueryScopes.php
 │
 ├── Jobs/
 │    └── SendTaskAssignedEmailJob.php
 │
 ├── Notifications/
 │    └── TaskAssignedNotification.php
 │
resources/
 └── views/
      └── emails/
           └── task_assigned.blade.php

routes/
 ├── api.php
 ├── web.php
 ├── admin.php
 └── console.php
```

---

## ⚙️ Installation & Setup

### 1️⃣ Clone the repository
```bash
git clone https://github.com/your-username/backend-skill-test.git
cd backend-skill-test
```

### 2️⃣ Install dependencies
```bash
composer install
```

### 3️⃣ Copy `.env` and set up environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configure Database
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skill_test
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ Run migrations and seeders
```bash
php artisan migrate --seed
```

### 6️⃣ Configure Mail (for queue email)
Use [Mailtrap.io](https://mailtrap.io) or your SMTP:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_user
MAIL_PASSWORD=your_pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@skilltest.com"
MAIL_FROM_NAME="Project Manager"
```

### 7️⃣ Configure Queue
Set queue driver in `.env`:
```
QUEUE_CONNECTION=database
```
Then create tables:
```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

Run queue worker:
```bash
php artisan queue:work
```

---

## 🔐 Authentication System

This project uses **two guards**:

- `admin` → for system administrators  
- `web` → for normal users  

Both are authenticated using **Laravel Sanctum**.

**Login Example (POST /api/login):**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Returns:
```json
{
  "status": true,
  "type": "admin",
  "token": "..."
}
```

---

## 🧩 Role & Permission System

The project uses **Spatie Laravel Permission** package to manage roles and permissions.

Example:
```bash
php artisan permission:create-role admin
php artisan permission:create-permission view_projects
```

Middleware example in controllers:
```php
$this->middleware('permission:view_projects|add_projects', ['only' => ['index','show']]);
```

---

## 🧰 CommonQueryScopes Trait

📄 `app/Traits/CommonQueryScopes.php`

```php
public function scopeFilterByStatus($query, $status)
{
    if (!empty($status)) return $query->where('status', $status);
    return $query;
}

public function scopeSearchByTitle($query, $request)
{
    if (!empty($request->search['value'])) {
        $search = '%' . $request->search['value'] . '%';
        return $query->where('title', 'LIKE', $search);
    }
    if ($request->has('search')) {
        $search = '%' . $request->search . '%';
        return $query->where('title', 'LIKE', $search);
    }
    return $query;
}
```

Used in:
- `ProjectController@index`
- `TaskController@index`

---

## 🧮 Middleware: LogUserActivity

📄 `app/Http/Middleware/LogUserActivity.php`

Logs every authenticated API request with:
- user_id
- endpoint
- method
- timestamp
- IP

Enabled in `Kernel.php`:
```php
'log.user.activity' => \App\Http\Middleware\LogUserActivity::class,
```

Applied globally on API routes:
```php
Route::middleware(['auth:sanctum', 'log.user.activity'])->group(...);
```

---

## 🧠 Service: TaskAssignmentService

📄 `app/Services/TaskAssignmentService.php`

Handles:
- Task validation
- Creation
- Assigning to user
- Dispatching email job

```php
dispatch(new SendTaskAssignedEmailJob($user, $task));
```

---

## 📬 Job: SendTaskAssignedEmailJob

📄 `app/Jobs/SendTaskAssignedEmailJob.php`

Sends an **email notification** when a task is assigned using the mail view:
`resources/views/emails/task_assigned.blade.php`

Executed automatically by:
```bash
php artisan queue:work
```

---

## 📨 Notifications

📄 `app/Notifications/TaskAssignedNotification.php`

Stores notifications in database and sends via email (queued with `ShouldQueue`).

---

## ⚡️ API Endpoints Summary

| Method | Endpoint | Description | Permissions |
|--------|-----------|--------------|--------------|
| POST | `/api/login` | Login for admin/user | Public |
| GET | `/api/projects` | List all projects | `view_projects` |
| POST | `/api/projects` | Create new project | `add_projects` |
| GET | `/api/projects/{id}` | View single project | `view_projects` |
| GET | `/api/projects/{id}/tasks` | List project tasks | `view_tasks` |
| POST | `/api/projects/{id}/tasks` | Assign new task | `add_tasks` |
| PATCH | `/api/tasks/{id}` | Update task | `edit_tasks` |
| DELETE | `/api/tasks/{id}` | Delete task | `delete_tasks` |

---

## 🧮 Caching Example

To optimize performance, project and task lists are cached for 60 minutes:

```php
Cache::remember('projects_list', 60, function() {
    return Project::with('creator')->get();
});
```

When tasks or projects are updated:
```php
Cache::forget('projects_list');
```

---

## 🧾 Testing Queue & Mail

1. Start queue worker:
   ```bash
   php artisan queue:work
   ```
2. Assign a task to a user through API.
3. Check Mailtrap inbox — you’ll receive an email with task details.
4. Check `storage/logs/laravel.log` for activity logs.

---

## ✅ Requirements

- PHP 8.2+
- Laravel 11+
- MySQL 8+
- Composer
- Queue driver (database or Redis)
- Mail driver configured (Mailtrap or SMTP)

---

## 📗 References

- [Laravel Official Docs](https://laravel.com/docs/12.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)
- [Laravel Queues & Jobs](https://laravel.com/docs/12.x/queues)
- [Laravel Notifications](https://laravel.com/docs/12.x/notifications)
- [Laravel Caching](https://laravel.com/docs/12.x/cache)

---

## 🧑‍💻 Author

**Developer:** Mohammad Mohaisen  
**Framework:** Laravel 11  
**Focus:** Clean architecture, modular services, and queued notifications.
