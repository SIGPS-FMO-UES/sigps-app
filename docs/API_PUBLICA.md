# Documentación de Endpoints Públicos — API SIGPS (UPS — SYS-01)

Esta documentación técnica está destinada a los equipos de desarrollo del **Portal de Estudiantes (SYS-03)**, **Sistema de Coordinadores y Tutores (SYS-02)** y sistemas satélites de la Facultad Multidisciplinaria Oriental de la Universidad de El Salvador (FMO-UES).

---

## 1. Información General y Convenciones

- **Prefijo base**: `/api/v1`
- **Formato de intercambio**: `application/json`
- **Encabezados recomendados**:
  ```http
  Accept: application/json
  Content-Type: application/json
  ```
- **Autenticación**: Los endpoints descritos a continuación son **públicos** y no requieren cabecera `Authorization: Bearer <token>`.
- **Estructura estándar de respuestas**:
  - Para listados/recursos: `{ "data": [ ... ] }` (o paginación con `links` y `meta`).
  - Para objetos puntuales: `{ "status": "success", "data": { ... } }`
  - Para errores: `{ "status": "error", "message": "Descripción del error." }`

---

## 2. Catálogo Académico: Carreras y Departamentos

Permite consultar dinámicamente la oferta académica de la facultad, los departamentos a los que pertenece cada carrera y las **horas reglamentarias de servicio social** para validar o mostrar en el portal de estudiantes.

### 2.1 Listado de Carreras

- **Método**: `GET`
- **URI**: `/api/v1/carreras`
- **Nombre de ruta**: `api.v1.carreras.index`
- **Parámetros de consulta (Query Params)**:
  - `departamento_id` *(opcional, int)*: Filtra carreras que pertenezcan al ID del departamento especificado.
  - `codigo` *(opcional, string)*: Filtra por código institucional de carrera (ej. `I10515`).
  - `search` *(opcional, string)*: Búsqueda por coincidencia en el código, nombre de la carrera o departamento.
  - `all` *(opcional, boolean)*: `true` o `1` para retornar todas las carreras sin paginación (por defecto retorna todas si no se especifica `page`).
  - `per_page` *(opcional, int)*: Cantidad de resultados por página al paginar (ej. `25`).
  - `page` *(opcional, int)*: Número de página solicitada.

#### Ejemplo de Petición:
```bash
curl -X GET "http://localhost:8000/api/v1/carreras" \
  -H "Accept: application/json"
```

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "data": [
    {
      "id_carrera": 1,
      "codigo": "I10515",
      "nombre": "Ingeniería de Sistemas Informáticos",
      "horas_servicio_social": 500,
      "horas_requeridas": 500,
      "departamento_id": 1,
      "departamento": {
        "id_departamento": 1,
        "nombre": "Departamento de Ingeniería y Arquitectura"
      },
      "created_at": "2026-10-14T08:30:00Z",
      "updated_at": "2026-10-22T11:00:00Z"
    },
    {
      "id_carrera": 2,
      "codigo": "L10501",
      "nombre": "Licenciatura en Administración de Empresas",
      "horas_servicio_social": 500,
      "horas_requeridas": 500,
      "departamento_id": 2,
      "departamento": {
        "id_departamento": 2,
        "nombre": "Departamento de Ciencias Económicas"
      },
      "created_at": "2026-10-14T08:30:00Z",
      "updated_at": "2026-10-22T11:00:00Z"
    }
  ]
}
```

---

### 2.2 Detalle de una Carrera

- **Método**: `GET`
- **URI**: `/api/v1/carreras/{id}` *(donde `{id}` puede ser el identificador numérico o el código alfanumérico institucional, ej. `/api/v1/carreras/I10515`)*
- **Nombre de ruta**: `api.v1.carreras.show`

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "status": "success",
  "data": {
    "id_carrera": 1,
    "codigo": "I10515",
    "nombre": "Ingeniería de Sistemas Informáticos",
    "horas_servicio_social": 500,
    "horas_requeridas": 500,
    "departamento_id": 1,
    "departamento": {
      "id_departamento": 1,
      "nombre": "Departamento de Ingeniería y Arquitectura"
    },
    "created_at": "2026-10-14T08:30:00Z",
    "updated_at": "2026-10-22T11:00:00Z"
  }
}
```

---

### 2.3 Listado de Departamentos (con Carreras Anidadas)

