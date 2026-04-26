# OCRE — Plataforma de Finanzas Personales

OCRE es una solución integral de gestión financiera personal que incluye un robusto panel de administración. El sistema está diseñado para operar de forma híbrida: como una **API JSON** para aplicaciones móviles y como una **aplicación web** responsiva para administradores y usuarios.

---

## 🚀 Stack Tecnológico

| Capa | Tecnología |
| :--- | :--- |
| **Framework** | Laravel 11 |
| **Autenticación** | Laravel Passport (OAuth 2.0) + Breeze (Sesiones) |
| **Frontend** | Blade + Tailwind CSS + DaisyUI + Alpine.js |
| **Build Tool** | Vite |
| **Testing** | Pest PHP |
| **Documentación API** | L5-Swagger (OpenAPI) |

---

## 🏗️ Arquitectura del Sistema

El proyecto se divide en dos capas principales con responsabilidades definidas:

### 1. API (Mobile First)
* **Auth:** Login, registro y recuperación de contraseña mediante código de 6 dígitos vía email.
* **Finanzas:** Gestión de balance total, ingresos, gastos y categorías.
* **Seguridad:** Protegida mediante tokens de **Passport** (`auth:api`).

### 2. Web (Panel Administrativo)
* **Dashboard:** Visualización de métricas diarias e interacciones mediante **Chart.js**.
* **Gestión de Perfil:** Edición de datos, seguridad y eliminación de cuenta.
* **Control de Acceso:** Middleware personalizado `IsAdmin` para restringir secciones críticas.

---

## 📊 Modelo de Datos y Relaciones

El sistema utiliza una estructura relacional optimizada con soporte para **SoftDeletes** en todos los modelos para garantizar la integridad del historial.



[Image of database schema relationship diagram]


* **User** 1:N **Transaction** N:1 **Category**
* **User** 1:N **Simulation**
* **User** 1:N **SupportMessage**
* **Category:** Clasificación por tipo (gasto, ingreso, meta).
* **InvestmentRate:** Parámetros para el simulador financiero.
* **DailyMetric:** Agregados de datos para analíticas del dashboard.
* **Post:** Artículos educativos con metadatos en formato `JSONB`.

---

## ✨ Características Destacadas

-   **Doble Autenticación:** Manejo simultáneo de sesiones de estado (Web) y tokens stateless (API).
-   **Sistema de Roles:** Implementación de niveles `admin` y `user` con middleware de acceso.
-   **Agregación de Datos:** Comando personalizado `app:aggregate-daily-metrics` para el procesamiento de estadísticas.
-   **Simulador de Inversiones:** Cálculo dinámico de rendimientos basado en capital inicial y plazos mensuales.
-   **Seguridad en Reset:** Flujo de recuperación de cuenta con códigos temporales (expiración de 60 min).
-   **Soporte Integrado:** Canal de comunicación interna entre usuarios y administradores.

---

## 📝 Próximos Pasos (Pendientes)

-   [ ] **App Móvil:** Desarrollo en **React Native** (Módulos de Auth, Home, Simulador y Biblioteca).

---

> **Nota:** Todos los modelos implementan `SoftDeletes` para prevenir la pérdida accidental de datos financieros.