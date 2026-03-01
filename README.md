# 🚀 Laravel 11 — Guía de instalación

Sigue estos pasos después de clonar el repositorio para levantar el proyecto localmente.

---

## 📋 Requisitos previos

Asegúrate de tener instalado:

| Herramienta | Verificar con |
|---|---|
| Docker | `docker --version` |
| Docker Compose v2 | `docker compose version` |

---

## ▶️ Instalación en un solo comando

```bash
bash setup.sh
```

Eso es todo. El script hace automáticamente:

1. Copia `.env.example` → `.env` *(si no existe)*
2. Levanta los contenedores Docker
3. Ejecuta `composer install`
4. Genera el `APP_KEY`
5. Ejecuta migraciones y seeders

---

## 🌐 URLs disponibles al terminar

| Servicio | URL |
|---|---|
| API Laravel | http://localhost:8000 |
| phpMyAdmin | http://localhost:8080 |

---

## ⚙️ Variables de entorno

Si necesitas cambiar algo (nombre de BD, credenciales, puertos), edita el archivo `.env` **antes** de correr el script:

```bash
cp .env.example .env
# edita .env con tu editor
bash setup.sh
```

---

## 🛠 Comandos útiles

```bash
# Ver logs en tiempo real
docker compose logs -f

# Entrar al contenedor
docker compose exec app bash

# Detener contenedores
docker compose down

# Reiniciar desde cero (borra datos)
docker compose exec app php artisan migrate:fresh --seed
```

---

## ❗ Problemas comunes

**El script dice "El archivo .env ya existe"**
Normal si ya corriste el script antes. Tu `.env` actual se conserva sin cambios.

**Error de permisos al ejecutar el script**
```bash
chmod +x setup.sh
bash setup.sh
```

**Los contenedores tardan en iniciar**
Si ves errores de conexión a la base de datos justo al correr, espera unos segundos y vuelve a ejecutar solo las migraciones:
```bash
docker compose exec app php artisan migrate --seed --force
```
