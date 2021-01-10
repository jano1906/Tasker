CREATE OR REPLACE TRIGGER is_admin_check
AFTER DELETE OR UPDATE ON user_team
DECLARE
    var NUMBER;
BEGIN
    SELECT MIN(SUM(is_admin)) INTO var
    FROM user_team
    GROUP BY team_id;

    IF var = 0
        THEN RAISE_APPLICATION_ERROR(-20000, 'NO ADMIN LEFT');
    END IF;
END;
/

CREATE OR REPLACE TRIGGER is_team_nonempty_check
AFTER DELETE OR UPDATE ON user_team
DECLARE
    n_teams NUMBER;
    n_teams_in_ut NUMBER;
BEGIN
    SELECT COUNT(team_id) INTO n_teams
    FROM teams;

    SELECT COUNT(DISTINCT team_id) INTO n_teams_in_ut
    FROM user_team;

    IF n_teams_in_ut < n_teams
        THEN RAISE_APPLICATION_ERROR(-20000, 'EMPTY TEAM');
    END IF;
END;
/


CREATE OR REPLACE TRIGGER is_event_nonempty_check
AFTER DELETE OR UPDATE ON user_event
DECLARE
    n_events NUMBER;
    n_events_in_ue NUMBER;
BEGIN
    SELECT COUNT(event_id) INTO n_events
    FROM events;

    SELECT COUNT(DISTINCT event_id) INTO n_events_in_ue
    FROM user_event;

    IF n_events_in_ue < n_events
        THEN RAISE_APPLICATION_ERROR(-20000, 'EMPTY EVENT');
    END IF;
END;
/

CREATE OR REPLACE TRIGGER is_user_in_team_of_event
BEFORE INSERT OR UPDATE ON user_event
FOR EACH ROW
DECLARE
    var NUMBER;
BEGIN
    SELECT COUNT(*) INTO var
    FROM events NATURAL JOIN
        tasks NATURAL JOIN
        user_team
    WHERE events.event_id = :new.event_id AND
          user_team.user_id = :new.user_id;

    IF var = 0
        THEN RAISE_APPLICATION_ERROR(-20000, 'USER IS NOT PART OF THE TEAM');
    END IF;
END;

