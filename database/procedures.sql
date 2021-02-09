
-- PROCEDURES

-- CREATES

CREATE OR REPLACE PROCEDURE new_event(  e_f events.e_from%TYPE,
                                            e_t events.e_to%TYPE,
                                            t_name tasks.name%TYPE,
                                            u_name users.name%TYPE) IS
    u_id NUMBER;
    t_id NUMBER;
    e_id NUMBER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED ;
    SELECT users.user_id INTO u_id FROM USERS WHERE USERS.name = u_name;
    SELECT tasks.task_id INTO t_id FROM TASKS WHERE TASKS.name = t_name;
    e_id := event_id_seq.nextval;
    INSERT INTO events VALUES (e_id, e_f, e_t, t_id);
    INSERT INTO user_event VALUES (u_id, e_id);
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE new_team(   t_name teams.name%TYPE,
                                            u_name users.name%TYPE,
                                            t_passw teams.password%TYPE) IS
    t_id NUMBER;
    u_id NUMBER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED ;
    t_id := team_id_seq.nextval;
    SELECT user_id INTO u_id FROM users WHERE name=u_name;
    INSERT INTO teams VALUES (t_id, t_name, t_passw);
    INSERT INTO user_team VALUES (u_id, t_id, 1);
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE new_task(tk_name tasks.name%TYPE,
                                    tm_name teams.name%TYPE,
                                    f_name tasks.name%TYPE,
                                    s_name tasks.name%TYPE) IS
    tk_id tasks.task_id%TYPE;
    tm_id teams.team_id%TYPE;
    f_id tasks.task_id%TYPE;
    s_id tasks.task_id%TYPE;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
    tk_id := task_id_seq.nextval;
    SELECT team_id INTO tm_id FROM teams WHERE name = tm_name;
    IF f_name IS NULL THEN f_id := NULL;
        ELSE
    SELECT task_id INTO f_id FROM tasks WHERE name = f_name;
    END IF;
    IF s_name IS NULL THEN s_id := NULL;
        ELSE
    SELECT task_id INTO s_id FROM tasks WHERE name = s_name;
    END IF;

    INSERT INTO tasks VALUES (tk_id, tk_name, tm_id, f_id);
    UPDATE tasks
    SET father_id = tk_id
    WHERE task_id = s_id;
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE new_user( u_name users.name%TYPE,
                                      u_psswd users.password%TYPE,
                                      u_email users.email%TYPE) IS
    u_id NUMBER;
BEGIN
    u_id := user_id_seq.nextval;
    INSERT INTO users VALUES (u_id, u_name, u_psswd, u_email);
END;
/

CREATE OR REPLACE PROCEDURE add_user_to_team( u_name users.name%TYPE,
                                      t_name teams.name%TYPE) IS
    u_id users.user_id%TYPE;
    t_id teams.team_id%TYPE;
BEGIN
    SELECT user_id INTO u_id FROM USERS WHERE name = u_name;
    SELECT team_id INTO t_id FROM TEAMS WHERE name = t_name;
    INSERT INTO user_team VALUES (u_id, t_id, 0);
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE add_user_to_event( u_name users.name%TYPE,
                                               e_id events.event_id%TYPE) IS
    u_id users.user_id%TYPE;
BEGIN
    SELECT user_id INTO u_id FROM users WHERE name = u_name;
    INSERT INTO user_event VALUES (u_id, e_id);
    COMMIT;
END;
/


-- UPDATES
CREATE OR REPLACE PROCEDURE make_admin( uname users.name%TYPE,
                                        tname teams.name%TYPE) IS
    u_id users.user_id%TYPE;
    t_id teams.team_id%TYPE;
BEGIN
    SELECT user_id INTO u_id FROM users WHERE users.name = uname;
    SELECT team_id INTO t_id FROM teams WHERE teams.name = tname;
    UPDATE user_team
    SET user_team.is_admin = 1
    WHERE user_id = u_id AND team_id = t_id;
    COMMIT;
END;
/
create or replace procedure set_team_passw(t_id teams.team_id%TYPE,
                                            passw teams.password%TYPE) IS
BEGIN
    UPDATE teams
    SET teams.password = passw
    WHERE teams.team_id = t_id;
    COMMIT;
end;
/

CREATE OR REPLACE PROCEDURE rename_task(fname tasks.name%TYPE,
                                        tname tasks.name%TYPE) IS
BEGIN
    UPDATE tasks
    SET tasks.name = tname
    WHERE tasks.name = fname;
    COMMIT;
end;
/

CREATE OR REPLACE PROCEDURE delete_task(tname tasks.name%TYPE) IS
    f_id tasks.task_id%TYPE;
    t_id tasks.task_id%TYPE;
BEGIN
    SELECT father_id INTO f_id FROM tasks WHERE name = tname;
    SELECT task_id INTO t_id FROM tasks WHERE name = tname;

    UPDATE tasks
    SET tasks.father_id = f_id
    WHERE tasks.father_id = t_id;
    DELETE FROM tasks WHERE name = tname;
    COMMIT;
end;
/

