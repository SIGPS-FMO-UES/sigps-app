# Graph Report - sigps-app  (2026-10-28)

## Corpus Check
- 145 files · ~66,340 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 944 nodes · 1702 edges · 106 communities (28 shown, 78 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `bc9cb628`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- ProyectoApiController.php
- package.json
- Illuminate\View\View
- Illuminate\Http\Request
- AppServiceProvider
- api.php
- CarreraApiController.php
- EstadisticasExportService
- dev/entrypoint.sh
- docker/entrypoint.sh
- EstudianteApiController.php
- MemoriaController.php
- PlantillaController.php
- DocumentoProyectoController.php
- UsuarioController.php
- EstudianteController.php
- ProyectoController
- Illuminate\Database\Eloquent\Relations\HasMany
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- BitacoraAuditoriaController.php
- Illuminate\Http\JsonResponse
- web.php
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- ProyectoController.php
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Proyecto.php
- Illuminate\Database\Migrations\Migration
- EstadisticasController
- UserFactory.php
- FormatoApiController.php
- GestionProrrogasController.php
- Carrera
- Memoria
- config
- require
- Illuminate\Database\Eloquent\Relations\BelongsTo
- areas/index.blade.php
- auditoria/index.blade.php
- plantillas/index.blade.php
- usuarios/create.blade.php
- usuarios/edit.blade.php
- usuarios/index.blade.php
- MemoriaYFinalizacionProyectoTest
- bandeja/index.blade.php
- AreaConocimientoController.php
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
- Illuminate\Database\Eloquent\Model
- AuditoriaApiController.php
- LoginRequest
- 0001_01_01_000002_create_jobs_table.php
- Proyecto
- 2026_10_14_000005_create_proyectos_tables.php
- Illuminate\Database\Seeder
- psr-4
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000006_create_memorias_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_create_certificaciones_table.php
- autoload-dev
- extra
- {closure#47}

## God Nodes (most connected - your core abstractions)
1. `Controller` - 63 edges
2. `Proyecto` - 49 edges
3. `EstadoMemoria` - 22 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Memoria` - 19 edges
7. `MemoriaController` - 18 edges
8. `Estudiante` - 16 edges
9. `ProyectoController` - 15 edges
10. `Carrera` - 15 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php

## Import Cycles
- None detected.

## Communities (106 total, 78 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 4 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.13
Nodes (4): CatalogoFormatosController, ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.16
Nodes (4): CarreraDepartamentoController, BandejaExpedientesController, MemoriaController, ProcesosExtraordinariosController

### Community 29 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 32 - "EstudianteController.php"
Cohesion: 0.13
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "api.php"
Cohesion: 0.14
Nodes (6): AuditoriaApiController, EstadisticaApiController, {closure#1}(), PlazoApiController, Controller, NotificacionController

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 48 - "Proyecto.php"
Cohesion: 0.22
Nodes (3): Plantilla, {closure#1}(), {closure#2}()

### Community 50 - "EstadisticasController"
Cohesion: 0.29
Nodes (3): {closure#46}(), {closure#48}(), EstadisticasController

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (5): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

### Community 65 - "MemoriaYFinalizacionProyectoTest"
Cohesion: 0.07
Nodes (13): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+5 more)

### Community 87 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.29
Nodes (3): AreaConocimiento, Departamento, VersionPlantilla

### Community 89 - "LoginRequest"
Cohesion: 0.11
Nodes (4): ProfileController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Proyecto"
Cohesion: 0.07
Nodes (5): {closure#1}(), {closure#1}(), {closure#9}(), ProcesoExtraordinario, Proyecto

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "Illuminate\Database\Seeder"
Cohesion: 0.06
Nodes (11): {closure#2}(), User, ExpedienteRecibidoNotification, AdminSeeder, DashboardDemoSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder (+3 more)

### Community 96 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 107 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 108 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **37 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+32 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 456 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **78 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `api.php` to `App\Http\Controllers\Ups\EstadisticasController`, `ProyectoApiController.php`, `BandejaExpedientesController.php`, `Illuminate\View\View`, `Illuminate\Http\Request`, `api.php`, `CarreraApiController.php`, `EstudianteApiController.php`, `MemoriaController.php`, `PlantillaController.php`, `DocumentoProyectoController.php`, `UsuarioController.php`, `EstudianteController.php`, `ProyectoController`, `CertificacionApiController.php`, `BitacoraAuditoriaController.php`, `Illuminate\Http\JsonResponse`, `web.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `ProyectoController.php`, `EstadisticasController`, `FormatoApiController.php`, `GestionProrrogasController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AreaConocimientoController.php`, `AuditoriaApiController.php`, `LoginRequest`, `Proyecto`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _37 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `App\Http\Controllers\Ups\EstadisticasController` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `ProyectoController`, `App\Http\Controllers\Ups\EstadisticasController`, `CertificacionApiController.php`, `ProyectoApiController.php`, `api.php`, `BandejaExpedientesController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `MemoriaYFinalizacionProyectoTest`, `ProyectoController.php`, `Proyecto.php`, `Memoria`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `MemoriaController.php`, `DocumentoProyectoController.php`?**
  _High betweenness centrality (0.069) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `User` connect `Illuminate\Database\Seeder` to `MemoriaYFinalizacionProyectoTest`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Http\JsonResponse`, `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `UsuarioController.php`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `Illuminate\View\View` be split into smaller, more focused modules?**
  _Cohesion score 0.12631578947368421 - nodes in this community are weakly interconnected._