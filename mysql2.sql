
DROP DATABASE Factory;

CREATE DATABASE IF NOT EXISTS Factory;


USE Factory;

DROP TABLE IF EXISTS Staff;
CREATE TABLE Staff(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
   user_name varchar(20),
   password varchar(4),
   role varchar(20),
   access_level varchar(1)
)
 AUTO_INCREMENT = 1;


INSERT INTO Staff(user_name,password,role,access_level) VALUES('Visitor','4321','auditor','4');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Xi','4321','administrator (CEO)','1');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Tony','4321','manager','2');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Elke','4321','manager','2');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Timmy','4321','operator','3');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Tammy','4321','operator','3');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Sally','4321','operator','3');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Tomi','4321','operator','3');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Janusz','4321','operator','3');
INSERT INTO Staff(user_name,password,role,access_level) VALUES('Remo','4321','operator','3');

DROP TABLE IF EXISTS Messages;

CREATE TABLE IF NOT EXISTS Messages (
    message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    body TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES Staff(id),
    FOREIGN KEY (receiver_id) REFERENCES Staff(id)
);



DROP TABLE IF EXISTS Machines;
DROP TABLE IF EXISTS MachineStatus;

CREATE TABLE MachineStatus(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  status_name VARCHAR(20) NOT NULL
) AUTO_INCREMENT = 1;

CREATE TABLE Machines(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  machine_name VARCHAR(40) NOT NULL,
  machine_status_id INT NOT NULL,
  additional_info TEXT,
  FOREIGN KEY (machine_status_id) REFERENCES MachineStatus(id)
) AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS MachineAssign;
CREATE TABLE MachineAssign(
  assign_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  machine_id INT NOT NULL,
  staff_id INT NOT NULL,
  assign_date DATETIME NOT NULL,
  release_date DATETIME,
  timer_1 INT,
  timer_2 INT,
  counter_1 INT,
  speed  INT,
  FOREIGN KEY (machine_id) REFERENCES Machines(id),
  FOREIGN KEY (staff_id) REFERENCES Staff(id)
) AUTO_INCREMENT = 1;

INSERT INTO MachineStatus(status_name) 
VALUES ('in-use'), ('retired');

INSERT INTO Machines(machine_name, machine_status_id) 
VALUES ('CNC Machine 1', 1),
       ('CNC Machine 1A', 1),
       ('CNC Machine 2', 1),
       ('CNC Machine 3', 1),
       ('CNC Machine 4', 1),
       ('CNC Machine 5', 1),
       ('CNC Machine 3D', 1),
       ('CNC Machine 4D 1', 1),
       ('CNC Machine 4D 2', 1),
       ('CNC Machine 4D 3', 1),
       ('CNC Machine 4D 4', 1),
       ('CNC Machine 4D 5', 1),
       ('CNC Machine 4D 6', 1),
       ('Small Parts Wash', 2),
       ('3D Printer', 1);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 5, '2023-09-01 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 1
(2, 5, '2023-09-02 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 1A
(3, 5, '2023-09-03 08:00:00', NULL, 1, 2, 3, 6),  -- Timmy assigned to CNC Machine 2
(4, 5, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 3
(5, 5, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 4
(6, 5, '2023-09-06 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 5
(7, 5, '2023-09-07 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 3D
(8, 5, '2023-09-08 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 4D 1
(9, 5, '2023-09-09 08:00:00', NULL, 1, 2, 3, 4),  -- Timmy assigned to CNC Machine 4D 2
(10, 5, '2023-09-10 08:00:00', NULL, 1, 2, 3, 4), -- Timmy assigned to CNC Machine 4D 3
(11, 10, '2023-09-11 08:00:00', '2023-09-20 17:00:00', 1, 2, 3, 4), -- Remo assigned to CNC Machine 4D 4
(12, 6, '2023-09-12 08:00:00', NULL, 1, 2, 3, 6), -- Tammy assigned to CNC Machine 4D 5
(13, 7, '2023-09-13 08:00:00', NULL, 1, 2, 3, 4), -- Sally assigned to CNC Machine 4D 6
(14, 9, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4), -- Janusz assigned to Small Parts Wash
(15, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4); -- Tomi assigned to 3D Printer

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 1, '2023-09-02 08:00:00', NULL, 1, 2, 3, 4),
(2, 1, '2023-09-02 08:00:00', NULL, 1, 2, 3, 4),
(3, 1, '2023-09-02 08:00:00', NULL, 1, 2, 3, 6),
(4, 1, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 1, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(6, 1, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(7, 1, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(8, 1, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(9, 1, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 2, '2023-09-02 08:00:00', NULL, 1, 2, 3, 4),
(2, 2, '2023-09-02 08:00:00', NULL, 1, 2, 3, 4),
(3, 2, '2023-09-02 08:00:00', NULL, 1, 2, 3, 6),
(4, 2, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 2, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(6, 2, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(7, 2, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(8, 2, '2023-09-03 08:00:00', NULL, 1, 2, 3, 4),
(9, 2, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 3, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(2, 3, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(3, 3, '2023-09-04 08:00:00', NULL, 1, 2, 3, 6),
(4, 3, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 3, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(6, 3, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(7, 3, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(8, 3, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(9, 3, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 4, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(2, 4, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(3, 4, '2023-09-04 08:00:00', NULL, 1, 2, 3, 6),
(4, 4, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 4, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(6, 4, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(7, 4, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(8, 4, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(9, 4, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 6, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(2, 6, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(3, 6, '2023-09-04 08:00:00', NULL, 1, 2, 3, 6),
(4, 6, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 6, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(6, 6, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(7, 6, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(8, 6, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(9, 6, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 7, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(2, 7, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(3, 7, '2023-09-04 08:00:00', NULL, 1, 2, 3, 6),
(4, 7, '2023-09-04 08:00:00', NULL, 1, 2, 3, 4),
(5, 7, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(6, 7, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(7, 7, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(8, 7, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4),
(9, 7, '2023-09-05 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 8, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(2, 8, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(3, 8, '2023-09-14 08:00:00', NULL, 1, 2, 3, 6),
(4, 8, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(5, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(6, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(7, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(8, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(9, 8, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4);

INSERT INTO MachineAssign(machine_id, staff_id, assign_date, release_date, timer_1, timer_2, counter_1, speed) 
VALUES 
(1, 9, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(2, 9, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(3, 9, '2023-09-14 08:00:00', NULL, 1, 2, 3, 6),
(4, 9, '2023-09-14 08:00:00', NULL, 1, 2, 3, 4),
(5, 9, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(6, 9, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(7, 9, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(8, 9, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4),
(9, 9, '2023-09-15 08:00:00', NULL, 1, 2, 3, 4);


