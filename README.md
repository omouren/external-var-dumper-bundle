# ExternalVarDumperBundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/694ddc50-0dab-4d11-962d-e973404d8ce2/mini.png)](https://insight.sensiolabs.com/projects/694ddc50-0dab-4d11-962d-e973404d8ce2)

The **ExternalVarDumperBundle** allows you to redirect Symfony dumps (VarDumper) to an external web service. Usefull to debug applications like REST APIs, background console commands or other applications that you don't have a direct output or that you can't break with a dump in the middle of the response.

In case of a classic request, queries to the external web service will occure after the response, in the kernel terminate.
In case of a console command, it will occure instantanetly (to allow to debug console command that run indefinitely like consumers)

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
    replace_dumper: false       # Define if dumps should be visible only on the external service
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
    "content":"html content of the symfony dump"
}
```

## VarDumper Viewer
Viewer in Vue.js to catch and see your dumps :
[external-var-dumper-viewer](https://github.com/omouren/external-var-dumper-viewer)

With Pre-built [Docker image](https://hub.docker.com/r/omouren/external-var-dumper-viewer/)
``` bash
# By default, internaly server use port 8080
$ docker run -p 80:8080 -ti omouren/external-var-dumper-viewer:latest
# You can override it with custom port by an environment variable
$ docker run -e "PORT=1337" -p 80:1337 -ti omouren/external-var-dumper-viewer:latest
# Now go to http://localhost:80
```
