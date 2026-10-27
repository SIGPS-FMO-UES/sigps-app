# Graph Report - sigps-app  (2026-10-27)

## Corpus Check
- 150 files · ~63,420 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 871 nodes · 1613 edges · 106 communities (55 shown, 51 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 13 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `cfbb5a86`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- UpdateProfileRequest
- Illuminate\Database\Schema\Blueprint
- AuditoriaApiController.php
- package.json
- EstadoMemoria
- Illuminate\View\View
- Illuminate\Http\Request
- AppServiceProvider
- Carrera
- CarreraApiController.php
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- EstudianteApiController.php
- Illuminate\Support\Facades\DB
- PlantillaController.php
- Controller
- UsuarioController.php
- EstudianteController.php
- Illuminate\Http\JsonResponse
- ProyectoApiController
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- ProyectoController
- ProyectoApiController.php
- FormatoApiController.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- web.php
- GestionProrrogasController.php
- BitacoraAuditoriaController.php
- Memoria
- config
- require
- IntegracionApiController.php
- areas/index.blade.php
- auditoria/index.blade.php
- plantillas/index.blade.php
- usuarios/create.blade.php
- usuarios/edit.blade.php
- usuarios/index.blade.php
- auditoria.blade.php
- bandeja/index.blade.php
- certificaciones/index.blade.php
- estudiantes/create.blade.php
- estudiantes/edit.blade.php
- estudiantes/index.blade.php
- estudiantes/show.blade.php
- memorias/index.blade.php
- memorias/show.blade.php
- plazos/index.blade.php
- remision.blade.php
- prorrogas/index.blade.php
- expediente.blade.php
- form.blade.php
- historial.blade.php
- proyectos/index.blade.php
- proyectos/show.blade.php
- MemoriaController.php
- BandejaExpedientesController.php
- 0001_01_01_000002_create_jobs_table.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- 2026_10_14_000005_create_proyectos_tables.php
- UserFactory.php
- psr-4
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- sanctum.php
- TestCase
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000006_create_memorias_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_create_certificaciones_table.php
- autoload-dev
- extra
- AreaConocimientoController.php
- Proyecto
- ProcesosExtraordinariosController.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 63 edges
2. `Proyecto` - 48 edges
3. `EstadoMemoria` - 21 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Estudiante` - 16 edges
7. `Carrera` - 15 edges
8. `ProyectoController` - 15 edges
9. `Memoria` - 15 edges
10. `Certificacion` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php
- `{closure#2}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (106 total, 51 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstadoMemoria"
Cohesion: 0.06
Nodes (12): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+4 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.20
Nodes (4): {closure#1}(), ControlPlazosController, EstudianteController, Illuminate\View\View

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.14
Nodes (7): CarreraDepartamentoController, BandejaExpedientesController, CertificacionController, {closure#11}(), ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse, Illuminate\Http\Request

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "Illuminate\Support\Facades\DB"
Cohesion: 0.24
Nodes (3): {closure#1}(), ProcesoExtraordinario, Illuminate\Support\Facades\DB

### Community 29 - "PlantillaController.php"
Cohesion: 0.20
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 30 - "Controller"
Cohesion: 0.15
Nodes (5): Controller, CatalogoFormatosController, DocumentoProyectoController, NotificacionController, Illuminate\Support\Facades\Storage

### Community 32 - "EstudianteController.php"
Cohesion: 0.05
Nodes (17): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante, User, AdminSeeder, DashboardDemoSeeder, DatabaseSeeder (+9 more)

### Community 33 - "Illuminate\Http\JsonResponse"
Cohesion: 0.13
Nodes (7): AuthApiController, EstadisticaApiController, {closure#1}(), PlazoApiController, Illuminate\Http\JsonResponse, Illuminate\Support\Facades\Hash, Illuminate\Validation\ValidationException

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 37 - "api.php"
Cohesion: 0.17
Nodes (4): AuditoriaApiController, IntegracionApiController, ProrrogaApiController, Illuminate\Support\Facades\Route

### Community 40 - "FormatoApiController.php"
Cohesion: 0.16
Nodes (3): FormatoApiController, FormatoResource, Illuminate\Http\Resources\Json\JsonResource

### Community 43 - "Illuminate\Http\Resources\Json\AnonymousResourceCollection"
Cohesion: 0.21
Nodes (3): DepartamentoApiController, DepartamentoResource, Illuminate\Http\Resources\Json\AnonymousResourceCollection

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.07
Nodes (21): App\Http\Controllers\Controller, EstadisticasController, App\Models\AreaConocimiento, AreaConocimiento, App\Models\Departamento, Departamento, Usuario, EstadisticasExportService (+13 more)

### Community 46 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Support\Facades\Schema

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.13
Nodes (15): App\Models\Carrera, App\Models\Certificacion, Plantilla, App\Models\Proyecto, {closure#1}(), {closure#2}(), VersionPlantilla, Illuminate\Database\Eloquent\Attributes\Fillable (+7 more)

### Community 49 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Database\Migrations\Migration

### Community 52 - "web.php"
Cohesion: 0.15
Nodes (3): LoginController, UpsDashboardController, Illuminate\Support\Facades\Auth

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "IntegracionApiController.php"
Cohesion: 0.21
Nodes (6): {closure#1}(), {closure#2}(), DocumentoProyecto, ExpedienteRecibidoNotification, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification

### Community 87 - "MemoriaController.php"
Cohesion: 0.12
Nodes (4): {closure#7}(), MemoriaController, DictamenMemoriaRequest, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 89 - "BandejaExpedientesController.php"
Cohesion: 0.09
Nodes (7): RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Rule, Illuminate\Validation\Validator

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.17
Nodes (3): Certificacion, LogAccion, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "UserFactory.php"
Cohesion: 0.20
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 96 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 99 - "sanctum.php"
Cohesion: 0.40
Nodes (4): Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 100 - "TestCase"
Cohesion: 0.47
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 107 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 113 - "Proyecto"
Cohesion: 0.21
Nodes (5): {closure#28}(), {closure#33}(), {closure#9}(), Proyecto, Illuminate\Http\UploadedFile

## Knowledge Gaps
- **91 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+86 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **51 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `UpdateProfileRequest`, `AuditoriaApiController.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Http\Request`, `CarreraApiController.php`, `EstudianteApiController.php`, `Illuminate\Support\Facades\DB`, `PlantillaController.php`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\JsonResponse`, `ProyectoApiController`, `CertificacionApiController.php`, `api.php`, `ProyectoController`, `ProyectoApiController.php`, `FormatoApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `Illuminate\Database\Eloquent\Relations\HasMany`, `web.php`, `GestionProrrogasController.php`, `BitacoraAuditoriaController.php`, `IntegracionApiController.php`, `MemoriaController.php`, `BandejaExpedientesController.php`, `ProyectoController.php`, `AreaConocimientoController.php`, `ProcesosExtraordinariosController.php`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `Proyecto` connect `Proyecto` to `EstudianteController.php`, `Illuminate\Http\JsonResponse`, `CertificacionApiController.php`, `EstadoMemoria`, `ProyectoApiController.php`, `Illuminate\View\View`, `ProyectoController`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Database\Eloquent\Model`, `ProcesosExtraordinariosController.php`, `Memoria`, `MemoriaController.php`, `BandejaExpedientesController.php`, `IntegracionApiController.php`, `Illuminate\Support\Facades\DB`, `Controller`, `ProyectoController.php`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Why does `User` connect `EstudianteController.php` to `Illuminate\Http\JsonResponse`, `Illuminate\Database\Eloquent\Relations\HasMany`, `IntegracionApiController.php`, `UserFactory.php`, `UsuarioController.php`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _91 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Should `EstadoMemoria` be split into smaller, more focused modules?**
  _Cohesion score 0.0625 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.13793103448275862 - nodes in this community are weakly interconnected._