# Haulmer API

El archivo [Dockerfile](Dockerfile) define los comandos necesarios para montar el servicio de API en cualquier host compatible con Docker.
El archivo [apache-config.conf](apache-config.conf) define la configuración de apache para disponibilizar la API.
El archivo [api.raml](api.raml) contiene la documentación de la API.
El archivo [.env](.env) define el endpoint del servicio mockAPI y el seed del token de JWT.
Se toma como condición que el correo electrónico sea único en la base de datos.


# Archivos intervenidos

```
routes/web.php
app/Http/Controllers/AuthController.php
app/Http/Controllers/UserController.php
app/Http/Middleware/JwtMiddleware.php
```

# Dependencias

- [Laravel](https://laravel.com/)
- [Lumen](https://lumen.laravel.com/)
- [Jwt](https://jwt.io/)
- [Guzzle Http](http://docs.guzzlephp.org/en/stable/)

# mockAPI

Para el registro de información se utiliza el servicio [mockAPI](https://www.mockapi.io/)

# JWT

Para el control de usuarios y sesión se utilizan token generados por la librería [Jwt](https://jwt.io/)

# Docker

Para empaquetar la solución se utiliza [Docker](https://www.docker.com/), el cual una vez instalado se deben seguir los siguientes pasos.

Para crear la imagen de Docker ejecutar la siguiente instrucción:

```
docker build --no-cache -t {IMAGE_NAME} .
```

Para ejecutar la imagen ejecutar el siguiente comando (importante: el puerto 8000 debe estar disponible en la máquina host, de otra forma utiliza otro puerto):

```
docker run -d -p 127.0.0.1:8000:80/tcp {IMAGE_NAME}
```

La ejecución de los comandos anteriores debería generar los siguientes endpoints y métodos disponibles (Tener en consideración el puerto utilizado al ejecutar la imagen):

```
POST:
http://localhost:8000/api/new

POST:
http://localhost:8000/api/login

GET, PUT, DELETE
http://localhost:8000/api/me

```

# Evidencias en imagen

## POST /login error

![Prueba 1](test/images/test_01.png?raw=true "POST /login error")

## POST /new success

![Prueba 2](test/images/test_01.png?raw=true "POST /new success")

## POST /login error

![Prueba 3](test/images/test_01.png?raw=true "POST /login error")

## GET /me error

![Prueba 4](test/images/test_01.png?raw=true "GET /me error")

## GET /me error

![Prueba 5](test/images/test_01.png?raw=true "GET /me error")

## GET /me success

![Prueba 6](test/images/test_01.png?raw=true "GET /me success")

## PUT /me success

![Prueba 7](test/images/test_01.png?raw=true "PUT /me success")

## GET /me success

![Prueba 8](test/images/test_01.png?raw=true "GET /me success")

## DELETE /me success

![Prueba 9](test/images/test_01.png?raw=true "DELETE /me success")

# Evidencias en video

https://youtu.be/ncwDmsHFPsY