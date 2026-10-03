# Ficha técnica: hoja electrónica de juego

Fecha de revisión: 21 de septiembre de 2026.

## Alcance y diagnóstico

Archivo analizado: `resources/views/games/show.blade.php` (1029 líneas), sus componentes, rutas, controladores, modelos y plantilla PDF relacionada. Hay otros archivos llamados show.blade.php; se eligió el de juegos por el objetivo de la hoja electrónica. Se interpreta «hoja electrónica» como acta digital de un partido, aparentemente de baloncesto por sus campos y límites; el reglamento concreto está por definir.

Revisión estática del código local, incluidos cambios existentes sin confirmar en Git. No se ejecutó el flujo en navegador ni se consultó la base de datos. Los nombres y usos de tablas proceden de consultas y modelos; no se certifica el esquema físico instalado. No se modificó código de la aplicación.

Diagnóstico: existe un prototipo de captura de actividad con persistencia parcial. Todavía no constituye una hoja electrónica completa, recuperable y cerrable. Los principales pendientes son autorización del servidor, consistencia del marcador, configuración persistente, periodos y tiempo, recuperación de eventos, cierre y documento final.

## Tecnología y responsabilidades

- Laravel 5.6 según composer.json; PHP en controladores/modelos y Blade para HTML y datos iniciales.
- jQuery 2.2.3 cargado por layouts/app.blade.php; AJAX, eventos delegados y manipulación del DOM.
- Modales con interfaz Bootstrap, SweetAlert2 para confirmaciones, jquery.cookie para estado local y Notify para avisos.
- Dompdf en el flujo PDF relacionado.
- La vista no consulta SQL directamente. Recibe `$game`, `$players`, `$tournament` y `$actions`; consume dos endpoints POST.
- Componentes: menuTournament, tablePlayers, tableGamePlayers y tableGameReferees.

## Tablas utilizadas por este flujo

| Tabla | Acceso | Uso comprobado |
|---|---|---|
| games | Lectura y actualización | Partido, equipos, fecha, estado, marcador, torneo, códigos público y de acceso. Se incrementan score_team1/score_team2 al registrar una acción exitosa con valor positivo. |
| teams | Lectura | Dos joins, alias t1 y t2, para nombres y logotipos. |
| categories | Lectura | Categoría del partido. |
| journeys | Lectura | Jornada; filtra is_deleted=0 y show_public='Si'. |
| stadiums | Lectura | Sede, mediante left join. |
| rosters | Lectura | Plantillas de ambos equipos: id, name, last_name, photo, number y team_id. Nombre inferido de la convención Eloquent del modelo Roster. |
| tournaments | Lectura | Información del torneo y sport_id para seleccionar acciones. |
| actions | Lectura | Catálogo del deporte: nombre, valor y necesidad de ofrecer resultado fallado. Se filtra is_deleted, pero no is_enabled. |
| gamelogs | Inserción | Registro de acciones; nombre explícito en App/GameLogs.php. |

Distinciones importantes:

- La tabla de eventos se llama `gamelogs`, no `game_logs`.
- Aunque se usa `$players` y se envía `player_id`, el identificador proviene de `rosters.id`, no de una consulta a `players`. Debe definirse esta semántica antes de crear relaciones o llaves foráneas.
- `sports` no se consulta directamente en este flujo: se usa tournaments.sport_id para filtrar actions.
- La pestaña de árbitros es una maqueta; no demuestra una tabla de árbitros ni persistencia.
- No se localizaron migraciones de gamelogs, actions o rosters en database/migrations. Esto no demuestra que las tablas no existan en la base instalada.

## Entrada y contratos HTTP

| Ruta | Responsable | Comportamiento |
|---|---|---|
| GET /tournament_info/{slug}/game/{code} | TournamentsController::game | Consulta el partido por code, sus plantillas, torneo y acciones; renderiza games.show. |
| POST /game_data_entry | GamesController::data_entry | Busca games.access_code y devuelve access como cadena 'true'/'false', game_id, type y message. No establece autorización del partido en sesión. |
| POST /game_log | GameLogsController::storeLog | Crea GameLogs con los campos recibidos y, cuando corresponde, incrementa el marcador. Devuelve id, score, type y message. |
| GET /print_paper_game/{gameId} | GamesController::listPDF | Genera PDF con pdf.games.gamePaper; la plantilla es mayormente estática. No está integrado como cierre de esta pantalla. |

Existe además GET /show_game/{code}, pero GamesController::showGame imprime un texto de prueba y devuelve games.show sin sus variables requeridas. No equivale al flujo completo del controlador de torneos. GET /games/{id} corresponde a otro método y no es la entrada principal de esta vista.

Campos enviados por handleLogGame: tournament_id, game_id, team_id, player_id, action_id, status, value, period, time y comment. El modelo permite además is_deleted por asignación masiva.

## Funcionamiento de la pantalla

