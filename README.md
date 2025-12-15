ShockingSales<br>
En este repositorio se subiran los avances del proyecto del semestre 4 de la tecnicatura de redes y software por parte de SSTeam.<br><br>
Equipo Formado por:<br>
-Martán Leites<br>
-Matías Franca De Lima<br>
-Miguel Malavé<br>
-Santiago Cabrera<br>
-Nicolás Núñez<br>


Guia de despliegue:<br>
Requisitos previos para usar el programa: <br>
-docker instalado <br>
-docker compose instalado <br>
-sistema operativo linux <br>
-npm instalado <br>
-composer instalado

1) Clonar repositorio
2) Usar el comando "docker network create shocking-sales-network" para crear la red que conecta a todos los contenedores
3) Entrar a las carpetas api-tech-store/src/ y api-toys-store/src/ y hacer estos comandos en orden:
-cp .env.example .env <br>
-composer install <br>
-sudo chmod -R 777 . <br>
-npm install <br>
-php artisan key:generate <br>
-docker compose up -d --build <br>
-docker exec -it api-web-nombreapi bash <br>
-php artisan migrate:fresh --seed
4) Volver a la carpeta principal(shockingsales) y establecer todos los archivos con permisos totales usando "chmod -R 777 ."
5) Hacerle build y up usando "docker compose up -d --build"
6) Entrar al bash de php usando "docker exec -it web bash" y migrar usando "php artisan migrate:fresh --seed"
7) Entrar a "localhost:8080" en el navegador de preferencia.

Evidencias del proyecto pueden ser encontradas <a href="https://github.com/matias-fdlv/shockingsales/tree/main/Explicaciones%20y%20auditoria">aqui</a> 

CHANGELOG
---

[0.1.0] - 2025-08-20<br>
Añadido<br>
-Migraciones a la BD desde laravel<br>
-Contenedores Docker pefectamente funcionando, con contenedor de PHP, BD y PHPMYadmin

---

[1.0.0] - 2025-08-30<br>
Añadido<br>
-Se agrega LogIn y SignIn<br>
-Se agrega CRUD básico

---

[1.1.0] - 2025-09-16<br>
Añadido<br>
-Vistas de usuarios y admin separadas

---

[1.2.0] - 2025-09-23<br>
Cambios<br>
-Mejor estructuración del registro de usuarios, uso de guards para sesiones, uso del patrón de diseño Service Layout

---

[1.3.0] - 2025-09-24<br>
Cambios<br>
-Actualización en tablas de la BD y modelos de laravel relacionados a estas

---

[1.4.0] - 2025-09-30<br>
Añadido<br>
-Se agrega seeder para Administrador

---

[2.0.0] - 2025-10-12<br>
Añadido<br>
-Busqueda a traves de la barra de busqueda usando la Fake Store API, esta solo permite busqueda por categorías y en ingles.<br>
-Se añade un seeder para las APIs de cada tienda.<br>
-docker-compose.yml ahora incluye un nuevo contenedor MYSQL usado para la replicacion.

Corrección de bugs<br>
-Se arregla un bug que mataba a los contenedores de BD y PHP segundos despues de iniciarlos por culpa de datos que no concordaban entre versiones de la imágen docker MySQL que usabamos antes y la que usamos ahora.

---

[2.1.0] - 2025-10-12<br>
Añadido<br>
-Integración de un perfil para usuarios(Tanto administradores como usuarios comunes), en el se ven los datos de estos y actualizar su información.<br>
-Actualizazion del estilo css para el registro de Usuario y el de Administradores.

---

[2.1.1] - 2025-10-12<br>
Correción de vista<br>
-Se arregló la vista de Registro de Usuario para que coincida parcialmente con la de Registro de Administrador,

---

[2.2.0] - 2025-10-12<br>
Añadido<br>
-Se configuró y modificó el contenedor slave para su uso en la réplica de la base de datos.

---

[2.3.0] - 2025-11-11<br>
Añadido<br>
-Implementación del 2FA para el login
<br>
-Mejora en el los estilos de las vistas 
<br>
-Implementacion del proxysql y de la relacion master-slave
<br>
-Reorganización de las vistas y el código

---

[2.4.0] - 2025-11-17<br>
Añadido<br>
-APIs reales, conectadas mediante docker networks
<br>
-Conexión total con estas apis, permitiendo al usuario buscar productos de dichas APIs mediante busquedas seguras.
<br>
-Interfaz de comparación, el usuario al hacer click a un producto es enviado a una interfaz donde puede ver todos los precios de productos iguales en otras tiendas.
<br>
-Cambio a la busqueda, ahora muestra todos los productos que concuerdan con lo escrito, pero muestra el mas barato de las tiendas que hay disponibles.

Modificado<br>
-ProxySQL desactivado de momento (fue todo comentado) por problemas de conexión con este.
-La conexión con la API ahora es mediante un flujo mejorado.

---
