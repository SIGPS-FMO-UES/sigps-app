# Graph Report - sigps-app  (2026-10-27)

## Corpus Check
- 151 files · ~66,867 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 965 nodes · 1726 edges · 105 communities (32 shown, 73 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b466e4da`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- ProyectoApiController.php
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
- Illuminate\Http\Request
- PlantillaController.php
- Controller
- UsuarioController.php
- EstudianteController.php
- Illuminate\Http\JsonResponse
- Illuminate\Database\Eloquent\Relations\HasMany
- CertificacionApiController.php
- bootstrap/app.php
- api.php
- BitacoraAuditoriaController.php
- AuditoriaApiController.php
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- Proyecto.php
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- EstadisticasController
- Symfony\Component\HttpFoundation\StreamedResponse
- Controller
- GestionProrrogasController.php
- Memoria
- config
- require
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
- LoginRequest
- 0001_01_01_000002_create_jobs_table.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- 2026_10_14_000005_create_proyectos_tables.php
- Illuminate\Database\Seeder
- ProyectoController.php
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
- Proyecto

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 49 edges
3. `EstadoMemoria` - 23 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Memoria` - 18 edges
7. `Estudiante` - 16 edges
8. `ProyectoController` - 15 edges
9. `Carrera` - 15 edges
10. `AreaConocimiento` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (105 total, 73 thin omitted)

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

### Community 6 - "EstadoMemoria"
Cohesion: 0.06
Nodes (13): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+5 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.14
Nodes (4): {closure#1}(), ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.18
Nodes (3): AreaConocimientoController, {closure#1}(), CarreraDepartamentoController

### Community 28 - "Illuminate\Http\Request"
Cohesion: 0.19
Nodes (3): BandejaExpedientesController, CertificacionController, ProcesosExtraordinariosController

### Community 29 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 32 - "EstudianteController.php"
Cohesion: 0.10
Nodes (6): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante, {closure#3}(), ProyectoSeeder

### Community 33 - "Illuminate\Http\JsonResponse"
Cohesion: 0.12
Nodes (3): AuthApiController, EstadisticaApiController, FormatoApiController

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "api.php"
Cohesion: 0.12
Nodes (5): AuditoriaApiController, IntegracionApiController, {closure#1}(), PlazoApiController, ProrrogaApiController

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 45 - "Proyecto.php"
Cohesion: 0.22
Nodes (3): Plantilla, {closure#1}(), {closure#2}()

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.19
Nodes (4): AreaConocimiento, Departamento, HistorialFaseProyecto, VersionPlantilla

### Community 50 - "EstadisticasController"
Cohesion: 0.29
Nodes (3): {closure#46}(), {closure#48}(), EstadisticasController

### Community 52 - "Controller"
Cohesion: 0.09
Nodes (6): LoginController, ProfileController, Controller, CatalogoFormatosController, NotificacionController, UpsDashboardController

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 89 - "LoginRequest"
Cohesion: 0.09
Nodes (5): RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.16
Nodes (5): {closure#1}(), {closure#9}(), Certificacion, DocumentoProyecto, LogAccion

### Community 93 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 94 - "Illuminate\Database\Seeder"
Cohesion: 0.05
Nodes (10): {closure#2}(), User, ExpedienteRecibidoNotification, UserFactory, AdminSeeder, DashboardDemoSeeder, DatabaseSeeder, EstudianteSeeder (+2 more)

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

### Community 113 - "Proyecto"
Cohesion: 0.06
Nodes (4): {closure#1}(), {closure#9}(), ProcesoExtraordinario, Proyecto

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 470 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **73 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `EstadisticasController.php`, `ProyectoApiController.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Http\Request`, `CarreraApiController.php`, `EstudianteApiController.php`, `Illuminate\Http\Request`, `PlantillaController.php`, `Controller`, `UsuarioController.php`, `EstudianteController.php`, `Illuminate\Http\JsonResponse`, `CertificacionApiController.php`, `api.php`, `BitacoraAuditoriaController.php`, `AuditoriaApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `EstadisticasController`, `GestionProrrogasController.php`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController.php`, `Proyecto`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstadisticasController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `EstudianteController.php`, `Illuminate\Http\JsonResponse`, `EstadisticasController.php`, `CertificacionApiController.php`, `ProyectoApiController.php`, `api.php`, `EstadoMemoria`, `Illuminate\View\View`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Proyecto.php`, `Illuminate\Database\Eloquent\Model`, `Memoria`, `MemoriaController.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Controller`, `ProyectoController.php`?**
  _High betweenness centrality (0.067) - this node is a cross-community bridge._
- **Should `ProyectoApiController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14705882352941177 - nodes in this community are weakly interconnected._
- **Why does `EstadoMemoria` connect `EstadoMemoria` to `AuditoriaApiController.php`, `Proyecto.php`, `Proyecto`, `Memoria`, `MemoriaController.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Controller`, `ProyectoController.php`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._