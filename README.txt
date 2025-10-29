# FoodExpress – Sistema de Pedidos Online

Autor: Hernán Stupniki
Materia: Paradigmas y Lenguajes de Programación III
Proyecto B – Ruta: “El Innovador Técnico”

## Desafío 1 – Evolución del HTML

HTML5 trajo un cambio enorme frente al HTML clásico. Sumó etiquetas semánticas como header, nav, section, article o footer que hacen el código más claro y fácil de interpretar, tanto para buscadores como para lectores de pantalla.
También incorporó video, audio y validaciones sin depender de plugins. En general, volvió la web más ordenada, accesible y moderna.

## Desafío 2 – Arquitectura CSS Avanzada

La arquitectura en CSS se refiere a cómo se organiza todo el código y las capas de estilo, mientras que la metodología define cómo se nombran las clases y se reutilizan los componentes.
Un ejemplo de arquitectura es ITCSS y de metodología BEM. Usarlas ayuda a mantener el proyecto limpio, escalable y fácil de entender cuando hay muchos desarrolladores.

## Desafío 3 – JavaScript vs PHP

JavaScript se ejecuta en el navegador (y también en el servidor con Node.js), es asíncrono y se usa para interfaces interactivas y tiempo real.
PHP corre en el servidor y genera el contenido que llega al usuario, siendo ideal para sitios tradicionales y sistemas como WordPress.

**Ejemplo JavaScript:**

```js
console.log("Hola desde JavaScript");
```

**Ejemplo PHP:**

```php
<?php echo "Hola desde PHP"; ?>
```

## Desafío 4 – Conexión a Bases de Datos

En PHP se puede conectar una base de datos con MySQLi o PDO, siendo PDO más seguro y compatible con varios motores.
Para hacerlo se crea una conexión, se prepara la consulta, se pasan los datos y se ejecuta.


## Documentación de la funcionalidad PHP implementada

El proyecto FoodExpress está hecho con PHP y MySQL, y la idea fue mantener una estructura simple pero bien organizada.
La conexión a la base de datos se maneja desde un solo archivo (`hs_conexion.php`) usando PDO, lo que permite trabajar con consultas preparadas y manejar errores de forma más segura.

El carrito de compras (`hs_carrito.php`) funciona con sesiones, guardando los productos que el usuario va agregando.
Desde este archivo también se manejan las acciones del carrito con AJAX, como agregar, cambiar cantidades o eliminar productos, sin tener que recargar la página.
Los precios, subtotales y totales siempre se recalculan en el servidor, para evitar que alguien los modifique desde el navegador.

En el checkout, el archivo `hs_guardar_pedido.php` recibe los datos del formulario, valida los campos y guarda el pedido en la base de datos.
Para eso se usa una transacción que inserta primero el pedido y después el detalle con los productos. Si algo falla, se cancela todo para que la información quede consistente.

En la parte de administración (`hs_admin`) se pueden agregar, editar o desactivar productos, sin borrarlos definitivamente (soft delete).
Esto permite mantener la información y tener más control sobre el catálogo.
Todas las consultas usan sentencias preparadas, lo que evita problemas de seguridad como la inyección SQL.

En general, la parte PHP del proyecto busca que todo funcione de forma segura, ordenada y clara, combinando la lógica del servidor con la parte visual para que la experiencia sea fluida, sin recargar las páginas y manteniendo los datos siempre correctos.


## Documentación de diseño (paleta, tipografía y layout)

El diseño de FoodExpress se pensó para que sea moderno, claro y fácil de usar, tanto en computadora como en celular.
Se eligió una paleta de colores cálida para transmitir cercanía y energía:
Rojo como color principal (#ff6347) y un tono más oscuro para los botones en hover (#e5533d).
Blanco y gris claro para los fondos, y texto en gris oscuro (#222) para asegurar buena legibilidad.
También se usan colores de estado: verde para éxito, amarillo para aviso y rojo para error.

En cuanto a la tipografía, se usa el estilo del sistema (por ejemplo Roboto, Segoe UI o Arial) para que cargue rápido y se vea bien en todos los dispositivos.
Los títulos son grandes y marcados (h1 de 32px, h2 de 24px, h3 de 18px) y el texto base de 16px, con una jerarquía que ayuda a leer fácilmente.

El layout está hecho con grid y flexbox, usando espacios uniformes entre los elementos para mantener el orden.
El sitio es totalmente responsive, con tres tamaños principales:
En celular, todo se muestra en una columna y el menú se vuelve tipo hamburguesa.
En tablet, se muestran tres columnas y el menú ya aparece desplegado.
En pantallas grandes, hay hasta cuatro columnas y más margen en los costados.

También se agregaron transiciones suaves en botones y enlaces para dar una sensación más fluida, y un loader con spinner cuando se envían formularios o se cargan datos.
Los botones e inputs muestran un borde o sombra al hacer foco, para mejorar la accesibilidad y que se pueda navegar con teclado.


## Conclusión

FoodExpress integra todos los niveles del trabajo práctico, desde el uso de HTML semántico y CSS responsive hasta la implementación completa del backend con PHP y MySQL.
El sistema cumple con los objetivos planteados, combinando funcionalidad, diseño y buenas prácticas de desarrollo.
El resultado final es una aplicación sencilla, segura y visualmente coherente, que brinda una buena experiencia tanto al usuario como al administrador.
