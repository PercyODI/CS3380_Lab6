-- The double dashes are comments within .sql files
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS university;
CREATE TABLE university (
    uid integer AUTO_INCREMENT PRIMARY KEY,
    university_name varchar(50),
    city varchar(50)
) ENGINE = INNODB;

DROP TABLE IF EXISTS person;
CREATE TABLE person (
    pid integer AUTO_INCREMENT PRIMARY KEY,
    uid integer,
    fname varchar(25) NOT NULL,
    lname varchar(25) NOT NULL,
    FOREIGN KEY (uid) REFERENCES university (uid) ON DELETE CASCADE
) ENGINE = INNODB;

DROP TABLE IF EXISTS activity;
CREATE TABLE activity (
    activity_name varchar(50) PRIMARY KEY
) ENGINE = INNODB; 

DROP TABLE IF EXISTS participated_in;
CREATE TABLE participated_in (
    pid integer,
    activity_name varchar(50),
    activity_date date,
    activity_duration integer,
    PRIMARY KEY(pid, activity_name, activity_date),
    FOREIGN KEY (activity_name) REFERENCES activity (activity_name),
    FOREIGN KEY (pid) REFERENCES person (pid)
) ENGINE = INNODB;

DROP TABLE IF EXISTS body_composition;
CREATE TABLE body_composition (
    pid integer PRIMARY KEY, 
    height integer NOT NULL,
    weight integer NOT NULL,
    age integer NOT NULL,
    FOREIGN KEY (pid) REFERENCES person (pid) ON DELETE CASCADE
) ENGINE = INNODB;
SET FOREIGN_KEY_CHECKS=1;


INSERT INTO university VALUES (11, 'University of Cincinnati', 'Cincinnati');
INSERT INTO university VALUES (21, 'University of Missouri Columbia', 'Columbia');
INSERT INTO university VALUES (31, 'Stanford University
', 'Stanford');
INSERT INTO university VALUES (41, 'Yale University
', 'New Haven');
INSERT INTO university VALUES (51, 'Ohio State University', 'Columbus');
INSERT INTO university VALUES (61, 'University of Alabama', 'Tuscaloosa');
INSERT INTO university VALUES (71, 'Missouri University of Science and Technology', 'Rolla');
INSERT INTO university VALUES (81, 'University of Georgia', 'Athens');
INSERT INTO university VALUES (91, 'Columbia College', 'Columbia');


INSERT INTO person VALUES (11, 21, 'Bob', 'Smith');
INSERT INTO person VALUES (21, 31, 'Aaron', 'Gates');
INSERT INTO person VALUES (31, 11, 'Justin', 'Long');
INSERT INTO person VALUES (41, 51, 'Steve', 'Rogers');
INSERT INTO person VALUES (51, 41, 'Alan', 'Belshore');
INSERT INTO person VALUES (61, 81, 'Fred', 'Bier');
INSERT INTO person VALUES (71, 91, 'Andrew', 'Watson');
INSERT INTO person VALUES (81, 91, 'Adam', 'Shore');
INSERT INTO person VALUES (91, 21, 'Alan', 'Lewis');
INSERT INTO person VALUES (101, 51, 'Bette', 'Lueking');
INSERT INTO person VALUES (111, 41, 'Kent', 'Jobs');
INSERT INTO person VALUES (121, 31, 'James', 'Ives');



INSERT INTO activity VALUES ('weightlifting');
INSERT INTO activity VALUES ('running');
INSERT INTO activity VALUES ('racquetball');
INSERT INTO activity VALUES ('baseball');
INSERT INTO activity VALUES ('biking');
INSERT INTO activity VALUES ('soccer');
INSERT INTO activity VALUES ('handball');
INSERT INTO activity VALUES ('tennis');



INSERT INTO participated_in VALUES 
(11, 'biking', '2014-04-13', 43),
(11, 'soccer', '2014-04-17', 77),
(21, 'weightlifting', '2014-04-27', 33),
(21, 'soccer', '2014-05-01', 41),
(31, 'weightlifting', '2014-05-06', 58),
(31, 'running', '2014-05-09', 41),
(41, 'biking', '2014-05-15', 131),
(41, 'soccer', '2014-05-21', 60),
(51, 'baseball', '2014-06-01', 95),
(61, 'racquetball', '2014-06-04', 48),
(61, 'soccer', '2014-06-07', 81),
(71, 'weightlifting', '2014-06-11', 71),
(71, 'soccer', '2014-06-17', 47),
(81, 'racquetball', '2014-06-23', 33),
(81, 'running', '2014-06-27', 24),
(91, 'soccer', '2014-07-02', 56),
(101, 'running', '2014-07-05', 78),
(111, 'racquetball', '2014-07-08', 61),
(121, 'biking', '2014-07-11', 123)
;



INSERT INTO body_composition VALUES (11, 71, 130, 22);
INSERT INTO body_composition VALUES (21, 68, 170, 19);
INSERT INTO body_composition VALUES (31, 85, 230, 21);
INSERT INTO body_composition VALUES (41, 60, 120, 33);
INSERT INTO body_composition VALUES (51, 80, 233, 31);
INSERT INTO body_composition VALUES (61, 70, 177, 16);
INSERT INTO body_composition VALUES (71, 71, 155, 20);
INSERT INTO body_composition VALUES (81, 75, 250, 51);
INSERT INTO body_composition VALUES (91, 76, 225, 65);
INSERT INTO body_composition VALUES (101, 65, 175, 45);
INSERT INTO body_composition VALUES (111, 60, 125, 67);
INSERT INTO body_composition VALUES (121, 55, 120, 42);
