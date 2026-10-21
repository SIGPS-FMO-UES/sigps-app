---
trigger: always_on
description: Cumplimiento estricto de la especificación técnica de endpoints y rutas MVC en Laravel para el Sistema de Proyección Social (SIGPS).
---

## SIGPS Endpoints & Routing Architecture Rule

Whenever constructing, modifying, refactoring, or generating any feature, route, controller, form request, policy, view, or API endpoint for the SIGPS application:

1. **Mandatory Specification & Workflow Compliance**:
   You MUST always consult and adhere strictly to the consolidated technical specification and workflow in:
   [.agents/workflows/endpoints.md](file:///home/mixgyt/Development/PHP/ProyeccionSocial/sigps-app/.agents/workflows/endpoints.md) (accessible via `/endpoints`).

3. **Core Standards to Enforce**:
   - **Routes Web (`routes/web.php`)**: Rutas con estado de sesión, CSRF, middleware `web`, nombres canónicos (`name(...)`), vistas Blade o redirecciones con mensajes flash (`with('success', ...)`).
   - **Routes API (`routes/api.php`)**: Rutas RESTful sin estado bajo prefijo `/api/v1/`, autenticación con `auth:sanctum`, retornando `JsonResource` o `JsonResponse` con códigos HTTP estándar.
   - **Namespaces de Controladores**: Respetar estrictamente los namespaces definidos en el documento (`App\Http\Controllers\Auth`, `Publico`, `Estudiante`, `Academico\Tutor`, `Academico\Coordinador`, `Ups`, `Admin`, `Api\V1`).
   - **RBAC con Spatie (`spatie/laravel-permission`)**: Aplicar los roles (`role:admin`, `role:jefe_ups`, `role:auxiliar_ups`, `role:coordinador`, `role:tutor`, `role:estudiante`) y permisos atómicos exactamente como están definidos en la matriz de la especificación.
   - **Validación**: Utilizar Form Requests dedicados (`app/Http/Requests/...`) para validación y autorización previa.
