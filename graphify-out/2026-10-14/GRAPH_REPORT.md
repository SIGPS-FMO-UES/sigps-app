# Graph Report - sigps-app  (2026-10-14)

## Corpus Check
- 57 files · ~13,161 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 270 nodes · 388 edges · 27 communities (20 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ac5f1073`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Usuario
- UserFactory.php
- Illuminate\Database\Migrations\Migration
- package.json
- ModelRelationshipsTest.php
- config
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

## God Nodes (most connected - your core abstractions)
1. `Usuario` - 14 edges
2. `Proyecto` - 12 edges
3. `Carrera` - 9 edges
4. `Memoria` - 9 edges
5. `require-dev` - 9 edges
6. `scripts` - 9 edges
7. `ModelRelationshipsTest` - 9 edges
8. `Estudiante` - 8 edges
9. `LogAccion` - 8 edges
10. `Plantilla` - 8 edges

## Surprising Connections (you probably didn't know these)
- `User` --inherits--> `Usuario`  [EXTRACTED]
  app/Models/User.php → app/Models/Usuario.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php
- `ModelRelationshipsTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ModelRelationshipsTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (27 total, 7 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.06
Nodes (35): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+27 more)

### Community 1 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 2 - "Usuario"
Cohesion: 0.13
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

### Community 6 - "ModelRelationshipsTest.php"
Cohesion: 0.10
Nodes (10): Plantilla, VersionPlantilla, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission, Spatie\Permission\Models\Role, ExampleTest (+2 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 10 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 11 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.11
Nodes (14): AreaConocimiento, Carrera, Departamento, Estudiante, LogAccion, Memoria, Proyecto, Illuminate\Database\Eloquent\Attributes\Fillable (+6 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **61 isolated node(s):** `Controller`, `$schema`, `name`, `type`, `description` (+56 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Usuario` connect `Usuario` to `Illuminate\Database\Eloquent\Factories\HasFactory`, `ModelRelationshipsTest.php`?**
  _High betweenness centrality (0.044) - this node is a cross-community bridge._
- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Why does `User` connect `Usuario` to `UserFactory.php`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **What connects `Controller`, `$schema`, `name` to the rest of the system?**
  _61 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.05555555555555555 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Usuario` be split into smaller, more focused modules?**
  _Cohesion score 0.12987012987012986 - nodes in this community are weakly interconnected._