# TodoCamisetas API

API REST desarrollada con Laravel para la gestión de ventas de camisetas deportivas.

La aplicación permite administrar clientes, camisetas, tallas y ventas, incorporando control de stock, descuentos personalizados según cliente y documentación interactiva mediante OpenAPI/Swagger.

El sistema implementa una arquitectura basada en recursos REST, validación de datos en servidor, relaciones entre entidades y respuestas estandarizadas en formato JSON, facilitando su integración con aplicaciones web, móviles o sistemas de gestión externos.


## Estructura de Archivos

![Estructura de Archivos 1](images/estructura-archivos-1.png)
![Estructura de Archivos 2](images/estructura-archivos-2.png)
![Estructura de Archivos 3](images/estructura-archivos-3.png)

## Modelo de Datos

```mermaid
erDiagram

    CLIENTES ||--o{ VENTAS : realiza
    VENTAS ||--o{ DETALLE_VENTAS : contiene
    CAMISETAS ||--o{ DETALLE_VENTAS : vendida_en

    CAMISETAS ||--o{ CAMISETA_TALLA : posee
    TALLAS ||--o{ CAMISETA_TALLA : disponible_en

    CLIENTES {
        int id
        string nombre_comercial
        string rut_comercial
        string categoria
        float porcentaje_oferta
    }

    CAMISETAS {
        int id
        string titulo
        string club
        string pais
        string tipo
        string color
        int precio
        int stock
        string codigo_producto
    }

    TALLAS {
        int id
        string nombre
    }

    CAMISETA_TALLA {
        int camiseta_id FK
        int talla_id FK
    }

    VENTAS {
        int id
        int cliente_id FK
        date fecha_venta
    }

    DETALLE_VENTAS {
        int id
        int venta_id FK
        int camiseta_id FK
        int cantidad
        int precio_unitario
        int subtotal
    }
```
