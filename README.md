# PBL2024_1A

CREATE TABLE IF NOT EXISTS members (
    employeenumber VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(255),
    password VARCHAR(100),
    position VARCHAR(100),
    created DATETIME
);


INSERT INTO members (employeenumber, name, password, position, created) VALUES
('001', 'admin', '8931', 'admin', '2024-11-29 19:46:55'),
('002', 'user', '8931', 'user', '2024-11-30 19:02:19');
