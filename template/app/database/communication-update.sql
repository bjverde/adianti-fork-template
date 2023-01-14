
--- new table/columns of 7.5
CREATE TABLE system_folder (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at date,
    name text not null,
    in_trash char(1),
    system_folder_parent_id int REFERENCES system_folder (id)
);

CREATE TABLE system_folder_user
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_folder_id INTEGER references system_folder(id),
    system_user_id INTEGER
);

CREATE TABLE system_folder_group
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_folder_id INTEGER references system_folder(id),
    system_group_id INTEGER
);

ALTER TABLE system_document add column system_folder_id INTEGER references system_folder(id);
ALTER TABLE system_document add column in_trash char(1);

CREATE TABLE system_document_bookmark (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_document_id INTEGER references system_document(id)
);

CREATE TABLE system_folder_bookmark (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_folder_id INTEGER references system_folder(id)
);

CREATE TABLE system_post (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    title TEXT not NULL,
    content TEXT not NULL,
    created_at timestamp not null,
    active char(1) default 'Y' not null
);

CREATE TABLE system_post_share_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_post_id int REFERENCES system_post (id) not null
);

CREATE TABLE system_post_tag (
    id INTEGER PRIMARY KEY NOT NULL,
    system_post_id int REFERENCES system_post (id) not null,
    tag text not null
);

CREATE TABLE system_post_comment (
    id INTEGER PRIMARY KEY NOT NULL,
    comment TEXT not NULL,
    system_user_id int not null,
    system_post_id int REFERENCES system_post (id) not null,
    created_at timestamp not null
);

CREATE TABLE system_post_like (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_post_id int REFERENCES system_post (id) not null,
    created_at timestamp not null
);

CREATE TABLE system_wiki_page (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at timestamp not null,
    updated_at timestamp,
    title TEXT not null,
    description TEXT not null,
    content TEXT not null,
    active char(1) default 'Y' not null,
    searchable char(1) default 'Y' not null
);

CREATE TABLE system_wiki_tag (
    id INTEGER PRIMARY KEY NOT NULL,
    system_wiki_page_id int REFERENCES system_wiki_page (id) not null,
    tag text not null
);

CREATE TABLE system_wiki_share_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_wiki_page_id int REFERENCES system_wiki_page (id) not null
);