- **Método**: `GET`
- **URI**: `/api/v1/departamentos`
- **Nombre de ruta**: `api.v1.departamentos.index`
- **Parámetros de consulta (Query Params)**:
  - `search` *(opcional, string)*: Búsqueda por nombre de departamento.
  - `all` *(opcional, boolean)*: `true` o `1` para retornar todos los departamentos.
  - `per_page` *(opcional, int)*: Resultados por página al paginar.
  - `page` *(opcional, int)*: Número de página.

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "data": [
    {
      "id_departamento": 1,
      "nombre": "Departamento de Ingeniería y Arquitectura",
      "carreras_count": 2,
      "carreras": [
        {
          "id_carrera": 1,
          "codigo": "I10515",
          "nombre": "Ingeniería de Sistemas Informáticos",
          "horas_servicio_social": 500,
          "horas_requeridas": 500,
          "departamento_id": 1
        },
        {
          "id_carrera": 3,
          "codigo": "I10502",
          "nombre": "Ingeniería Civil",
          "horas_servicio_social": 500,
          "horas_requeridas": 500,
          "departamento_id": 1
        }
      ],
      "created_at": "2026-10-14T08:00:00Z",
      "updated_at": "2026-10-14T08:00:00Z"
    }
  ]
}
```

---

### 2.4 Detalle de un Departamento

- **Método**: `GET`
- **URI**: `/api/v1/departamentos/{id}`
- **Nombre de ruta**: `api.v1.departamentos.show`

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "status": "success",
  "data": {
    "id_departamento": 1,
    "nombre": "Departamento de Ingeniería y Arquitectura",
    "carreras_count": 2,
    "carreras": [
      {
        "id_carrera": 1,
        "nombre": "Ingeniería de Sistemas Informáticos",
        "horas_servicio_social": 500,
        "horas_requeridas": 500,
        "departamento_id": 1
      }
    ],
    "created_at": "2026-10-14T08:00:00Z",
    "updated_at": "2026-10-14T08:00:00Z"
  }
}
```

---

### 2.5 Carreras de un Departamento Específico

- **Método**: `GET`
- **URI**: `/api/v1/departamentos/{id}/carreras`
- **Nombre de ruta**: `api.v1.departamentos.carreras`

Devuelve exclusivamente la lista plana de carreras que pertenezcan al departamento especificado en la ruta `{id}`.

---

### 2.6 Catálogo Académico Unificado (Carga Todo en 1 Petición)

Ideal para la inicialización rápida de vistas, modales y selects dinámicos ("Seleccione Departamento" -> "Seleccione Carrera") en el sistema de estudiantes con una única llamada de red.

- **Método**: `GET`
- **URI**: `/api/v1/catalogo-academico`
- **Nombre de ruta**: `api.v1.catalogo.academico`

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "status": "success",
  "data": {
    "departamentos": [
      {
        "id_departamento": 1,
        "nombre": "Departamento de Ingeniería y Arquitectura",
        "carreras": [
          {
            "id_carrera": 1,
            "nombre": "Ingeniería de Sistemas Informáticos",
            "horas_servicio_social": 500,
            "horas_requeridas": 500,
            "departamento_id": 1
          }
        ]
      }
    ],
    "carreras": [
      {
        "id_carrera": 1,
        "nombre": "Ingeniería de Sistemas Informáticos",
        "horas_servicio_social": 500,
        "horas_requeridas": 500,
        "departamento_id": 1,
        "departamento": {
          "id_departamento": 1,
          "nombre": "Departamento de Ingeniería y Arquitectura"
        }
      }
    ]
  }
}
```

---

## 3. Catálogo de Formatos y Plantillas Oficiales (F1 a F8)

Permite consultar los formatos y documentos oficiales vigentes de la Proyección Social (F1, F2, F3, F7, F8, etc.) y descargar el archivo binario (Word/PDF) oficial para que el estudiante o tutor trabaje sobre él.

### 3.1 Listado de Formatos Oficiales

- **Método**: `GET`
- **URI**: `/api/v1/formatos`
- **Nombre de ruta**: `api.v1.formatos.index`
- **Parámetros de consulta (Query Params)**:
  - `search` *(opcional, string)*: Búsqueda por nombre o descripción del formato.
  - `tipo` *(opcional, string)*: Filtra por tipo de documento (`word`, `pdf`, `excel`, etc.).
  - `per_page` *(opcional, int)*: Resultados por página (por defecto `25`).
  - `page` *(opcional, int)*: Número de página.

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "data": [
    {
      "id_plantilla": 1,
      "nombre": "Formato F1 - Perfil de Proyecto",
      "descripcion": "Plantilla oficial para la formulación inicial de proyectos de horas sociales.",
      "activa": true,
      "version_publicada": {
        "id_version_plantilla": 1,
        "numero_version": "1.0",
        "tipo": "word",
        "tipo_label": "Microsoft Word",
        "nombre_archivo": "F1_Perfil_Proyecto_2026.docx",
        "fecha_publicacion": "2026-10-15T09:00:00Z",
        "url_descarga": "http://localhost:8000/api/v1/formatos/1/descargar"
      }
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/v1/formatos?page=1",
    "last": "http://localhost:8000/api/v1/formatos?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 25,
    "total": 1
  }
}
```

