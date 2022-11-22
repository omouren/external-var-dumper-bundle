# ExternalVarDumperBundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/694ddc50-0dab-4d11-962d-e973404d8ce2/mini.png)](https://insight.sensiolabs.com/projects/694ddc50-0dab-4d11-962d-e973404d8ce2)

The **ExternalVarDumperBundle** allows you to redirect Symfony dumps (VarDumper) to an external web service. Usefull to debug applications like REST APIs, background console commands or other applications that you don't have a direct output or that you can't break with a dump in the middle of the response.

## Installation

Via Composer

``` bash
$ composer require omouren/external-var-dumper-bundle
```

Register the bundle in the application kernel :

```php
<?php
// app/AppKernel.php
// ...
public function registerBundles()
{
    $bundles = [
        // ...
        new Omouren\ExternalVarDumperBundle\OmourenExternalVarDumperBundle(),
        // ...
    ];
// ...
```

Add configuration (optional) :

```yml
# app/config/config.yml
omouren_external_var_dumper:
    uri: http://localhost:1337  # Uri to use for the query to the external service
    method: post                # HTTP method used by the query to the external service
    app_name: Symfony           # Added to the query to identify your dump source
    enabled: false              # Define if dumps are send to the external service
```

Usage
=====

```php
<?php
// ...
dump($var);
```

It will send a query to the external web service in JSON:
```json
{
    "app":"Symfony",
    "id":"sf-dump-1542914713",
    "content":"html content of the symfony dump",
    "source":{
        "name":"DefaultController.php",
        "line":21
    },
    "datetime":"2018-04-06T20:04:37+00:00"
}
```

## VarDumper Viewer
Viewer in Vue.js to catch and see your dumps :
[external-var-dumper-viewer](https://github.com/omouren/external-var-dumper-viewer)

With Pre-built [Docker image](https://hub.docker.com/r/omouren/external-var-dumper-viewer/)
``` bash
# By default, internaly server use port 8080
$ docker run --rm -p 80:8080 -ti omouren/external-var-dumper-viewer:latest
# You can override it with custom port by an environment variable
$ docker run --rm -e "PORT=1337" -p 80:1337 -ti omouren/external-var-dumper-viewer:latest
# Now go to http://localhost:80
```

![Var Dumper Viewer](https://raw.githubusercontent.com/omouren/external-var-dumper-viewer/master/screenshot.png)
