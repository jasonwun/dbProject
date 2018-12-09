drop table if exists Location;
create table Location(place_id varchar(255) Primary Key,
						place_name varchar(255),
						address varchar(255),
						lat double,
						lng double);


drop table if exists Users;
create table Users (uid integer AUTO_INCREMENT PRIMARY KEY,
                    username varchar(255),
                    email varchar(255),
                    password varchar(255),
                    ulatt double null,
                    ulong double null,
                    utime datetime null,
                    ustate varchar(255) null);

drop table if exists Friendship;
create table Friendship (user1 integer,
                         user2 integer,
						primary key(user1, user2),
                        FOREIGN KEY (user1) REFERENCES Users(uid) on DELETE CASCADE,
                        foreign key (user2) references Users(uid) on delete CASCADE);

drop table if exists Schedule;
create table Schedule(schedule_id integer AUTO_INCREMENT Primary Key,
						sdate date,
						startime time,
						endtime time
						);

drop table if exists Repeat;
create table Repeat (repeat_id integer Primary key AUTO_INCREMENT,
                    daynum integer,
					period integer);

drop table if exists RepeatSchedule;
Create table RepeatSchedule(schedule_id integer,
							repeat_id integer,
							primary key (schedule_id, repeat_id),
							foreign key (schedule_id) references Schedule(schedule_id) on DELETE cascade,
							foreign key (repeat_id) references Repeat(repeat_id) on DELETE cascade);

drop table if exists Note;
create table Note (nid integer AUTO_INCREMENT primary key,
                   nuid integer,
                   place_id varchar(255) ,
                   nradius integer,
                   nschedule integer,
                   nvisibility varchar(10),
                   ncontent varchar(255),
                   FOREIGN key (nuid) REFERENCES Users(uid) on delete CASCADE,
                   FOREIGN key (nschedule) REFERENCES schedule(schedule_id) on delete CASCADE);

drop table if exists RepeatNote;
create table RepeatNote (nid integer,
                         daynum integer,
                         foreign key (nid) references Note(nid) on DELETE cascade);

drop table if exists Comment;
create table Comment (uid integer,
                      nid integer,
                      content varchar(255),
                      createtime datetime,
                      primary key (uid, nid, createtime),
                      FOREIGN KEY (uid) REFERENCES Users(uid) on delete cascade,
                      FOREIGN KEY (nid) references Note(nid) on delete cascade);

drop table if exists Tag;
create table Tag (tid integer AUTO_INCREMENT primary key,
                  tagname varchar(255));

drop table if exists NoteTag;
create table NoteTag (nid integer,
                      tid integer,
                      FOREIGN key (nid) REFERENCES Note(nid) on delete cascade,
                      FOREIGN KEY (tid) REFERENCES Tag(tid) on delete cascade);

drop table if exists Filter;
create table Filter(fid integer AUTO_INCREMENT primary key,
                    fuid integer,
                    ftag integer null,
                    fstate varchar(50) null,
                    flati double null,
                    flong double null,
                    fradius integer null,
                    fdate date null,
                    fstarttime time null,
                    fendtime time null,
                    fvisibility varchar(10),
                    foreign key (fuid) references Users(uid) on delete cascade,
                    foreign key (ftag) references Tag(tid) on delete cascade);
