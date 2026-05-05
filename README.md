# 🦅 HALCON — Sistema de Gestión de Pedidos

> Aplicación web Laravel para la administración del ciclo de vida de pedidos de una distribuidora de materiales de construcción. Incluye portal público de rastreo para clientes y panel administrativo con control de acceso por roles.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías utilizadas](#-tecnologías-utilizadas)
- [Requisitos previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración de la base de datos](#-configuración-de-la-base-de-datos)
- [Migraciones y Seeders](#-migraciones-y-seeders)
- [Cómo correr el proyecto](#-cómo-correr-el-proyecto)
- [Usuarios de prueba](#-usuarios-de-prueba)
- [Estructura del proyecto](#-estructura-del-proyecto)
- [Modelos y relaciones](#-modelos-y-relaciones)
- [Roles y permisos](#-roles-y-permisos)
- [Ciclo de vida de un pedido](#-ciclo-de-vida-de-un-pedido)
- [Rutas disponibles](#-rutas-disponibles)
- [Cambios recientes](#-cambios-recientes)

---

## ✨ Características

- 🌐 **Portal público** — consulta de pedidos sin registro con número de cliente y factura
- 🔐 **Autenticación por username** — login seguro para empleados
- 👥 **RBAC (Control de Acceso por Roles)** — 5 roles departamentales
- 📦 **CRUD completo de pedidos** — creación, edición, cambios de estado y eliminación lógica
- 📸 **Subida de fotos** — evidencia de carga y entrega por personal de Ruta
- 🔍 **Búsqueda y filtros** — por factura, cliente, fecha y estado
- 🗃 **Papelera de pedidos** — archivado lógico con opción de restaurar
- 👤 **Gestión de usuarios** — el Admin crea usuarios, asigna roles y activa/desactiva cuentas
- 📋 **Bitácora de estados** — registro de cada cambio de estado con usuario y timestamp

---

## 🛠 Tecnologías utilizadas

| Tecnología | Versión | Uso |
|---|---|---|
| [Laravel](https://laravel.com) | 11.x | Framework PHP principal |
| PHP | 8.2+ | Lenguaje de programación |
| MySQL / MariaDB | 8.0+ | Base de datos relacional |
| Blade | — | Motor de plantillas |
| [Composer](https://getcomposer.org) | 2.x | Gestor de dependencias PHP |

---

## 📦 Requisitos previos

### 1. PHP 8.2+

**Windows:**
1. Descarga [XAMPP](https://www.apachefriends.org/) (incluye PHP y MySQL)
2. Instala y activa el módulo Apache y MySQL desde el panel de XAMPP
3. Verifica en terminal:
```bash
php --version
```

**Mac:**
```bash
brew install php
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update && sudo apt install php8.2 php8.2-mbstring php8.2-xml php8.2-curl php8.2-mysql php8.2-zip -y
```

---

### 2. Composer

**Windows:** Descarga el instalador en [https://getcomposer.org/download/](https://getcomposer.org/download/)

**Mac / Linux:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Verifica:
```bash
composer --version
```

---

### 3. MySQL

**Con XAMPP:** ya viene incluido. Inícialo desde el panel de XAMPP.

**Sin XAMPP (Mac):**
```bash
brew install mysql
brew services start mysql
```

**Sin XAMPP (Linux):**
```bash
sudo apt install mysql-server -y
sudo service mysql start
```

---

### 4. Git (para clonar el repositorio)

Descarga en [https://git-scm.com](https://git-scm.com) o con Homebrew:
```bash
brew install git   # Mac
```

---

## 🚀 Instalación

### Paso 1 — Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/halcon.git
cd halcon
```

### Paso 2 — Instalar dependencias PHP

```bash
composer install
```

### Paso 3 — Crear el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### Paso 4 — Crear el enlace de almacenamiento (para fotos)

```bash
php artisan storage:link
```

---

## 🗄 Configuración de la base de datos

### Crear la base de datos

Abre tu cliente MySQL (phpMyAdmin, TablePlus, DBeaver, o la terminal):

```sql
CREATE DATABASE halcon_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Configurar el archivo .env

Abre `.env` y actualiza estas líneas:

```env
APP_NAME=HALCON
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=halcon_db
DB_USERNAME=root
DB_PASSWORD=          # tu contraseña de MySQL (vacío si usas XAMPP por defecto)
```

---

## 🔄 Migraciones y Seeders

### Ejecutar migraciones (crea las tablas)

```bash
php artisan migrate
```

Esto crea las siguientes tablas:
- `users` — usuarios del sistema con rol/departamento
- `orders` — pedidos con todos los campos del ciclo de vida
- `order_status_logs` — bitácora de cambios de estado

### Poblar la base de datos con datos de prueba

```bash
php artisan db:seed
```

Esto ejecuta:
- **UserSeeder** — crea 6 usuarios (uno por cada rol + un Ventas inactivo)
- **OrderSeeder** — crea 6 pedidos de prueba en diferentes estados

### Todo en un solo comando (fresh + seed)

```bash
php artisan migrate:fresh --seed
```

> ⚠️ `migrate:fresh` elimina todas las tablas y las vuelve a crear. Úsalo solo en desarrollo.

---

## ▶️ Cómo correr el proyecto

```bash
php artisan serve
```

La aplicación estará disponible en:
```
http://localhost:8000
```

Para acceder desde otro dispositivo en la misma red:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Luego desde tu celular: `http://TU_IP_LOCAL:8000`

Para conocer tu IP:
- **Windows:** `ipconfig` → busca "Dirección IPv4"
- **Mac/Linux:** `ifconfig | grep "inet "`

---

## 👤 Usuarios de prueba

| Usuario | Contraseña | Rol | Accesos principales |
|---|---|---|---|
| `admin` | `admin123` | Admin | Todo: usuarios, pedidos, archivados |
| `cmendoza` | `ventas123` | Ventas | Crear y editar pedidos, archivar |
| `lramirez` | `alma123` | Almacén | Cambiar a En Proceso / En Ruta |
| `jsoto` | `compras123` | Compras | Solo consulta de pedidos |
| `matorres` | `ruta123` | Ruta | Subir fotos, marcar como Entregado |
| `svilalba` | `ventas123` | Ventas | **Cuenta inactiva** (no puede iniciar sesión) |

**Portal público (sin login):**
- # Cliente: `C-101` / # Factura: `F-0001` → Entregado
- # Cliente: `C-102` / # Factura: `F-0002` → En Ruta
- # Cliente: `C-103` / # Factura: `F-0003` → En Proceso
- # Cliente: `C-104` / # Factura: `F-0004` → Ordenado

---

## 📁 Estructura del proyecto

```
halcon/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login / logout
│   │   │   ├── DashboardController.php     # Panel principal
│   │   │   ├── OrderController.php         # CRUD pedidos + fotos + estados
│   │   │   ├── PublicOrderController.php   # Portal público
│   │   │   └── UserController.php          # CRUD usuarios (Admin)
│   │   └── Middleware/
│   │       ├── CheckRole.php               # Verificar rol del usuario
│   │       └── CheckActive.php             # Bloquear usuarios inactivos
│   ├── Models/
│   │   ├── User.php                        # Modelo usuario con relaciones
│   │   ├── Order.php                       # Modelo pedido con ciclo de vida
│   │   └── OrderStatusLog.php             # Bitácora de cambios de estado
│   └── Providers/
│       └── AppServiceProvider.php          # Directivas Blade personalizadas
├── bootstrap/
│   └── app.php                             # Registro de middleware
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_orders_table.php
│   │   └── ..._create_order_status_logs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── OrderSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                   # Layout con sidebar y navegación
│   ├── auth/
│   │   └── login.blade.php
│   ├── public/
│   │   └── home.blade.php                  # Portal público de rastreo
│   ├── dashboard.blade.php
│   ├── orders/
│   │   ├── index.blade.php                 # Lista con filtros
│   │   ├── create.blade.php
│   │   ├── show.blade.php                  # Detalle + bitácora + fotos
│   │   ├── edit.blade.php                  # Edición + cambio de estado + fotos
│   │   └── archived.blade.php             # Papelera con restauración
│   └── users/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
└── routes/
    └── web.php                             # Todas las rutas protegidas por rol
```

---

## 🔗 Modelos y relaciones

### User
| Relación | Tipo | Descripción |
|---|---|---|
| `orders()` | hasMany | Pedidos creados por este usuario (Ventas) |
| `statusLogs()` | hasMany | Cambios de estado registrados por este usuario |

### Order
| Relación | Tipo | Descripción |
|---|---|---|
| `creator()` | belongsTo → User | Usuario Ventas que creó el pedido |
| `statusLogs()` | hasMany | Todos los cambios de estado del pedido |

### OrderStatusLog
| Relación | Tipo | Descripción |
|---|---|---|
| `order()` | belongsTo → Order | Pedido al que pertenece este log |
| `user()` | belongsTo → User | Usuario que realizó el cambio |

---

## 🔐 Roles y permisos

| Acción | Admin | Ventas | Almacén | Compras | Ruta |
|---|:---:|:---:|:---:|:---:|:---:|
| Ver todos los pedidos | ✅ | ✅ | ✅ | ✅ | ✅ |
| Crear pedido | ❌ | ✅ | ❌ | ❌ | ❌ |
| Editar datos del pedido | ❌ | ✅ | ❌ | ❌ | ❌ |
| Cambiar a En Proceso | ❌ | ❌ | ✅ | ❌ | ❌ |
| Cambiar a En Ruta | ❌ | ❌ | ✅ | ❌ | ❌ |
| Subir foto de carga | ❌ | ❌ | ❌ | ❌ | ✅ |
| Subir evidencia / Entregar | ❌ | ❌ | ❌ | ❌ | ✅ |
| Archivar (soft delete) | ✅ | ✅ | ❌ | ❌ | ❌ |
| Restaurar archivados | ✅ | ✅ | ❌ | ❌ | ❌ |
| Gestionar usuarios | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 🔄 Ciclo de vida de un pedido

```
Cliente llama
      ↓
[Ventas] Crea el pedido → Estado: ORDENADO  (automático)
      ↓
[Almacén] Prepara materiales → Estado: EN PROCESO
      ↓
[Almacén] Carga la unidad → Estado: EN RUTA
      ↓
[Ruta] Sube foto de carga (opcional antes de entregar)
      ↓
[Ruta] Sube foto de evidencia → Estado: ENTREGADO  (automático)
      ↓
[Cliente] Consulta en portal público → ve foto de entrega
```

> Los estados son **secuenciales e irreversibles**. Solo el rol correspondiente puede avanzar cada transición.

---

## 🗺 Rutas disponibles

| Método | URL | Controlador | Roles permitidos |
|---|---|---|---|
| GET | `/` | PublicOrderController@index | Público |
| POST | `/search` | PublicOrderController@search | Público |
| GET | `/login` | AuthController@showLogin | Invitado |
| POST | `/login` | AuthController@login | Invitado |
| POST | `/logout` | AuthController@logout | Autenticado |
| GET | `/dashboard` | DashboardController@index | Todos |
| GET | `/orders` | OrderController@index | Todos |
| GET | `/orders/create` | OrderController@create | Ventas |
| POST | `/orders` | OrderController@store | Ventas |
| GET | `/orders/{id}` | OrderController@show | Todos |
| GET | `/orders/{id}/edit` | OrderController@edit | Admin, Ventas, Almacén, Ruta |
| PUT | `/orders/{id}` | OrderController@update | Admin, Ventas, Almacén, Ruta |
| DELETE | `/orders/{id}` | OrderController@destroy | Admin, Ventas |
| GET | `/orders-archived` | OrderController@archived | Admin, Ventas |
| PATCH | `/orders/{id}/restore` | OrderController@restore | Admin, Ventas |
| GET | `/users` | UserController@index | Admin |
| GET | `/users/create` | UserController@create | Admin |
| POST | `/users` | UserController@store | Admin |
| GET | `/users/{id}/edit` | UserController@edit | Admin |
| PUT | `/users/{id}` | UserController@update | Admin |

---

## 📝 Cambios recientes

### v1.1.0 — Backend Laravel

- ✅ Modelos `User`, `Order`, `OrderStatusLog` con relaciones Eloquent
- ✅ Migraciones con claves primarias, foráneas e índices optimizados
- ✅ Controladores con validaciones, autorización por rol y lógica de negocio
- ✅ Middleware `CheckRole` y `CheckActive` para protección de rutas
- ✅ Seeders con 6 usuarios y 6 pedidos de prueba en distintos estados
- ✅ Vistas Blade completas: portal público, dashboard, pedidos (CRUD), usuarios (CRUD), archivados
- ✅ Bitácora de cambios de estado por pedido
- ✅ Subida de fotos con almacenamiento en `storage/app/public/photos/`
- ✅ Eliminación lógica (soft delete) con restauración

### v1.0.0 — Prototipo React

- Prototipo funcional frontend en React (sin backend)

---

## 🐛 Problemas comunes

**`php artisan migrate` falla con error de conexión**
→ Verifica que MySQL esté corriendo y que las credenciales en `.env` sean correctas.

**Error 403 al acceder a una ruta**
→ Tu usuario no tiene el rol requerido para esa sección. Usa las credenciales de prueba correctas.

**Las fotos no se muestran**
→ Asegúrate de haber corrido `php artisan storage:link`.

**`Class not found` en seeders o controllers**
→ Corre `composer dump-autoload`.

---

## 📄 Licencia

Uso interno — **Halcon Materiales de Construcción**.
