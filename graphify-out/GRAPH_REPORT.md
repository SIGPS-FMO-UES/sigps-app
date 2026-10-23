# Graph Report - sigps-app  (2026-10-23)

## Corpus Check
- 148 files · ~47,203 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 846 nodes · 1539 edges · 110 communities (31 shown, 79 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9a3362bd`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- MemoriaController.php
- Illuminate\Database\Schema\Blueprint
- package.json
- EstudianteController.php
- Illuminate\Database\Eloquent\Model
- Controller
- AppServiceProvider
- AreaConocimientoController.php
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Support\Facades\Schema
- Illuminate\View\View
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000005_create_proyectos_tables.php
- 2026_10_14_000006_create_memorias_table.php
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000008_create_plantillas_tables.php
- Illuminate\Database\Seeder
- bootstrap/app.php
- ProyectoController
- Illuminate\Http\RedirectResponse
- .guardarDocumento
- RoleAndPermissionSeeder.php
- User
- ProyectoSeeder.php
- UpdateProfileRequest
- PlantillaController.php
- Usuario
- catalogos/index.blade.php
- UsuarioController.php
- App\Http\Controllers\Api\V1\AuditoriaApiController
- Controller
- App\Http\Controllers\Api\V1\FormatoApiController
- DocumentoProyectoController.php
- EstadoMemoria
- CertificacionApiController.php
- CarreraApiController.php
- 2026_10_21_224046_create_certificaciones_table.php
- EstudianteSeeder.php
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
- form.blade.php
- historial.blade.php
- proyectos/index.blade.php
- proyectos/show.blade.php
- App\Http\Controllers\Api\V1\ProyectoApiController
- Carrera
- BandejaExpedientesController.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- web.php
- IntegracionApiController.php
- require-dev
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ExpedienteRecibidoNotification
- GestionProrrogasController.php
- TestCase
- config
- UserFactory.php
- require
- 0001_01_01_000002_create_jobs_table.php
- psr-4
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- autoload-dev
- extra
- Proyecto
- Illuminate\Http\Resources\Json\JsonResource

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 40 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `ProyectoController` - 15 edges
6. `Carrera` - 14 edges
7. `Certificacion` - 14 edges
8. `Estudiante` - 14 edges
9. `Memoria` - 14 edges
10. `AreaConocimiento` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#9}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/CertificacionController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (110 total, 79 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 2 - "MemoriaController.php"
Cohesion: 0.12
Nodes (3): {closure#7}(), MemoriaController, DictamenMemoriaRequest

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstudianteController.php"
Cohesion: 0.19
Nodes (4): {closure#10}(), {closure#11}(), {closure#8}(), Estudiante

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.15
Nodes (4): AreaConocimiento, Departamento, Plantilla, VersionPlantilla

### Community 8 - "Controller"
Cohesion: 0.13
Nodes (4): BitacoraAuditoriaController, LoginController, Controller, NotificacionController

### Community 28 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (3): CertificacionController, ControlPlazosController, EstudianteController

### Community 31 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 35 - "Illuminate\Database\Seeder"
Cohesion: 0.25
Nodes (3): AdminSeeder, DatabaseSeeder, JefaturaSeeder

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 38 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.17
Nodes (4): CarreraDepartamentoController, BandejaExpedientesController, {closure#12}(), ProcesosExtraordinariosController

### Community 44 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 49 - "App\Http\Controllers\Api\V1\AuditoriaApiController"
Cohesion: 0.20
Nodes (3): {closure#1}(), Memoria, RevisionMemoria

### Community 50 - "Controller"
Cohesion: 0.11
Nodes (6): AuditoriaApiController, AuthApiController, EstadisticaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController

### Community 51 - "App\Http\Controllers\Api\V1\FormatoApiController"
Cohesion: 0.09
Nodes (5): FormatoApiController, CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 53 - "EstadoMemoria"
Cohesion: 0.06
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 54 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 87 - "App\Http\Controllers\Api\V1\ProyectoApiController"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 89 - "BandejaExpedientesController.php"
Cohesion: 0.09
Nodes (3): RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}()

### Community 92 - "IntegracionApiController.php"
Cohesion: 0.18
Nodes (4): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto

### Community 93 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 95 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.13
Nodes (3): {closure#1}(), LogAccion, ProcesoExtraordinario

### Community 102 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

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

### Community 112 - "Proyecto"
Cohesion: 0.19
Nodes (3): {closure#1}(), {closure#9}(), Proyecto

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 384 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **79 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `MemoriaController.php`, `ProyectoController.php`, `EstudianteController.php`, `AreaConocimientoController.php`, `Illuminate\View\View`, `ProyectoController`, `Illuminate\Http\RedirectResponse`, `User`, `UpdateProfileRequest`, `PlantillaController.php`, `UsuarioController.php`, `App\Http\Controllers\Api\V1\AuditoriaApiController`, `Controller`, `App\Http\Controllers\Api\V1\FormatoApiController`, `DocumentoProyectoController.php`, `EstadoMemoria`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `BandejaExpedientesController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `web.php`, `IntegracionApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `GestionProrrogasController.php`, `Proyecto`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `MemoriaController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.11688311688311688 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `MemoriaController.php`, `ProyectoController.php`, `ProyectoController`, `.guardarDocumento`, `Illuminate\Database\Eloquent\Model`, `ProyectoSeeder.php`, `Usuario`, `Controller`, `DocumentoProyectoController.php`, `EstadoMemoria`, `CertificacionApiController.php`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `BandejaExpedientesController.php`, `IntegracionApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Should `ProyectoController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.0625 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `ExpedienteRecibidoNotification`, `Illuminate\Database\Seeder`, `UserFactory.php`, `ProyectoSeeder.php`, `Usuario`, `UsuarioController.php`, `IntegracionApiController.php`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._