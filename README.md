Symfony Bootstrap
=================

Bootstrap para usar los componentes de symfony en apps ya creadas.

Componentes agregados:

Routing
------

Se maneja mediante un controlador frontal **web/app_dev.php**, permite definir las rutas en archivos yml, ejemplo:

```yml
# _app/config/routes/routing.yml

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

El valor de _file es la ruta a un archivo php, escrita desde la raiz del proyecto, ejemplos:

```yml
_file: home.php
_file: pages/user/info.php
_file: pages/user_info.php
_file: admin/home.php
```

Para cambiar el dir desde donde se buscan los archivos controladores se hace en **_app/config/config.yml**:

```yml
# _app/config/config.yml

application:
    # controller_dir: %root_dir%
    controller_dir: %root_dir%/controllers/
    # tambien puede ser
    controller_dir: %root_dir%/../pages/
```

**Manejando variables globales**
___

Muy posiblemente hagamos uso de variables globales en nuestras app, para poder seguirlas usando debemos indicar sus nombres en el **_app/config/config.yml**:

```yml
# _app/config/config.yml

application:
    globals: 
        # Aca definimos las variables globales que queremos tener disponibles en los controladores.
        - request
        - sessionManager
        - security
        - pagesManager
        - ...
```

Twig
----

Para usarlo, lo hacemos mediante la clase **App**:

```php
<?php # algun/controlador.php

# Para obtener o imprimir templates como strings:

echo App::get("twig")->render("home.twig", array('name' => 'Manuel'));
echo App::get("twig")->render("home.twig");

# Para devolver una respuesta desde el archivo controlador:

return App::render("home.twig", array('name' => 'Manuel'));
return App::render("home.twig");

```

Servicios (DependencyInjection)
=================

Se hace uso del componente de inyeccion de dependencias de symfony, las mimas se registran en **_app/config/services/***, y se acceden desde la clase **App**:

```php
<?php # algun/controlador.php

echo App::get("twig")->render("home.twig");

App::get("router");

App::get("session");

App::get("event_dispatcher");
App::get("service_container");


```

Parametros
------

```php
<?php # algun/controlador.php

echo App::getParameter("debug");
echo App::getParameter("environment");
echo App::getParameter("root_dir");


```

