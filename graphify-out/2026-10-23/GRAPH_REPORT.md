# Graph Report - sigps-app  (2026-10-23)

## Corpus Check
- 148 files · ~47,203 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 791 nodes · 1490 edges · 103 communities (50 shown, 53 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 15 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `cd30165a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- MemoriaController.php
- FormatoApiController.php
- Illuminate\Database\Schema\Blueprint
- package.json
- EstudianteController.php
- Illuminate\Database\Eloquent\Model
- Controller
- AppServiceProvider
- AreaConocimientoController.php
- 0001_01_01_000001_create_cache_table.php
- logging.php
- ExampleTest
- console.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- artisan
- Illuminate\Database\Migrations\Migration
- Illuminate\View\View
- BitacoraAuditoriaController.php
- 2026_10_14_000003_create_estudiantes_tables.php
- Memoria
- Plantilla
- 2026_10_14_000007_create_logs_acciones_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- DocumentoProyecto
- bootstrap/app.php
- Proyecto
- Illuminate\Http\RedirectResponse
- CertificacionController
- 2026_10_22_014646_create_personal_access_tokens_table.php
- 2026_10_22_100000_create_notifications_table.php
- UpdateProfileRequest
- PlantillaController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- catalogos/index.blade.php
- UsuarioController.php
- AuditoriaApiController.php
- api.php
- EstadisticasController.php
- Illuminate\Support\Facades\DB
- CertificacionApiController.php
- CarreraApiController.php
- 2026_10_21_224046_create_certificaciones_table.php
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
- estudiantes/index.blade.php
- estudiantes/show.blade.php
- memorias/index.blade.php
- memorias/show.blade.php
- plazos/index.blade.php
- remision.blade.php
- prorrogas/index.blade.php
- expediente.blade.php
- historial.blade.php
- proyectos/index.blade.php
- Illuminate\Http\Request
- ProyectoController.php
- IntegracionApiController.php
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
1. `Controller` - 61 edges
2. `Proyecto` - 49 edges
3. `LogAccion` - 23 edges
4. `EstadoMemoria` - 18 edges
5. `Estudiante` - 16 edges
6. `ProyectoController` - 15 edges
7. `Carrera` - 15 edges
8. `Memoria` - 14 edges
9. `AreaConocimiento` - 14 edges
10. `Certificacion` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php

## Import Cycles
- None detected.

## Communities (103 total, 53 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 2 - "MemoriaController.php"
Cohesion: 0.14
Nodes (4): {closure#7}(), MemoriaController, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (9): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+1 more)

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstudianteController.php"
Cohesion: 0.06
Nodes (16): {closure#10}(), {closure#11}(), {closure#8}(), Estudiante, User, AdminSeeder, DatabaseSeeder, EstudianteSeeder (+8 more)

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.20
Nodes (10): App\Models\AreaConocimiento, AreaConocimiento, Departamento, App\Models\LogAccion, VersionPlantilla, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model (+2 more)

### Community 8 - "Controller"
Cohesion: 0.24
Nodes (4): LoginController, Controller, NotificacionController, Illuminate\Support\Facades\Auth

### Community 12 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "Illuminate\View\View"
Cohesion: 0.17
Nodes (5): {closure#1}(), ControlPlazosController, {closure#12}(), EstudianteController, Illuminate\View\View

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (12): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware (+4 more)

### Community 37 - "Proyecto"
Cohesion: 0.10
Nodes (5): {closure#1}(), ProyectoController, {closure#9}(), Proyecto, Carbon\CarbonImmutable

### Community 38 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.15
Nodes (4): CarreraDepartamentoController, BandejaExpedientesController, ProcesosExtraordinariosController, Illuminate\Http\RedirectResponse

### Community 44 - "PlantillaController.php"
Cohesion: 0.20
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 45 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.17
Nodes (7): Usuario, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Laravel\Sanctum\HasApiTokens, Spatie\Permission\Traits\HasRoles

### Community 50 - "api.php"
Cohesion: 0.12
Nodes (5): AuditoriaApiController, EstadisticaApiController, PlazoApiController, ProrrogaApiController, Illuminate\Support\Facades\Route

### Community 51 - "EstadisticasController.php"
Cohesion: 0.14
Nodes (6): CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController, Illuminate\Support\Collection, Symfony\Component\HttpFoundation\StreamedResponse

### Community 53 - "Illuminate\Support\Facades\DB"
Cohesion: 0.06
Nodes (12): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+4 more)

### Community 54 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 87 - "Illuminate\Http\Request"
Cohesion: 0.24
Nodes (6): AuthApiController, ProyectoApiController, App\Http\Controllers\Controller, Illuminate\Http\JsonResponse, Illuminate\Http\Request, Illuminate\Validation\ValidationException

### Community 88 - "ProyectoController.php"
Cohesion: 0.16
Nodes (6): App\Enums\EstadoMemoria, App\Models\Carrera, Carrera, App\Models\Estudiante, Illuminate\Database\Eloquent\Relations\BelongsToMany, Illuminate\Http\UploadedFile

### Community 89 - "IntegracionApiController.php"
Cohesion: 0.07
Nodes (13): {closure#2}(), IntegracionApiController, RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest, ExpedienteRecibidoNotification, Illuminate\Bus\Queueable (+5 more)

### Community 90 - "Illuminate\Http\Resources\Json\AnonymousResourceCollection"
Cohesion: 0.25
Nodes (3): DepartamentoApiController, DepartamentoResource, Illuminate\Http\Resources\Json\AnonymousResourceCollection

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.11
Nodes (7): {closure#9}(), Certificacion, App\Models\HistorialFaseProyecto, HistorialFaseProyecto, LogAccion, ProcesoExtraordinario, Illuminate\Database\Eloquent\Relations\BelongsTo

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
Cohesion: 0.18
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

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

## Knowledge Gaps
- **89 isolated node(s):** `description`, `keywords`, `license`, `minimum-stability`, `name` (+84 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **53 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `MemoriaController.php`, `FormatoApiController.php`, `EstudianteController.php`, `AreaConocimientoController.php`, `Illuminate\View\View`, `BitacoraAuditoriaController.php`, `Proyecto`, `Illuminate\Http\RedirectResponse`, `CertificacionController`, `UpdateProfileRequest`, `PlantillaController.php`, `UsuarioController.php`, `AuditoriaApiController.php`, `api.php`, `EstadisticasController.php`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `Illuminate\Http\Request`, `IntegracionApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `web.php`, `GestionProrrogasController.php`?**
  _High betweenness centrality (0.080) - this node is a cross-community bridge._
- **Why does `Proyecto` connect `Proyecto` to `MemoriaController.php`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Model`, `Illuminate\View\View`, `Illuminate\Database\Eloquent\Relations\HasMany`, `api.php`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `Illuminate\Http\Request`, `ProyectoController.php`, `IntegracionApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Memoria`?**
  _High betweenness centrality (0.051) - this node is a cross-community bridge._
- **Why does `User` connect `EstudianteController.php` to `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `UsuarioController.php`, `Illuminate\Http\Request`, `IntegracionApiController.php`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **What connects `description`, `keywords`, `license` to the rest of the system?**
  _89 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `MemoriaController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14035087719298245 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Should `EstudianteController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06201550387596899 - nodes in this community are weakly interconnected._