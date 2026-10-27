# Graph Report - sigps-app  (2026-10-26)

## Corpus Check
- 150 files · ~62,893 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 934 nodes · 1665 edges · 110 communities (58 shown, 52 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `18f96181`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- EstadisticasExportService.php
- package.json
- EstadoMemoria
- Illuminate\View\View
- Illuminate\Http\RedirectResponse
- AppServiceProvider
- Illuminate\Database\Seeder
- CarreraApiController.php
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- EstudianteApiController.php
- Proyecto
- PlantillaController.php
- DocumentoProyectoController.php
- UsuarioController.php
- EstudianteController.php
- Illuminate\Http\Request
- Illuminate\Http\Resources\Json\JsonResource
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- ProyectoController
- ProyectoApiController.php
- FormatoApiController.php
- Carrera
- Plantilla
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Factories\HasFactory
- Illuminate\Database\Migrations\Migration
- User
- BandejaExpedientesController.php
- web.php
- GestionProrrogasController.php
- Controller
- Memoria
- config
- require
- ExpedienteRecibidoNotification
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
- RoleAndPermissionSeeder.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- 2026_10_14_000005_create_proyectos_tables.php
- UserFactory.php
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
- EstudianteSeeder.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 48 edges
3. `EstadoMemoria` - 21 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Estudiante` - 16 edges
7. `Carrera` - 15 edges
8. `Memoria` - 15 edges
9. `ProyectoController` - 15 edges
10. `Certificacion` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php
- `{closure#2}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (110 total, 52 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 4 - "EstadisticasExportService.php"
Cohesion: 0.07
Nodes (10): EstadisticasController, EstadisticasExportService, Illuminate\Support\Collection, PhpOffice\PhpSpreadsheet\IOFactory, PhpOffice\PhpSpreadsheet\Style\Alignment, PhpOffice\PhpSpreadsheet\Style\Border, PhpOffice\PhpSpreadsheet\Worksheet\Worksheet, PhpOffice\PhpSpreadsheet\Writer\Xlsx (+2 more)

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstadoMemoria"
Cohesion: 0.06
Nodes (12): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+4 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (4): CertificacionController, ControlPlazosController, EstudianteController, Illuminate\View\View

### Community 8 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.11
Nodes (5): AreaConocimientoController, {closure#1}(), CarreraDepartamentoController, ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse

### Community 10 - "Illuminate\Database\Seeder"
Cohesion: 0.25
Nodes (5): AdminSeeder, DashboardDemoSeeder, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "Proyecto"
Cohesion: 0.13
Nodes (6): {closure#1}(), {closure#1}(), {closure#9}(), ProcesoExtraordinario, Proyecto, Illuminate\Http\UploadedFile

### Community 29 - "PlantillaController.php"
Cohesion: 0.20
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 30 - "DocumentoProyectoController.php"
Cohesion: 0.16
Nodes (4): CatalogoFormatosController, DocumentoProyectoController, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 32 - "EstudianteController.php"
Cohesion: 0.15
Nodes (5): {closure#10}(), {closure#11}(), {closure#7}(), {closure#9}(), Estudiante

### Community 33 - "Illuminate\Http\Request"
Cohesion: 0.21
Nodes (8): AuditoriaApiController, AuthApiController, {closure#1}(), ProrrogaApiController, Illuminate\Http\JsonResponse, Illuminate\Http\Request, Illuminate\Support\Facades\DB, Illuminate\Validation\ValidationException

### Community 34 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.16
Nodes (3): DepartamentoResource, FormatoResource, Illuminate\Http\Resources\Json\JsonResource

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 37 - "api.php"
Cohesion: 0.20
Nodes (3): EstadisticaApiController, PlazoApiController, Illuminate\Support\Facades\Route

### Community 39 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.20
Nodes (7): Usuario, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens, Spatie\Permission\Traits\HasRoles

### Community 46 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Support\Facades\Schema

### Community 48 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.10
Nodes (9): {closure#1}(), AreaConocimiento, Departamento, RevisionMemoria, VersionPlantilla, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Factories\HasFactory, Spatie\Permission\DefaultTeamResolver (+1 more)

### Community 49 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Database\Migrations\Migration

### Community 50 - "User"
Cohesion: 0.25
Nodes (3): {closure#2}(), User, JefaturaSeeder

### Community 54 - "Controller"
Cohesion: 0.13
Nodes (5): BitacoraAuditoriaController, LoginController, Controller, NotificacionController, Illuminate\Support\Facades\Auth

### Community 55 - "Memoria"
Cohesion: 0.20
Nodes (5): Memoria, {closure#1}(), {closure#2}(), Illuminate\Database\Eloquent\SoftDeletes, Illuminate\Support\Carbon

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "ExpedienteRecibidoNotification"
Cohesion: 0.38
Nodes (3): ExpedienteRecibidoNotification, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification

### Community 89 - "LoginRequest"
Cohesion: 0.07
Nodes (11): IntegracionApiController, ProfileController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest, Illuminate\Contracts\Validation\ValidationRule (+3 more)

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 91 - "RoleAndPermissionSeeder.php"
Cohesion: 0.40
Nodes (3): RoleAndPermissionSeeder, Spatie\Permission\Models\Role, Spatie\Permission\PermissionRegistrar

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (7): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "UserFactory.php"
Cohesion: 0.18
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

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

## Knowledge Gaps
- **91 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+86 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **52 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `EstadisticasController.php`, `EstadisticasExportService.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Http\RedirectResponse`, `CarreraApiController.php`, `EstudianteApiController.php`, `Proyecto`, `PlantillaController.php`, `DocumentoProyectoController.php`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\Request`, `CertificacionApiController.php`, `api.php`, `ProyectoController`, `ProyectoApiController.php`, `FormatoApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `BandejaExpedientesController.php`, `web.php`, `GestionProrrogasController.php`, `MemoriaController.php`, `LoginRequest`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Why does `Proyecto` connect `Proyecto` to `EstadisticasController.php`, `CertificacionApiController.php`, `EstadoMemoria`, `ProyectoApiController.php`, `Illuminate\Http\RedirectResponse`, `ProyectoController`, `Plantilla`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `ProyectoSeeder.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `DocumentoProyectoController.php`, `ProyectoController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Why does `EstadoMemoria` connect `EstadoMemoria` to `Illuminate\Http\Request`, `Illuminate\Database\Seeder`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _91 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstadisticasController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Should `EstadisticasExportService.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06707317073170732 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._