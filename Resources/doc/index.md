Getting started
===============

This bundle provides a way to generate a cron file from your configuration per environment.

The cron is generated in the following format, extracted from [template](/Resources/views/template.txt.twig)

```twig
{{ cron.expression }} {{ user }} {{ php_version }} {{ absolute_path }} {{ cron.command }} --env={{ env }}
```

All the parameters are required.

Configuration
-------------

Example with the following context:

* You have two environments: prod and staging
* One user project_staging on your staging server and project_prod on your prod server
* The php version is the same for each server.
* Absolute_path is the absolute path to your console binary (bin/console).
* Output_path will be the path where the cron file will be created.
* "crons" is an array of cron commands.

**Note** Having different PHP versions is not possible and not recommended.

Configure your crons per env in your `config/packages/lexik_cron_file_generator.yaml`:

``` yaml
lexik_cron_file_generator:
  env_available:                 # declare your available environements
    - staging                    # example: staging and prod
    - prod
  user:
    staging: project_staging
    prod: project_prod
  php_version: php8.2
  absolute_path:
    staging: path/to/staging/bin/console
    prod: path/to/prod/bin/console
  output_path: '%kernel.cache_dir%/cron_test'
  crons:
    - { name: 'Send email', command: 'app:test', env: { staging: '* * * * *', prod: '* 5 * * *' } }

```

As you can see you just have to configure your crons for each environment.

Command
-------

To generate the file, just execute the command:

``` bash
bin/console lexik:cron:generate-file prod // --dry-run 
```

The output with the file path:

``` bash
Generated cron file
==================

 [OK] File generated

 ! [NOTE] path : /my-project/var/cache/cron_test
```

The file content:

``` bash
# Send email

* * * * * project_staging 8.2 path/to/prod app:test --env=prod
```

Bonus
-----

You can use this file in your Continous Deployment flow
