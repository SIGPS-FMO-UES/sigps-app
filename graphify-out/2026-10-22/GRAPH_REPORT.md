# Graph Report - sigps-app  (2026-10-22)

## Corpus Check
- 157 files · ~47,764 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 883 nodes · 1604 edges · 120 communities (29 shown, 91 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9305fd1f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- MemoriaController.php
- Illuminate\Database\Schema\Blueprint
- package.json
- Illuminate\Database\Seeder
- Illuminate\Database\Eloquent\Model
- Controller
- AppServiceProvider
- Illuminate\Http\RedirectResponse
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Database\Migrations\Migration
- Illuminate\View\View
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000005_create_proyectos_tables.php
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000007_create_logs_acciones_table.php
- 2026_10_14_000008_create_plantillas_tables.php
- GestionProrrogasController.php
- bootstrap/app.php
- 2026_10_21_000001_create_tutores_table.php
- 2026_10_21_000002_remove_tutors_from_sys01.php
- CarreraApiController.php
- 2026_10_21_000004_add_project_lifecycle_and_history.php
- 2026_10_21_224046_add_activa_to_areas_de_conocimiento_table.php
- 2026_10_21_224046_add_remision_fields_to_proyectos_table.php
- Proyecto
- PlantillaController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- 2026_10_22_100000_create_notifications_table.php
- catalogos/index.blade.php
- UsuarioController.php
- api.php
- Illuminate\Http\JsonResponse
- EstadisticasController.php
- CertificacionApiController.php
- EstadoMemoria
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- UpsDashboardController.php
- User
- IntegracionApiController.php
- EstudianteApiController.php
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
- ProyectoApiController.php
- EstudianteController.php
- DictamenMemoriaRequest
- web.php
- ProyectoController
- require-dev
- AreaConocimientoController.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- EstudianteSeeder.php
- IntegracionApiController.php
- LogAccion
- TestCase
- BitacoraAuditoriaController.php
- config
- UserFactory.php
- require
- 0001_01_01_000002_create_jobs_table.php
- psr-4
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- autoload-dev
- extra
- Proyecto
- Memoria
- Carrera
- Plantilla
- ProyectoSeeder.php
- Illuminate\Http\Resources\Json\JsonResource
- 2026_10_22_110000_add_horas_servicio_social_to_carreras_table.php
- RoleAndPermissionSeeder.php
- 2026_10_21_224046_create_certificaciones_table.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 39 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `ProyectoController` - 15 edges
6. `Carrera` - 14 edges
7. `Certificacion` - 14 edges
8. `Estudiante` - 14 edges
9. `Memoria` - 14 edges
10. `User` - 13 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php
- `AreaConocimientoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/AreaConocimientoController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (120 total, 91 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "Illuminate\Database\Seeder"
Cohesion: 0.25
Nodes (3): AdminSeeder, DatabaseSeeder, JefaturaSeeder

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.17
Nodes (5): {closure#1}(), AreaConocimiento, Departamento, RevisionMemoria, VersionPlantilla

### Community 8 - "Controller"
Cohesion: 0.17
Nodes (3): AuditoriaApiController, EstadisticaApiController, Controller

### Community 10 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.12
Nodes (4): CarreraDepartamentoController, BandejaExpedientesController, NotificacionController, ProcesosExtraordinariosController

### Community 28 - "Illuminate\View\View"
Cohesion: 0.15
Nodes (3): CertificacionController, ControlPlazosController, EstudianteController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 40 - "2026_10_21_000004_add_project_lifecycle_and_history.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 49 - "api.php"
Cohesion: 0.15
Nodes (3): FormatoApiController, IntegracionApiController, PlazoApiController

### Community 50 - "Illuminate\Http\JsonResponse"
Cohesion: 0.20
Nodes (3): AuthApiController, CertificacionApiController, ProrrogaApiController

### Community 51 - "EstadisticasController.php"
Cohesion: 0.10
Nodes (4): CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 53 - "EstadoMemoria"
Cohesion: 0.07
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 87 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 88 - "EstudianteController.php"
Cohesion: 0.15
Nodes (5): {closure#10}(), {closure#11}(), {closure#12}(), {closure#8}(), Estudiante

### Community 89 - "DictamenMemoriaRequest"
Cohesion: 0.09
Nodes (5): RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest

### Community 91 - "web.php"
Cohesion: 0.13
Nodes (3): LoginController, ProfileController, DocumentoProyectoController

### Community 93 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 95 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (5): {closure#1}(), {closure#9}(), Certificacion, HistorialFaseProyecto, ProcesoExtraordinario

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
Nodes (4): {closure#1}(), {closure#1}(), {closure#9}(), Proyecto

## Knowledge Gaps
- **39 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+34 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 402 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **91 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `MemoriaController.php`, `ProyectoController.php`, `Illuminate\Http\RedirectResponse`, `Illuminate\View\View`, `GestionProrrogasController.php`, `CarreraApiController.php`, `PlantillaController.php`, `UsuarioController.php`, `api.php`, `Illuminate\Http\JsonResponse`, `EstadisticasController.php`, `CertificacionApiController.php`, `EstadoMemoria`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `UpsDashboardController.php`, `IntegracionApiController.php`, `EstudianteApiController.php`, `ProyectoApiController.php`, `EstudianteController.php`, `Illuminate\Support\Facades\DB`, `web.php`, `ProyectoController`, `AreaConocimientoController.php`, `BitacoraAuditoriaController.php`, `Proyecto`?**
  _High betweenness centrality (0.090) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ProyectoController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.0625 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `MemoriaController.php`, `ProyectoController.php`, `Illuminate\Database\Eloquent\Model`, `Controller`, `Proyecto`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Memoria`, `CertificacionApiController.php`, `EstadoMemoria`, `UpsDashboardController.php`, `ProyectoApiController.php`, `ProyectoSeeder.php`, `IntegracionApiController.php`, `Illuminate\Support\Facades\DB`, `ProyectoController`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `Illuminate\Database\Seeder`, `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `UsuarioController.php`, `Illuminate\Http\JsonResponse`, `ProyectoSeeder.php`, `IntegracionApiController.php`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `Illuminate\Http\RedirectResponse` be split into smaller, more focused modules?**
  _Cohesion score 0.12333333333333334 - nodes in this community are weakly interconnected._