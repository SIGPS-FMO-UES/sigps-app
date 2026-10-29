# Graph Report - sigps-app  (2026-10-28)

## Corpus Check
- 144 files · ~65,470 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 875 nodes · 1653 edges · 95 communities (58 shown, 37 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 24 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `d10113e7`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- App\Http\Controllers\Ups\EstadisticasController
- Illuminate\Database\Schema\Blueprint
- App\Http\Controllers\Api\V1\ProyectoApiController
- package.json
- App\Http\Controllers\Ups\BandejaExpedientesController
- Illuminate\View\View
- Illuminate\Http\Request
- AppServiceProvider
- App\Http\Controllers\Api\V1\CarreraApiController
- logging.php
- Illuminate\Foundation\Testing\RefreshDatabase
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- EstudianteApiController.php
- LogAccion
- PlantillaController.php
- ProyectoController.php
- Illuminate\Support\Facades\DB
- Proyecto
- CertificacionApiController.php
- bootstrap/app.php
- Controller
- EstadisticasExportService.php
- Illuminate\Http\JsonResponse
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Migrations\Migration
- Illuminate\Database\Eloquent\Builder
- UserFactory.php
- App\Http\Controllers\Api\V1\FormatoApiController
- GestionProrrogasController.php
- config
- require
- Illuminate\Database\Eloquent\Relations\BelongsTo
- areas/index.blade.php
- auditoria/index.blade.php
- plantillas/index.blade.php
- usuarios/create.blade.php
- usuarios/edit.blade.php
- usuarios/index.blade.php
- TestCase
- bandeja/index.blade.php
- estadisticas/index.blade.php
- estudiantes/create.blade.php
- estudiantes/edit.blade.php
- estudiantes/index.blade.php
- estudiantes/show.blade.php
- plazos/index.blade.php
- remision.blade.php
- prorrogas/index.blade.php
- expediente.blade.php
- form.blade.php
- historial.blade.php
- proyectos/index.blade.php
- App\Http\Controllers\Api\V1\IntegracionApiController
- 0001_01_01_000002_create_jobs_table.php
- App\Http\Controllers\Ups\UpsDashboardController
- 2026_10_14_000005_create_proyectos_tables.php
- App\Http\Controllers\Ups\EstudianteController
- psr-4
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- sanctum.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000006_create_memorias_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_create_certificaciones_table.php
- autoload-dev
- extra
- {closure#47}

## God Nodes (most connected - your core abstractions)
1. `Proyecto` - 61 edges
2. `Controller` - 57 edges
3. `LogAccion` - 26 edges
4. `MemoriaController` - 18 edges
5. `Estudiante` - 18 edges
6. `EstadoMemoria` - 18 edges
7. `Carrera` - 16 edges
8. `ProyectoController` - 15 edges
9. `AreaConocimiento` - 14 edges
10. `Usuario` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php

## Import Cycles
- None detected.

## Communities (95 total, 37 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 4 - "App\Http\Controllers\Api\V1\ProyectoApiController"
Cohesion: 0.15
Nodes (4): App\Http\Controllers\Api\V1\ProyectoApiController, {closure#8}(), ProyectoApiController, ProyectoResource

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "App\Http\Controllers\Ups\BandejaExpedientesController"
Cohesion: 0.17
Nodes (3): App\Http\Controllers\Admin\CarreraDepartamentoController, App\Http\Controllers\Ups\BandejaExpedientesController, Illuminate\Validation\Rule

### Community 7 - "Illuminate\View\View"
Cohesion: 0.13
Nodes (5): UsuarioController, ControlPlazosController, EstudianteController, UpsDashboardController, Illuminate\View\View

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.14
Nodes (9): App\Http\Controllers\Admin\AreaConocimientoController, AreaConocimientoController, {closure#1}(), CarreraDepartamentoController, {closure#6}(), BandejaExpedientesController, ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse (+1 more)

### Community 11 - "App\Http\Controllers\Api\V1\CarreraApiController"
Cohesion: 0.15
Nodes (3): App\Http\Controllers\Api\V1\CarreraApiController, CarreraApiController, CarreraResource

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 27 - "EstudianteApiController.php"
Cohesion: 0.14
Nodes (3): App\Http\Controllers\Api\V1\EstudianteApiController, EstudianteApiController, EstudianteResource

### Community 28 - "LogAccion"
Cohesion: 0.23
Nodes (4): MemoriaController, LogAccion, App\Models\Memoria, Memoria

### Community 29 - "PlantillaController.php"
Cohesion: 0.20
Nodes (7): TipoPlantilla, ArchivoEjemplo, Autorellenable, App\Http\Controllers\Admin\PlantillaController, {closure#2}(), {closure#3}(), PlantillaController

### Community 30 - "ProyectoController.php"
Cohesion: 0.18
Nodes (8): App\Enums\EstadoMemoria, App\Http\Controllers\Controller, App\Http\Controllers\Publico\CatalogoFormatosController, CatalogoFormatosController, DocumentoProyectoController, App\Models\LogAccion, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 31 - "Illuminate\Support\Facades\DB"
Cohesion: 0.12
Nodes (6): App\Http\Controllers\Admin\UsuarioController, App\Http\Controllers\Api\V1\ProrrogaApiController, {closure#1}(), ProrrogaApiController, App\Http\Controllers\Ups\ProcesosExtraordinariosController, Illuminate\Support\Facades\DB

### Community 34 - "Proyecto"
Cohesion: 0.09
Nodes (13): ProyectoController, AreaConocimiento, Proyecto, Usuario, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Http\UploadedFile (+5 more)

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 38 - "Controller"
Cohesion: 0.11
Nodes (12): App\Http\Controllers\Admin\BitacoraAuditoriaController, BitacoraAuditoriaController, App\Http\Controllers\Auth\LoginController, LoginController, App\Http\Controllers\Auth\ProfileController, ProfileController, Controller, App\Http\Controllers\Ups\ControlPlazosController (+4 more)

### Community 39 - "EstadisticasExportService.php"
Cohesion: 0.06
Nodes (9): EstadisticasController, EstadisticasExportService, PhpOffice\PhpSpreadsheet\IOFactory, PhpOffice\PhpSpreadsheet\Style\Alignment, PhpOffice\PhpSpreadsheet\Style\Border, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet, PhpOffice\PhpSpreadsheet\Writer\Xlsx, RuntimeException (+1 more)

### Community 40 - "Illuminate\Http\JsonResponse"
Cohesion: 0.12
Nodes (10): AuditoriaApiController, App\Http\Controllers\Api\V1\AuthApiController, AuthApiController, FormatoApiController, App\Http\Controllers\Api\V1\PlazoApiController, {closure#1}(), PlazoApiController, Illuminate\Http\JsonResponse (+2 more)

### Community 43 - "Illuminate\Http\Resources\Json\AnonymousResourceCollection"
Cohesion: 0.25
Nodes (4): App\Http\Controllers\Api\V1\DepartamentoApiController, DepartamentoApiController, DepartamentoResource, Illuminate\Http\Resources\Json\AnonymousResourceCollection

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 46 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Support\Facades\Schema

### Community 49 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Database\Migrations\Migration

### Community 50 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.67
Nodes (3): {closure#46}(), {closure#48}(), Illuminate\Database\Eloquent\Builder

### Community 51 - "UserFactory.php"
Cohesion: 0.20
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 52 - "App\Http\Controllers\Api\V1\FormatoApiController"
Cohesion: 0.17
Nodes (4): App\Http\Controllers\Api\V1\EstadisticaApiController, EstadisticaApiController, App\Http\Controllers\Api\V1\FormatoApiController, Illuminate\Support\Facades\Route

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.05
Nodes (25): {closure#1}(), {closure#1}(), App\Models\AreaConocimiento, App\Models\Carrera, Carrera, Certificacion, Departamento, App\Models\DocumentoProyecto (+17 more)

### Community 65 - "TestCase"
Cohesion: 0.47
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 89 - "App\Http\Controllers\Api\V1\IntegracionApiController"
Cohesion: 0.09
Nodes (11): App\Http\Controllers\Api\V1\IntegracionApiController, {closure#2}(), IntegracionApiController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, ExpedienteRecibidoNotification, Illuminate\Bus\Queueable (+3 more)

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "App\Http\Controllers\Ups\UpsDashboardController"
Cohesion: 0.09
Nodes (13): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+5 more)

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "App\Http\Controllers\Ups\EstudianteController"
Cohesion: 0.05
Nodes (21): App\Http\Controllers\Ups\EstudianteController, {closure#10}(), {closure#11}(), {closure#7}(), {closure#9}(), Estudiante, User, AdminSeeder (+13 more)

### Community 96 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 99 - "sanctum.php"
Cohesion: 0.40
Nodes (4): Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 107 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **87 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+82 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **37 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Proyecto` connect `Proyecto` to `App\Http\Controllers\Ups\EstadisticasController`, `CertificacionApiController.php`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `App\Http\Controllers\Ups\BandejaExpedientesController`, `Controller`, `Illuminate\Http\JsonResponse`, `App\Http\Controllers\Ups\UpsDashboardController`, `App\Http\Controllers\Api\V1\FormatoApiController`, `App\Http\Controllers\Ups\EstudianteController`, `App\Http\Controllers\Api\V1\IntegracionApiController`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `LogAccion`, `ProyectoController.php`, `Illuminate\Support\Facades\DB`?**
  _High betweenness centrality (0.070) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `App\Http\Controllers\Ups\EstadisticasController`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `App\Http\Controllers\Ups\BandejaExpedientesController`, `Illuminate\View\View`, `Illuminate\Http\Request`, `App\Http\Controllers\Api\V1\CarreraApiController`, `EstudianteApiController.php`, `PlantillaController.php`, `ProyectoController.php`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `EstadisticasExportService.php`, `Illuminate\Http\JsonResponse`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `App\Http\Controllers\Api\V1\FormatoApiController`, `GestionProrrogasController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `App\Http\Controllers\Api\V1\IntegracionApiController`, `App\Http\Controllers\Ups\UpsDashboardController`, `App\Http\Controllers\Ups\EstudianteController`?**
  _High betweenness centrality (0.069) - this node is a cross-community bridge._
- **Why does `User` connect `App\Http\Controllers\Ups\EstudianteController` to `Proyecto`, `Illuminate\View\View`, `Illuminate\Http\JsonResponse`, `UserFactory.php`, `App\Http\Controllers\Api\V1\IntegracionApiController`, `Illuminate\Support\Facades\DB`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _87 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `App\Http\Controllers\Ups\EstadisticasController` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Should `Illuminate\View\View` be split into smaller, more focused modules?**
  _Cohesion score 0.13157894736842105 - nodes in this community are weakly interconnected._