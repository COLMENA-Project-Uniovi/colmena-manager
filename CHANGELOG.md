# Neo CMS Release Notes

En este documento se encontrará toda la documentación relacionada con las nuevas versiones del CMS de Neozink, para que seamos conscientes de todas aquellas cosas nuevas que van saliendo y cuáles de ellas necesitan de una revisión profunda cuando se actualiza algún CMS desde una versión antigua.

El formato de este documento está basado en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), y el proyecto sigue el convenio de versiones de [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.27](https://gitlab.com/neozink/neo-admin-3x/tags/v0.26) - 10/01/2021


### Added
* Refactorizazión de exportador de resultados
* Añadido font Awesome al core

### Added on database

### Changed

### Pending

* Adecuar la funcion de formatSeo a la nueva estructura del templateManager (Ojo, secciones con hijas)
* Hipervinculo en el listado de secciones
* Comprobaciones del ecommerceManager
* Añadir acciones a las notificaciones

## [0.26](https://gitlab.com/neozink/neo-admin-3x/tags/v0.26) - 24/12/2020


### Added
* Optimización del listado de los productos del ecommerce
* Posibilidad de exportar productos a archivos .csv o .xlxs con los formatos para Google Shopping y Facebook Ads
* Configuración Avanzada: Página de configuración de notificaciones
* Core: revisión de la caché
* Secciones: parámetros configurables de forma visual" and "Core: Asistente de configuración visual de la sección
* Auxiliar: Revisar selector de colores del CMS
* Core: meter integración de reducción automática de imágenes
* Core: Corrección de SEO en AppSearchableTable y entidades relacionadas
* Updated External Api Manager
* Product reviews
* Core: revisar la administración del sitio
* Template Manager
* Menú Manager
* Auxiliar: hacer versión moviles de la imagen principal
* Secciones: Ordenar elementos dentro de partes múltiples
* Seguridad: Cambiar restricciones alta de usuario
* Auxiliar: funcionalidad de duplicar botones

### Added on database
 * Los campos con tinyint se han renombrado a is_*
 * Creación tabla de "notifications"
 * Creación tabla "parameters"
 * Alter tabla "ecm_products" añadida columna "brand"
 * Se han añadido relaciones a todas las tablas de bbdd.

### Changed
 * Correccion del appSearcheableTable 
 * Agregada funcion validateField en appTable

### Pending

* Adecuar la funcion de formatSeo a la nueva estructura del templateManager (Ojo, secciones con hijas)
* Hipervinculo en el listado de secciones
* Comprobaciones del ecommerceManager
* Añadir acciones a las notificaciones

## [0.25](https://gitlab.com/neozink/neo-admin-3x/tags/v0.25) - 06/10/2020


### Added
* Añadido dentro de Ecommerce productos con opciones y Stocks.
* Añadido dentro de Ecommerce los impuestos y las Zonas.
* Añadido dentro de Ecommerce los pedidos.
* Añadido un controlador para los gastos de envío según los pesos de las opciones del producto ShippingCostsController añadida la tabla ShippingCostsTable y añadida la entidad ShippingCosts.
* Añadidos el edit.ctp y el add.ctp de ShippingCostsController
* Modificados el edit.ctp y el add.ctp de AttributesController para añadir dos campos, un selector del tipo de gasto de envío y otro campo con el valor del gasto de envío en este caso el peso.
* Modificados el edit.ctp y el add.ctp de ZonesController para indicar si la zona corresponde a un gasto de envío peninsular o regional.
* Añadida funcionalidad para adjuntar facturas al ecommerce de manera manual.
* Añadidas templates de envío de correo para pedidos, facturas, y usuarios.

### Added on database
 * Tabla ecm_shipping-costs para el manejo de gastos de envío según peso.
 * Campo use_region_data en ecm_zones para identificar la zona como regional o como peninsular.
 * Campos shipping_costs_id en ecm_atributtes para identificar el tipo de gasto de envío y el campo value para asignar el valor en este caso del peso.
 * Campos remind_email, novat_price, final_price_no_shipping, hasv_vat, total_vat,
final_price_no_vat_no_discount, total_discount, billing_zip_code, shipping_comments, shpiing_responce para unificar precios en toda la web.

### Changed

* Implementación del back a través del neo-admin-3x
* Implementada la funcionalidad de opciones en productos del Ecommerce.
* Implementación de Zonas de Envío asociandoles unos precios de envío.
* Implementación de gastos de envío según los pesos de las opciones de producto.
* Arreglo en el controlador de atributos al borrar una opción asociada a un producto.
* Arreglada la asignación de Opciones sobre un producto.
* Arreglo en OrdersListener.php para la creación de un pedido.
* Cambio en el FormatSeo de los productos.

### Pending

* Modificar el getByUrl de todas las tablas para encapsular el 50-70% de la funcionalidad, ya que es común a todas las tablas
* Mover el header_actions, el table_buttons y el tab_actions desde los controladores a la tabla, y redefinir su estructura
* Revisar funciones genéricas de getHeaderActions y getTableButtons en AppTable y AppController
* CamelCase en variables y funciones
* Crear una clase intermedia AppSearchableTable que herede de AppTable y que sea la que implementen todas las tablas que queramos tengan los métodos getByUrl, formatSeo y getEntitySeo, y que así no se lo tenga que tener implementado en todas las tablas sin necesidad, forzando el implements de la interfaz cuando no se usa.
* Unificar el beforeMarshall para no repetir código en algo relacionado con el sort


## [0.24.9](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.9) - 25/08/2020

### Added

* Incluido plugin de preguntas, respuestas, temas y cuestionarios
* Clase AppModel para la gestión centralizada de operaciones de modelos
* Añadidos campos
* Añadido ImportableBehaviour con complejidad y posibilidad de importar elementos relacionados

### Changed

* Extends de casi todas las clases del modelo para que hereden de AppTable en lugar de Table
* Eliminados los métodos de validación si eran solo de IDs
* Orden de los includes
* EntitySeo, formatSeo genéricos
* Cambios en estilo genéricos
* Cambios en el AppController para que utilice los métodos getDisplayName con sus variantes de la tabla, y así eliminar $entity_name y $entity_name_plural de las tablas

* **Cambios en la base de datos**
    * Añadida tablas `qm_` relacionadas con `questions_manager`.

### Fixed

* Template 'autores' cambiada por 'authors' en `AuthorsTable`

### Pending

* Modificar el getByUrl de todas las tablas para encapsular el 50-70% de la funcionalidad, ya que es común a todas las tablas
* Mover el header_actions, el table_buttons y el tab_actions desde los controladores a la tabla, y redefinir su estructura
* Revisar funciones genéricas de getHeaderActions y getTableButtons en AppTable y AppController
* CamelCase en variables y funciones
* Crear una clase intermedia AppSearchableTable que herede de AppTable y que sea la que implementen todas las tablas que queramos tengan los métodos getByUrl, formatSeo y getEntitySeo, y que así no se lo tenga que tener implementado en todas las tablas sin necesidad, forzando el implements de la interfaz cuando no se usa.
* Unificar el beforeMarshall para no repetir código en algo relacionado con el sort


## [0.24.8](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.8) - 22/06/2020

### Added

* Email de recordatorio en carritos abandonados
* Añadidos códigos promocionales a usuarios concretos
* Redsys
* Añadido old_price en productos del `EcommerceManager`
* Añadido old_price en opciones de productos del `EcommerceManager`
* Añadido descripción en opciones de productos del `EcommerceManager`

### Changed

* Emails de ecommerce actualizados
* Facturación necesaria para un pedido
* Modificado campo de código postal
* **Cambios en la base de datos**
    * Añadido campo `username` de tipo **VARCHAR** a la tabla `ecm_promo_codes`.
    * Añadido campo `billing_needed` de tipo **TINYINT** a la tabla `ecm_orders`.
    * Añadido campo `billing` de tipo **TINYINT** a la tabla `ecm_zones`.
    * Añadido campo `billing_note` de tipo **TEXT** a la tabla `ecm_zones`.
    * Añadido campo `shipping_interval` de tipo **INT** a la tabla `ecm_zones`.
    * Añadido campo `old_price` de tipo **FLOAT** a la tabla `ecm_products`.
    * Modificado nombre del campo `zip_code` a `zipcode` en la tabla `addresses`.
    * Añadido campo `old_price` de tipo **FLOAT** a la tabla `ecm_stocks`.
    * Añadido campo `description` de tipo **TEXT** a la tabla `ecm_stocks`.
    * Añadido campo `invoice_name` de tipo **TEXT** a la tabla `ecm_stocks`.
    * Añadido campo `invoice_description` de tipo **TEXT** a la tabla `ecm_stocks`.
    * Añadido campo `promo_code` de tipo **VARCHATR** a la tabla `ecm_orders`.

### Fixed

* Hidden id en WebUsers
* Hidden id en Stocks
* Metodos API de `OrdersController` con una condición errónea



## [0.24.7](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.7) - 12/06/2020

### Added

* Reorganación de productos en categorías

### Changed

* Añadida referencia en búsqueda de productos
* Posibilidad de poner una categoría solamente como un listado de categorías sin productos
* **Cambios en la base de datos**
    * Añadido campo `sort` de tipo **INT** a la tabla `ecm_products_product_cats`.

### Fixed

* Permisos en respuestas de formularios
* Corregido home block de eproducts
* Correcciones en tabla de productos (campo sort ambiguo)
* Correcciones permisos en stocks
* Correcciones en productos (ordenamiento)
* Correcciones en plantilla de eproducts



## [0.24.6](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.6) - 08/06/2020

### Fixed

* Cambiado bootstrap para no repetir importaciones



## [0.24.5](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.5) - 01/06/2020

### Fixed

* Añadido entidad WebUser como Api entity (no permitía hacer login)



## [0.24.4](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.4) - 28/05/2020

### Added

* MailManager para enviar correos desde la api `/api/1.0/mails/sendMail.json`
* Privacidad en la partes
  * Solo público
  * Solo privado
  * Público y privado
* Funciones nuevas de tinymce y traducción español
* Envío de email al actualizar Stock a suscriptores y usuarios
* Envío de email de carrito abandonado

### Changed

* Relación N:N para productos y categorías del `EcommerceManager`
* Posibilidad de poner una categoría solamente como un listado de categorías sin productos
* **Cambios en la base de datos**
    * Añadido campo `privacy_level` de tipo **VARCHAR** a la tabla `pam_parts`.
    * Añadido campo `list_categories` de tipo **TINYINT** a la tabla `ecm_product_cats`.
    * Añadida tabla intermedia `ecm_products_product_cats` de productos con categorías de `EcommerceManager`.
    * Añadida tabla intermedia `ecm_stocks_sbm_subscribers` de notificación de stock para suscriptores de `EcommerceManager`.
    * Añadida tabla intermedia `ecm_stocks_wm_web_users` de notificación de stock para usuarios de `EcommerceManager`.
    * Cambiadas llaves foráneas de `ecm_related_products` en **CASCADE** en el borrado.

### Fixed

* Limipiados residuos
* Base64 para evitar XSS en métricas
* Borrado de cache
* Permisos en el plugin `EcommerceManager` corregidos
* Api entities en `EcommerceManager`
* Borrado en cascada de productos relacionados



## [0.24.3](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.3) - 02/05/2020

### Fixed

* Limipiados residuos



## [0.24.2](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.2) - 28/03/2020

### Added

* Parámetros a las partes
* Seo de categorías y parteables de categorías y productos del `EcommerceManager`

### Changed

* **Cambios en la base de datos**
    * Añadido campo `parameters` de tipo **TEXT** a la tabla `pam_parts`.

### Fixed

* Limipiados residuos



## [0.24.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24.1) - 31/12/2019

### Added

* Nuevo editor TinyMce Actualizado.

### Fixed

* Ya no intenta obtener los recursos cuando el directorio no existe en el `ResizeTrait`.
* Correcciones de partes de los artículos del `ArticlesTable`.


## [0.24](https://gitlab.com/neozink/neo-admin-3x/tags/v0.24) - 10/12/2019

### Added

* Desarrollo de utilidad de caché.
* Nuevos estilos de correos.
* Nueva utilidad para previsualización de estilos de emails.

### Changed

* Mejoras en el sistema de reservas.
    * Día máximo de reserva en los horarios.
    * Ordenación correcta de las reservas en los listados.
    * Correcciones en el `MediaManager`.
    * Correcciones en el estilo del paginador de las tablas.
* **Cambios en la base de datos**
    * Añadidos campos `billing_name`, `billing_id`, `observations` y `newsletter` a la tabla `wm_web_users`.
    * Añadido campo `name` a la tabla `addresses`.
    * Añadidas tablas relacionadas con el `Reservations Manager`: `rm_lounges`, `rm_reservations`, `rm_reservations_tables`, `rm_schedules`, `rm_status_types`, `rm_statuses` y `rm_tables`.
    * Eliminados campos `facebook`, `twitter`, `google`, `instagram`, `youtube` y `linkedin` y añadido campo `socials` a la tabla `sm_site_properties`.
    * Añadida tabla `configs` para guardar información de configuración sobre características del CMS.

### Fixed

* Correcciones de errores en el `MediaManager`.
* Correcciones de permisos en la edición de partes del `PartsManager`.

## [0.23.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.23.1) - 11/11/2019

### Fixed

* Corregido un error al obtener secciones del menú.

## [0.23](https://gitlab.com/neozink/neo-admin-3x/tags/v0.23) - 06/11/2019

### Added

* Integración kit FontawesomePro.
* Desarrollo de plugin para el sistema de reservas.
* Implementación de artículos relacionados.

### Changed

* Eliminada la clase `ckeditor` deprecada.
* Corregidos errores por columnas ambiguas.
* Cambios en la gestion de colores para que admita canal alpha.
* Añadido campo `model` en los `Behavior`.
* Corregido error de permisos de usuarios.
* Mejoras en plantillas de correos.
* Corrección de errores y mejoras en los plugins de botones y secciones.
* Cambiada funcionalidad del `AddressBehavior`.
* `ArticleTags` ahora es parteable y se puede usar en una parte de entitdades.
* Cambios en la gestión de ficheros de del `SiteProperties`.
* Corregidos errores en el Media Manager y en el `SiteProperties`.
* Solucionado error al obtener las secciones hijas en el `SiteProperties`.

## [0.22.6](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.6) - 15/07/2019

### Fixed

* Corregido un error con los ficheros `svg` en el `MediaManager` que hacía que no se mostrasen correctamente en la vista de mosaico.
* Añadido el formato `webp` al `MediaManager` para poder subir ficheros con ficho formato.
* Corregido un error en el que solo se podían elegir imágenes al utilizar el tipo de input único del `MediaManager`.
* Corregido un error en la vista del SEO del `SectionsManager'.
* Corregido un error que no permitía borrar botones del `ButtonsManager`.
* Corregido un error por el cuál no se cargaban correctamente los modos de edición de los editores de código.
* Corregido un error por el cuál no se guardaban los ficheros de configuración correctamente.

## [0.22.5](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.5) - 10/07/2019

### Changed

* Actualización a la versión `3.8.*` de CakePHP [+ info](https://book.cakephp.org/3.0/en/appendices/3-8-migration-guide.html).
* Correcciones de errores en las plantillas de los emails.

## [0.22.4](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.4) - 10/07/2019

### Changed

* Estandarización de API al hacer submits de formularios.

## [0.22.3](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.3) - 09/07/2019

### Added

* Configuración de rutas de la aplicación de manera automatizada.

### Changed

* Creación de clases Mailer para envío de correos encapsulado.
* Mejora de las plantillas de correos para compatibilidad con más gestores de correo.

## [0.22.2](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.2) - 20/05/2019

### Added

* Añadido campo `timer` para establecer el tiempo de cada slide.

### Changed

* **Cambios en la base de datos**
    * Añadido campo `timer` a la tabla `sm_slides`.

### Fixed

* Corregido un error por el cuál en las partes de formularios no aparecía correctamente seleccionado el formulario que se había establecido.
* Corregido error con la función deprecada `hiddenProperties` de varias entidades.

## [0.22.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22.1) - 16/05/2019

### Changed

* Mejorada la gestión de partes de vídeo con selectores que modifican la visualización de los elementos del formulario para guiar al usuario a rellenar los datos realmente necesarios.

## [0.22](https://gitlab.com/neozink/neo-admin-3x/tags/v0.22) - 16/05/2019

### Added

* Añadidas partes de modales.
* Añadidas partes de galerías de vídeos.

### Changed

* **Cambios en la base de datos**
    * Se ha añadido la tabla `pam_modal_parts`.
    * Se ha añadido la tabla `pam_video_gallery_parts`.

### Fixed

* Deshecha la corrección para obtener únicamente las partes visibles de una entidad ya que ahora tenemos 3 campos `visible` que determinan la visibilidad de cada parte en escritorio, tabley y móvil.
* Corregido un error con el controlador de actividades.

## [0.21.2](https://gitlab.com/neozink/neo-admin-3x/tags/v0.21.2) - 09/05/2019

### Fixed

* Corregida al obtención de las partes para solamente obtener aquellas que están visibles.
* Comillas duplicadas de cierre en varias vistas `index.ctp` en los elementos de tipo check.

## [0.21.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.21.1) - 30/04/2019

### Added

* Añadido requerimiento para `php >= 7.0` en el `composer.json`.
* Añadido un campo para poder seleccionar el tipo de animación de los cubos en el Neosite Cube.
* Añadido `legal_menu` al `SiteProperties`.

### Changed

* Cambiada la funcionalidad del `PartsTable` donde se añadían los campos de la tabla de partes genérica a la tabla de partes especifica.
* **Cambios en la base de datos**
    * Añadido campo `js_animate` a la tabla `pam_parts`.

### Fixed

* Añadida correctamente la clase `SearchableInterface` a las actividades del `SchedulesManager`.

## [0.21](https://gitlab.com/neozink/neo-admin-3x/tags/v0.21) - 29/04/2019

### Added

* Añadido nuevo plugin de administración de horarios en entidades.
    * Se ha desarrollado la funcionalidad de poder añadir horarios semanales en entidades.
* Añadidos campos para poder especificar en qué dispositivos queremos que estén visibles las partes de entidades.

### Changed

* Desarrollo de plugins y mejoras de código adaptándose a los nuevos estándares ES6 de Javascript.
* Desarrollo de nueva duplicación de entidades:
    * Se han mejorado los métodos de duplicación de entidades para que cada propia entidad pueda tener su propia implementación pero una estructura genérica para todas ellas.
    * De esta manera tendremos los métodos `duplicate` y `duplicateAll` que nos permiten duplicar una entidad o todas las entidades de un mismo tipo en base a diferentes parámetros: idioma, entidad relacionada, etc.
    * Esta implementación se ha realizado para Secciones, Partes de entidades, Formularios y Campos de formularios.
* Cambiada la configuración de las `searchable_entities` a la API y eliminada de los Roles.
* Separación de los diferentes traits e interfaces en sus propios namespaces para que sea más sencillo realizar mejoras futuras en las implementaciones relacionadas con esas funcionalidades.
* Actualización de paquetes con Composer.
* **Cambios en la base de datos**
    * Añadidas tablas `scm_activities` y `scm_schedules` para la administración de horarios y actividades.
    * Añadidos campos `visible_tablet` y `visible_mobile` en la tabla `pam_parts`.

### Fixed

* Revertida una corrección errónea con el `DateTimeWidget`.
* Corregido un error con la duplicación de entidades por las nuevas propiedades `_hidden` que introdujimos en todas las entidades.
* Corregido un error al añadir partes de vídeo sin un ID de vídeo de Youtube especificado.
* Corregido un error el editar el SEO de todas las secciones cuando no hay ninguna sección añadida.

## [0.20](https://gitlab.com/neozink/neo-admin-3x/tags/v0.20) - 11/04/2019

### Added

* Añadida la posibilidad de seleccionar la sección a la que redirigirá un formulario (Thank you page).
* Añadida nueva parte de mapa.
* Añadida configuración estándar para los layouts utilizados en el `ParteableBehavior` de los artículos del `BlogManager`.
* Añadida configuración de las partes del `PartsManager` en el `bootstrap`.
* Añadido selector de colores al `theme_color` del `SiteProperties`.
* Añadida configuración de `action` para botones en el `ButtonsManager`.
* Añadida funcionalidad para editar los ficheros sitemap.xml, robots.txt y .htaccess desde el `SiteProperties`.
* Añadido nuevo modo para el plugin `CodeMirror` para editar ficheros de Apache (`.htaccess`, `.conf`) con sintaxis coloreada.
* Añadida clase `Configure` para Javascript que puede que nos sea útil en el futuro cuando hagamos una refactorización mayor del código.
* Añadida ordenación de Productos del `ProductsManager` por Drag&Drop.
* Añadida ordenación de Distribuidores del `DistributorsManager` por Drag&Drop.
* Añadida parte múltiple de mosaico alternativo para poder añadir bloques a partes múltiples que muestren algún tipo de contenido alternativo al realizar una acción del ratón sobre ellas.
* Añadido campo `is_legal_menu` a la tabla `Sections` del `SectionsManager` para marcar secciones que aparezcan solo en el menú legal del footer de la página.
* Añadido nuevo `PartsComponent` en el `PartsManager` para realizar la configuración de variables necesarias para el funcionamiento del plugin.
* Añadida configuración del tipo de layout de front que se utilizará en el proyecto para que se muestre u oculten algunos elementos en las vistas, sobre todo de la gestión de partes.
* Creación de nueva entidad para los botones para gestionar todo lo relativo a la misma de manera separada al `ButtonsBehavior`.

### Changed

* Actualización de la versión de Fontawesome a la `5.8.1`.
* Refactorización del método `getParts` del `PartsTable` de manera que delegue la configuración de los tipos de partes a los propios tipos.
* Refactorización de las vistas de partes para que sean más fácilmente configurables y mantenibles.
* Estandarización y ordenación de todas las variables de configuración para que sea más sencillo añadir y quitar plugins.
    * Se ha cambiado la manera en la que se carga la configuración de los plugins, para que sea cada plugin el responsable de añadir la configuración que luego se utilizará en el resto de la aplicación y que se carga a través del `ConfigComponent`.
    * De esta manera, es más sencillo quitar y añadir componentes y, siguiendo el nombrado de cada tipo de configuración, será más sencillo añadir elementos al menú, a los roles, etc.
* Se ha aislado la funcionalidad de modificar el `.htaccess` desde el `SeoBehavior` para que cada funcionalidad esté en un sitio adecuado.
* Añadido campo `_hidden` a todas las entidades para limitar el número de registros que se envían en las peticiones JSON.
* **Cambios en la base de datos**
    * Añadida nuevas tablas para las partes de Mapas: `pam_map_parts` y `pam_map_parts_contacts`.
    * Eliminados campos `parent_id`, `lft`, `rght` y `level` de la tabla `pm_products`.
    * Eliminados campos `parent_id`, `lft`, `rght` y `level` de la tabla `dm_distributors`.
    * Añadido campo `is_legal_menu` a la tabla `sm_sections`.
    * Cambiado el nombre de la base de datos de `web_users` a `wm_web_users`.
    * Recuperada tabla de para la entidad `WebUserSessions` llamada `wm_web_user_sessions`.

### Fixed

* Corregido un problema con las querys para el `ParteableBehavior` de los artículos del `BlogManager`.
* Corregido error con los colores del back para que se cambien el orden de los dos primeros dependiendo de si se utilizan para colores de tipografía o colores de fondo.
* Añadidos los `ParteableBehavior` correctamente a las clases que implementaban el método `findParteable` para que sigan el nuevo estándar.

### Removed

* Eliminado el fichero JSON de configuración de colores ya que era innecesario porque se guarda la misma información en la base de datos.
* Eliminado el método `addLanguage` del `UrlController` ya que no se le estaba dando ningún tipo de uso.

## [0.19.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.19.1) - 01/04/2019

### Fixed

* Corregido un error con las partes de formulario colapsables

## [0.19](https://gitlab.com/neozink/neo-admin-3x/tags/v0.19) - 01/04/2019

> **NOTA**: Esta versión supone un **cambio bastante significativo** y **es incompatible en muchos aspectos** con versiones anteriores. Conlleva unos cambios bastante grandes, por lo que realizar alguna actualización de cualquier CMS antiguo requiere **planificación** y mucho **cuidado**.

Como características principales, se ha desarrollado lo siguiente:

* Estandarización de partes de entidades.
* Añadir a los artículos del `BlogManager` el behavior de partes.
* Nueva administración de colores.
* **Cambios profundos en la base de datos** de la aplicación.
* Actualización a CakePHP 3.7.*. [+ info](https://book.cakephp.org/3.0/en/appendices/3-7-migration-guide.html).
* Cambios en la configuración general de la aplicación.
* Estandarización de funcionalidades.
* Correcciones de errores y mejoras.

A continuación se detallan los cambios realizados en esta nueva versión:

### Added

* Añadida parte de Slides.
* Añadida una nueva pestaña para editar el SEO de cada sección de manera separada.
* Añadida funcionalidad para que los bloques de formularios puedan ser colapsables.
* Nuevas pestañas para la administración de diferentes partes del `SiteProperties`.
* Nueva administración de colores en el `SiteProperties`.
* Nuevo selector de colores.
* Añadidos atributos `rel` y `title` a secciones y botones.
* Añadida nueva vista de administración del SEO de todas las secciones
* Añadida posibilidad de enlazar un fichero del `MediaManager` con un botón.
* Añadida implementación de un método `list` en los artículos del `BlogManager` para poder realizar búsquedas, paginaciones, filtrados, etc.
* Añadido `ParteableBehavior` en sustitución al `LayoutTrait` con una configuración más acorde a la funcionalidad real del comportamiento.
* Añadido campo `legal_text` a la entidad `Forms` del `FormsManager`.
* Añadidos campos `image_width_mobile` e `image_height_mobile` a las entidates `TextImageParts` e `ImageParts` del `PartsManager`.

### Changed

* Actualización a la versión `3.7.*` de CakePHP. Esta actualización implica diferentes cambios:
    * Desarrollo de un nuevo componente para la configuración de variables no relativas directamente a una entidad en concreto, si no a toda la aplicación.
    * Estandarización de la configuración del bootstrap de los diferentes plugins.
    * Separación de la configuración de colores, idiomas, emails y SEO en diferentes ficheros.
* Separación de estilos en ficheros más pequeños y atómicos. Esto se seguirá mejorando en futuras releases.
* Mejoras en las vistas de añadir y editar secciones.
* Mejoras en las vistas de slides.
* Estandarización de todos los nombres de `controller` y `action` en las diferentes configuraciones de entidades.
    * `controller` en formato `UpperCamelCase`.
    * `action` en formato `lowerCamelCase`.
* Separación de las propiedades del sitio en varias pestañas.
* Estandarización de la configuración de colores y tipografías para que sean utilizados por el TinyMCE y por el resto de la aplicación.
* Desarrollada ordenación de productos del Ecommerce por Drag&Drop.
* Desarrollada ordenación de opciones y valores del Ecommerce por Drag&Drop.
* Desarrollada ordenación de stocks de los productos del Ecommerce por Drag&Drop.
* **Cambios en la base de datos**:
    * Añadido campo `bg_color` a la tabla `sm_sections`.
    * Eliminado campo `description` de la tabla `sm_sections`.
    * Cambiado el tipo del campo `header` de la tabla `sm_sections` de `VARCHAR (255)` a `LONGTEXT`.
    * Estandarización de nombres de tablas en plugins:
        * MediaManager: `mm_media`, `mm_resources`.
        * Buttons Manager: `bt_buttons`.
        * Entidades de ejemplo: `test_categories`, `test_posts`, `test_tags`, `test_posts_tags`.
    * Añadido campo `colors` a la tabla `sm_site_properties`.
    * Eliminada tabla `pam_banner_parts`.
    * Añadida tabla `pam_slider_parts`.
    * Eliminadas tablas de partes del `BlogManager`.
        * `bm_form_parts`
        * `bm_gallery_parts`
        * `bm_text_parts`
        * `bm_video_parts`
    * Añadido campo `legal_text`a la tabla `fm_forms`.
    * Añadidos campos `image_width_mobile` e `image_height_mobile` a las tablas `pam_text_image_parts` y `pam_image_parts`.
    * Eliminado campo `image_position` de la tabla `pam_image_parts`.
    * Eliminados campos `parent_id`, `lft` y `rght` de la tabla `ecm_products`.
    * Eliminados campos `parent_id`, `lft` y `rght` de la tabla `ecm_options`.
    * Eliminados campos `parent_id`, `lft` y `rght` de la tabla `ecm_option_values`.
    * Eliminados campos `parent_id`, `lft` y `rght` de la tabla `ecm_stocks`.

### Fixed

* Corregido un problema con el SEO de las secciones que se había intentado subsanar en la versión [0.18.3](https://gitlab.com/neozink/neo-admin-3x/tags/v0.18.3).
* Corregido un error al mostrar las fechas de los estados de los pedidos.
* Corregido un error con el borrado de recursos en el `MediaManager`.

### Removed

* Eliminada la librería de SCSS bourbon ya que no se utilizaba para nada que proporcionara valor añadido.
* Eliminada parte de Banner del `PartsManager`.
* Eliminadas partes de artículos del `BlogManager`.
* Eliminado `LayoutTrait`.

## [0.18.3](https://gitlab.com/neozink/neo-admin-3x/tags/v0.18.3) - 07/03/2019

### Fixed

* Error con el seo de las secciones.

## [0.18.2](https://gitlab.com/neozink/neo-admin-3x/tags/v0.18.2) - 26/02/2019

### Added

* Externalización de parámetros en las templates de correos.
* Flag `is_legal` para las secciones de contenidos legales.

### Fixed

* Corregido un error en la ordenación de partes.
* Corregido un error en la visibilidad de las partes.

### Changed

* Nueva columna `is_legal` en la tabla `sm_sections`.
* Datasource cambiado para apuntar a GCP.

## [0.18.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.18.1) - 29/01/2019

### Added

* Añadidas las variables para recuperar el ancho y el alto de las partes de entidades para el Neosite Cube.

### Fixed

* Corregido un error al devolver los botones de las partes de entidades.

## [0.18](https://gitlab.com/neozink/neo-admin-3x/tags/v0.18) - 25/01/2019

### Added

* Añadida funcionalidad para cerrar automáticamente las notificaciones flotantes.

### Changed

* Estandarización de llamadas Ajax al cambiar booleanos y al ordenar elementos.
* Adecuación de los nombres de los controladores y las acciones a formato CamelCase, compatible con la nueva estructura de URLs con guiones "-" en vez de con guiones bajos "_".

### Fixed

* Cambiada la manera en la que se dan permisos a las partes de entidades, para que el control recaiga sobre la entidad en sí y no sobre la parte.
* Corregidos varios errores con el DateTimeWidget.
* Arreglado el guardado de categorías de eventos.
* Correcciones menores y mejoras de configuración.

## [0.17.9](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.9) - 31/10/2018

### Fixed

* Revisados los métodos que devuelven información en JSON para utilizar el objeto `Response` de `CakePHP`.

## [0.17.8](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.8) - 26/10/2018

### Fixed

* Corregido un error con los plugins del editor de código.
* Otras correcciones menores.

## [0.17.7](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.7) - 22/10/2018

### Fixed

* Corregidos errores en la configuración de los botones del `ProductsController`.

## [0.17.6](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.6) - 19/10/2018

### Added

* Añadido `Behavior` para exportación de entidades en CSV.

### Fixed

* Corregida la visibilidad del método `getColors` del `AppController` a `protected` para que no pueda ser accedido como un `action`.
* Correcciones en los docs de algunas funciones del `AppController`.

## [0.17.5](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.5) - 19/10/2018

### Fixed

* Corregida visualización de notificaciones en la parte de login.

## [0.17.4](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.4) - 18/10/2018

### Changed

* Actualizada a la versión `3.6.12` de CakePHP
* Cambiado el estilo de los mensajes de notificación.
* Cambiada la implementación de los mensajes de notificación para que se puedan utilizar fácilmente desde llamadas `Ajax`.
* Estandarización de peticiones `Ajax` a nivel de `PHP` y de `Javascript` de manera que la comunicación entre ambos lenguajes se realice utilzando `JSON`.
    * Actualmente, las llamadas `Ajax` realizadas en la aplicación se comunican con la parte del servidor utilizando `JSON` en todas ellas y además se devuelven códigos de error correctamente para manejarlos en `Javascript`.
    * Se han estandarizado las funciones utilizadas en todas las llamadas `Ajax` para que se usen los métodos `done`, `fail` y `always` correctamente.

### Fixed

* Corregidas las notas de la version 0.17.3.
* Corregidos errores de visualización de los mensajes de notificación en el `MediaManager`.
* Correcciones menores de ejecución y sintaxis.

## [0.17.3](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.3) - 17/10/2018

### Added

* Se ha añadido el cálculo automático del tamaño de las subidas para el `MediaManager`.
* Se ha añadido la generación de imágenes de thumbnail con el `ResizeTrait` para utilizarlo en los `media-block`.

### Fixed

* Se han corregido los errores de duplicación de Secciones y Partes.
* Corregido un error con un mensaje de error en el flash de duplicación de formularios.

## [0.17.2](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.2) - 17/10/2018

### Fixed

* Arreglado un problema en el `MediaManager` al intentar utilizar el `ResizeTrait` sobre tipos de archivo no permitidos, como por ejemplo `svg`.

## [0.17.1](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17.1) - 16/10/2018

### Fixed

* Se ha arreglado la gestión de errores de las llamadas `Ajax` del `MediaManager`

## [0.17](https://gitlab.com/neozink/neo-admin-3x/tags/v0.17) - 16/10/2018

### Fixed

* Refactorización del código de la aplicación siguiendo las reglas [PSR2](https://www.php-fig.org/psr/psr-2/) utilizando el plugin [PHP-CS](https://github.com/FriendsOfPHP/PHP-CS-Fixer).
* Se ha refactorizado completamente la gestión de botones ya que la primera implementación no era fácilmente escalable ni reutilizable. Actualmente está implementado en los siguientes elementos, pero puede reutilizarse para utilizarlo donde queramos.
    * Partes de secciones
    * Partes múltiples
    * Slides
* Mejoras de rendimiento en el MediaManager:
    * Integración de `Resizer` para carga de archivos más ligera reutilizando el `ImageManager`.
    * Mejorar el rendimiento del procesamiento de carpetas, limitándolo a solo los recursos de la carpeta seleccionada.
    * Hacer que aparezca un fichero marcado después de subirlo utilizando `Uppy`.
    * Arreglar la visualización del `CroppeJS` con imágenes verticales.
* Correcciones de errores y mejoras en diferentes partes de la aplicación.

### Removed

* Se ha eliminado el `ImageManager` ya que no se estaba utilizando en ningún proyecto y se ha reaprovechado el código para utilizarlo en el `MediaManager`.

## [0.16.5](https://gitlab.com/neozink/neo-admin-3x/tags/v0.16.5) - 22/08/2018

### Added

* Se ha desarrollado la primera versión de configuración de botones para las partes de secciones.

## [Previous versions]

**TODO**: desarrollar la documentación de versiones anteriores
