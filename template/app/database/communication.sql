--- Create system_notification table
CREATE TABLE system_notification (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_user_to_id int,
    subject varchar(256),
    message text,
    dt_message varchar(20),
    action_url text,
    action_label varchar(256),
    icon varchar(100),
    checked char(1)
);

--- Create system_message table
CREATE TABLE system_message (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_user_to_id int,
    subject varchar(256),
    message text,
    dt_message varchar(20),
    checked char(1),
    removed char(1),
    viewed char(1),
    attachments text
);

--- Create system_message_tag table
CREATE TABLE system_message_tag (
    id int PRIMARY KEY NOT NULL,
    system_message_id int not null REFERENCES system_message (id),
    tag varchar(256) not null
);

--- Create system_folder table
CREATE TABLE system_folder (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at varchar(20),
    name varchar(256) not null,
    in_trash char(1),
    system_folder_parent_id int REFERENCES system_folder (id)
);

--- Create system_folder_user table
CREATE TABLE system_folder_user (
    id int PRIMARY KEY NOT NULL,
    system_folder_id int REFERENCES system_folder(id),
    system_user_id int
);

--- Create system_folder_group table
CREATE TABLE system_folder_group (
    id int PRIMARY KEY NOT NULL,
    system_folder_id int REFERENCES system_folder(id),
    system_group_id int
);

--- Create system_document table
CREATE TABLE system_document (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    title varchar(256),
    description text,
    submission_date date,
    archive_date date,
    filename varchar(512),
    in_trash char(1),
    system_folder_id int REFERENCES system_folder(id),
    content text,
    content_type varchar(100)
);

--- Create system_document_user table
CREATE TABLE system_document_user (
    id int PRIMARY KEY NOT NULL,
    document_id int REFERENCES system_document(id),
    system_user_id int
);

--- Create system_document_group table
CREATE TABLE system_document_group (
    id int PRIMARY KEY NOT NULL,
    document_id int REFERENCES system_document(id),
    system_group_id int
);

--- Create system_document_bookmark table
CREATE TABLE system_document_bookmark (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_document_id int REFERENCES system_document(id)
);

--- Create system_folder_bookmark table
CREATE TABLE system_folder_bookmark (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_folder_id int REFERENCES system_folder(id)
);

--- Create system_post table
CREATE TABLE system_post (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    title varchar(256) not NULL,
    content text not NULL,
    created_at varchar(20),
    updated_at varchar(20),
    updated_by int,
    active char(1) default 'Y' not null
);

--- Create system_post_share_group table
CREATE TABLE system_post_share_group (
    id int PRIMARY KEY NOT NULL,
    system_group_id int,
    system_post_id int NOT NULL REFERENCES system_post (id)
);

--- Create system_post_tag table
CREATE TABLE system_post_tag (
    id int PRIMARY KEY NOT NULL,
    system_post_id int not null REFERENCES system_post (id),
    tag varchar(256) not null
);

--- Create system_post_comment table
CREATE TABLE system_post_comment (
    id int PRIMARY KEY NOT NULL,
    comment text not NULL,
    system_user_id int not null,
    system_post_id int not null REFERENCES system_post (id),
    created_at varchar(20)
);

--- Create system_post_like table
CREATE TABLE system_post_like (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_post_id int not null REFERENCES system_post (id),
    created_at varchar(20)
);

--- Create system_wiki_page table
CREATE TABLE system_wiki_page (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at varchar(20),
    updated_at varchar(20),
    title varchar(256) not null,
    description varchar(256),
    content text not null,
    active char(1) default 'Y' not null,
    searchable char(1) default 'Y' not null,
    updated_by int
);

--- Create system_wiki_tag table
CREATE TABLE system_wiki_tag (
    id int PRIMARY KEY NOT NULL,
    system_wiki_page_id int not null REFERENCES system_wiki_page (id),
    tag varchar(256) not null
);

--- Create system_wiki_share_group table
CREATE TABLE system_wiki_share_group (
    id int PRIMARY KEY NOT NULL,
    system_group_id int,
    system_wiki_page_id int not null REFERENCES system_wiki_page (id)
);

--- Create system_schedule table
CREATE TABLE system_schedule (
    id int PRIMARY KEY NOT NULL,
    schedule_type char(1),
    title varchar(256),
    class_name varchar(256),
    method varchar(256),
    monthday char(2),
    weekday char(1),
    hour char(2),
    minute char(2),
    active char(1)
);

--- Create indexes
CREATE INDEX sys_notification_user_id_idx ON system_notification(system_user_id);
CREATE INDEX sys_notification_user_to_idx ON system_notification(system_user_to_id);


CREATE INDEX sys_message_user_id_idx ON system_message(system_user_id);
CREATE INDEX sys_message_user_to_idx ON system_message(system_user_to_id);
CREATE INDEX sys_folder_user_id_idx ON system_folder(system_user_id);
CREATE INDEX sys_folder_name_idx ON system_folder(name);
CREATE INDEX sys_folder_parend_id_idx ON system_folder(system_folder_parent_id);
CREATE INDEX sys_folder_user_folder_idx ON system_folder_user(system_folder_id);
CREATE INDEX sys_folder_user_user_idx ON system_folder_user(system_user_id);
CREATE INDEX sys_folder_group_folder_idx ON system_folder_group(system_folder_id);
CREATE INDEX sys_folder_group_group_idx ON system_folder_group(system_group_id);
CREATE INDEX sys_document_user_idx ON system_document(system_user_id);
CREATE INDEX sys_document_folder_idx ON system_document(system_folder_id);
CREATE INDEX sys_document_user_document_idx ON system_document_user(document_id);
CREATE INDEX sys_document_user_user_idx ON system_document_user(system_user_id);
CREATE INDEX sys_document_group_document_idx ON system_document_group(document_id);
CREATE INDEX sys_document_group_group_idx ON system_document_group(system_group_id);
CREATE INDEX sys_document_bookmark_user_idx ON system_document_bookmark(system_user_id);
CREATE INDEX sys_document_bookmark_document_idx ON system_document_bookmark(system_document_id);
CREATE INDEX sys_folder_bookmark_user_idx ON system_folder_bookmark(system_user_id);
CREATE INDEX sys_folder_bookmark_folder_idx ON system_folder_bookmark(system_folder_id);
CREATE INDEX sys_post_user_idx ON system_post(system_user_id);
CREATE INDEX sys_post_share_group_group_idx ON system_post_share_group(system_group_id);
CREATE INDEX sys_post_share_group_post_idx ON system_post_share_group(system_post_id);
CREATE INDEX sys_post_tag_post_idx ON system_post_tag(system_post_id);
CREATE INDEX sys_post_comment_user_idx ON system_post_comment(system_user_id);
CREATE INDEX sys_post_comment_post_idx ON system_post_comment(system_post_id);
CREATE INDEX sys_post_like_user_idx ON system_post_like(system_user_id);
CREATE INDEX sys_post_like_post_idx ON system_post_like(system_post_id);
CREATE INDEX sys_wiki_page_user_idx ON system_wiki_page(system_user_id);
CREATE INDEX sys_wiki_tag_page_idx ON system_wiki_tag(system_wiki_page_id);
CREATE INDEX sys_wiki_share_group_group_idx ON system_wiki_share_group(system_group_id);
CREATE INDEX sys_wiki_share_group_page_idx ON system_wiki_share_group(system_wiki_page_id);
CREATE INDEX sys_message_tag_msg_idx ON system_message_tag(system_message_id);
