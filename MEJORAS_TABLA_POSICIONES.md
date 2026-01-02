# Mejoras a la Tabla de Posiciones - Criterios de Desempate

## ✅ Cambios Implementados

He mejorado la consulta SQL de la tabla de posiciones en `TournamentsController::getScores()` para incluir **criterios adicionales de desempate** cuando los equipos tienen los mismos puntos.

### 📊 Nuevas Columnas Agregadas

1. **JG_LOCAL** - Victorias como equipo local (jugando de casa)
2. **JG_VISIT** - Victorias como visitante (jugando de visitante)
3. **GF_LOCAL** - Goles a favor como local
4. **GF_VISIT** - Goles a favor como visitante

### 🎯 Criterios de Desempate Aplicados

Siguiendo las reglas FIFA/CONMEBOL, el orden de desempate es:

**Sin sistema de puntos:**
1. JG (Juegos Ganados)
2. DIF (Diferencia de goles)
3. PAF (Goles a favor)
4. JP (Juegos perdidos)

**Con sistema de puntos:**
1. **PUNTOS** (total de puntos del equipo)
2. **DIF** (diferencia de goles)
3. **PAF** (goles a favor totales)
4. **JG** (partidos ganados)
5. **JG_VISIT** (victorias como visitante - criterio FIFA)

### 🔧 Cambios Técnicos

#### Antes:
```sql
SELECT t.id, t.name as team,
    count('x') as JJ,
    count(case when PAF > PEC then 1 end) JG,
    ...
    FROM (
        (SELECT h.team1_id team, h.score_team1 PAF, ...
         FROM games h ...)
        UNION ALL
        (SELECT v.team2_id team, v.score_team2 PEF, ...
         FROM games v ...)
    ) AS score
    ...
ORDER BY JG desc, JP asc, DIF DESC
```

#### Después:
```sql
SELECT t.id, t.name as team,
    count('x') as JJ,
    count(case when PAF > PEC then 1 end) JG,
    ...
    -- NUEVAS COLUMNAS
    count(case when ES_LOCAL = 1 and PAF > PEC then 1 end) as JG_LOCAL,
    count(case when ES_LOCAL = 0 and PAF > PEC then 1 end) as JG_VISIT,
    sum(case when ES_LOCAL = 1 then PAF else 0 end) as GF_LOCAL,
    sum(case when ES_LOCAL = 0 then PAF else 0 end) as GF_VISIT
    FROM (
        (SELECT h.team1_id team, h.score_team1 PAF, 
                1 as ES_LOCAL  -- MARCA JUEGOS COMO LOCAL
         FROM games h ...)
        UNION ALL
        (SELECT v.team2_id team, v.score_team2 PEF, 
                0 as ES_LOCAL  -- MARCA JUEGOS COMO VISITANTE
         FROM games v ...)
    ) AS score
    ...
ORDER BY PUNTOS DESC, DIF DESC, PAF DESC, JG DESC, JG_VISIT DESC
```

### 📝 Ejemplo de Uso

Cuando dos equipos tienen los mismos puntos:

| Equipo | PTS | DIF | PAF | JG | JG_VISIT | Posición |
|--------|-----|-----|-----|----|---------|-|
| Tigres | 15 | +8 | 20 | 5 | 3 | 1° |
| Rayados | 15 | +8 | 20 | 5 | 2 | 2° |

En este caso, **Tigres queda primero** porque tiene 3 victorias de visitante vs 2 de Rayados.

### 🗂️ Archivos Modificados

- `app/Http/Controllers/TournamentsController.php` (método `getScores()`)
- Backup creado en: `app/Http/Controllers/TournamentsController.php.bak`

### ⚡ Próximos Pasos (Opcional)

Si quieres implementar el **enfrentamiento directo** (head-to-head) entre equipos empatados, necesitarás:

1. Una consulta adicional que identifique equipos con mismos puntos
2. Filtrar solo los juegos entre esos equipos específicos
3. Calcular quién ganó más partidos en esos enfrentamientos directos

Esto es más complejo y requiere lógica adicional en el backend o una view/stored procedure en MySQL.

### 📌 Nota Importante

He comentado la línea `echo $query;` para que no se muestre en producción. Si necesitas debuggear, puedes descomentarla temporalmente.

---

**¿Necesitas que aplique estos cambios también en otros métodos?** 

Los siguientes métodos tienen queries similares que podrían beneficiarse:
- `showTournament()` (línea ~650)
- `frontPage()` (línea ~770)  
- `standings()` (línea ~1000)

¿Quieres que los actualice también?
