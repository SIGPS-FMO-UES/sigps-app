# Graph Report - sigps-app  (2026-10-15)

## Corpus Check
- 56 files · ~12,713 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 261 nodes · 348 edges · 29 communities (22 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0ae51f99`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Eloquent\Relations\HasMany
- UserFactory.php
- Illuminate\Database\Migrations\Migration
- package.json
- TestCase
- config
- require-dev
- AppServiceProvider
- bootstrap/app.php
- Illuminate\Database\Eloquent\Factories\HasFactory
- logging.php
- ExampleTest
- console.php
- Controller.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Support\Facades\Route
- permission.php

## God Nodes (most connected - your core abstractions)
1. `Proyecto` - 10 edges
2. `Usuario` - 10 edges
3. `require-dev` - 9 edges
4. `scripts` - 9 edges
5. `Memoria` - 7 edges
6. `setup` - 7 edges
7. `Carrera` - 6 edges
8. `LogAccion` - 6 edges
9. `Plantilla` - 6 edges
10. `User` - 6 edges

## Surprising Connections (you probably didn't know these)
- `User` --inherits--> `Usuario`  [EXTRACTED]
  app/Models/User.php → app/Models/Usuario.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (29 total, 7 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.07
Nodes (26): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+18 more)

### Community 1 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 2 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.16
Nodes (10): User, Usuario, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Database\Seeder, Illuminate\Foundation\Auth\User (+2 more)

### Community 3 - "UserFactory.php"
Cohesion: 0.20
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

### Community 4 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.09
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, @laravel/multiplex, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+12 more)

### Community 6 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 8 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 10 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 11 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.08
Nodes (16): AreaConocimiento, Carrera, Departamento, Estudiante, LogAccion, Memoria, Plantilla, Proyecto (+8 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "permission.php"
Cohesion: 0.50
Nodes (3): Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission, Spatie\Permission\Models\Role

## Knowledge Gaps
- **61 isolated node(s):** `Controller`, `$schema`, `name`, `type`, `description` (+56 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Why does `Usuario` connect `Illuminate\Database\Eloquent\Relations\HasMany` to `Illuminate\Database\Eloquent\Factories\HasFactory`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `User` connect `Illuminate\Database\Eloquent\Relations\HasMany` to `UserFactory.php`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **What connects `Controller`, `$schema`, `name` to the rest of the system?**
  _61 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Migrations\Migration` be split into smaller, more focused modules?**
  _Cohesion score 0.0873015873015873 - nodes in this community are weakly interconnected._