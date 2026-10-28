# Graph Report - sigps-app  (2026-10-27)

## Corpus Check
- 150 files · ~65,396 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 903 nodes · 1644 edges · 115 communities (59 shown, 56 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 10 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b466e4da`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- UpdateProfileRequest
- Illuminate\Database\Schema\Blueprint
- ProyectoApiController.php
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
- Illuminate\Database\Eloquent\Relations\BelongsTo
- PlantillaController.php
- Controller
- UsuarioController.php
- EstudianteController.php
- Illuminate\Http\JsonResponse
- ProyectoApiController
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- BitacoraAuditoriaController.php
- App\Services\EstadisticasExportService
- Controller
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- EstadisticasController
- Symfony\Component\HttpFoundation\StreamedResponse
- web.php
- GestionProrrogasController.php
- Illuminate\Database\Seeder
- Memoria
- config
- require
- RoleAndPermissionSeeder.php
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
- ProyectoSeeder.php
- LoginRequest
- 0001_01_01_000002_create_jobs_table.php
- User
- Certificacion
- 2026_10_14_000005_create_proyectos_tables.php
- Usuario
- ProyectoController.php
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
- ExpedienteRecibidoNotification
- EstudianteSeeder.php
- Illuminate\Database\Query\Builder
- IntegracionApiController.php
- Proyecto
- LogAccion

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
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php

## Import Cycles
- None detected.

## Communities (115 total, 56 thin omitted)

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
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.14
Nodes (5): {closure#1}(), ControlPlazosController, {closure#11}(), EstudianteController, Illuminate\View\View

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (8): AreaConocimientoController, {closure#1}(), CarreraDepartamentoController, BandejaExpedientesController, CertificacionController, ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse, Illuminate\Http\Request

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.19
Nodes (7): HistorialFaseProyecto, ProcesoExtraordinario, App\Models\Proyecto, {closure#1}(), {closure#2}(), Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Support\Carbon

### Community 29 - "PlantillaController.php"
Cohesion: 0.20
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 30 - "Controller"
Cohesion: 0.16
Nodes (4): CatalogoFormatosController, DocumentoProyectoController, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 32 - "EstudianteController.php"
Cohesion: 0.18
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 33 - "Illuminate\Http\JsonResponse"
Cohesion: 0.15
Nodes (7): AuthApiController, FormatoApiController, {closure#1}(), PlazoApiController, Illuminate\Http\JsonResponse, Illuminate\Support\Facades\Hash, Illuminate\Validation\ValidationException

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 37 - "api.php"
Cohesion: 0.20
Nodes (3): EstadisticaApiController, IntegracionApiController, Illuminate\Support\Facades\Route

### Community 39 - "App\Services\EstadisticasExportService"
Cohesion: 0.06
Nodes (6): App\Services\EstadisticasExportService, PhpOffice\PhpSpreadsheet\IOFactory, PhpOffice\PhpSpreadsheet\Style\Alignment, PhpOffice\PhpSpreadsheet\Style\Border, PhpOffice\PhpSpreadsheet\Writer\Xlsx, RuntimeException

### Community 41 - "Controller"
Cohesion: 0.11
Nodes (7): AuditoriaApiController, {closure#1}(), {closure#1}(), ProrrogaApiController, Controller, NotificacionController, Illuminate\Support\Facades\DB

### Community 42 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.22
Nodes (3): DepartamentoResource, FormatoResource, Illuminate\Http\Resources\Json\JsonResource

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.18
Nodes (3): Plantilla, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Database\Eloquent\Relations\HasOne

### Community 46 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Support\Facades\Schema

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.19
Nodes (11): App\Models\AreaConocimiento, AreaConocimiento, App\Models\Carrera, App\Models\Certificacion, App\Models\Departamento, Departamento, RevisionMemoria, VersionPlantilla (+3 more)

### Community 49 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Database\Migrations\Migration

### Community 50 - "EstadisticasController"
Cohesion: 0.31
Nodes (4): App\Http\Controllers\Controller, EstadisticasController, EstadisticasExportService, Illuminate\Database\Eloquent\Builder

### Community 51 - "Symfony\Component\HttpFoundation\StreamedResponse"
Cohesion: 0.38
Nodes (3): EstadisticasExportService, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet, Symfony\Component\HttpFoundation\StreamedResponse

### Community 52 - "web.php"
Cohesion: 0.15
Nodes (3): LoginController, UpsDashboardController, Illuminate\Support\Facades\Auth

### Community 54 - "Illuminate\Database\Seeder"
Cohesion: 0.25
Nodes (5): AdminSeeder, DashboardDemoSeeder, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "RoleAndPermissionSeeder.php"
Cohesion: 0.29
Nodes (5): RoleAndPermissionSeeder, Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission, Spatie\Permission\Models\Role, Spatie\Permission\PermissionRegistrar

### Community 89 - "LoginRequest"
Cohesion: 0.11
Nodes (8): RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Rule, Illuminate\Validation\Validator

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 91 - "User"
Cohesion: 0.29
Nodes (3): {closure#2}(), User, JefaturaSeeder

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "Usuario"
Cohesion: 0.11
Nodes (11): Usuario, UserFactory, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Illuminate\Support\Str, Laravel\Sanctum\HasApiTokens (+3 more)

### Community 95 - "ProyectoController.php"
Cohesion: 0.05
Nodes (5): {closure#28}(), {closure#33}(), ProyectoController, Illuminate\Http\UploadedFile, Illuminate\Support\Collection

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

### Community 109 - "ExpedienteRecibidoNotification"
Cohesion: 0.38
Nodes (3): ExpedienteRecibidoNotification, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification

## Knowledge Gaps
- **91 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+86 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **56 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `UpdateProfileRequest`, `ProyectoApiController.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Http\Request`, `CarreraApiController.php`, `EstudianteApiController.php`, `PlantillaController.php`, `Controller`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\JsonResponse`, `ProyectoApiController`, `CertificacionApiController.php`, `api.php`, `BitacoraAuditoriaController.php`, `FormatoApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `EstadisticasController`, `web.php`, `GestionProrrogasController.php`, `MemoriaController.php`, `Certificacion`, `ProyectoController.php`, `IntegracionApiController.php`, `LogAccion`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **Why does `Proyecto` connect `Proyecto` to `ProyectoApiController.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Controller`, `Illuminate\Http\JsonResponse`, `CertificacionApiController.php`, `Controller`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Model`, `EstadisticasController`, `Illuminate\Database\Seeder`, `Memoria`, `MemoriaController.php`, `ProyectoSeeder.php`, `Certificacion`, `ProyectoController.php`, `IntegracionApiController.php`, `LogAccion`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Http\JsonResponse`, `IntegracionApiController.php`, `Illuminate\Database\Seeder`, `ProyectoSeeder.php`, `Usuario`, `UsuarioController.php`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _91 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Should `EstadoMemoria` be split into smaller, more focused modules?**
  _Cohesion score 0.06451612903225806 - nodes in this community are weakly interconnected._
- **Should `Illuminate\View\View` be split into smaller, more focused modules?**
  _Cohesion score 0.1437908496732026 - nodes in this community are weakly interconnected._