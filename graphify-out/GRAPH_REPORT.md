# Graph Report - sigps-app  (2026-10-27)

## Corpus Check
- 150 files · ~65,396 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 951 nodes · 1695 edges · 114 communities (28 shown, 86 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.85)
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
- package.json
- EstadoMemoria
- Illuminate\View\View
- Illuminate\Http\Request
- AppServiceProvider
- Carrera
- CarreraApiController.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- EstudianteApiController.php
- Illuminate\Support\Facades\DB
- PlantillaController.php
- Controller
- UsuarioController.php
- EstudianteController.php
- Illuminate\Http\Request
- ProyectoApiController
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- ProyectoController
- FormatoApiController.php
- BandejaExpedientesController.php
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- EstadisticasController
- EstadisticasExportService
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
- MemoriaController.php
- ProyectoSeeder.php
- ProyectoApiController.php
- 0001_01_01_000002_create_jobs_table.php
- User
- Illuminate\Database\Eloquent\Relations\BelongsTo
- 2026_10_14_000005_create_proyectos_tables.php
- UserFactory.php
- ProyectoController.php
- psr-4
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- TestCase
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000006_create_memorias_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_create_certificaciones_table.php
- autoload-dev
- extra
- DatabaseSeeder
- EstudianteSeeder.php
- {closure#47}
- Proyecto
- Illuminate\Support\Facades\DB

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 48 edges
3. `EstadoMemoria` - 21 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Estudiante` - 16 edges
7. `ProyectoController` - 15 edges
8. `Carrera` - 15 edges
9. `Memoria` - 15 edges
10. `Certificacion` - 14 edges

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

## Communities (114 total, 86 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 2 - "UpdateProfileRequest"
Cohesion: 0.16
Nodes (4): LoginController, ProfileController, Controller, UpdateProfileRequest

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstadoMemoria"
Cohesion: 0.09
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (3): CertificacionController, ControlPlazosController, EstudianteController

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (5): AreaConocimientoController, {closure#1}(), CarreraDepartamentoController, NotificacionController, ProcesosExtraordinariosController

### Community 28 - "Illuminate\Support\Facades\DB"
Cohesion: 0.18
Nodes (4): {closure#1}(), ProcesoExtraordinario, {closure#1}(), {closure#2}()

### Community 29 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 32 - "EstudianteController.php"
Cohesion: 0.13
Nodes (5): {closure#10}(), {closure#11}(), {closure#7}(), {closure#9}(), Estudiante

### Community 33 - "Illuminate\Http\Request"
Cohesion: 0.22
Nodes (3): AuditoriaApiController, AuthApiController, CertificacionApiController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "api.php"
Cohesion: 0.18
Nodes (3): EstadisticaApiController, PlazoApiController, ProrrogaApiController

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.13
Nodes (6): {closure#1}(), AreaConocimiento, Departamento, Plantilla, RevisionMemoria, VersionPlantilla

### Community 50 - "EstadisticasController"
Cohesion: 0.29
Nodes (3): {closure#46}(), {closure#48}(), EstadisticasController

### Community 54 - "Illuminate\Database\Seeder"
Cohesion: 0.27
Nodes (3): AdminSeeder, DashboardDemoSeeder, JefaturaSeeder

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 89 - "ProyectoApiController.php"
Cohesion: 0.06
Nodes (8): {closure#2}(), IntegracionApiController, {closure#8}(), RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest, ExpedienteRecibidoNotification

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (5): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 96 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 107 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 113 - "Proyecto"
Cohesion: 0.16
Nodes (3): {closure#1}(), {closure#9}(), Proyecto

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 462 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **86 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `UpdateProfileRequest` to `EstadisticasController.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Http\Request`, `CarreraApiController.php`, `EstudianteApiController.php`, `Illuminate\Support\Facades\DB`, `PlantillaController.php`, `Controller`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\Request`, `ProyectoApiController`, `CertificacionApiController.php`, `api.php`, `ProyectoController`, `FormatoApiController.php`, `BandejaExpedientesController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `EstadisticasController`, `web.php`, `GestionProrrogasController.php`, `MemoriaController.php`, `ProyectoApiController.php`, `ProyectoController.php`, `Proyecto`, `Illuminate\Support\Facades\DB`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstadisticasController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `CertificacionApiController.php`, `EstadisticasController.php`, `EstadoMemoria`, `ProyectoController`, `BandejaExpedientesController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Support\Facades\DB`, `Memoria`, `Illuminate\Database\Seeder`, `MemoriaController.php`, `ProyectoSeeder.php`, `ProyectoApiController.php`, `Illuminate\Support\Facades\DB`, `Controller`, `ProyectoController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `EstadoMemoria` connect `EstadoMemoria` to `Illuminate\Http\Request`, `BandejaExpedientesController.php`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Support\Facades\DB`, `Memoria`, `Illuminate\Database\Seeder`, `MemoriaController.php`, `ProyectoApiController.php`, `ProyectoController.php`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Should `EstadoMemoria` be split into smaller, more focused modules?**
  _Cohesion score 0.09090909090909091 - nodes in this community are weakly interconnected._