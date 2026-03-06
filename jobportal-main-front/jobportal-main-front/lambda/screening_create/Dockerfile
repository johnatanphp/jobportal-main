FROM python:3.10-slim-buster

# Establece el directorio de trabajo dentro del contenedor.
WORKDIR /app

# Copia el archivo de requisitos al contenedor.
# Esto es crucial para que Docker pueda instalar las dependencias.
COPY requirements.txt .

# Instala todas las librerías listadas en requirements.txt.
RUN pip install --no-cache-dir -r requirements.txt

# Aquí se asume que tus scripts están en el mismo directorio que el Dockerfile.
COPY . .

# Comando por defecto para iniciar un shell de Python en el contenedor.
# Esto te permite ejecutar tus scripts o probar comandos interactivamente.
CMD ["python3"]