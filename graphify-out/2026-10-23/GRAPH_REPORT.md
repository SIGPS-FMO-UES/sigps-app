# Graph Report - sigps-app  (2026-10-23)

## Corpus Check
- 148 files · ~50,589 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 835 nodes · 1545 edges · 104 communities (57 shown, 47 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 16 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7caf1f9f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- ProyectoController
- Proyecto
- Illuminate\Database\Schema\Blueprint
- package.json
- UpsDashboardController.php
- Estudiante
- Controller
- AppServiceProvider
- IntegracionApiController.php
- 0001_01_01_000001_create_cache_table.php
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- Illuminate\Support\Facades\Schema
- Illuminate\View\View
- ProyectoApiController.php
- 2026_10_14_000003_create_estudiantes_tables.php
- LogAccion
- Illuminate\Database\Eloquent\Attributes\Fillable
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000008_create_plantillas_tables.php
- AreaConocimientoController.php
- bootstrap/app.php
- Illuminate\Http\RedirectResponse
- Illuminate\Http\Request
- .guardarDocumento
- 2026_10_14_000005_create_proyectos_tables.php
- DocumentoProyectoController.php
- PlantillaController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- catalogos/index.blade.php
- UsuarioController.php
- Illuminate\Database\Eloquent\Factories\HasFactory
- EstadisticasController.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- Illuminate\Support\Facades\DB
- CertificacionApiController.php
- CarreraApiController.php
- 2026_10_21_224046_create_certificaciones_table.php
- 2026_10_14_000006_create_memorias_table.php
- App\Http\Controllers\Api\V1\EstudianteApiController
- areas/index.blade.php
- auditoria/index.blade.php
- plantillas/index.blade.php
- usuarios/create.blade.php
- usuarios/edit.blade.php
- usuarios/index.blade.php
- auditoria.blade.php
- bandeja/index.blade.php
- certificaciones/index.blade.php
- estadisticas/index.blade.php
- estudiantes/create.blade.php
- estudiantes/edit.blade.php
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
- Illuminate\Http\JsonResponse
- MemoriaController.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- web.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- require-dev
- GestionProrrogasController.php
- sanctum.php
- TestCase
- config
- UserFactory.php
- require
- 0001_01_01_000002_create_jobs_table.php
- psr-4
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- autoload-dev
- extra
- Illuminate\Http\Resources\Json\JsonResource

## God Nodes (most connected - your core abstractions)
1. `Controller` - 63 edges
2. `Proyecto` - 40 edges
3. `LogAccion` - 23 edges
4. `Estudiante` - 20 edges
5. `EstadoMemoria` - 20 edges
6. `Carrera` - 19 edges
7. `ProyectoController` - 15 edges
8. `Memoria` - 14 edges
9. `Certificacion` - 14 edges
10. `Usuario` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `DocumentoProyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/IntegracionApiController.php → app/Models/DocumentoProyecto.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php

## Import Cycles
- None detected.

## Communities (104 total, 47 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Proyecto"
Cohesion: 0.19
Nodes (4): {closure#1}(), {closure#1}(), {closure#9}(), Proyecto

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 7 - "Estudiante"
Cohesion: 0.14
Nodes (6): App\Http\Controllers\Controller, EstudianteController, App\Models\Carrera, Carrera, Estudiante, Illuminate\Database\Eloquent\Relations\BelongsToMany

### Community 8 - "Controller"
Cohesion: 0.24
Nodes (4): LoginController, Controller, NotificacionController, Illuminate\Support\Facades\Auth

### Community 10 - "IntegracionApiController.php"
Cohesion: 0.07
Nodes (17): {closure#1}(), {closure#2}(), User, ExpedienteRecibidoNotification, AdminSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder (+9 more)

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 27 - "Illuminate\Support\Facades\Schema"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Support\Facades\Schema

### Community 28 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (4): BitacoraAuditoriaController, ProfileController, ControlPlazosController, Illuminate\View\View

### Community 29 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 31 - "LogAccion"
Cohesion: 0.29
Nodes (4): App\Models\LogAccion, LogAccion, Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission

### Community 32 - "Illuminate\Database\Eloquent\Attributes\Fillable"
Cohesion: 0.17
Nodes (5): Memoria, Plantilla, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Relations\HasOne, Illuminate\Database\Eloquent\SoftDeletes

### Community 33 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.22
Nodes (3): {closure#1}(), {closure#1}(), Illuminate\Database\Migrations\Migration

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 38 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.20
Nodes (3): CarreraDepartamentoController, ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse

### Community 39 - "Illuminate\Http\Request"
Cohesion: 0.27
Nodes (3): BandejaExpedientesController, CertificacionController, Illuminate\Http\Request

### Community 40 - ".guardarDocumento"
Cohesion: 0.32
Nodes (3): {closure#28}(), {closure#33}(), Illuminate\Http\UploadedFile

### Community 41 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "PlantillaController.php"
Cohesion: 0.20
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.14
Nodes (9): App\Models\AreaConocimiento, AreaConocimiento, Usuario, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens (+1 more)

### Community 49 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.17
Nodes (6): {closure#1}(), App\Models\Departamento, Departamento, RevisionMemoria, VersionPlantilla, Illuminate\Database\Eloquent\Factories\HasFactory

### Community 51 - "EstadisticasController.php"
Cohesion: 0.09
Nodes (8): FormatoApiController, CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController, Illuminate\Support\Collection, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\StreamedResponse

### Community 53 - "Illuminate\Support\Facades\DB"
Cohesion: 0.06
Nodes (12): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+4 more)

### Community 54 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 87 - "Illuminate\Http\JsonResponse"
Cohesion: 0.10
Nodes (10): AuditoriaApiController, AuthApiController, EstadisticaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController, Illuminate\Http\JsonResponse, Illuminate\Support\Facades\Hash (+2 more)

### Community 89 - "MemoriaController.php"
Cohesion: 0.06
Nodes (12): {closure#7}(), MemoriaController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest, Illuminate\Contracts\Validation\ValidationRule (+4 more)

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (7): {closure#9}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, ProcesoExtraordinario, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 93 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 99 - "sanctum.php"
Cohesion: 0.40
Nodes (4): Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 100 - "TestCase"
Cohesion: 0.47
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 102 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 104 - "UserFactory.php"
Cohesion: 0.20
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 105 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-permission

### Community 106 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 108 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 110 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 111 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 117 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.16
Nodes (3): DepartamentoResource, FormatoResource, Illuminate\Http\Resources\Json\JsonResource

## Knowledge Gaps
- **90 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+85 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **47 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ProyectoController`, `Proyecto`, `UpsDashboardController.php`, `Estudiante`, `IntegracionApiController.php`, `Illuminate\View\View`, `ProyectoApiController.php`, `AreaConocimientoController.php`, `ProyectoController.php`, `Illuminate\Http\RedirectResponse`, `Illuminate\Http\Request`, `DocumentoProyectoController.php`, `ProcesosExtraordinariosController.php`, `PlantillaController.php`, `UsuarioController.php`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `EstadisticasController.php`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `Illuminate\Http\JsonResponse`, `MemoriaController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `web.php`, `GestionProrrogasController.php`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Database\Eloquent\Attributes\Fillable`, `ProyectoController`, `ProyectoController.php`, `UpsDashboardController.php`, `.guardarDocumento`, `IntegracionApiController.php`, `DocumentoProyectoController.php`, `ProcesosExtraordinariosController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `Illuminate\Http\JsonResponse`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoApiController.php`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._
- **Why does `User` connect `IntegracionApiController.php` to `UsuarioController.php`, `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Http\JsonResponse`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _90 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Should `Estudiante` be split into smaller, more focused modules?**
  _Cohesion score 0.13846153846153847 - nodes in this community are weakly interconnected._
- **Should `IntegracionApiController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06970128022759602 - nodes in this community are weakly interconnected._