1. Muestra equipos, escudos y marcador. Los registros '(23-1)' y '(12 -2)' son texto fijo. Los detalles de fecha, sede y categoría están comentados.
2. Si el estado no es Finalizado, presenta «Capturar Actividad». Solicita código y lo consulta por AJAX.
3. El navegador compara el game_id devuelto y habilita el panel mediante cookies: enableGame, game_id, playersList, playersListA y playersListB.
4. Configuración muestra capitán, asistencia, titulares y árbitros. Hay controles locales para máximo un capitán y cinco titulares; no guardan en servidor. La asistencia no tiene manejador de persistencia. «Iniciar Juego» no tiene implementación asociada en la vista.
5. Permite pasar jugadores de la lista disponible a cancha y devolverlos a banca. Modifica arrays, cookies y filas HTML; INGRESA y SALE no se guardan en gamelogs.
6. Al elegir un jugador presenta las acciones del catálogo. Permite Exitoso y, si need_confirmation='Si', Fallado. Tras confirmar, envía POST /game_log.
7. El servidor suma value cuando es positivo y status es Exitoso, y guarda el evento. No consulta el catálogo para calcular ese valor ni comprueba el campo scoring.
8. La respuesta actualiza el marcador del equipo y agrega una fila todavía con datos de ejemplo.
9. «Finalizar» elimina cookies y oculta el panel. No cambia games.status, no valida el resultado ni genera el acta.

## Hallazgos y pendientes comprobados

### Prioridad crítica: autorización y exactitud

- **Guardar no exige autorización del partido.** storeLog está excluido del middleware auth y no verifica código, permiso de sesión o token del partido. La comprobación inicial solo habilita la interfaz. El middleware web incluye CSRF, pero eso no acredita permiso para editar un partido.
- **El servidor confía en los datos enviados.** No valida pertenencia de jugador/equipo/torneo, acción habilitada, periodo, estado del partido ni valor oficial de la acción. Un partido Finalizado solo pierde el botón en la vista; el endpoint no lo bloquea.
- **Marcador y evento pueden separarse.** Se actualiza games antes de guardar gamelogs, sin transacción. Dos solicitudes concurrentes pueden perder incrementos; un reintento puede duplicar una acción. Se necesita transacción, control de concurrencia e identificador único de operación.
- **Error residual de JavaScript.** Al terminar addPlayerToTable aparece `}s` cerca de la línea 951. Esa `s` se interpreta como referencia a una variable y puede producir ReferenceError al ejecutar el callback si no existe. No debe describirse simplemente como un error de sintaxis; requiere eliminación y verificación en navegador.

### Prioridad alta: completar la operación

- **Configuración no persistente:** faltan guardado y recuperación de asistencia, capitán y titulares por partido. Los botones btnSaveConfig y btnStart no tienen implementación en el código de la vista.
- **Límites inconsistentes:** el ingreso a cancha se limita con maxPlayers=12, aunque maxStarters=5. Deben distinguirse convocados y jugadores simultáneos según el deporte.
- **Árbitros pendientes:** tabla con texto fijo y listado comentado; faltan asignación, roles y guardado.
- **Periodo y tiempo pendientes:** se lee $('#period').html(), pero ese elemento no existe en esta vista ni sus componentes revisados. El tiempo enviado siempre es '00:00'.
- **Sin recuperación de bitácora:** el controlador no consulta gamelogs para reconstruir el historial. Recargar conserva parcialmente jugadores por cookies, pero no reconstruye el acta.
- **Sustituciones locales:** INGRESA/SALE solo existen en el DOM y cookies; no permiten reconstruir alineación ni minutos.
- **Estadística incompleta:** faltas y puntos por jugador se dibujan en cero y no se recalculan en la tabla. El catálogo podría contener faltas, pero no hay aquí lógica que aplique límites o consecuencias reglamentarias.
- **Corrección pendiente:** btnDeleteLog se dibuja, pero no tiene manejador ni endpoint asociado en este flujo. Hace falta una política de anulación con auditoría y recálculo.
- **Cierre pendiente:** falta transición de estado en servidor, validación final, responsable y bloqueo de cambios; también definir quién puede reabrir.
- **PDF sin datos completos:** gamePaper contiene nombres y fecha de ejemplo, espacios en blanco y un marcador de posición para progresión de puntos. No produce todavía el acta del partido capturado.

### Calidad y robustez

