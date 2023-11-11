SELECT 
t.name as team, 
count('x') as JJ, 
count(case when PAF > PEC then 1 end) JG, 
count(case when PAF < PEC then 1 end) JP, 
count(case when PAF = PEC then 1 end) JE, 
count(case when PPAF > PPEC then 1 end) JGP, 
count(case when PPAF < PPEC then 1 end) JPP, 
sum(score.PAF) as PAF, 
sum(score.PEC) as PEC, 
sum(score.PAF) - sum(score.PEC) as DIF , 
sum( case when PAF > PEC then 3 else 0 end 
    + case when PPAF > PPEC then 2 else 0 end 
    + case when PPAF < PPEC then 1 else 0 end ) as PUNTOS 
    FROM ( 
            (SELECT h.team1_id team, 
            h.score_team1 PAF, 
            h.score_team2 PEC, 
            h.score_penalties_team1 PPAF, 
            h.score_penalties_team2 PPEC 
            FROM games h 
            INNER JOIN journeys j on j.id = h.journey_id 
            WHERE h.category_id=21 
            and h.status = 'Finalizado' 
            and h.type = 'Temporada Regular' 
            and j.show_public ='Si' 
            and j.tournament_id = 17 ) 
            UNION ALL 
            ( SELECT v.team2_id team, 
            v.score_team2 PAF, 
            v.score_team1 PEC, 
            v.score_penalties_team2 PPAF, 
            v.score_penalties_team1 PPEC 
            FROM games v 
            INNER JOIN journeys j on j.id = v.journey_id 
            WHERE v.category_id=21 
            and v.status = 'Finalizado' 
            and v.type = 'Temporada Regular' 
            and j.show_public ='Si' 
            and j.tournament_id = 17 
            ) 
         ) as score 
         join teams t on t.id = score.team where t.status = 'Activo' group by t.name ORDER BY PUNTOS desc, DIF desc;