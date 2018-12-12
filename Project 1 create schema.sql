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
                         status integer,
						primary key(user1, user2),
                        FOREIGN KEY (user1) REFERENCES Users(uid) on DELETE CASCADE,
                        foreign key (user2) references Users(uid) on delete CASCADE);

drop table if exists Schedules;
create table Schedule(schedule_id integer AUTO_INCREMENT Primary Key,
						sdate date,
						startime time,
						endtime time
						);

drop table if exists Repeats;
create table Repeats (repeat_id integer,
                    daynum integer,
                    primary key(repeat_id, daynum));

drop table if exists RepeatSchedule;
Create table RepeatSchedule(schedule_id integer,
							repeat_id integer,
							primary key (schedule_id, repeat_id),
							foreign key (schedule_id) references Schedule(schedule_id) on DELETE cascade,
							foreign key (repeat_id) references Repeats(repeat_id) on DELETE cascade);

drop table if exists Note;
create table Note (nid integer AUTO_INCREMENT primary key,
                   nuid integer,
                   place_id varchar(255) ,
                   nradius integer,
                   schedule_id integer,
                   nvisibility varchar(10),
                   ncontent varchar(255),
                   FOREIGN key (nuid) REFERENCES Users(uid) on delete CASCADE,
									 Foreign key (schedule_id) references Schedule(schedule_id) on delete CASCADE,
                   FOREIGN key (place_id) REFERENCES Location(place_id) on delete CASCADE);

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
                    ftag integer,
                    fstate varchar(50),
                    place_id varchar(255),
                    fradius integer,
                    fdate date,
                    fstarttime time,
                    fendtime time,
                    fvisibility varchar(10),
                    fname varchar(50),
                    foreign key (fuid) references Users(uid) on delete cascade,
                    foreign key (ftag) references Tag(tid) on delete cascade);


create temporary table AllNote 
select nid, nuid, place_name, address, lat, lng, nradius, nvisibility, ncontent, sdate,startime, endtime, daynum, tagname from Note 
natural join location 
natural join schedule
natural join notetag 
natural join tag 
left join repeatschedule on repeatschedule.schedule_id = Note.schedule_id
left join repeats on repeatschedule.repeat_id = repeats.repeat_id;

create TEMPORARY table AllFilter
select fid, ftag, fuid, tagname, fstate, lat,lng, ulatt, ulong, fradius, fdate, fstarttime, fendtime, utime, fvisibility, fname, ustate, tid,user2
from filter
natural join location
join Users on fuid = uid
join tag on tid = ftag
left join Friendship on fuid = user1
where fuid = 1;


select distinct AllNote.nid, nuid, place_name, ncontent, AllNote.lat lat, AllNote.lng lng, ftag, tid, ustate, fstate, nvisibility, fvisibility
from AllNote
join AllFilter
where mydistance(ulatt, ulong, AllFilter.lat, AllFilter.lng) <= fradius
and mydistance(ulatt,ulong, AllNote.lat, AllFilter.lng) <= nradius
And Date(utime) = fdate And time(utime) between fstarttime And fendtime
And (Date(utime) = sdate or dayofweek(utime) = daynum)
And ((ftag = tid and ustate = fstate) Or (ftag=tid and fstate = null) Or (ftag=null And fstate=null) Or (ftag = null And fstate=ustate))
And ((fvisibility= 'everyone' And (nvisibility='everyone' Or (nvisibility = 'friend' And nuid=user2) Or (nvisibility = 'private' And nuid=fuid)))
Or (fvisibility='friend' And nuid=user2 And (nvisibility= 'everyone' Or nvisibility= 'friend')));