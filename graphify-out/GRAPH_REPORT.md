# Graph Report - sigps-app  (2026-10-24)

## Corpus Check
- 148 files · ~52,586 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 857 nodes · 1568 edges · 103 communities (31 shown, 72 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f08b4df4`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- Illuminate\Http\JsonResponse
- EstudianteController.php
- Illuminate\Database\Schema\Blueprint
- package.json
- Memoria
- Illuminate\View\View
- Controller
- AppServiceProvider
- User
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- Illuminate\Support\Facades\Schema
- Proyecto
- ProyectoApiController.php
- 2026_10_14_000003_create_estudiantes_tables.php
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Migrations\Migration
- 2026_10_14_000008_create_plantillas_tables.php
- Proyecto.php
- bootstrap/app.php
- ProyectoController.php
- Illuminate\Http\Request
- Illuminate\Http\Request
- Carrera
- 2026_10_14_000005_create_proyectos_tables.php
- DocumentoProyectoController.php
- UpdateProfileRequest
- PlantillaController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- catalogos/index.blade.php
- UsuarioController.php
- AuditoriaApiController.php
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
- GestionProrrogasController.php
- MemoriaController.php
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
2. `Proyecto` - 46 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Estudiante` - 16 edges
7. `ProyectoController` - 15 edges
8. `Carrera` - 14 edges
9. `Certificacion` - 14 edges
10. `Memoria` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Ups/ControlPlazosController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Ups/EstadisticasController.php → app/Models/Certificacion.php

## Import Cycles
- None detected.

## Communities (103 total, 72 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 3 - "EstudianteController.php"
Cohesion: 0.13
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 7 - "Illuminate\View\View"
Cohesion: 0.17
Nodes (4): {closure#1}(), ControlPlazosController, {closure#11}(), EstudianteController

### Community 8 - "Controller"
Cohesion: 0.13
Nodes (4): BitacoraAuditoriaController, LoginController, Controller, NotificacionController

### Community 10 - "User"
Cohesion: 0.07
Nodes (10): {closure#2}(), User, ExpedienteRecibidoNotification, AdminSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder, {closure#3}() (+2 more)

### Community 28 - "Proyecto"
Cohesion: 0.07
Nodes (5): {closure#1}(), {closure#1}(), {closure#9}(), ProcesoExtraordinario, Proyecto

### Community 29 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 32 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.26
Nodes (3): AreaConocimiento, Departamento, VersionPlantilla

### Community 35 - "Proyecto.php"
Cohesion: 0.22
Nodes (3): Plantilla, {closure#1}(), {closure#2}()

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "ProyectoController.php"
Cohesion: 0.05
Nodes (3): {closure#28}(), {closure#33}(), ProyectoController

### Community 38 - "Illuminate\Http\Request"
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

### Community 51 - "EstadisticasController.php"
Cohesion: 0.09
Nodes (5): FormatoApiController, CatalogoFormatosController, {closure#1}(), {closure#2}(), EstadisticasController

### Community 53 - "EstadoMemoria"
Cohesion: 0.07
Nodes (10): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+2 more)

### Community 54 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 87 - "Illuminate\Http\JsonResponse"
Cohesion: 0.14
Nodes (4): AuditoriaApiController, IntegracionApiController, PlazoApiController, ProrrogaApiController

### Community 89 - "MemoriaController.php"
Cohesion: 0.07
Nodes (6): {closure#7}(), MemoriaController, RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (6): {closure#1}(), {closure#9}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

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
  These have ≤1 connection - possible missing edges. (Counts symbols only; 386 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **72 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `Illuminate\Http\JsonResponse`, `EstudianteController.php`, `Illuminate\View\View`, `Proyecto`, `ProyectoApiController.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `ProyectoController.php`, `Illuminate\Http\Request`, `Illuminate\Http\Request`, `DocumentoProyectoController.php`, `UpdateProfileRequest`, `PlantillaController.php`, `UsuarioController.php`, `AuditoriaApiController.php`, `EstadisticasController.php`, `EstadoMemoria`, `CertificacionApiController.php`, `CarreraApiController.php`, `App\Http\Controllers\Api\V1\EstudianteApiController`, `Illuminate\Http\JsonResponse`, `GestionProrrogasController.php`, `MemoriaController.php`, `web.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`?**
  _High betweenness centrality (0.088) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `EstudianteController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.13450292397660818 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `Illuminate\Database\Eloquent\Model`, `Illuminate\Http\JsonResponse`, `Proyecto.php`, `ProyectoController.php`, `Memoria`, `Illuminate\View\View`, `DocumentoProyectoController.php`, `User`, `Illuminate\Database\Eloquent\Relations\HasMany`, `EstadoMemoria`, `CertificacionApiController.php`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoApiController.php`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `Illuminate\Http\JsonResponse`, `UserFactory.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `UsuarioController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Should `Controller` be split into smaller, more focused modules?**
  _Cohesion score 0.13071895424836602 - nodes in this community are weakly interconnected._