# Quick Start Guide - Task Management System

## ⚡ 5-Minute Setup

### **Step 1: Install Dependencies**

```bash
composer install && npm install
```

### **Step 2: Configure Database**

Edit `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

### **Step 3: Setup Database**

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### **Step 4: Build Assets**

```bash
npm run build
```

### **Step 5: Start Server**

**Terminal 1:**

```bash
php artisan serve
# Runs on http://localhost:8000
```

**Terminal 2:**

```bash
npm run dev
# Vite dev server on http://localhost:5173
```

---

## 🔑 Login Credentials (Seeded Data)

| Role     | Email                | Password | Access Level |
| -------- | -------------------- | -------- | ------------ |
| Admin    | admin@example.com    | password | Full System  |
| Manager  | manager@example.com  | password | Team + Tasks |
| Employee | employee@example.com | password | Own Tasks    |

---

## ✅ Quick Test Checklist

### **Test 1: Create Task** (2 min)

- [ ] Login as employee
- [ ] Click "Create Task"
- [ ] Fill: Title="Buy Groceries", Priority="High"
- [ ] Click "Save Task"
- [ ] ✅ See success message and task in list

### **Test 2: Quick Complete** (1 min)

- [ ] In task list, click "✓ Complete" button
- [ ] ✅ Task status changes to "Completed" instantly

### **Test 3: Edit Task** (2 min)

- [ ] Click "Edit" on any task
- [ ] Change title to "Buy Milk & Bread"
- [ ] Click "Update Task"
- [ ] ✅ See changes reflected in list

### **Test 4: Delete Task** (1 min)

- [ ] Click "Delete" on any task
- [ ] Confirm dialog
- [ ] ✅ Task removed from list

### **Test 5: Form Validation** (1 min)

- [ ] Click "Create Task"
- [ ] Leave title empty
- [ ] Click "Save Task"
- [ ] ✅ See red error: "Title field is required"

### **Test 6: Dashboard** (1 min)

- [ ] Click "Dashboard" in sidebar
- [ ] ✅ See statistics with completed %
- [ ] See all tasks in recent tasks list

### **Test 7: Mobile Responsive** (2 min)

- [ ] Open DevTools (F12)
- [ ] Toggle Device Toolbar
- [ ] ✅ Hamburger button appears
- [ ] Click hamburger
- [ ] ✅ Sidebar slides in

---

## 🎯 Key Features to Demo

### **For Graders/Stakeholders:**

1. **Professional UI** - Modern design with Tailwind + Flowbite
2. **Full CRUD** - Create, Read, Update, Delete all working
3. **Responsive** - Works on desktop, tablet, mobile
4. **Validation** - All forms have proper error handling
5. **Status Management** - Quick toggle between Pending/Complete
6. **Dashboard** - Shows statistics and key metrics
7. **Authorization** - Role-based access control
8. **Database** - Proper relationships and migrations

---

## 📁 Important Files

| File                                      | Purpose             |
| ----------------------------------------- | ------------------- |
| `app/Http/Controllers/TaskController.php` | Task CRUD logic     |
| `resources/views/tasks/`                  | Task views          |
| `resources/views/components/`             | Reusable components |
| `database/migrations/`                    | Database schema     |
| `app/Policies/TaskPolicy.php`             | Authorization rules |
| `tailwind.config.js`                      | Styling config      |

---

## 🐛 If Something Breaks

```bash
# Clear cache and logs
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Rebuild assets
npm run build

# Reset database
php artisan migrate:fresh --seed
```

---

## 📞 Need Help?

Check the complete documentation:

```bash
cat SYSTEM_DOCUMENTATION.md
```

Or review the code:

- Controllers: `app/Http/Controllers/`
- Views: `resources/views/`
- Models: `app/Models/`

---

**🎊 System Ready! Login and Start Testing! 🎊**