- Fila de evento con 'NN', 'Player Name', 'Registro', periodo '-' y hora fija; usa player_id en la columna de equipo y cadenas literales 'player.id'/'player.team_id' en atributos.
- IDs repetidos en botones y checkboxes; tableGamePlayers genera `id="table_"` seguido del teamId como atributo separado. Usar clases y atributos data-* y reservar IDs únicos.
- HTML de imágenes mal formado y estructura de tabla inválida en el modal de acciones.
- Cookies con listas completas de jugadores, sin claves específicas del partido: estado manipulable, capacidad limitada y problemas entre pestañas. Además se restauran jugadores antes de comprobar que la cookie pertenece al juego abierto.
- Tras validar un código se llama enableGame(true) antes de escribir game_id en la cookie: sin cookie previa válida, la función puede volver a deshabilitar el panel en ese primer intento.
- Nombres y otros datos se concatenan como HTML; revisar escape para evitar inyección de contenido. `$players` se incrusta sin escape mediante Blade raw: usar serialización apropiada al contexto JavaScript.
- TournamentsController::game recibe slug pero no lo valida contra el torneo del partido, y accede a get()[0] sin tratar el resultado vacío.
- GameLogs::game apunta a App\Game, mientras el modelo observado es App\Games. Games::gameLog tampoco declara la llave game_id explícitamente: revisar la inferencia de Eloquent antes de usar esa relación para reconstruir el acta.
- storeLog devuelve type='success' incluso en la rama donde save() retorna false; falta respuesta de error consistente y manejo transaccional de excepciones.

## Modelo de datos propuesto, sujeto a validar el esquema real

No son tablas comprobadas existentes; son opciones de diseño:

| Entidad | Datos mínimos |
|---|---|
| Participantes por partido (p. ej. game_players) | game_id, team_id, roster_id, dorsal del partido, asistencia, capitán y titular; unicidad por participante/partido. |
| Oficiales por partido (p. ej. game_officials) | game_id, persona o referencia, rol y observaciones. |
| Periodos (p. ej. game_periods) | game_id, número, tipo normal/prórroga, duración y estado. |
| Eventos, ampliando gamelogs | Tipo/acción, participante opcional, equipo, periodo, tiempo, orden, valor validado, autor, clave de idempotencia y datos de anulación. |
| Estado del partido, ampliando games | Estado del reloj si aplica, periodo actual, inicio, cierre, responsable y versión para concurrencia. |

Definir si faltas de equipo, tiempos muertos y cambios son eventos del mismo registro; varios no corresponden a un único jugador. Evitar duplicar totales sin una estrategia de reconstrucción. Conservar la identidad y dorsal de la participación para que una modificación futura de rosters no altere actas históricas.

## Orden sugerido de implementación

1. Acordar deporte, reglamento y formato objetivo: periodos, prórrogas, cupos, tipos de falta, tiempos muertos y firmas requeridas. Reloj integrado frente a tiempo capturado manualmente.
2. Corregir bloqueos de JS, autorización del servidor, validación de acciones y transacción de evento/marcador.
3. Guardar configuración, oficiales y estado; implementar inicio y recuperación completa al recargar o cambiar de dispositivo.
4. Completar eventos de anotación, fallo, sustitución, falta y tiempo muerto, con periodo/hora reales y corrección auditada.
5. Calcular marcador, estadísticas y acumulados por periodo desde eventos válidos; sincronizar clientes si habrá varios anotadores.
6. Implementar cierre, permisos de reapertura y PDF alimentado con datos reales. Añadir firmas/validación según requisitos acordados.

## Criterios de aceptación para considerar completa la hoja

- Un capturista autorizado configura y comienza el partido; una solicitud sin permiso no puede alterarlo.
- Recargar o entrar desde otro dispositivo autorizado recupera participantes, periodo, marcador y eventos.
- Cada acción válida produce exactamente un evento; un reintento no duplica puntos y concurrencia no pierde anotaciones.
- El servidor rechaza participantes ajenos, valores manipulados y modificaciones de partidos cerrados.
- Una anulación conserva autor/motivo y recompone marcador y estadísticas.
- Totales por jugador, equipo y periodo concuerdan con los eventos vigentes.
- Finalizar cambia el estado en servidor y produce una hoja con datos reales y el formato acordado.

## Archivos de referencia para continuar la revisión

- resources/views/games/show.blade.php
- resources/views/components/tablePlayers.blade.php
- resources/views/components/tableGamePlayers.blade.php
- resources/views/components/tableGameReferees.blade.php
- resources/views/layouts/app.blade.php
- app/Http/Controllers/TournamentsController.php:1084 (game)
- app/Http/Controllers/GamesController.php:466 (data_entry), :604 (listPDF)
- app/Http/Controllers/GameLogsController.php
- app/GameLogs.php, app/Games.php, app/Roster.php, app/Actions.php
- routes/web.php:230, :267, :269, :275
- resources/views/pdf/games/gamePaper.blade.php

## Texto para continuar con ChatGPT web

Actúa como analista de PHP, Laravel 5.6, Blade y jQuery. Con base en esta ficha, ayúdame a especificar y terminar una hoja electrónica de partido. Separa hechos observados, propuestas y requisitos por confirmar. Primero identifica las decisiones reglamentarias pendientes; después propón modelo de datos, contratos HTTP, flujo de estados y etapas con criterios de aceptación. Considera que player_id actualmente recibe rosters.id, gamelogs es la tabla real de eventos referenciada por el código, las sustituciones/configuración no se guardan y el endpoint de captura necesita autorización y transacciones. No presupongas que las tablas propuestas existen ni que el PDF está terminado.
