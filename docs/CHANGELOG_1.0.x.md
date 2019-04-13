# Changelog 1.0.x


The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) and this project adheres to 
[Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [1.0.7 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.7) (2019-03-30)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.6...1.0.7)

### Added

- Optional port for queue (`Pheanstalk`) and fixed documentation about worker setting up. Issue 
[#288](https://github.com/php-censor/php-censor/issues/288).

### Fixed

- Config path for PHPCodeSniffer config. Issue [#287](https://github.com/php-censor/php-censor/issues/287).
- GitHub sources links for errors with only one line.

### Changed

- Improved code style.
- Improved documentation (About configuring projects).


## [1.0.6 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.6) (2019-03-06)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.5...1.0.6)

### Added

- Information about actual releases and release branches to `README.md`.

### Fixed

- Validation for fields `project.access_information` and `build.extra` in models `Project` and `Build`.

### Changed

- Improved code style.


## [1.0.5 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.5) (2019-02-10)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.4...1.0.5)

### Fixed

- Overload for plugin options: "directory" and "binary_path". Issue 
[#270](https://github.com/php-censor/php-censor/issues/270).
- Models creation for cases when we have more columns in DB then model fields (Case: new feature with new columns in 
the another branch). Issue [#270](https://github.com/php-censor/php-censor/issues/270).
- Guzzle version for correct Slack plugin working. Issue [#270](https://github.com/php-censor/php-censor/issues/270).
- Behavior of application config option `email_settings.from_address` for case when `from_address` like 
`test@test.test` without user name (Now the addresses like `test@test.test` will be transform automatically to format: 
`PHP Censor <test@test.test>`). Issue [#270](https://github.com/php-censor/php-censor/issues/270).

### Changed

- Improved documentation for plugins. Issue [#271](https://github.com/php-censor/php-censor/issues/271). Pull requests 
[#275](https://github.com/php-censor/php-censor/pull/275), [#273](https://github.com/php-censor/php-censor/pull/273), 
[#274](https://github.com/php-censor/php-censor/pull/274). Thanks to [@benr77](https://github.com/benr77).


## [1.0.4 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.4) (2019-02-02)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.3...1.0.4)

### Fixed

- Calls of the `chdir` command in plugins. Issue [#264](https://github.com/php-censor/php-censor/issues/264).
- Errors trend for the first build.

### Changed

- Improved documentation. Pull request [#267](https://github.com/php-censor/php-censor/pull/267). Thanks to 
[@ptejada](https://github.com/ptejada).


## [1.0.3 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.3) (2019-01-27)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.2...1.0.3)

### Fixed

- Errors trend processing (total errors count and previous build errors count).
- Rebuild without debug for builds with debug.
- PhpCodeSniffer and PhpMessDetector plugins output for non-debug mode.
- Codeception plugin config (codeception.yml) path. Issue [#262](https://github.com/php-censor/php-censor/issues/262).
- Paths with symlinks for plugins.
- Arrow icon for build errors trend for pending/running builds (Arrow removed).
- Method `getDiffLineNumber` for case errors without file (`$file = NULL`).


## [1.0.2 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.2) (2019-01-13)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.1...1.0.2)

### Fixed

- MySQL column types after updating Phinx version. Issue [#239](https://github.com/php-censor/php-censor/issues/239).


## [1.0.1 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.1) (2019-01-09)

[Full Changelog](https://github.com/php-censor/php-censor/compare/1.0.0...1.0.1)

### Fixed

- Migrations for MySQL. Issue [#249](https://github.com/php-censor/php-censor/issues/249).


## [1.0.0 (Morty Smith)](https://github.com/php-censor/php-censor/tree/1.0.0) (2019-01-08)

[Full Changelog](https://github.com/php-censor/php-censor/compare/0.25.0...1.0.0)

### Added

- Total errors trend to the dashboard for builds.
- `PHP_CENSOR_*` env variables (Like `PHPCI_*`).
- Several missing interpolation variables (`%PHPCI_BRANCH%`, `%PHPCI_BRANCH_URI%`, `%PHPCI_ENVIRONMENT%`).
- **A lot of notices about deprecated features for version 1.0 (It will be delete in version 2.0): cronjob worker 
(Use worker instead), `phpci.yml`/`.phpci.yml` configs (Use `.php-censor.yml` instead), a lot of plugin options, 
`PHPCi_*` interpolation and env variables etc.**

### Fixed

- Wrong namespace in BuildInterpolator for PHP version 5.6.
- Wrong namespace in PHPSpec plugin constructor.
