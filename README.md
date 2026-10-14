#  UniRide — Plataforma de Movilidad Estudiantil Compartida

> *"Conecta tu ruta, comparte el camino."*

**UniRide** es una aplicación web de *carpooling* universitario diseñada para conectar a estudiantes que se desplazan hacia el campus universitario (**Sede Principal** y **Sede La Primavera**). La plataforma permite compartir vehículos de forma segura, económica y colaborativa dentro de una comunidad exclusivamente estudiantil.

---

##  Características Principales

###  Seguridad e Identidad Estudiantil
* **Registro validado:** Captura de documento de identidad, código estudiantil y correo institucional (arquitectura preparada para conexión con API universitaria de validación de carnet y fotografía).
* **Sistema Dual de Roles (Switch en Vivo):** Un mismo estudiante puede alternar con un solo clic entre **Modo Pasajero ** y **Modo Conductor ** sin necesidad de crear cuentas separadas.
* **Gestión de Perfil Contextual:** Visualización y edición de datos personales adaptada al rol activo del usuario.

###  Módulo de Pasajero
* **Puntos de Recogida Geolocalizados:** Creación, edición y eliminación de paradas exactas mediante un mapa interactivo (**Leaflet + OpenStreetMap**), definiendo días, hora de salida y aporte económico para gasolina.
* **Radar de Compatibilidad Matemática:** Algoritmo basado en la **Fórmula de Haversine** que calcula la distancia real en kilómetros entre el pasajero y las rutas activas de los conductores, mostrando un porcentaje de compatibilidad (`0%` a `100%`) y los cupos libres en tiempo real.
* **Gestión de Viajes:** Seguimiento del estado de las solicitudes (`Pendiente`, `Aceptado`, `Rechazado`, `Cancelado`, `Realizado`) con bloqueo inteligente contra solicitudes duplicadas.

###  Módulo de Conductor
* **Mi Garaje (Gestión de Vehículos):** Registro, edición y eliminación de vehículos con reglas de negocio automáticas para la capacidad de cupos:
  *  **Moto:** Máximo **1 pasajero**.
  *  **Carro:** Entre **1 y 3 pasajeros**.
* **Trazado de Rutas:** Publicación de rutas en el mapa vinculadas a un vehículo específico, días de la semana y horarios de circulación.
* ** Mapa de Calor de Demanda (`Leaflet.heat`):** Visualización térmica en tiempo real de las zonas donde se concentran más pasajeros esperando transporte, con filtro por sede universitaria y opción para **trazar una ruta directamente desde una zona roja de alta demanda**.
* **Control de Solicitudes y Cupos:** Aceptación o rechazo de pasajeros validando automáticamente la capacidad disponible del vehículo asignado a la ruta.

###  Sistema de Reputación Mutua
* **Calificación Bidireccional:** Al finalizar un viaje (`Realizado`), tanto el pasajero como el conductor pueden calificarse mutuamente de **1 a 5 estrellas** y dejar una reseña.
* **Promedio en Tiempo Real:** Visualización de la reputación promedio del conductor en el Radar y del pasajero en las Solicitudes Recibidas.
* **Historial de Reseñas:** Apartado dedicado (` Mis Reseñas`) donde el estudiante consulta su puntuación promedio como conductor y como pasajero junto con los comentarios recibidos.

---

##  Tecnologías Utilizadas

| Capa | Tecnología |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 12 |
| **Base de Datos** | PostgreSQL (Arquitectura relacional personalizada gestionada en pgAdmin 4) |
| **Frontend & UI** | Blade Templates, Tailwind CSS (Diseño *Mobile-First*), Google Fonts (`Montserrat` & `Roboto`) |
| **Mapas y Geolocalización** | Leaflet.js, OpenStreetMap, Leaflet.heat |
| **Autenticación y Seguridad** | Laravel Auth personalizado (`usuarios`), Middlewares de rol (`es.conductor`, `es.pasajero`) |

---

##  Requisitos Previos

* **PHP** >= 8.2 (con las extensiones `pdo_pgsql` y `pgsql` habilitadas en `php.ini`)
* **Composer**
* **PostgreSQL** >= 14 y **pgAdmin 4**
* Servidor local (XAMPP / Laragon / Herd)

---

##  Instalación y Configuración

### 1. Clonar el repositorio e instalar dependencias
```bash
git clone [https://github.com/yohanorlan2112/UniRide.git]
cd uniride
composer install
```

### 2. Configurar las variables de entorno
Copia el archivo `.env.example` a `.env` y configura la conexión a **PostgreSQL** y el controlador de sesiones en archivo:

```env
APP_NAME=UniRide
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=uniride_db
DB_USERNAME=postgres
DB_PASSWORD=tu_contrasena

SESSION_DRIVER=file
```

### 3. Preparar la Base de Datos en PostgreSQL
1. Abre **pgAdmin 4** y crea una base de datos llamada **`uniride_db`**.
2. Ejecuta el script SQL del esquema relacional del proyecto para crear las tablas (`usuarios`, `roles`, `roles_usuarios`, `perfiles_conductores`, `perfiles_pasajeros`, `vehiculos`, `rutas_conductores`, `horarios_rutas`, `solicitudes_pasajeros`, `horarios_solicitudes`, `viajes`, `calificaciones`, `dias_semana`, `ubicaciones`).
3. Asegúrate de tener insertados los roles base (`conductor`, `pasajero`) en la tabla `roles` y los días de la semana en `dias_semana`.

### 4. Generar llave de aplicación y limpiar caché
```bash
php artisan key:generate
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 5. Iniciar el servidor de desarrollo
```bash
php artisan serve
```
Accede desde tu navegador a: **`http://127.0.0.1:8000`**

---

##  Estructura Clave del Proyecto

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login, Registro con código estudiantil y Logout
│   │   ├── PerfilController.php        # Switch Conductor/Pasajero y gestión de datos personales
│   │   ├── VehiculoController.php      # CRUD de vehículos y validación de cupos (Moto=1, Carro=3)
│   │   ├── RutaConductorController.php # CRUD de rutas geolocalizadas y Mapa de Calor
│   │   ├── SolicitudController.php     # CRUD de puntos de recogida del pasajero
│   │   ├── MatchController.php         # Radar de compatibilidad con Fórmula de Haversine
│   │   ├── ViajeController.php         # Ciclo de vida del viaje y control de asientos disponibles
│   │   └── CalificacionController.php  # Evaluación mutua (1-5 estrellas) e historial de reseñas
│   └── Middleware/
│       ├── EsConductor.php             # Protección de rutas exclusivas de conductores
│       └── EsPasajero.php              # Protección de rutas exclusivas de pasajeros
└── Models/
    ├── Usuario.php                     # Modelo de autenticación principal y cálculo de reputación
    ├── PerfilConductor.php / PerfilPasajero.php
    ├── Vehiculo.php / RutaConductor.php
    ├── SolicitudPasajero.php / Viaje.php
    └── Calificacion.php
```

---

##  Próximas Mejoras (Roadmap)

* [ ] **Integración con API Institucional:** Validación automática del código estudiantil activo y carga de fotografía oficial del carnet universitario.
* [ ] **Verificación Documental:** Carga y validación de documentos del vehículo (SOAT, Tecnomecánica y Licencia de Conducción).
* [ ] **Rastreo GPS en Vivo:** Actualización en tiempo real de la ubicación del vehículo durante el trayecto activo.

---

##  Autores
Yohan Orlando Perez Jimenez , 
Jose Manuel Perez Paez ,
Osdania Martines Amaya  
Proyecto académico desarrollado para optimizar la movilidad estudiantil universitaria.