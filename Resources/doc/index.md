Getting started
===============

This bundle provide a way for generate a cron file from your configuration per environment.

The cron is generate with the following format, extracted from [template](/Resources/views/template.txt.twig)

```twig
{{ cron.expression }} {{ user }} {{ php_version }} {{ absolute_path }} {{ cron.command }} --env={{ env }}
```

All the parameters are required.

Configuration
-------------

Example with the following context:
You have two environments : prod and staging
One user project_staging on your staging server and project_prod on your prod server
The php version is the same for each server.
Absolute_path is the path for your project.
Output_path will be the path where the file will be create.
Crons is an array of cron per env.

**Note** Have different php version is not possible and not recommended.

Configure your crons per env in your `config/packages/lexik_cron_file_generator.yaml`:

``` yaml
lexik_cron_file_generator:
  env_available:                 # declare your env availables
    - staging                    # exemple: staging and prod
    - prod
  user:
    staging: project_staging
    prod: project_prod
  php_version: php7.3
  absolute_path:
    staging: path/to/staging
    prod: path/to/prod
  output_path: '%kernel.cache_dir%/cron_test'
  crons:
    - { name: 'Send email', command: 'app:test', env: { staging: '* * * * *', prod: '* 5 * * *' } }

```

As you can see you just have to configure your crons for each environment.

Command
-------

For generate the file just execute the command:

``` bash
bin/console lexik:cron:generate-file --env-mode=prod  // --dry-run 
```

The output with the file path:

``` bash
Generate cron file
==================

 [OK] File generated

 ! [NOTE] path : /my-project/var/cache/cron_test
```

The file content:

``` bash
# Send email

* * * * * project_staging php7.3 path/to/prod app:test --env=prod
```

Bonus
-----

You can use this file with you CI when you deploy!
