# Graph Report - sigps-app  (2026-10-14)

## Corpus Check
- 58 files · ~12,004 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 273 nodes · 410 edges · 26 communities (19 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ac5f1073`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Eloquent\Factories\HasFactory
- User
- Illuminate\Database\Migrations\Migration
- package.json
- require-dev
- AppServiceProvider
- bootstrap/app.php
- ModelRelationshipsTest.php
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
10. `Permiso` - 8 edges

## Surprising Connections (you probably didn't know these)
- `User` --inherits--> `Usuario`  [EXTRACTED]
  app/Models/User.php → app/Models/Usuario.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → tests/TestCase.php
- `ModelRelationshipsTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ModelRelationshipsTest.php → tests/TestCase.php

## Import Cycles
- None detected.

## Communities (26 total, 7 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.06
Nodes (32): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+24 more)

### Community 1 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 2 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.10
Nodes (17): AreaConocimiento, Carrera, Departamento, Estudiante, Permiso, Plantilla, Rol, Usuario (+9 more)

### Community 3 - "User"
Cohesion: 0.13
Nodes (10): User, UserFactory, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder, Illuminate\Support\Facades\Hash, Illuminate\Support\Str (+2 more)

### Community 4 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.09
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, @laravel/multiplex, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+12 more)

### Community 7 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 10 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 11 - "ModelRelationshipsTest.php"
Cohesion: 0.08
Nodes (11): LogAccion, Memoria, Proyecto, VersionPlantilla, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\SoftDeletes, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase (+3 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **60 isolated node(s):** `Controller`, `$schema`, `name`, `type`, `description` (+55 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Usuario` connect `Illuminate\Database\Eloquent\Factories\HasFactory` to `ModelRelationshipsTest.php`, `User`?**
  _High betweenness centrality (0.041) - this node is a cross-community bridge._
- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Database\Eloquent\Factories\HasFactory`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **What connects `Controller`, `$schema`, `name` to the rest of the system?**
  _60 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.06060606060606061 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Eloquent\Factories\HasFactory` be split into smaller, more focused modules?**
  _Cohesion score 0.10202020202020202 - nodes in this community are weakly interconnected._