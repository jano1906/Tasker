-- PROCEDURES

-- CREATES

CREATE OR REPLACE PROCEDURE new_event(  e_f events.e_from%TYPE,
                                            e_t events.e_to%TYPE,
                                            t_id events.task_id%TYPE,
                                            u_id users.user_id%TYPE) IS
    e_id NUMBER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED ;
    e_id := event_id_seq.nextval;
    INSERT INTO events VALUES (e_id, e_f, e_t, t_id);
    INSERT INTO user_event VALUES (u_id, e_id);
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE new_team(   t_name teams.name%TYPE,
                                            u_id users.user_id%TYPE) IS
    t_id NUMBER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL READ COMMITTED ;
    t_id := team_id_seq.nextval;
    INSERT INTO teams VALUES (t_id, t_name);
    INSERT INTO user_team VALUES (u_id, t_id, 1);
    COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE new_task(tk_name tasks.name%TYPE,
                                    tm_id teams.team_id%TYPE,
                                    f_id tasks.father_id%TYPE,
                                    s_id tasks.task_id%TYPE) IS
    tk_id NUMBER;
BEGIN
    tk_id := task_id_seq.nextval;
    INSERT INTO tasks VALUES (tk_id, tk_name, tm_id, f_id);
    UPDATE tasks
    SET father_id = tk_id
    WHERE task_id = s_id;
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

CREATE OR REPLACE PROCEDURE add_user_to_team( u_id users.user_id%TYPE,
                                      t_id teams.team_id%TYPE) IS
BEGIN
    INSERT INTO user_team VALUES (u_id, t_id, 0);
END;
/

CREATE OR REPLACE PROCEDURE add_user_to_event( u_id users.user_id%TYPE,
                                               e_id events.event_id%TYPE) IS
BEGIN
    INSERT INTO user_event VALUES (u_id, e_id);
END;
/


-- UPDATES
CREATE OR REPLACE PROCEDURE make_admin( u_id users.user_id%TYPE,
                                        t_id teams.team_id%TYPE) IS
BEGIN
    UPDATE user_team
    SET user_team.is_admin = 1
    WHERE user_id = u_id AND team_id = t_id;
END;
/


