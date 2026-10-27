# Graph Report - sigps-app  (2026-10-27)

## Corpus Check
- 150 files · ~63,420 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 944 nodes · 1681 edges · 117 communities (29 shown, 88 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `18f96181`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- package.json
- EstadoMemoria
- Illuminate\View\View
- Illuminate\Http\RedirectResponse
- AppServiceProvider
- Illuminate\Database\Seeder
- CarreraApiController.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
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
- CertificacionController.php
- Plantilla
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Factories\HasFactory
- Illuminate\Database\Migrations\Migration
- User
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
- LoginRequest
- 0001_01_01_000002_create_jobs_table.php
- RoleAndPermissionSeeder.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- 2026_10_14_000005_create_proyectos_tables.php
- UserFactory.php
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
- UpsDashboardController.php
- ProcesoExtraordinario
- EstadisticasExportService
- AreaConocimientoController.php
- .guardarDocumento
- EstadisticasController
- DatabaseSeeder

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
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `AreaConocimientoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/AreaConocimientoController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (117 total, 88 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstadoMemoria"
Cohesion: 0.17
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.19
Nodes (3): ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.15
Nodes (3): CarreraDepartamentoController, BandejaExpedientesController, ProcesosExtraordinariosController

### Community 10 - "Illuminate\Database\Seeder"
Cohesion: 0.28
Nodes (3): AdminSeeder, DashboardDemoSeeder, EstudianteSeeder

### Community 28 - "Proyecto"
Cohesion: 0.17
Nodes (3): {closure#1}(), {closure#1}(), Proyecto

### Community 29 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 32 - "EstudianteController.php"
Cohesion: 0.10
Nodes (5): {closure#10}(), {closure#7}(), {closure#9}(), Carrera, Estudiante

### Community 33 - "Illuminate\Http\Request"
Cohesion: 0.21
Nodes (4): AuditoriaApiController, AuthApiController, {closure#1}(), ProrrogaApiController

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 39 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 48 - "Illuminate\Database\Eloquent\Factories\HasFactory"
Cohesion: 0.16
Nodes (5): {closure#1}(), AreaConocimiento, Departamento, RevisionMemoria, VersionPlantilla

### Community 50 - "User"
Cohesion: 0.25
Nodes (3): {closure#2}(), User, JefaturaSeeder

### Community 54 - "Controller"
Cohesion: 0.13
Nodes (4): BitacoraAuditoriaController, LoginController, Controller, NotificacionController

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 89 - "LoginRequest"
Cohesion: 0.07
Nodes (7): IntegracionApiController, ProfileController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.13
Nodes (6): {closure#1}(), {closure#9}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

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

### Community 110 - "ProcesoExtraordinario"
Cohesion: 0.18
Nodes (3): ProcesoExtraordinario, {closure#1}(), {closure#2}()

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 462 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **88 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `EstadisticasController.php`, `Illuminate\View\View`, `Illuminate\Http\RedirectResponse`, `CarreraApiController.php`, `EstudianteApiController.php`, `Proyecto`, `PlantillaController.php`, `DocumentoProyectoController.php`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\Request`, `CertificacionApiController.php`, `api.php`, `ProyectoController`, `ProyectoApiController.php`, `FormatoApiController.php`, `CertificacionController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `BandejaExpedientesController.php`, `web.php`, `GestionProrrogasController.php`, `MemoriaController.php`, `LoginRequest`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`, `UpsDashboardController.php`, `AreaConocimientoController.php`, `EstadisticasController`, `ProcesosExtraordinariosController.php`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstadisticasController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `EstadisticasController.php`, `Illuminate\Database\Seeder`, `DocumentoProyectoController.php`, `CertificacionApiController.php`, `ProyectoController`, `ProyectoApiController.php`, `CertificacionController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `ProyectoSeeder.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`, `UpsDashboardController.php`, `ProcesoExtraordinario`, `.guardarDocumento`, `ProcesosExtraordinariosController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `EstadisticasExportService.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06060606060606061 - nodes in this community are weakly interconnected._
- **Why does `EstadoMemoria` connect `EstadoMemoria` to `Illuminate\Http\Request`, `CertificacionController.php`, `Illuminate\Database\Seeder`, `UpsDashboardController.php`, `ProcesoExtraordinario`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._