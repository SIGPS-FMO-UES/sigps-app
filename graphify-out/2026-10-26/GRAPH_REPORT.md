# Graph Report - sigps-app  (2026-10-26)

## Corpus Check
- 150 files · ~62,589 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 644 nodes · 1478 edges · 70 communities (60 shown, 10 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6d28af1d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- ExpedienteRecibidoNotification
- Illuminate\Database\Migrations\Migration
- package.json
- Illuminate\View\View
- AppServiceProvider
- User
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- ProcesoExtraordinario
- Proyecto.php
- bootstrap/app.php
- AreaConocimiento
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Database\Eloquent\Model
- Illuminate\Http\Request
- Memoria
- Plantilla
- LoginRequest
- RevisionMemoria
- Illuminate\Database\Eloquent\Relations\BelongsTo
- UserFactory.php
- ProyectoController
- sanctum.php
- TestCase

## God Nodes (most connected - your core abstractions)
1. `Proyecto` - 77 edges
2. `Controller` - 65 edges
3. `LogAccion` - 48 edges
4. `Carrera` - 32 edges
5. `Estudiante` - 26 edges
6. `AreaConocimiento` - 25 edges
7. `Memoria` - 25 edges
8. `Departamento` - 24 edges
9. `ProcesoExtraordinario` - 24 edges
10. `Certificacion` - 22 edges

## Surprising Connections (you probably didn't know these)
- `AreaConocimientoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/AreaConocimientoController.php → app/Http/Controllers/Controller.php
- `CarreraDepartamentoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/CarreraDepartamentoController.php → app/Http/Controllers/Controller.php
- `PlantillaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/PlantillaController.php → app/Http/Controllers/Controller.php
- `UsuarioController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/UsuarioController.php → app/Http/Controllers/Controller.php
- `CarreraApiController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CarreraApiController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (70 total, 10 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.04
Nodes (44): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+36 more)

### Community 1 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 2 - "ExpedienteRecibidoNotification"
Cohesion: 0.38
Nodes (3): ExpedienteRecibidoNotification, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification

### Community 3 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.07
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, @laravel/multiplex, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+12 more)

### Community 8 - "Illuminate\View\View"
Cohesion: 0.07
Nodes (18): CarreraDepartamentoController, CarreraApiController, DepartamentoApiController, EstudianteApiController, EstudianteController, CarreraResource, CertificacionResource, DepartamentoResource (+10 more)

### Community 10 - "User"
Cohesion: 0.10
Nodes (13): UsuarioController, User, AdminSeeder, DatabaseSeeder, JefaturaSeeder, ProyectoSeeder, RoleAndPermissionSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents (+5 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 32 - "Proyecto.php"
Cohesion: 0.18
Nodes (4): Illuminate\Database\Eloquent\Relations\BelongsToMany, Illuminate\Database\Eloquent\Relations\HasOne, Illuminate\Database\Eloquent\SoftDeletes, Illuminate\Support\Carbon

### Community 36 - "bootstrap/app.php"
Cohesion: 0.18
Nodes (9): EnsureCarreraServiceToken, Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Spatie\Permission\Middleware\PermissionMiddleware, Spatie\Permission\Middleware\RoleMiddleware, Spatie\Permission\Middleware\RoleOrPermissionMiddleware (+1 more)

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.12
Nodes (7): Usuario, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens, Spatie\Permission\Traits\HasRoles

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.21
Nodes (4): VersionPlantilla, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 54 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (30): BitacoraAuditoriaController, AuditoriaApiController, AuthApiController, CertificacionApiController, EstadisticaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController (+22 more)

### Community 87 - "Memoria"
Cohesion: 0.23
Nodes (4): MemoriaController, Memoria, DashboardDemoSeeder, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 88 - "Plantilla"
Cohesion: 0.08
Nodes (16): PlantillaController, FormatoApiController, CatalogoFormatosController, EstadisticasController, FormatoResource, Plantilla, EstadisticasExportService, Illuminate\Support\Collection (+8 more)

### Community 89 - "LoginRequest"
Cohesion: 0.09
Nodes (8): ProfileController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, DictamenMemoriaRequest, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Validator

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.13
Nodes (3): Certificacion, DocumentoProyecto, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 94 - "UserFactory.php"
Cohesion: 0.20
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

### Community 99 - "sanctum.php"
Cohesion: 0.40
Nodes (4): Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 100 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

## Knowledge Gaps
- **62 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+57 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **10 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Proyecto` connect `Illuminate\Http\Request` to `Proyecto.php`, `Illuminate\View\View`, `User`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Database\Eloquent\Model`, `Memoria`, `Plantilla`, `ProcesoExtraordinario`, `ProyectoController`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **Why does `Controller` connect `Illuminate\Http\Request` to `AreaConocimiento`, `Illuminate\View\View`, `User`, `Memoria`, `Plantilla`, `LoginRequest`, `ProyectoController`?**
  _High betweenness centrality (0.059) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Http\Request`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _62 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.044444444444444446 - nodes in this community are weakly interconnected._
- **Should `scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Migrations\Migration` be split into smaller, more focused modules?**
  _Cohesion score 0.06648936170212766 - nodes in this community are weakly interconnected._