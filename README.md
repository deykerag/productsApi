# Reto Técnico Begento Healthcare Solutions — API RESTful

API RESTful desarrollada con Laravel para la gestión de productos, sus precios en distintas divisas y las monedas disponibles. Este proyecto forma parte de una evaluación técnica y busca demostrar buenas prácticas en el desarrollo de servicios web, persistencia de datos, validación de solicitudes y documentación de APIs.

## 📋 Tabla de contenidos

- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Documentación de la API](#-documentación-de-la-api)
- [Endpoints disponibles](#-endpoints-disponibles)
- [Ejemplos de uso](#-ejemplos-de-uso)
- [Pruebas](#-pruebas)
- [Autor](#-autor)

## 🚀 Características

- Gestión de productos: creación, consulta, actualización y eliminación.
- Consulta de productos con paginación (10 por página).
- Registro, consulta y eliminación de monedas y sus tasas de cambio.
- Registro y consulta de precios adicionales por producto y moneda.
- Prevención de precios duplicados para un mismo producto y moneda.
- Protección contra la eliminación de monedas que estén en uso.
- Validación de datos de entrada y respuestas de error en formato JSON (`404`, `405`, `409`, `422`).
- Documentación interactiva mediante Swagger UI y OpenAPI.

## 🛠️ Tecnologías

| Tecnología                  | Uso                                 |
| --------------------------- | ----------------------------------- |
| PHP 8.3+ (probado con 8.5)  | Lenguaje de programación            |
| Laravel 13                  | Framework backend                   |
| Composer                    | Gestión de dependencias             |
| Eloquent ORM                | Acceso y persistencia de datos      |
| SQLite / MySQL / PostgreSQL | Almacenamiento de la información    |
| Swagger UI / OpenAPI        | Documentación interactiva de la API |

## 📦 Requisitos previos

Antes de comenzar, asegúrate de tener instalado:

- PHP 8.3 o superior (el proyecto se probó con PHP 8.5).
- La extensión de PHP correspondiente al motor de base de datos:
    - `pdo_sqlite` para SQLite (opción por defecto).
    - `pdo_mysql` o `pdo_pgsql` si usarás MySQL o PostgreSQL.
- Composer.
- Git.
- Opcional: un servidor MySQL o PostgreSQL si no usarás SQLite.

Puedes verificar las instalaciones con:

```bash
php -v
php -m | grep -i pdo
composer --version
git --version
```

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/deykerag/productsApi.git
```

Accede al directorio del proyecto:

```bash
cd productsApi
```

### 2. Instalar las dependencias

```bash
composer install
```

### 3. Configurar las variables de entorno

Crea tu archivo `.env` a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

Verifica que `APP_URL` coincida con la URL desde la que servirás la aplicación. Swagger UI usa este valor para construir la URL base de la API:

```dotenv
APP_URL=http://localhost:8000
```

### 4. Configurar la base de datos

#### Opción por defecto: SQLite

No requiere instalar ningún servidor de base de datos. Crea el archivo si no existe:

```bash
touch database/database.sqlite
```

Y deja la conexión configurada así en `.env`:

```dotenv
DB_CONNECTION=sqlite
```

#### Opción alternativa: MySQL o PostgreSQL

Crea una base de datos y configura las variables en `.env` según tu entorno. Ejemplo para MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

> **Nota:** No compartas contraseñas ni archivos `.env` reales en el repositorio.

### 5. Ejecutar las migraciones y los seeders

```bash
php artisan migrate --seed
```

Este comando crea las tablas y carga las monedas iniciales (`USD` y `EUR`).

Si necesitas reiniciar la base de datos durante el desarrollo:

```bash
php artisan migrate:fresh --seed
```

**Advertencia:** `migrate:fresh` elimina todas las tablas existentes de la base de datos seleccionada. Utilízalo únicamente en un entorno de desarrollo donde puedas perder esos datos.

### 6. Iniciar el servidor

```bash
php artisan serve
```

Por defecto, la aplicación estará disponible en:

http://localhost:8000

## 📚 Documentación de la API

El proyecto utiliza Swagger UI para facilitar la exploración y ejecución de los endpoints.

Una vez iniciada la aplicación, abre:

**[http://localhost:8000/swagger](http://localhost:8000/swagger)**

Desde la interfaz podrás consultar los métodos HTTP disponibles, revisar los parámetros y las respuestas documentadas, y ejecutar solicitudes de prueba. La documentación está disponible en entorno local.

La especificación OpenAPI se encuentra en:

```text
resources/swagger/openapi.json
```

La API utiliza el prefijo:

```text
/api/v1
```

Puedes inspeccionar las rutas registradas con:

```bash
php artisan route:list --path=api
```

## 🔌 Endpoints disponibles

### Productos

| Método   | Endpoint                | Descripción                                                  |
| -------- | ----------------------- | ------------------------------------------------------------ |
| `GET`    | `/api/v1/products`      | Listar productos con paginación                              |
| `POST`   | `/api/v1/products`      | Crear un producto                                            |
| `GET`    | `/api/v1/products/{id}` | Consultar un producto                                        |
| `PUT`    | `/api/v1/products/{id}` | Actualizar un producto                                       |
| `PATCH`  | `/api/v1/products/{id}` | Actualizar un producto (reemplazo completo, igual que `PUT`) |
| `DELETE` | `/api/v1/products/{id}` | Eliminar un producto                                         |

Cada producto se asocia a una moneda base (`currency_id`) y expone `name`, `description`, `price`, `tax_cost`, `manufacturing_cost` y `currency`.

### Precios adicionales

| Método | Endpoint                       | Descripción                                      |
| ------ | ------------------------------ | ------------------------------------------------ |
| `GET`  | `/api/v1/products/{id}/prices` | Consultar los precios adicionales de un producto |
| `POST` | `/api/v1/products/{id}/prices` | Registrar un precio adicional                    |

- El producto se toma de la ruta; no debe enviarse `product_id` en el cuerpo.
- El cuerpo requiere `currency_id` y `price`.
- No se permite registrar más de un precio para la misma combinación de producto y moneda (`409`).

### Monedas

| Método   | Endpoint                  | Descripción                            |
| -------- | ------------------------- | -------------------------------------- |
| `GET`    | `/api/v1/currencies`      | Listar monedas disponibles             |
| `POST`   | `/api/v1/currencies`      | Crear una moneda                       |
| `DELETE` | `/api/v1/currencies/{id}` | Eliminar una moneda que no esté en uso |

- El código de la moneda debe tener 3 letras y es único; se normaliza a mayúsculas.
- Una moneda no se puede eliminar si es usada por un producto o por un precio de producto (`409`).

### Códigos de respuesta frecuentes

| Código | Significado                                              |
| ------ | -------------------------------------------------------- |
| `200`  | Consulta o actualización exitosa                         |
| `201`  | Recurso creado correctamente                             |
| `204`  | Recurso eliminado correctamente, sin cuerpo de respuesta |
| `404`  | El recurso de la ruta no existe                          |
| `409`  | Conflicto de negocio (precio duplicado o moneda en uso)  |
| `422`  | Datos inválidos o referencia a una moneda inexistente    |

Todas las respuestas se devuelven en formato JSON, excepto los `204`, que no tienen cuerpo.

## 👨‍💻 Autor

**Deyker Gil - @deykerag**

Proyecto desarrollado como parte de un reto técnico para demostrar conocimientos en desarrollo backend con Laravel y diseño de APIs RESTful.

---

_Gracias por revisar este proyecto._
