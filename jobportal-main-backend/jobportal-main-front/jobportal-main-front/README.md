# Portal de empleo

_Portal de empleo Overall_

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._

### Pre-requisitos 📋

```
PHP == 8.1.x
Mysql == 8.0.x

```

### Instalación 🔧

_Por favor sigue los siguientes pasos para tener el proyecto en funcionamiento_

#### 1) Importar la base de datos proporcionada por el equipo.

#### 2) Configuración archivo constants.php:

   * Crea un archivo en el directorio ca_app/config con el nombre constants.php
   * Copia el contenido del archivo de ejemplo constants.example.php en el nuevo archivo
   * Edita la variable SITE_URL por la url del proyecto a instalar
    
   Ejemplo:
 
``` 
define('SITE_URL', 'http://localhost/jobportal-example');
```
#### 3) Configuracion archivo database.php:

   * Crea un archivo en el directorio ca_app/config con el nombre database.php
   * Copia el contenido del archivo de ejemplo database.example.php en el nuevo archivo
   * Edita la configuración con las credenciales de tu base de datos
    
#### 4) Instalar dependencias de librerias através de composer 
   
   * Ingresar por terminal a la raiz del proyecto y ejecutar el siguiente comando:
``` 
composer install
```
   * Esperar que se descarguen todas las dependencias
```
       NOTA: Si no tienes composer instalado en tu sistema debes instalarlo
   
       https://getcomposer.org/
```
#### 5) Ingresar a la url del proyecto para probar que todo este OK


⌨️ con ❤️ por Team Portal empleo Overall 😊