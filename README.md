# 🚀 Sistema de Afiliados — Globant SRL

Panel de administración construido con **Laravel 12**, **AdminLTE 3** y **Spatie Permission** (roles y permisos). El front se compila con **Vite + Tailwind CSS 4**.

Este proyecto está configurado para ejecutarse utilizando Docker, permitiendo un entorno de desarrollo consistente para todo el equipo.

---

## 🧰 Requisitos

Antes de comenzar, asegúrate de tener instalado:

* Git
* Docker (Docker Desktop o Docker Engine)
* Docker Compose


****** IMPORTANTE *******

`sudo usermod -aG docker $USER`  // `$USER` es tu usuario; este comando sirve para tener acceso a Docker sin ser usuario root. Ejecutarlo si usas Linux.

`git config --global core.fileMode false` // ejecútalo para no tener problemas con la configuración de permisos que da Docker (Linux o Windows).

`sudo chown -R usuario:grupo .`  // si tienes problemas con los permisos ejecuta este comando, solo en Linux.

---

## 📥 Instalación del proyecto

### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd afiliados-app
```

---

### 2. Configurar variables de entorno

**Windows:** haz una copia del archivo `.env.example` y renómbrala como `.env`

**Linux:**
```bash
cp .env.example .env
```

Edita el archivo `.env` y configura la base de datos. El **host debe ser `db`**, que es el nombre del servicio en `docker-compose.yml`:

```env
DB_CONNECTION=mysql        # mysql como motor de base de datos
DB_HOST=db                 # nombre del servicio de la BD en docker-compose.yml
DB_PORT=3306               # puerto de la base de datos
DB_DATABASE=afiliados      # nombre de la base de datos
DB_USERNAME=afiliados      # usuario de la base de datos
DB_PASSWORD=secret         # contraseña del usuario
DB_ROOT_PASSWORD=root_secret   # contraseña root (¡no la compartas!)
```

---

### 3. Levantar los contenedores

```bash
docker compose up -d --build
```

Esto iniciará los servicios necesarios:

* PHP + Apache
* Base de datos (MariaDB)

---

### 4. Instalar dependencias de Laravel

```bash
docker compose exec app composer install
```

---

### 5. Generar la clave de la aplicación

```bash
docker compose exec app php artisan key:generate
```

---

### 6. Ejecutar migraciones y sembrar datos iniciales

Crea las tablas y carga el rol `administrador` junto con el usuario inicial:

```bash
docker compose exec app php artisan migrate --seed
```

---

### 7. Instalar y compilar el front (Vite + Tailwind)

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

---

## 🌐 Acceso al sistema

Una vez completados los pasos anteriores, puedes acceder al proyecto en:

```
http://localhost:8080
```

### 🔑 Credenciales por defecto

| Usuario | Contraseña |
|---------|-----------|
| `admin` | `1234` |

> ⚠️ El inicio de sesión es **por nombre de usuario** (campo `usuario`), no por correo. Cambia esta contraseña en producción.

¡Listo! 🎉 Ahora puedes empezar a trabajar en el sistema 🚀

---

## 🔄 Flujo de trabajo diario

Cada vez que haya actualizaciones en el proyecto:

```bash
git pull origin {nombre de rama}
docker compose exec app composer install
docker compose exec app php artisan migrate
docker compose exec app npm run build
```

---

## 🛠️ Comandos útiles

### Ver contenedores en ejecución
```bash
docker compose ps
```

### Detener contenedores
```bash
docker compose stop
```

### Reconstruir contenedores
```bash
docker compose up -d --build      # agrega -v si quieres eliminar también la base de datos
```

### Limpiar caché de vistas (si no ves cambios de diseño)
```bash
docker compose exec app php artisan view:clear
```

### Recompilar / desarrollo del front en caliente
```bash
docker compose exec app npm run dev
```

---

## ⚠️ Notas importantes

* La carpeta `vendor/` se genera con `composer install`.
* Si hay cambios en dependencias (`composer.json` o `composer.lock`), vuelve a ejecutar:
  ```bash
  docker compose exec app composer install
  ```
* Si cambian los assets del front (`resources/`), recompila con `npm run build`.
* La base de datos se mantiene persistente gracias a los volúmenes de Docker.

---

## 🧩 Stack

* **Backend:** Laravel 12 (PHP 8.2+)
* **Roles y permisos:** spatie/laravel-permission
* **UI:** AdminLTE 3 (Blade)
* **Build front:** Vite + Tailwind CSS 4
* **Base de datos:** MariaDB 11.3 / MySQL

---

## 🎯 Objetivo

Este entorno permite que cualquier desarrollador pueda levantar el proyecto rápidamente sin preocuparse por configuraciones locales de PHP, Apache o base de datos.
