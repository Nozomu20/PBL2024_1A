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



初期値情報  
ーーーーーーーーー  
社員番号　001  
名前　admin  
パスワード 8931  
役職　admin(管理者)  
ーーーーーーーーー  
ーーーーーーーーー  
社員番号 002  
名前 user  
パスワード 8931  
役職 user(一般社員)  
ーーーーーーーーー

長町悟志(j317naga)

・inputinfo.html
お知らせの入力と確認を行う画面です。
・editinfo.html
お知らせの編集と確認を行う画面です。
・info.css
inputinfo.htmlとeditinfo.htmlのスタイルを変更するcssファイルです。
