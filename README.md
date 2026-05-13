# OCRE — Personal Finance Platform

OCRE is a comprehensive financial management solution featuring a robust administration panel. The system is architected to operate as a hybrid platform: a high-performance **JSON API** for mobile applications and a responsive **Web Application** for administrators and users.

---

## 🚀 Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Framework** | Laravel 11 |
| **Authentication** | Laravel Passport (OAuth 2.0) + Breeze (Sessions) |
| **Frontend** | Blade + Tailwind CSS + DaisyUI + Alpine.js |
| **Build Tool** | Vite |
| **Testing** | Pest PHP |
| **API Documentation** | L5-Swagger (OpenAPI) |

---

## 🏗️ System Architecture

The project is structured into two main layers with strictly defined responsibilities:

### 1. API (Mobile First)
* **Auth:** Secure login, registration, and 6-digit email-based password recovery.
* **Finances:** Management of total balance, income, expenses, and categories.
* **Security:** All endpoints are protected via **Passport** tokens (`auth:api`).

### 2. Web (Admin Panel)
* **Dashboard:** Visual representation of daily metrics and interactions using **Chart.js**.
* **Profile Management:** Secure data editing and account deletion workflows.
* **Access Control:** Custom `IsAdmin` middleware to restrict access to critical business logic.

---

## 📊 Data Model & Relationships

The system utilizes an optimized relational structure with **SoftDeletes** implemented across all models to ensure historical data integrity.

* **User** 1:N **Transaction** N:1 **Category**
* **User** 1:N **Simulation**
* **User** 1:N **SupportMessage**
* **Category:** Classification by type (expense, income, goal).
* **InvestmentRate:** Parameters for the financial simulator engine.
* **DailyMetric:** Aggregated data for high-performance dashboard analytics.
* **Post:** Educational articles utilizing `JSONB` metadata.

---

## ✨ Key Features

- **Dual Authentication:** Simultaneous handling of stateful sessions (Web) and stateless tokens (API).
- **Role-Based Access Control (RBAC):** Native implementation of `admin` and `user` tiers with custom middleware.
- **Data Aggregation:** Custom `app:aggregate-daily-metrics` command for optimized statistics processing.
- **Investment Simulator:** Dynamic yield calculation based on initial capital and monthly terms.
- **Secure Reset Flow:** Account recovery with time-limited (60 min) temporary codes.
- **Integrated Support:** Internal communication channel between users and administrators.

---

## 📝 Roadmap

- [ ] **Mobile App:** Development in **React Native** (Modules: Auth, Home, Simulator, and Library).

---

> **Note:** All models implement `SoftDeletes` to prevent accidental loss of sensitive financial data.
