# Graph Report - sigps-app  (2026-10-26)

## Corpus Check
- 150 files · ~62,775 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 935 nodes · 1666 edges · 109 communities (29 shown, 80 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2bc5976b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- package.json
- EstadoMemoria
- Illuminate\View\View
- CarreraApiController.php
- AppServiceProvider
- User
- EstudianteApiController.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- PlantillaController.php
- Proyecto
- DocumentoProyectoController.php
- UsuarioController.php
- Illuminate\Http\Request
- EstudianteController.php
- Illuminate\Http\Resources\Json\JsonResource
- CertificacionApiController.php
- api.php
- bootstrap/app.php
- ProyectoApiController
- AreaConocimiento
- FormatoApiController.php
- Carrera
- Proyecto.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Illuminate\Http\RedirectResponse
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- UpdateProfileRequest
- web.php
- GestionProrrogasController.php
- Controller
- Memoria
- config
- require
- EstadisticasController
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
- EstadisticasExportService
- LoginRequest
- 0001_01_01_000002_create_jobs_table.php
- AuditoriaApiController.php
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
- `{closure#9}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/CertificacionController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php
- `{closure#2}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Models/Proyecto.php → app/Models/ProcesoExtraordinario.php

## Import Cycles
- None detected.

## Communities (109 total, 80 thin omitted)

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
Cohesion: 0.09
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (3): CertificacionController, ControlPlazosController, EstudianteController

### Community 10 - "User"
Cohesion: 0.07
Nodes (10): {closure#2}(), User, ExpedienteRecibidoNotification, AdminSeeder, DashboardDemoSeeder, DatabaseSeeder, JefaturaSeeder, {closure#3}() (+2 more)

### Community 27 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 28 - "Proyecto"
Cohesion: 0.06
Nodes (7): {closure#1}(), {closure#1}(), {closure#8}(), {closure#1}(), {closure#9}(), ProcesoExtraordinario, Proyecto

### Community 32 - "EstudianteController.php"
Cohesion: 0.13
Nodes (5): {closure#10}(), {closure#11}(), {closure#7}(), {closure#9}(), Estudiante

### Community 34 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 35 - "api.php"
Cohesion: 0.17
Nodes (3): EstadisticaApiController, IntegracionApiController, PlazoApiController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 41 - "Proyecto.php"
Cohesion: 0.22
Nodes (3): Plantilla, {closure#1}(), {closure#2}()

### Community 43 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 44 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.15
Nodes (3): CarreraDepartamentoController, BandejaExpedientesController, ProcesosExtraordinariosController

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.18
Nodes (3): AreaConocimiento, Departamento, VersionPlantilla

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
Cohesion: 0.11
Nodes (4): RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 91 - "AuditoriaApiController.php"
Cohesion: 0.29
Nodes (3): AuditoriaApiController, {closure#1}(), RevisionMemoria

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.10
Nodes (5): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 95 - "ProyectoController.php"
Cohesion: 0.05
Nodes (3): {closure#28}(), {closure#33}(), ProyectoController

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
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 454 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **80 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `EstadisticasController.php`, `EstadoMemoria`, `Illuminate\View\View`, `CarreraApiController.php`, `EstudianteApiController.php`, `PlantillaController.php`, `Proyecto`, `DocumentoProyectoController.php`, `UsuarioController.php`, `Illuminate\Http\Request`, `EstudianteController.php`, `CertificacionApiController.php`, `api.php`, `ProyectoApiController`, `AreaConocimiento`, `FormatoApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `Illuminate\Http\RedirectResponse`, `Illuminate\Database\Eloquent\Model`, `UpdateProfileRequest`, `BandejaExpedientesController.php`, `web.php`, `GestionProrrogasController.php`, `EstadisticasController`, `MemoriaController.php`, `AuditoriaApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstadisticasController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `CertificacionApiController.php`, `EstadisticasController.php`, `EstadoMemoria`, `Proyecto.php`, `User`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Model`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `DocumentoProyectoController.php`, `ProyectoController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `EstadisticasExportService.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Why does `EstadoMemoria` connect `EstadoMemoria` to `Proyecto.php`, `User`, `Proyecto`, `BandejaExpedientesController.php`, `Memoria`, `MemoriaController.php`, `AuditoriaApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._