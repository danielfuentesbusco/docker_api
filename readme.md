# Dokcer / API

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

![Prueba 1](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_01.png "POST /login error")

## POST /new success

![Prueba 2](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_02.png "POST /new success")

## POST /login error

![Prueba 3](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_03.png "POST /login error")

## GET /me error

![Prueba 4](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_04.png "GET /me error")

## GET /me error

![Prueba 5](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_05.png "GET /me error")

## GET /me success

![Prueba 6](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_06.png "GET /me success")

## PUT /me success

![Prueba 7](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_07.png "PUT /me success")

## GET /me success

![Prueba 8](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_08.png "GET /me success")

## DELETE /me success

![Prueba 9](https://s3.amazonaws.com/elasticbeanstalk-us-east-1-122864340064/test_09.png "DELETE /me success")

# Evidencias en video

https://youtu.be/ncwDmsHFPsY
