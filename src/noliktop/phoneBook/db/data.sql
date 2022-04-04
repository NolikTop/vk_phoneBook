insert into phones (phone)
values ('79119001234'),
       ('78005553535');
insert into users (name)
values ('aydan'),
       ('obama'),
       ('tramp'),
       ('bidon');

insert into reviews (phone_id, user_id, review)
values (1, 1, 'spam'),
       (1, null, 'telefonniy spam'),
       (1, null, 'test');

insert into reviews_marks (review_id, user_id, mark)
VALUES (1, 1, 1),
       (1, 2, 1),
       (2, 1, -1),
       (2, 2, 1),
       (2, 3, -1);

insert into tokens (token, user_id) values ('abcdef', 1);