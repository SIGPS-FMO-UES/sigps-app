# Graph Report - sigps-app  (2026-10-21)

## Corpus Check
- 145 files · ~36,584 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 791 nodes · 1421 edges · 111 communities (27 shown, 84 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6a7f2c0d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Http\JsonResponse
- Illuminate\Database\Schema\Blueprint
- package.json
- EstudianteController.php
- config
- TestCase
- AppServiceProvider
- BandejaExpedientesController.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Http\Request
- MemoriaController.php
- CertificacionController.php
- EstadisticasController.php
- web.php
- PlantillaController.php
- UsuarioController.php
- Controller
- Controller
- bootstrap/app.php
- Illuminate\View\View
- require-dev
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- GestionProrrogasController.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_10_21_000002_remove_tutors_from_sys01.php
- 2026_10_21_000004_add_project_lifecycle_and_history.php
- psr-4
- require
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000005_create_proyectos_tables.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000001_create_tutores_table.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_add_activa_to_areas_de_conocimiento_table.php
- 2026_10_21_224046_add_remision_fields_to_proyectos_table.php
- Illuminate\Database\Eloquent\Relations\HasMany
- autoload-dev
- extra
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
- EstudianteApiController.php
- CertificacionApiController.php
- api.php
- Proyecto
- Illuminate\Database\Eloquent\Model
- FormatoApiController.php
- ProyectoController
- ProyectoApiController.php
- .guardarDocumento
- ProyectoApiController
- Illuminate\Support\Facades\DB
- UpsDashboardController.php
- Illuminate\Http\Resources\Json\JsonResource
- 2026_10_21_224046_create_certificaciones_table.php
- 2026_10_22_014646_create_personal_access_tokens_table.php
- UserFactory.php
- IntegracionApiController.php
- LogAccion.php
- AuditoriaApiController.php
- Plantilla
- TipoPlantilla
- Memoria

## God Nodes (most connected - your core abstractions)
1. `Controller` - 57 edges
2. `Proyecto` - 37 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 19 edges
5. `Memoria` - 15 edges
6. `ProyectoController` - 14 edges
7. `Certificacion` - 14 edges
8. `Usuario` - 14 edges
9. `AreaConocimiento` - 12 edges
10. `Estudiante` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#7}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php

## Import Cycles
- None detected.

## Communities (111 total, 84 thin omitted)

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

### Community 6 - "EstudianteController.php"
Cohesion: 0.07
Nodes (9): {closure#8}(), Estudiante, User, AdminSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder, {closure#3}() (+1 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 10 - "BandejaExpedientesController.php"
Cohesion: 0.09
Nodes (5): RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest

### Community 11 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.15
Nodes (5): {closure#1}(), {closure#9}(), Certificacion, LogAccion, ProcesoExtraordinario

### Community 27 - "Illuminate\Http\Request"
Cohesion: 0.16
Nodes (6): AreaConocimientoController, {closure#1}(), BandejaExpedientesController, CertificacionController, {closure#9}(), ProcesosExtraordinariosController

### Community 29 - "CertificacionController.php"
Cohesion: 0.10
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 30 - "EstadisticasController.php"
Cohesion: 0.18
Nodes (3): {closure#1}(), {closure#2}(), EstadisticasController

### Community 32 - "PlantillaController.php"
Cohesion: 0.33
Nodes (3): {closure#2}(), {closure#3}(), PlantillaController

### Community 34 - "Controller"
Cohesion: 0.14
Nodes (4): ProfileController, Controller, CatalogoFormatosController, DocumentoProyectoController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 38 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 42 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 43 - "2026_10_21_000002_remove_tutors_from_sys01.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "2026_10_21_000004_add_project_lifecycle_and_history.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 45 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 46 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-permission

### Community 57 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 58 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 88 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 89 - "api.php"
Cohesion: 0.15
Nodes (4): AuditoriaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController

### Community 91 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.22
Nodes (4): AreaConocimiento, Carrera, Departamento, VersionPlantilla

### Community 105 - "IntegracionApiController.php"
Cohesion: 0.22
Nodes (3): {closure#1}(), DocumentoProyecto, HistorialFaseProyecto

### Community 109 - "TipoPlantilla"
Cohesion: 0.40
Nodes (3): TipoPlantilla, ArchivoEjemplo, Autorellenable

## Knowledge Gaps
- **38 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+33 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 355 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **84 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ProyectoController.php`, `Illuminate\Http\JsonResponse`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Http\Request`, `MemoriaController.php`, `CertificacionController.php`, `EstadisticasController.php`, `web.php`, `PlantillaController.php`, `UsuarioController.php`, `Controller`, `Illuminate\View\View`, `GestionProrrogasController.php`, `EstudianteApiController.php`, `CertificacionApiController.php`, `api.php`, `Proyecto`, `FormatoApiController.php`, `ProyectoController`, `ProyectoApiController.php`, `ProyectoApiController`, `Illuminate\Support\Facades\DB`, `UpsDashboardController.php`, `IntegracionApiController.php`, `AuditoriaApiController.php`?**
  _High betweenness centrality (0.084) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _38 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ProyectoController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.10526315789473684 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Support\Facades\DB`, `Controller`, `Illuminate\Http\JsonResponse`, `ProyectoController.php`, `UpsDashboardController.php`, `EstudianteController.php`, `IntegracionApiController.php`, `ProyectoController`, `CertificacionApiController.php`, `Illuminate\Database\Eloquent\Model`, `MemoriaController.php`, `CertificacionController.php`, `ProyectoApiController.php`, `.guardarDocumento`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `LogAccion` connect `Illuminate\Database\Eloquent\Relations\BelongsTo` to `UsuarioController.php`, `Illuminate\Support\Facades\DB`, `Controller`, `Controller`, `ProyectoController.php`, `EstudianteController.php`, `IntegracionApiController.php`, `GestionProrrogasController.php`, `AuditoriaApiController.php`, `LogAccion.php`, `CertificacionApiController.php`, `Illuminate\Database\Eloquent\Model`, `MemoriaController.php`, `ProyectoApiController.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `EstudianteController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06882591093117409 - nodes in this community are weakly interconnected._