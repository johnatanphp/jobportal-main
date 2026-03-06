# Configuración de Despliegue - Job Portal

Este repositorio está listo para ser procesado por herramientas de despliegue y Firebase Studio.

## Pasos Realizados
1. **Dependencias**: Se ejecutó `composer install` para asegurar que todas las librerías necesarias estén presentes.
2. **Directorios**: Se crearon los directorios de carga (`public/uploads/tmp`) con los permisos adecuados (755).
3. **Base de Datos**: El sistema está configurado para usar PostgreSQL nativo de Replit, lo cual facilita el despliegue en entornos de nube modernos.
4. **Punto de Entrada**: `index.php` en la raíz es el punto de entrada principal, compatible con la mayoría de los servicios de hosting PHP.

## Despliegue
Para desplegar en Replit, simplemente utiliza el botón de **Desplegar** o **Publicar**. El flujo de trabajo `Start Application` ya está configurado para iniciar el servidor correctamente.
