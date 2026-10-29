# Graph Report - sigps-app  (2026-10-28)

## Corpus Check
- 144 files · ~65,775 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 932 nodes · 1680 edges · 104 communities (28 shown, 76 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `404dc04e`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Database\Schema\Blueprint
- App\Http\Controllers\Api\V1\ProyectoApiController
- package.json
- Illuminate\Database\Eloquent\Attributes\Fillable
- EstudianteController
- Illuminate\Http\RedirectResponse
- AppServiceProvider
- App\Http\Controllers\Api\V1\CarreraApiController
- EstudianteController.php
- dev/entrypoint.sh
- docker/entrypoint.sh
- EstudianteApiController.php
- Memoria
- PlantillaController.php
- DocumentoProyectoController.php
- UsuarioController.php
- ProyectoController
- api.php
- Illuminate\Database\Eloquent\Relations\HasMany
- CertificacionApiController.php
- bootstrap/app.php
- Controller
- Plantilla
- EstadisticasExportService.php
- AuditoriaApiController.php
- Illuminate\Http\JsonResponse
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- require-dev
- AreaConocimientoController.php
- Illuminate\Support\Facades\Schema
- catalogos/index.blade.php
- Carrera
- Illuminate\Database\Migrations\Migration
- EstadisticasController
- UserFactory.php
- FormatoApiController.php
- GestionProrrogasController.php
- Illuminate\Database\Eloquent\Model
- EstadoMemoria
- config
- require
- Illuminate\Database\Eloquent\Relations\BelongsTo
- areas/index.blade.php
- auditoria/index.blade.php
- plantillas/index.blade.php
- usuarios/create.blade.php
- usuarios/edit.blade.php
- usuarios/index.blade.php
- TestCase
- bandeja/index.blade.php
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
2. `Proyecto` - 48 edges
3. `EstadoMemoria` - 21 edges
4. `LogAccion` - 21 edges
5. `ProcesoExtraordinario` - 20 edges
6. `MemoriaController` - 18 edges
7. `Memoria` - 18 edges
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
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (104 total, 76 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 4 - "App\Http\Controllers\Api\V1\ProyectoApiController"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 7 - "EstudianteController"
Cohesion: 0.14
Nodes (4): {closure#1}(), ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.16
Nodes (4): CarreraDepartamentoController, BandejaExpedientesController, MemoriaController, ProcesosExtraordinariosController

### Community 13 - "EstudianteController.php"
Cohesion: 0.18
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 29 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 35 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "Controller"
Cohesion: 0.09
Nodes (6): BitacoraAuditoriaController, LoginController, ProfileController, Controller, NotificacionController, UpsDashboardController

### Community 41 - "Illuminate\Http\JsonResponse"
Cohesion: 0.13
Nodes (5): AuditoriaApiController, AuthApiController, EstadisticaApiController, {closure#1}(), PlazoApiController

### Community 44 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 50 - "EstadisticasController"
Cohesion: 0.29
Nodes (3): {closure#46}(), {closure#48}(), EstadisticasController

### Community 54 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.33
Nodes (3): AreaConocimiento, Departamento, VersionPlantilla

### Community 55 - "EstadoMemoria"
Cohesion: 0.06
Nodes (12): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+4 more)

### Community 56 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 57 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

### Community 58 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.15
Nodes (5): {closure#1}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

### Community 89 - "LoginRequest"
Cohesion: 0.13
Nodes (3): RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest

### Community 90 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 92 - "Proyecto"
Cohesion: 0.07
Nodes (6): {closure#1}(), {closure#28}(), {closure#33}(), {closure#9}(), ProcesoExtraordinario, Proyecto

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
  These have ≤1 connection - possible missing edges. (Counts symbols only; 447 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **76 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `App\Http\Controllers\Ups\EstadisticasController`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `EstudianteController`, `Illuminate\Http\RedirectResponse`, `ProyectoController.php`, `App\Http\Controllers\Api\V1\CarreraApiController`, `EstudianteController.php`, `EstudianteApiController.php`, `PlantillaController.php`, `DocumentoProyectoController.php`, `UsuarioController.php`, `ProyectoController`, `api.php`, `CertificacionApiController.php`, `EstadisticasExportService.php`, `AuditoriaApiController.php`, `Illuminate\Http\JsonResponse`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `AreaConocimientoController.php`, `EstadisticasController`, `FormatoApiController.php`, `GestionProrrogasController.php`, `EstadoMemoria`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `CarreraDepartamentoController.php`, `Proyecto`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _37 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `App\Http\Controllers\Ups\EstadisticasController` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `ProyectoController`, `App\Http\Controllers\Ups\EstadisticasController`, `CertificacionApiController.php`, `App\Http\Controllers\Api\V1\ProyectoApiController`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Attributes\Fillable`, `EstudianteController`, `Plantilla`, `Illuminate\Http\JsonResponse`, `ProyectoController.php`, `Illuminate\Database\Eloquent\Model`, `EstadoMemoria`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Memoria`, `DocumentoProyectoController.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `App\Http\Controllers\Api\V1\ProyectoApiController` be split into smaller, more focused modules?**
  _Cohesion score 0.14705882352941177 - nodes in this community are weakly interconnected._
- **Why does `User` connect `Illuminate\Database\Seeder` to `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Http\JsonResponse`, `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `UsuarioController.php`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._