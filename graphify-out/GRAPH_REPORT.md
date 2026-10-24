# Graph Report - sigps-app  (2026-10-23)

## Corpus Check
- 148 files · ~54,369 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 848 nodes · 1544 edges · 105 communities (28 shown, 77 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7caf1f9f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- CertificacionController.php
- EstudianteController.php
- Illuminate\Database\Schema\Blueprint
- package.json
- Illuminate\View\View
- Carrera
- Controller
- AppServiceProvider
- IntegracionApiController.php
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Support\Facades\Schema
- BitacoraAuditoriaController.php
- ProyectoApiController.php
- 2026_10_14_000003_create_estudiantes_tables.php
- Illuminate\Http\JsonResponse
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000008_create_plantillas_tables.php
- AreaConocimientoController.php
- bootstrap/app.php
- Illuminate\Http\RedirectResponse
- Illuminate\Http\Request
- ProyectoApiController
- 2026_10_14_000005_create_proyectos_tables.php
- DocumentoProyectoController.php
- UpdateProfileRequest
- PlantillaController.php
- Proyecto
- catalogos/index.blade.php
- UsuarioController.php
- Memoria
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- EstadisticasController.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- EstadoMemoria
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
- Illuminate\Http\JsonResponse
- MemoriaController.php
- Illuminate\Support\Facades\DB
- web.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- require-dev
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
1. `Controller` - 65 edges
2. `Proyecto` - 40 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `Estudiante` - 16 edges
6. `ProyectoController` - 15 edges
7. `Carrera` - 14 edges
8. `Certificacion` - 14 edges
9. `Memoria` - 14 edges
10. `AreaConocimiento` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php
- `AreaConocimientoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/AreaConocimientoController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (105 total, 77 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 2 - "CertificacionController.php"
Cohesion: 0.10
Nodes (3): {closure#5}(), {closure#9}(), Certificacion

### Community 3 - "EstudianteController.php"
Cohesion: 0.12
Nodes (5): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante, {closure#3}()

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "Illuminate\View\View"
Cohesion: 0.19
Nodes (3): ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Controller"
Cohesion: 0.22
Nodes (3): LoginController, Controller, NotificacionController

### Community 10 - "IntegracionApiController.php"
Cohesion: 0.06
Nodes (11): {closure#1}(), {closure#2}(), DocumentoProyecto, User, ExpedienteRecibidoNotification, AdminSeeder, DatabaseSeeder, EstudianteSeeder (+3 more)

### Community 32 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.17
Nodes (4): AreaConocimiento, Departamento, Plantilla, VersionPlantilla

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 39 - "Illuminate\Http\Request"
Cohesion: 0.21
Nodes (3): BandejaExpedientesController, CertificacionController, GestionProrrogasController

### Community 41 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 45 - "Proyecto"
Cohesion: 0.07
Nodes (8): {closure#1}(), {closure#1}(), {closure#28}(), {closure#33}(), ProyectoController, {closure#9}(), Proyecto, Usuario

### Community 49 - "Memoria"
Cohesion: 0.20
Nodes (3): {closure#1}(), Memoria, RevisionMemoria

### Community 51 - "EstadisticasController.php"
Cohesion: 0.09
Nodes (5): FormatoApiController, CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 53 - "EstadoMemoria"
Cohesion: 0.09
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 87 - "Illuminate\Http\JsonResponse"
Cohesion: 0.14
Nodes (4): AuditoriaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController

### Community 89 - "MemoriaController.php"
Cohesion: 0.07
Nodes (6): {closure#7}(), MemoriaController, RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.12
Nodes (3): HistorialFaseProyecto, LogAccion, ProcesoExtraordinario

### Community 93 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

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

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 385 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **77 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `CertificacionController.php`, `EstudianteController.php`, `Illuminate\View\View`, `IntegracionApiController.php`, `BitacoraAuditoriaController.php`, `ProyectoApiController.php`, `Illuminate\Http\JsonResponse`, `AreaConocimientoController.php`, `ProyectoController.php`, `Illuminate\Http\RedirectResponse`, `Illuminate\Http\Request`, `ProyectoApiController`, `DocumentoProyectoController.php`, `UpdateProfileRequest`, `PlantillaController.php`, `Proyecto`, `UsuarioController.php`, `Memoria`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `EstadisticasController.php`, `EstadoMemoria`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `Illuminate\Http\JsonResponse`, `MemoriaController.php`, `Illuminate\Support\Facades\DB`, `web.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `CertificacionController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Database\Eloquent\Model`, `CertificacionController.php`, `EstudianteController.php`, `ProyectoController.php`, `IntegracionApiController.php`, `DocumentoProyectoController.php`, `EstadoMemoria`, `MemoriaController.php`, `Illuminate\Support\Facades\DB`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoApiController.php`, `Illuminate\Http\JsonResponse`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Should `EstudianteController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12380952380952381 - nodes in this community are weakly interconnected._
- **Why does `User` connect `IntegracionApiController.php` to `EstudianteController.php`, `UserFactory.php`, `Proyecto`, `UsuarioController.php`, `Illuminate\Http\JsonResponse`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._