DROP TABLE user_event;
DROP TABLE user_team;
DROP TABLE events;
DROP TABLE tasks;
DROP TABLE teams;
DROP TABLE users;

CREATE TABLE users(
user_id NUMBER(10) PRIMARY KEY,
name VARCHAR(20) NOT NULL UNIQUE,
password VARCHAR(20) NOT NULL,
email VARCHAR(30) CHECK ( REGEXP_LIKE(email, '.*@.*') )
);

CREATE TABLE teams(
team_id NUMBER(10) PRIMARY KEY, -- auto incremented
name VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE tasks(
task_id NUMBER(10) PRIMARY KEY, -- auto incremented
name VARCHAR(30) NOT NULL,
team_id NUMBER(10) NOT NULL REFERENCES teams,
father_id NUMBER(10) REFERENCES tasks -- NULL iff root
);
ALTER TABLE tasks
ADD CONSTRAINT name_tid_unique UNIQUE (name, team_id); -- no 2 tasks are named the same in one team

CREATE TABLE events(
event_id NUMBER(10) PRIMARY KEY, -- auto incremented
e_from DATE NOT NULL,
e_to DATE NOT NULL,
task_id NUMBER(10) NOT NULL REFERENCES tasks
);

CREATE TABLE user_event(
user_id NUMBER(10) NOT NULL REFERENCES users,
event_id NUMBER(10) NOT NULL REFERENCES events,
PRIMARY KEY (user_id, event_id)
);

CREATE TABLE user_team(
user_id NUMBER(10) NOT NULL REFERENCES users,
team_id NUMBER(10) NOT NULL REFERENCES teams,
is_admin NUMBER(1) NOT NULL CHECK(is_admin IN (0,1)), -- 0 IFF FALSE, 1 IFF TRUE
PRIMARY KEY (user_id, team_id)
);
