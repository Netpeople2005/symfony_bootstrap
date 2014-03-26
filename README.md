Symfony Bootstrap
=================

Bootstrap para usar los componentes de symfony en apps ya creadas.

Componentes agregados:

Routing
------

Se maneja mediante un controlador frontal **web/app_dev.php**, permite definir las rutas en archivos yml, ejemplo:

```yaml
home:
    path: /
    defaults:
        _file: home.php
```
