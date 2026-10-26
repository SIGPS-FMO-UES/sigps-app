# Graph Report - sigps-app  (2026-10-25)

## Corpus Check
- 149 files · ~61,846 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 19, .conf 4, .example 2)

## Summary
- 932 nodes · 1659 edges · 110 communities (29 shown, 81 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c64154b1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- scripts
- ProyectoApiController.php
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Schema\Blueprint
- package.json
- Memoria
- Controller
- AppServiceProvider
- EstudianteController.php
- 0001_01_01_000001_create_cache_table.php
- ExampleTest
- dev/entrypoint.sh
- docker/entrypoint.sh
- 2026_10_14_000002_create_departamentos_and_carreras_tables.php
- Proyecto
- 2026_10_14_000003_create_estudiantes_tables.php
- Illuminate\View\View
- EstudianteController.php
- Illuminate\Support\Facades\Schema
- 2026_10_14_000008_create_plantillas_tables.php
- PlantillaController.php
- bootstrap/app.php
- Illuminate\Http\JsonResponse
- AreaConocimientoController.php
- UsuarioController.php
- CertificacionApiController.php
- 2026_10_14_000005_create_proyectos_tables.php
- web.php
- CarreraApiController.php
- Illuminate\Database\Eloquent\Relations\HasMany
- api.php
- catalogos/index.blade.php
- Illuminate\Database\Eloquent\Model
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Http\Resources\Json\AnonymousResourceCollection
- FormatoApiController.php
- BitacoraAuditoriaController.php
- EstadoMemoria
- Illuminate\Http\Request
- EstadisticasController
- 2026_10_21_224046_create_certificaciones_table.php
- 2026_10_14_000006_create_memorias_table.php
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
- MemoriaController.php
- EstadisticasExportService
- MemoriaController.php
- UpdateProfileRequest
- AuditoriaApiController.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- require-dev
- UserFactory.php
- ProyectoController
- GestionProrrogasController.php
- .guardarDocumento
- App\Models\Proyecto
- TestCase
- config
- require
- 0001_01_01_000002_create_jobs_table.php
- psr-4
- 2026_10_21_000005_create_project_documents_and_extraordinary_processes.php
- autoload-dev
- extra

## God Nodes (most connected - your core abstractions)
1. `Controller` - 65 edges
2. `Proyecto` - 47 edges
3. `LogAccion` - 21 edges
4. `EstadoMemoria` - 20 edges
5. `ProcesoExtraordinario` - 20 edges
6. `Estudiante` - 16 edges
7. `ProyectoController` - 15 edges
8. `Carrera` - 15 edges
9. `Certificacion` - 14 edges
10. `Memoria` - 14 edges

## Surprising Connections (you probably didn't know these)
- `{closure#3}()` --references--> `Estudiante`  [EXTRACTED]
  database/seeders/ProyectoSeeder.php → app/Models/Estudiante.php
- `{closure#5}()` --references--> `Certificacion`  [EXTRACTED]
  app/Http/Controllers/Api/V1/CertificacionApiController.php → app/Models/Certificacion.php
- `{closure#1}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/PlazoApiController.php → app/Models/Proyecto.php
- `{closure#1}()` --references--> `ProcesoExtraordinario`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProrrogaApiController.php → app/Models/ProcesoExtraordinario.php
- `{closure#8}()` --references--> `Proyecto`  [EXTRACTED]
  app/Http/Controllers/Api/V1/ProyectoApiController.php → app/Models/Proyecto.php

## Import Cycles
- None detected.

## Communities (110 total, 81 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 2 - "ProyectoApiController.php"
Cohesion: 0.15
Nodes (3): {closure#8}(), ProyectoApiController, ProyectoResource

### Community 4 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.21
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 5 - "package.json"
Cohesion: 0.10
Nodes (20): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+12 more)

### Community 8 - "Controller"
Cohesion: 0.14
Nodes (4): Controller, CatalogoFormatosController, DocumentoProyectoController, NotificacionController

### Community 10 - "EstudianteController.php"
Cohesion: 0.07
Nodes (10): {closure#2}(), User, ExpedienteRecibidoNotification, AdminSeeder, DatabaseSeeder, EstudianteSeeder, JefaturaSeeder, {closure#3}() (+2 more)

### Community 28 - "Proyecto"
Cohesion: 0.13
Nodes (3): {closure#9}(), ProcesoExtraordinario, Proyecto

### Community 31 - "Illuminate\View\View"
Cohesion: 0.20
Nodes (3): {closure#1}(), ControlPlazosController, EstudianteController

### Community 32 - "EstudianteController.php"
Cohesion: 0.14
Nodes (4): {closure#10}(), {closure#7}(), {closure#9}(), Estudiante

### Community 35 - "PlantillaController.php"
Cohesion: 0.19
Nodes (6): TipoPlantilla, ArchivoEjemplo, Autorellenable, {closure#2}(), {closure#3}(), PlantillaController

### Community 36 - "bootstrap/app.php"
Cohesion: 0.15
Nodes (4): EnsureCarreraServiceToken, {closure#1}(), {closure#3}(), {closure#4}()

### Community 37 - "Illuminate\Http\JsonResponse"
Cohesion: 0.17
Nodes (3): AuthApiController, {closure#1}(), PlazoApiController

### Community 40 - "CertificacionApiController.php"
Cohesion: 0.14
Nodes (3): CertificacionApiController, {closure#5}(), CertificacionResource

### Community 41 - "2026_10_14_000005_create_proyectos_tables.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 46 - "api.php"
Cohesion: 0.14
Nodes (4): AuditoriaApiController, EstadisticaApiController, IntegracionApiController, ProrrogaApiController

### Community 48 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.16
Nodes (4): AreaConocimiento, Carrera, Departamento, VersionPlantilla

### Community 53 - "EstadoMemoria"
Cohesion: 0.05
Nodes (11): EstadoMemoria, Aprobada, Aprovada, EnRevision, Entregada, EsperandoFirmas, Lista, Observada (+3 more)

### Community 54 - "Illuminate\Http\Request"
Cohesion: 0.14
Nodes (5): CarreraDepartamentoController, BandejaExpedientesController, CertificacionController, {closure#11}(), ProcesosExtraordinariosController

### Community 89 - "MemoriaController.php"
Cohesion: 0.11
Nodes (4): RecepcionExpedienteAvaladoRequest, LoginRequest, {closure#1}(), DictamenMemoriaRequest

### Community 92 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.14
Nodes (6): {closure#1}(), {closure#9}(), Certificacion, DocumentoProyecto, HistorialFaseProyecto, LogAccion

### Community 93 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+1 more)

### Community 98 - "App\Models\Proyecto"
Cohesion: 0.22
Nodes (3): Plantilla, {closure#1}(), {closure#2}()

### Community 102 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 105 - "require"
Cohesion: 0.29
Nodes (7): require, laravel/framework, laravel/sanctum, laravel/tinker, php, phpoffice/phpspreadsheet, spatie/laravel-permission

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
  These have ≤1 connection - possible missing edges. (Counts symbols only; 453 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **81 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `ProyectoApiController.php`, `ProyectoController.php`, `Proyecto`, `EstadisticasController.php`, `Illuminate\View\View`, `EstudianteController.php`, `PlantillaController.php`, `Illuminate\Http\JsonResponse`, `AreaConocimientoController.php`, `UsuarioController.php`, `CertificacionApiController.php`, `web.php`, `CarreraApiController.php`, `api.php`, `Illuminate\Http\Resources\Json\AnonymousResourceCollection`, `FormatoApiController.php`, `BitacoraAuditoriaController.php`, `EstadoMemoria`, `Illuminate\Http\Request`, `EstadisticasController`, `EstudianteApiController.php`, `MemoriaController.php`, `UpdateProfileRequest`, `AuditoriaApiController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProyectoController`, `GestionProrrogasController.php`, `ProcesosExtraordinariosController.php`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `Recibido`, `EnRevision`, `Observada` to the rest of the system?**
  _39 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ProyectoApiController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14705882352941177 - nodes in this community are weakly interconnected._
- **Why does `Proyecto` connect `Proyecto` to `.guardarDocumento`, `ProyectoApiController.php`, `App\Models\Proyecto`, `Illuminate\Http\JsonResponse`, `ProcesosExtraordinariosController.php`, `ProyectoController.php`, `CertificacionApiController.php`, `Controller`, `Memoria`, `EstudianteController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Model`, `EstadoMemoria`, `MemoriaController.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `EstadisticasController.php`, `ProyectoController`, `Illuminate\View\View`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09956709956709957 - nodes in this community are weakly interconnected._
- **Why does `User` connect `EstudianteController.php` to `Illuminate\Http\JsonResponse`, `UsuarioController.php`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `UserFactory.php`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Should `ProyectoController.php` be split into smaller, more focused modules?**
  _Cohesion score 0.0625 - nodes in this community are weakly interconnected._