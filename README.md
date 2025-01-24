# PBL2024_1A

CREATE TABLE IF NOT EXISTS members (
    employeenumber VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(255),
    password VARCHAR(100),
    department VARCHAR(50),
    position VARCHAR(100),
    created DATETIME
);


INSERT INTO members (employeenumber, name, password, department, position, created) VALUES
('001', '統括', '8931', 'デジタル報道部配信班', 'admin', '2024-11-29 19:46:55'),
('002', '部員A', '8931', 'デジタル報道部配信班', 'user', '2024-11-30 19:02:19');



初期値情報  
ーーーーーーーーー  
社員番号　001  
名前　統括
パスワード 8931  
部署　デジタル報道部配信班
役職　admin(管理者)  
ーーーーーーーーー  
ーーーーーーーーー  
社員番号 002  
名前 部員A  
パスワード 8931  
部署　デジタル報道部配信班
役職 user(一般社員)  
ーーーーーーーーー