INSERT INTO system_post VALUES(1,1,'Primeira notícia','<p style="text-align: justify; "><span style="font-family: &quot;Source Sans Pro&quot;; font-size: 18px;">﻿</span><span style="font-family: &quot;Source Sans Pro&quot;; font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Id cursus metus aliquam eleifend mi in nulla posuere sollicitudin. Tincidunt nunc pulvinar sapien et ligula ullamcorper. Odio pellentesque diam volutpat commodo sed egestas egestas. Eget egestas purus viverra accumsan in nisl nisi scelerisque. Habitant morbi tristique senectus et netus et malesuada. Vitae ultricies leo integer malesuada nunc vel risus commodo viverra. Vehicula ipsum a arcu cursus. Rhoncus est pellentesque elit ullamcorper dignissim. Faucibus in ornare quam viverra orci sagittis eu. Nisi scelerisque eu ultrices vitae auctor. Tellus cras adipiscing enim eu turpis egestas. Eget lorem dolor sed viverra ipsum nunc aliquet. Neque convallis a cras semper auctor neque. Bibendum ut tristique et egestas. Amet nisl suscipit adipiscing bibendum.</span></p><p style="text-align: justify;"><span style="font-family: &quot;Source Sans Pro&quot;; font-size: 18px;">Mattis nunc sed blandit libero volutpat sed cras ornare. Leo duis ut diam quam nulla. Tempus imperdiet nulla malesuada pellentesque elit eget gravida cum sociis. Non quam lacus suspendisse faucibus. Enim nulla aliquet porttitor lacus luctus accumsan tortor posuere ac. Dignissim enim sit amet venenatis urna. Elit sed vulputate mi sit. Sit amet nisl suscipit adipiscing bibendum est. Maecenas accumsan lacus vel facilisis. Orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Aenean pharetra magna ac placerat vestibulum lectus mauris ultrices eros. Augue lacus viverra vitae congue eu consequat ac felis. Bibendum neque egestas congue quisque egestas diam. Facilisis magna etiam tempor orci eu lobortis elementum. Rhoncus est pellentesque elit ullamcorper dignissim cras tincidunt lobortis. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit amet. Nullam eget felis eget nunc. Nec ullamcorper sit amet risus nullam eget felis. Lacus vel facilisis volutpat est velit egestas dui id.</span></p>','2022-11-03 14:59:39','Y');
INSERT INTO system_post VALUES(2,1,'Segunda notícia','<p style="text-align: justify; "><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ac orci phasellus egestas tellus rutrum. Pretium nibh ipsum consequat nisl vel pretium lectus quam. Faucibus scelerisque eleifend donec pretium vulputate sapien. Mattis molestie a iaculis at erat pellentesque adipiscing commodo elit. Ultricies mi quis hendrerit dolor magna eget. Quam id leo in vitae turpis massa sed elementum tempus. Eget arcu dictum varius duis at consectetur lorem. Quis varius quam quisque id diam. Consequat interdum varius sit amet mattis vulputate. Purus non enim praesent elementum facilisis leo vel fringilla. Nulla facilisi nullam vehicula ipsum a arcu. Habitant morbi tristique senectus et netus et malesuada fames. Risus commodo viverra maecenas accumsan lacus. Mattis molestie a iaculis at erat pellentesque adipiscing commodo elit. Imperdiet proin fermentum leo vel orci porta non pulvinar neque. Massa massa ultricies mi quis hendrerit. Vel turpis nunc eget lorem dolor sed viverra ipsum nunc. Quisque egestas diam in arcu cursus euismod quis.</span></p><p style="text-align: justify; "><span style="font-size: 18px;">Posuere morbi leo urna molestie at elementum eu facilisis. Dolor morbi non arcu risus quis varius quam. Fermentum posuere urna nec tincidunt praesent semper feugiat nibh. Consectetur adipiscing elit ut aliquam purus sit. Gravida cum sociis natoque penatibus et magnis. Sollicitudin aliquam ultrices sagittis orci. Tortor consequat id porta nibh venenatis cras sed felis. Dictumst quisque sagittis purus sit amet volutpat consequat mauris nunc. Arcu dictum varius duis at consectetur. Mauris commodo quis imperdiet massa tincidunt nunc pulvinar. At tellus at urna condimentum mattis pellentesque. Tellus mauris a diam maecenas sed.</span></p>','2022-11-03 15:03:31','Y');

INSERT INTO system_post_share_group VALUES(1,1,1);
INSERT INTO system_post_share_group VALUES(2,2,1);
INSERT INTO system_post_share_group VALUES(3,1,2);
INSERT INTO system_post_share_group VALUES(4,2,2);

INSERT INTO system_post_tag VALUES(1,1,'novidades');
INSERT INTO system_post_tag VALUES(2,2,'novidades');

INSERT INTO system_post_comment VALUES(1,'My first comment',1,2,'2022-11-03 15:22:11');
INSERT INTO system_post_comment VALUES(2,'Another comment',1,2,'2022-11-03 15:22:17');
INSERT INTO system_post_comment VALUES(3,'The best comment',2,2,'2022-11-03 15:23:11');

