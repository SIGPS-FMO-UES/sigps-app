# Graph Report - sigps-app  (2026-10-22)

## Corpus Check
- 145 files · ~36,796 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 792 nodes · 1426 edges · 104 communities (28 shown, 76 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3c5d7954`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- UserFactory.php
- Illuminate\Database\Schema\Blueprint
- package.json
- Illuminate\Database\Seeder
- Illuminate\Database\Eloquent\Model
- EstudianteApiController.php
- AppServiceProvider
- EstudianteController.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- CertificacionApiController.php
- DocumentoProyectoController.php
- CertificacionController.php
- EstadisticasController.php
- web.php
- PlantillaController.php
- UsuarioController.php
- Illuminate\Support\Facades\DB
- BitacoraAuditoriaController.php
- bootstrap/app.php
- Illuminate\View\View
- Illuminate\Database\Eloquent\Relations\HasMany
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- 2026_10_22_014646_create_personal_access_tokens_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_10_21_000002_remove_tutors_from_sys01.php
- 2026_10_21_000004_add_project_lifecycle_and_history.php
- require-dev
- ProyectoController
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000005_create_proyectos_tables.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000001_create_tutores_table.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_add_activa_to_areas_de_conocimiento_table.php
- 2026_10_21_224046_add_remision_fields_to_proyectos_table.php
- MemoriaController.php
- Proyecto
- config
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
- GestionProrrogasController.php
- require
- LogAccion
- AuditoriaApiController.php
- Controller
- IntegracionApiController.php
- Illuminate\Http\Resources\Json\JsonResource
- psr-4
- autoload-dev
- extra
- Illuminate\Http\Request
- LoginController.php
- TestCase
- 2026_10_21_224046_create_certificaciones_table.php
- TipoPlantilla

## God Nodes (most connected - your core abstractions)
1. `Controller` - 57 edges
2. `Proyecto` - 38 edges
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
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php
- `{closure#4}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/UpsDashboardController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (104 total, 76 thin omitted)

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
Cohesion: 0.06
Nodes (11): {closure#8}(), {closure#9}(), Estudiante, User, AdminSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder (+3 more)

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.17
Nodes (4): AreaConocimiento, Carrera, Plantilla, VersionPlantilla

### Community 10 - "EstudianteController.php"
Cohesion: 0.06
Nodes (6): ProfileController, RecepcionExpedienteAvaladoRequest, LoginRequest, UpdateProfileRequest, {closure#1}(), DictamenMemoriaRequest

### Community 11 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.17
Nodes (4): {closure#1}(), {closure#9}(), Certificacion, ProcesoExtraordinario

### Community 27 - "CertificacionApiController.php"
Cohesion: 0.18
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 29 - "CertificacionController.php"
Cohesion: 0.07
Nodes (13): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+5 more)

### Community 30 - "EstadisticasController.php"
Cohesion: 0.10
Nodes (4): CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 32 - "PlantillaController.php"
Cohesion: 0.33
Nodes (3): {closure#2}(), {closure#3}(), PlantillaController

### Community 34 - "Illuminate\Support\Facades\DB"
Cohesion: 0.16
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 42 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 43 - "2026_10_21_000002_remove_tutors_from_sys01.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "2026_10_21_000004_add_project_lifecycle_and_history.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 45 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 46 - "ProyectoController"
Cohesion: 0.22
Nodes (3): {closure#16}(), {closure#21}(), ProyectoController

### Community 57 - "Proyecto"
Cohesion: 0.11
Nodes (3): {closure#1}(), {closure#1}(), Proyecto

### Community 58 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 88 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-permission

### Community 91 - "Controller"
Cohesion: 0.11
Nodes (8): AuditoriaApiController, AuthApiController, EstadisticaApiController, FormatoApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController, Controller

### Community 94 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 95 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 96 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 97 - "Illuminate\Http\Request"
Cohesion: 0.15
Nodes (6): AreaConocimientoController, {closure#1}(), BandejaExpedientesController, CertificacionController, GestionProrrogasController, ProcesosExtraordinariosController

### Community 102 - "TipoPlantilla"
Cohesion: 0.40
Nodes (3): TipoPlantilla, ArchivoEjemplo, Autorellenable

## Knowledge Gaps
- **38 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+33 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 356 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **76 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ProyectoController.php`, `Illuminate\Database\Seeder`, `EstudianteApiController.php`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `CertificacionApiController.php`, `DocumentoProyectoController.php`, `CertificacionController.php`, `EstadisticasController.php`, `web.php`, `PlantillaController.php`, `UsuarioController.php`, `Illuminate\Support\Facades\DB`, `BitacoraAuditoriaController.php`, `Illuminate\View\View`, `ProyectoController`, `MemoriaController.php`, `Proyecto`, `GestionProrrogasController.php`, `AuditoriaApiController.php`, `IntegracionApiController.php`, `Illuminate\Http\Request`, `LoginController.php`?**
  _High betweenness centrality (0.083) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _38 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ProyectoController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09523809523809523 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Support\Facades\DB`, `ProyectoController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Model`, `CertificacionApiController.php`, `Illuminate\Database\Seeder`, `EstudianteController.php`, `DocumentoProyectoController.php`, `ProyectoController`, `MemoriaController.php`, `Controller`, `IntegracionApiController.php`, `CertificacionController.php`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `LogAccion` connect `LogAccion` to `UsuarioController.php`, `Illuminate\Support\Facades\DB`, `BitacoraAuditoriaController.php`, `ProyectoController.php`, `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Model`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `DocumentoProyectoController.php`, `GestionProrrogasController.php`, `MemoriaController.php`, `Proyecto`, `AuditoriaApiController.php`, `CertificacionApiController.php`, `IntegracionApiController.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `Illuminate\Database\Seeder` be split into smaller, more focused modules?**
  _Cohesion score 0.057004830917874394 - nodes in this community are weakly interconnected._