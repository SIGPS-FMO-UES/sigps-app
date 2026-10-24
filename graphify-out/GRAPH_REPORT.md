# Graph Report - sigps-app  (2026-10-23)

## Corpus Check
- 148 files · ~50,585 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 847 nodes · 1543 edges · 107 communities (32 shown, 75 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `cd30165a`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- ProyectoApiController
- Proyecto
- Illuminate\Database\Schema\Blueprint
- package.json
- EstudianteController.php
- Illuminate\Database\Eloquent\Model
- Controller
- AppServiceProvider
- Illuminate\Database\Seeder
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Support\Facades\Schema
- Illuminate\View\View
- ProyectoApiController.php
- 2026_10_14_000003_create_estudiantes_tables.php
- RoleAndPermissionSeeder.php
- Plantilla
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000008_create_plantillas_tables.php
- IntegracionApiController.php
- bootstrap/app.php
- ProyectoController.php
- Illuminate\Http\RedirectResponse
- Illuminate\Http\Request
- ExpedienteRecibidoNotification
- 2026_10_14_000005_create_proyectos_tables.php
- ProyectoSeeder.php
- UpdateProfileRequest
- PlantillaController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- User
- catalogos/index.blade.php
- UsuarioController.php
- Memoria
- api.php
- EstadisticasController.php
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- Illuminate\Support\Facades\DB
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
- EstudianteSeeder.php
- IntegracionApiController.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- web.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- require-dev
- Illuminate\Support\Facades\DB
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
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php

## Import Cycles
- None detected.

## Communities (107 total, 75 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "Proyecto"
Cohesion: 0.19
Nodes (3): {closure#1}(), {closure#9}(), Proyecto

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 6 - "EstudianteController.php"
Cohesion: 0.15
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 7 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.16
Nodes (4): AreaConocimiento, Carrera, Departamento, VersionPlantilla

### Community 8 - "Controller"
Cohesion: 0.13
Nodes (4): BitacoraAuditoriaController, LoginController, Controller, NotificacionController

### Community 10 - "Illuminate\Database\Seeder"
Cohesion: 0.25
Nodes (3): AdminSeeder, DatabaseSeeder, JefaturaSeeder

### Community 28 - "Illuminate\View\View"
Cohesion: 0.19
Nodes (3): ControlPlazosController, {closure#11}(), EstudianteController

### Community 35 - "IntegracionApiController.php"
Cohesion: 0.22
Nodes (3): {closure#1}(), DocumentoProyecto, HistorialFaseProyecto

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "ProyectoController.php"
Cohesion: 0.05
Nodes (3): {closure#28}(), {closure#33}(), ProyectoController

### Community 38 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.18
Nodes (3): AreaConocimientoController, {closure#1}(), CarreraDepartamentoController

### Community 39 - "Illuminate\Http\Request"
Cohesion: 0.19
Nodes (3): BandejaExpedientesController, CertificacionController, ProcesosExtraordinariosController

### Community 41 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 44 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 49 - "Memoria"
Cohesion: 0.18
Nodes (3): {closure#1}(), Memoria, RevisionMemoria

### Community 50 - "api.php"
Cohesion: 0.17
Nodes (3): AuditoriaApiController, IntegracionApiController, ProrrogaApiController

### Community 51 - "EstadisticasController.php"
Cohesion: 0.09
Nodes (5): FormatoApiController, CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 53 - "Illuminate\Support\Facades\DB"
Cohesion: 0.06
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 54 - "CertificacionApiController.php"
Cohesion: 0.20
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 87 - "Illuminate\Http\JsonResponse"
Cohesion: 0.13
Nodes (4): AuthApiController, EstadisticaApiController, {closure#1}(), PlazoApiController

### Community 89 - "IntegracionApiController.php"
Cohesion: 0.05
Nodes (6): {closure#7}(), MemoriaController, RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.13
Nodes (5): {closure#1}(), {closure#9}(), Certificacion, LogAccion, ProcesoExtraordinario

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
  These have ≤1 connection - possible missing edges. (Counts symbols only; 384 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **75 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ProyectoApiController`, `Proyecto`, `EstudianteController.php`, `Illuminate\View\View`, `ProyectoApiController.php`, `IntegracionApiController.php`, `ProyectoController.php`, `Illuminate\Http\RedirectResponse`, `Illuminate\Http\Request`, `UpdateProfileRequest`, `PlantillaController.php`, `UsuarioController.php`, `Memoria`, `api.php`, `EstadisticasController.php`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `Illuminate\Http\JsonResponse`, `IntegracionApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `web.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Support\Facades\DB`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Support\Facades\DB`, `IntegracionApiController.php`, `ProyectoController.php`, `Illuminate\Database\Eloquent\Model`, `ProyectoSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Support\Facades\DB`, `CertificacionApiController.php`, `Illuminate\Http\JsonResponse`, `IntegracionApiController.php`, `ProyectoApiController.php`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Should `Controller` be split into smaller, more focused modules?**
  _Cohesion score 0.13071895424836602 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `IntegracionApiController.php`, `UserFactory.php`, `Illuminate\Database\Seeder`, `ProyectoSeeder.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `UsuarioController.php`, `Illuminate\Http\JsonResponse`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `bootstrap/app.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14705882352941177 - nodes in this community are weakly interconnected._