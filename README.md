Symfony Bootstrap
=================

Bootstrap para usar los componentes de symfony en apps ya creadas.

Componentes agregados:

Routing
------

Se maneja mediante un controlador frontal **web/app_dev.php**, permite definir las rutas en archivos yml, ejemplo:

```yml
#_app/config/routes/routing.yml

home:
    path: /
    defaults:
        _file: home.php
```

El controlador frontal funciona de la siguiente manera:

```php
<?php # web/app_dev.php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$app = new App('dev', true);

$app->run($request);
```