INSERT INTO system_wiki_page VALUES(1,1,'2022-11-02 15:33:58','2022-11-02 15:35:10','Manual de operações','Este manual explica os procedimentos básicos de operação','<p style="text-align: justify; "><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sapien nec sagittis aliquam malesuada bibendum arcu vitae. Quisque egestas diam in arcu cursus euismod quis. Risus nec feugiat in fermentum posuere urna nec tincidunt praesent. At imperdiet dui accumsan sit amet. Est pellentesque elit ullamcorper dignissim cras tincidunt lobortis. Elementum facilisis leo vel fringilla est ullamcorper. Id porta nibh venenatis cras. Viverra orci sagittis eu volutpat odio facilisis mauris sit. Senectus et netus et malesuada fames ac turpis. Sociis natoque penatibus et magnis dis parturient montes. Vel turpis nunc eget lorem dolor sed viverra ipsum nunc. Sed viverra tellus in hac habitasse. Tellus id interdum velit laoreet id donec ultrices tincidunt arcu. Pharetra et ultrices neque ornare aenean euismod elementum. Volutpat blandit aliquam etiam erat velit scelerisque in. Neque aliquam vestibulum morbi blandit cursus risus. Id consectetur purus ut faucibus pulvinar elementum.</span></p><p style="text-align: justify; "><br></p>','Y','Y');
INSERT INTO system_wiki_page VALUES(2,1,'2022-11-02 15:35:04','2022-11-02 15:37:49','Instruções de lançamento','Este manual explica as instruções de lançamento de produto','<p><span style="font-size: 18px;">Non curabitur gravida arcu ac tortor dignissim convallis. Nunc scelerisque viverra mauris in aliquam sem fringilla ut morbi. Nunc eget lorem dolor sed viverra. Et odio pellentesque diam volutpat commodo sed egestas. Enim lobortis scelerisque fermentum dui faucibus in ornare quam viverra. Faucibus et molestie ac feugiat. Erat velit scelerisque in dictum non consectetur a erat nam. Quis risus sed vulputate odio ut enim blandit volutpat. Pharetra vel turpis nunc eget lorem dolor sed viverra. Nisl tincidunt eget nullam non nisi est sit. Orci phasellus egestas tellus rutrum tellus pellentesque eu. Et tortor at risus viverra adipiscing at in tellus integer. Risus ultricies tristique nulla aliquet enim. Ac felis donec et odio pellentesque diam volutpat commodo sed. Ut morbi tincidunt augue interdum. Morbi tempus iaculis urna id volutpat.</span></p><p><a href="index.php?class=SystemWikiView&amp;method=onLoad&amp;key=3" generator="adianti">Sub página de instruções 1</a></p><p><a href="index.php?class=SystemWikiView&amp;method=onLoad&amp;key=4" generator="adianti">Sub página de instruções 2</a><br><span style="font-size: 18px;"><br></span><br></p>','Y','Y');
INSERT INTO system_wiki_page VALUES(3,1,'2022-11-02 15:36:59','2022-11-02 15:37:21','Instruções - sub página 1','Instruções - sub página 1','<p><span style="font-size: 18px;">Follow these steps:</span></p><ol><li><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span></li><li><span style="font-size: 18px;">Sapien nec sagittis aliquam malesuada bibendum arcu vitae.</span></li><li><span style="font-size: 18px;">Quisque egestas diam in arcu cursus euismod quis.</span><br></li></ol>','Y','N');
INSERT INTO system_wiki_page VALUES(4,1,'2022-11-02 15:37:17','2022-11-02 15:37:22','Instruções - sub página 2','Instruções - sub página 2','<p><span style="font-size: 18px;">Follow these steps:</span></p><ol><li><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span></li><li><span style="font-size: 18px;">Sapien nec sagittis aliquam malesuada bibendum arcu vitae.</span></li><li><span style="font-size: 18px;">Quisque egestas diam in arcu cursus euismod quis.</span></li></ol>','Y','N');

INSERT INTO system_wiki_tag VALUES(3,1,'manual');
INSERT INTO system_wiki_tag VALUES(5,4,'manual');
INSERT INTO system_wiki_tag VALUES(6,3,'manual');
INSERT INTO system_wiki_tag VALUES(7,2,'manual');

INSERT INTO system_wiki_share_group VALUES(1,1,1);
INSERT INTO system_wiki_share_group VALUES(2,2,1);
INSERT INTO system_wiki_share_group VALUES(3,1,2);
INSERT INTO system_wiki_share_group VALUES(4,2,2);
INSERT INTO system_wiki_share_group VALUES(5,1,3);
INSERT INTO system_wiki_share_group VALUES(6,2,3);
INSERT INTO system_wiki_share_group VALUES(7,1,4);
INSERT INTO system_wiki_share_group VALUES(8,2,4);
