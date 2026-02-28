#!/bin/bash

echo "🚀 Iniciando configuración del proyecto..."

# 1️⃣ Copiar .env si no existe
if [ ! -f .env ]; then
    echo "📄 Creando archivo .env..."
    cp .env.example .env
else
    echo "⚠️ El archivo .env ya existe. Se omite copia."
fi

# 2️⃣ Levantar contenedores
echo "🐳 Levantando contenedores..."
docker compose up -d --build

# Esperar a que el contenedor esté listo
echo "⏳ Esperando a que el contenedor esté disponible..."
sleep 8

# 3️⃣ Composer install
echo "📦 Ejecutando composer install..."
docker compose exec app composer install

# 4️⃣ Generar APP_KEY
echo "🔑 Generando APP_KEY..."
docker compose exec app php artisan key:generate --force

# 6️⃣ Migraciones + Seed
echo "🗄 Ejecutando migraciones y seeders..."
docker compose exec app php artisan migrate --seed --force

echo "✅ Instalación completada correctamente."
