#!/bin/bash

# Verificar que se pasen exactamente 3 argumentos
if [ "$#" -ne 3 ]; then
    echo "Uso: $0 <nombre_archivo_pub> <nombre_usuario> <nombre_app>"
    exit 1
fi

# Asignar los argumentos a variables
ARCHIVO_PUB=$1
NOMBRE_USUARIO=$2
NOMBRE_APP=$3

# Construir la ruta completa del archivo de clave pública
RUTA_CLAVE_PUB="/var/www/html/uploads/$ARCHIVO_PUB"

# Crear la aplicación en dokku
sudo dokku apps:create "$NOMBRE_APP"
if [ $? -ne 0 ]; then
    echo "Error al crear la aplicación $NOMBRE_APP."
    exit 1
fi

# Verificar que el archivo de clave pública existe
if [ ! -f "$RUTA_CLAVE_PUB" ]; then
    echo "El archivo de clave pública no existe: $RUTA_CLAVE_PUB"
    exit 1
fi

# Agregar la clave pública a dokku
sudo dokku ssh-keys:add "$NOMBRE_USUARIO" "$RUTA_CLAVE_PUB"
if [ $? -ne 0 ]; then
    echo "Error al agregar la clave pública para el usuario $NOMBRE_USUARIO."
    exit 1
fi

# Añadir el usuario a la lista de control de acceso de la aplicación
sudo dokku acl:add "$NOMBRE_APP" "$NOMBRE_USUARIO"
if [ $? -ne 0 ]; then
    echo "Error al añadir el usuario $NOMBRE_USUARIO a la lista de control de acceso de la aplicación $NOMBRE_APP."
    exit 1
fi

echo "La aplicación $NOMBRE_APP ha sido creada y el usuario $NOMBRE_USUARIO ha sido agregado."
