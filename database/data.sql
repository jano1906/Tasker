
begin
    new_user('janek', 'janek', '@janek');
    new_user('marek', 'marek', '@marek');
    new_user('dominik', 'dominik', '');
    new_user('mateusz', 'mateusz', 'mail@mateusz');
end;
/
begin
    new_team('teamJANEK', 'janek', 'PASSteamJANEK');
    new_team('teamMAREK', 'marek', 'PASSteamMAREK');
end;
/
begin
    new_task('matematyka', 'teamJANEK', NULL, NULL);
    new_task('analiza', 'teamJANEK', 'matematyka', NULL);
    new_task('analizaZadDom', 'teamJANEK', 'analiza', NULL);
    new_task('analizaZadDom*', 'teamJANEK', 'analizaZadDom', NULL);
    new_task('algebra','teamJANEK' , 'matematyka', NULL);
    new_task('algebraZadDom','teamJANEK' , 'algebra', NULL);
end;
/
begin
    new_event(TO_DATE('2021-01-22 22:00', 'yyyy-mm-dd hh24:mi'), TO_DATE('2021-01-22 23:00', 'yyyy/mm/dd hh24:mi:ss'), 'analiza','janek');
    new_event(TO_DATE('2021-01-23 14:00', 'yyyy-mm-dd hh24:mi'), TO_DATE('2021-01-23 16:00', 'yyyy/mm/dd hh24:mi:ss'), 'analizaZadDom','janek');
end;
/
begin
    add_user_to_team('dominik', 'teamJANEK');
    add_user_to_team('mateusz', 'teamJANEK');
end;
/
