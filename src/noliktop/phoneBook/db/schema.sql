create table phones
(
    id    int unsigned not null auto_increment primary key,
    phone varchar(15)  not null unique
);

create table users
(
    id   int unsigned not null auto_increment primary key,
    name varchar(32)  not null
);

create table reviews
(
    id         int unsigned not null auto_increment primary key,
    phone_id   int unsigned not null,
    user_id    int unsigned null,
    review     text         not null,
    created_at timestamp    not null default current_timestamp,

    foreign key (phone_id) references phones (id),
    foreign key (user_id) references users (id)
);

create table reviews_marks
(
    review_id int unsigned not null,
    user_id   int unsigned not null,
    mark      tinyint      not null check (mark = -1 or mark = 1),

    primary key (review_id, user_id),
    foreign key (review_id) references reviews (id),
    foreign key (user_id) references users (id)
);

create table tokens
(
    token   varchar(16)  not null primary key,
    user_id int unsigned not null,

    foreign key (user_id) references users (id)
);