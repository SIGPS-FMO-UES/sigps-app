# Graph Report - sigps-app  (2026-10-22)

## Corpus Check
- 145 files · ~44,660 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 814 nodes · 1459 edges · 103 communities (29 shown, 74 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0b8e3e63`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- FormatoApiController.php
- api.php
- Illuminate\Database\Schema\Blueprint
- package.json
- EstudianteController.php
- Illuminate\Database\Eloquent\Model
- EstudianteApiController.php
- AppServiceProvider
- IntegracionApiController.php
- UpdateProfileRequest
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- CertificacionApiController.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- EstadoMemoria
- EstadisticasController.php
- web.php
- PlantillaController.php
- UsuarioController.php
- Controller
- bootstrap/app.php
- Illuminate\Support\Facades\DB
- AuditoriaApiController.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Migrations\Migration
- Illuminate\Http\JsonResponse
- 0001_01_01_000002_create_jobs_table.php
- Usuario
- 2026_10_21_000004_add_project_lifecycle_and_history.php
- require-dev
- GestionProrrogasController.php
- 0001_01_01_000001_create_cache_table.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- 2026_10_14_000003_create_estudiantes_tables.php
- 2026_10_14_000008_create_plantillas_tables.php
- 2026_10_21_000001_create_tutores_table.php
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- 2026_10_21_224046_add_activa_to_areas_de_conocimiento_table.php
- 2026_10_21_224046_add_remision_fields_to_proyectos_table.php
- ProyectoApiController.php
- Illuminate\Database\Eloquent\Relations\HasMany
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
- Illuminate\Http\Request
- require
- EstudianteController.php
- Memoria
- ProyectoController.php
- Illuminate\Http\Resources\Json\JsonResource
- psr-4
- autoload-dev
- extra
- Illuminate\Http\Request
- TestCase
- 2026_10_14_000005_create_proyectos_tables.php
- 2026_10_14_000004_create_areas_de_conocimiento_table.php
- 2026_10_21_000002_remove_tutors_from_sys01.php
- 2026_10_22_014646_create_personal_access_tokens_table.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 57 edges
2. `Proyecto` - 39 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `ProyectoController` - 15 edges
6. `Certificacion` - 14 edges
7. `Estudiante` - 14 edges
8. `Memoria` - 14 edges
9. `Usuario` - 14 edges
10. `AreaConocimiento` - 12 edges

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

## Communities (103 total, 74 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "api.php"
Cohesion: 0.18
Nodes (3): AuditoriaApiController, IntegracionApiController, ProrrogaApiController

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstudianteController.php"
Cohesion: 0.06
Nodes (13): {closure#10}(), {closure#11}(), {closure#12}(), {closure#8}(), Estudiante, User, AdminSeeder, DatabaseSeeder (+5 more)

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.16
Nodes (4): AreaConocimiento, Carrera, Departamento, VersionPlantilla

### Community 10 - "IntegracionApiController.php"
Cohesion: 0.22
Nodes (3): {closure#1}(), DocumentoProyecto, HistorialFaseProyecto

### Community 27 - "CertificacionApiController.php"
Cohesion: 0.18
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 28 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.15
Nodes (5): {closure#1}(), {closure#9}(), Certificacion, LogAccion, ProcesoExtraordinario

### Community 29 - "EstadoMemoria"
Cohesion: 0.06
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 30 - "EstadisticasController.php"
Cohesion: 0.21
Nodes (3): {closure#1}(), {closure#2}(), EstadisticasController

### Community 32 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 35 - "Controller"
Cohesion: 0.14
Nodes (4): BitacoraAuditoriaController, EstadisticaApiController, Controller, DocumentoProyectoController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 41 - "Illuminate\Http\JsonResponse"
Cohesion: 0.19
Nodes (3): AuthApiController, {closure#1}(), PlazoApiController

### Community 42 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "2026_10_21_000004_add_project_lifecycle_and_history.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 45 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 56 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 58 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 87 - "Illuminate\Http\Request"
Cohesion: 0.18
Nodes (3): BandejaExpedientesController, CertificacionController, EstudianteController

### Community 88 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-permission

### Community 89 - "EstudianteController.php"
Cohesion: 0.07
Nodes (6): {closure#7}(), MemoriaController, RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 92 - "ProyectoController.php"
Cohesion: 0.05
Nodes (3): {closure#28}(), {closure#33}(), ProyectoController

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
Cohesion: 0.26
Nodes (3): AreaConocimientoController, {closure#1}(), ProcesosExtraordinariosController

## Knowledge Gaps
- **38 isolated node(s):** `Recibido`, `EnRevision`, `Observada`, `Aprobada`, `Aprovada` (+33 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 375 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **74 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `FormatoApiController.php`, `api.php`, `EstudianteController.php`, `EstudianteApiController.php`, `IntegracionApiController.php`, `UpdateProfileRequest`, `CertificacionApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `EstadoMemoria`, `EstadisticasController.php`, `web.php`, `PlantillaController.php`, `UsuarioController.php`, `BandejaExpedientesController.php`, `Illuminate\Support\Facades\DB`, `AuditoriaApiController.php`, `Illuminate\Http\JsonResponse`, `GestionProrrogasController.php`, `ProyectoApiController.php`, `Illuminate\Http\Request`, `EstudianteController.php`, `ProyectoController.php`, `Illuminate\Http\Request`?**
  _High betweenness centrality (0.082) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _38 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `FormatoApiController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.13970588235294118 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Illuminate\Database\Eloquent\Relations\HasMany` to `BandejaExpedientesController.php`, `Controller`, `Illuminate\Support\Facades\DB`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Http\JsonResponse`, `IntegracionApiController.php`, `Memoria`, `ProyectoApiController.php`, `EstudianteController.php`, `CertificacionApiController.php`, `ProyectoController.php`, `EstadoMemoria`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `LogAccion` connect `Illuminate\Database\Eloquent\Relations\BelongsTo` to `UsuarioController.php`, `BandejaExpedientesController.php`, `Controller`, `Illuminate\Support\Facades\DB`, `AuditoriaApiController.php`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Model`, `IntegracionApiController.php`, `GestionProrrogasController.php`, `ProyectoApiController.php`, `EstudianteController.php`, `CertificacionApiController.php`, `ProyectoController.php`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `EstudianteController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05919661733615222 - nodes in this community are weakly interconnected._