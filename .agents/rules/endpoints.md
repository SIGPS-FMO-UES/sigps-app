---
trigger: always_on
description: Cumplimiento estricto de la especificación técnica de endpoints y rutas MVC en Laravel para el Sistema de Proyección Social (UPS — SYS-01).
---

## SIGPS Endpoints & Routing Architecture Rule (UPS — SYS-01)

Whenever constructing, modifying, refactoring, or generating any feature, route, controller, form request, policy, view, or API endpoint for the SIGPS application:

1. **Scope & System Boundary ([ADR-06])**:
   This repository corresponds **exclusively to the Backoffice of the Unidad de Proyección Social (UPS — SYS-01)**. It does NOT contain controllers or endpoints for academic tutors or coordinators (SYS-02). The sole point of contact with SYS-02 is the integration endpoint: `POST /api/v1/integracion/expedientes-avalados` ([HU-72]).

2. **Mandatory Specification & Workflow Compliance**:
   You MUST always consult and adhere strictly to the consolidated technical specification and workflow in:
   [.agents/workflows/endpoints.md](../workflows/endpoints.md) (accessible via `/endpoints`).

3. **Core Standards to Enforce**:
   - **Routes Web (`routes/web.php`)**: Sesión de estado, protección CSRF (`@csrf`), middleware `web`, nombres canónicos (`ups.*`, `admin.*`), vistas Blade o redirecciones con mensajes flash (`with('success', ...)`).
   - **Routes API (`routes/api.php`)**: Rutas RESTful bajo prefijo `/api/v1/ups/` con `auth:sanctum`, y endpoint de integración `/api/v1/integracion/expedientes-avalados` con token de servicio de carrera (`carrera-service-token`).
   - **Namespaces de Controladores**: Respetar estrictamente los namespaces definidos en el documento (`App\Http\Controllers\Auth`, `Publico`, `Ups`, `Admin`, `Api\V1`).
   - **RBAC con Spatie (`spatie/laravel-permission`)**: Aplicar exclusivamente los roles de la UPS (`role:jefe_ups`, `role:auxiliar_ups`) y permisos atómicos correspondientes (`prorrogas.resolver`, `certificaciones.emitir`, etc.). La administración técnica también pertenece a `jefe_ups`.
   - **Validación**: Utilizar Form Requests dedicados (`app/Http/Requests/...`) para validación y autorización previa.
