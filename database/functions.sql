
-- FUNCTIONS

CREATE OR REPLACE FUNCTION user_cnt (u_name users.name%TYPE,
                                         u_pass users.password%TYPE) RETURN NUMBER IS
    cnt NUMBER;
begin
    SELECT COUNT(*) INTO cnt FROM USERS WHERE name = u_name AND password = u_pass;
    return cnt;
end;
/

CREATE OR REPLACE FUNCTION team_cnt (t_name teams.name%TYPE,
                                         t_pass teams.password%TYPE) RETURN NUMBER IS
    cnt NUMBER;
begin
    SELECT COUNT(*) INTO cnt FROM TEAMS WHERE name = t_name AND password = t_pass;
    return cnt;
end;
/

CREATE OR REPLACE FUNCTION get_task_id(taskName tasks.name%TYPE,
                                        teamName teams.name%TYPE) RETURN tasks.task_id%TYPE IS
    res tasks.task_id%TYPE;
    BEGIN
        SELECT tasks.task_id INTO res
        FROM tasks JOIN teams ON tasks.team_id = teams.team_id
        WHERE tasks.name = taskName AND teams.name = teamName;
        return res;
    end;
/
CREATE OR REPLACE FUNCTION is_admin(uname users.name%TYPE,
                                    tname teams.name%TYPE)
                                        return user_team.is_admin%TYPE IS
    res user_team.is_admin%TYPE;
    BEGIN
        SELECT is_admin INTO res
        FROM users
        JOIN user_team ut on users.user_id = ut.user_id
        JOIN teams ON ut.team_id = teams.team_id
        where users.name = uname AND teams.name = tname;
        RETURN res;
    end;
/
