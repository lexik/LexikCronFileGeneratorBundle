UPGRADE FROM 1.x to 2.0
=======================

Requirements
------------

* PHP 8.1 or higher is required.
* Symfony 6.4, 7.4 or 8.x is required (support for Symfony 4.4, 5.4 and 6.0 to 6.3 is dropped).
* Twig 3 is required (support for Twig 2 is dropped), with a lower bound raised to
  a version without known security advisories.
* Services are now loaded from PHP configuration files instead of XML ones. Service IDs
  and the `Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile` alias are unchanged.

Command
-------

* The command name and description are now declared with the `#[AsCommand]` attribute.
  The command is still available as `lexik:cron:generate-file`.
* The `$name` argument of the `GenerateCronCommand` constructor has been removed.

  Before:

  ```php
  new GenerateCronCommand($dumpFileFactory, 'app:my-cron-command');
  ```

  After:

  ```php
  new GenerateCronCommand($dumpFileFactory);
  ```

* The `console.command` tag of the
  `lexik_bundle_cron_file_generator.command.generate_cron_command` service no longer
  carries a `command` option. If you extend `GenerateCronCommand` or re-tag the service
  and relied on that option to set a custom name, declare it on your side, either with
  `#[AsCommand]` on your subclass or with the `command` option of your own tag.

Dependency conflicts
--------------------

The `conflict` section of `composer.json` excludes versions of some transitive Symfony
dependencies (`symfony/cache`, `symfony/http-foundation`, `symfony/http-kernel`,
`symfony/routing`) affected by known security advisories. These bounds are revised
when new advisories are published; `composer audit` runs in the CI as a safeguard.
