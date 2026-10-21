# Graph Report - sigps-app  (2026-10-21)

## Corpus Check
- 64 files · ~14,354 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 297 nodes · 407 edges · 27 communities (22 shown, 5 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e55677db`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- UserFactory.php
- Illuminate\Database\Migrations\Migration
- package.json
- Usuario
- config
- TestCase
- AppServiceProvider
- LoginController.php
- Illuminate\Database\Eloquent\Factories\HasFactory
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh

## God Nodes (most connected - your core abstractions)
1. `Proyecto` - 10 edges
2. `Usuario` - 10 edges
3. `require-dev` - 9 edges
4. `scripts` - 9 edges
5. `LoginRequest` - 8 edges
6. `UpdateProfileRequest` - 7 edges
7. `Memoria` - 7 edges
8. `setup` - 7 edges
9. `LoginController` - 6 edges
10. `Carrera` - 6 edges

## Surprising Connections (you probably didn't know these)
- `User` --inherits--> `Usuario`  [EXTRACTED]
  app/Models/User.php → app/Models/Usuario.php

## Import Cycles
- None detected.

## Communities (27 total, 5 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.06
Nodes (35): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+27 more)

### Community 1 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 3 - "UserFactory.php"
Cohesion: 0.20
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

### Community 4 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.09
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, @laravel/multiplex, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+12 more)

### Community 6 - "Usuario"
Cohesion: 0.10
Nodes (15): App\Models\User, User, Usuario, DatabaseSeeder, RoleAndPermissionSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Seeder (+7 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 8 - "TestCase"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Testing\TestCase, ExampleTest, Tests\TestCase, TestCase

### Community 10 - "LoginController.php"
Cohesion: 0.08
Nodes (20): LoginController, ProfileController, App\Http\Controllers\Controller, Controller, LoginRequest, UpdateProfileRequest, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Application (+12 more)

### Community 11 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.09
Nodes (17): AreaConocimiento, Carrera, Departamento, Estudiante, LogAccion, Memoria, Plantilla, Proyecto (+9 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **61 isolated node(s):** `Controller`, `description`, `dont-discover`, `license`, `minimum-stability` (+56 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **5 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Why does `Usuario` connect `Usuario` to `Illuminate\Database\Eloquent\Factories\HasFactory`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **Why does `UserFactory` connect `UserFactory.php` to `Usuario`?**
  _High betweenness centrality (0.014) - this node is a cross-community bridge._
- **What connects `Controller`, `description`, `dont-discover` to the rest of the system?**
  _61 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.05555555555555555 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Migrations\Migration` be split into smaller, more focused modules?**
  _Cohesion score 0.0873015873015873 - nodes in this community are weakly interconnected._