---

### 3.2 Detalle de Formato Oficial

- **Método**: `GET`
- **URI**: `/api/v1/formatos/{id}`
- **Nombre de ruta**: `api.v1.formatos.show`

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "status": "success",
  "data": {
    "id_plantilla": 1,
    "nombre": "Formato F1 - Perfil de Proyecto",
    "descripcion": "Plantilla oficial para la formulación inicial de proyectos de horas sociales.",
    "activa": true,
    "version_publicada": {
      "id_version_plantilla": 1,
      "numero_version": "1.0",
      "tipo": "word",
      "tipo_label": "Microsoft Word",
      "nombre_archivo": "F1_Perfil_Proyecto_2026.docx",
      "url_descarga": "http://localhost:8000/api/v1/formatos/1/descargar"
    }
  }
}
```

---

### 3.3 Descarga Directa del Archivo Oficial

- **Método**: `GET`
- **URI**: `/api/v1/formatos/{id}/descargar`
- **Nombre de ruta**: `api.v1.formatos.descargar`
- **Tipo de Respuesta**: Descarga de archivo binario (`StreamedResponse`) con encabezados `Content-Disposition: attachment; filename="..."` y el `Content-Type` correspondiente (ej. `application/vnd.openxmlformats-officedocument.wordprocessingml.document` o `application/pdf`).

---

## 4. Estado y Disponibilidad del Servicio

### 4.1 Ping de Disponibilidad

- **Método**: `GET`
- **URI**: `/api/v1/ping`

#### Ejemplo de Respuesta (`200 OK`):
```json
{
  "status": "ok",
  "sistema": "SIGPS Backoffice UPS (SYS-01)",
  "timestamp": "2026-10-22T23:15:00+00:00"
}
```

---

## 5. Ejemplos de Integración Frontend (JavaScript / Fetch)

### Ejemplo A: Cargar Departamentos y Carreras Dependientes
```javascript
// Obtener todos los departamentos con sus carreras
async function cargarCatalogos() {
  try {
    const response = await fetch('http://localhost:8000/api/v1/departamentos', {
      headers: { 'Accept': 'application/json' }
    });
    const { data: departamentos } = await response.json();

    const deptoSelect = document.getElementById('departamento_select');
    departamentos.forEach(depto => {
      const option = document.createElement('option');
      option.value = depto.id_departamento;
      option.textContent = depto.nombre;
      deptoSelect.appendChild(option);
    });

    // Guardar para actualizar dinámicamente las carreras según el departamento seleccionado
    deptoSelect.addEventListener('change', (e) => {
      const deptoSeleccionado = departamentos.find(d => d.id_departamento == e.target.value);
      const carreraSelect = document.getElementById('carrera_select');
      carreraSelect.innerHTML = '<option value="">Seleccione carrera</option>';

      if (deptoSeleccionado) {
        deptoSeleccionado.carreras.forEach(carrera => {
          const opt = document.createElement('option');
          opt.value = carrera.id_carrera;
          opt.textContent = `${carrera.nombre} (${carrera.horas_servicio_social} hrs)`;
          carreraSelect.appendChild(opt);
        });
      }
    });
  } catch (error) {
    console.error('Error al consultar departamentos y carreras:', error);
  }
}
```

### Ejemplo B: Descarga de Formato Oficial F1
```javascript
// Descarga directa en el navegador
function descargarFormato(idPlantilla) {
  window.open(`http://localhost:8000/api/v1/formatos/${idPlantilla}/descargar`, '_blank');
}
```
