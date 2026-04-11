create database `corpstarter`;
CREATE USER 'corpstarter'@'localhost' IDENTIFIED BY 'Po@rmsoigshknn549073';
GRANT ALL PRIVILEGES ON *.* TO 'corpstarter'@'localhost';

-- symfony console doctrine:schema:create
-- Une fois la migration faite
INSERT into user_types(name,creation_date,can_accept_project)
VALUES ("Admin",now(),true),("User",now(),false);

insert into project_status(creation_date ,status_name, validated)
VALUES (now(),"Pending", false),(now(),"Approved", true),(now(),"Rejected", false);