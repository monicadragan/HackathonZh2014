CREATE TABLE Domains(
ID int NOT NULL AUTO_INCREMENT,
DomainName varchar(1000) NOT NULL UNIQUE,
PRIMARY KEY (ID)
);

CREATE TABLE Courses(
ID int NOT NULL AUTO_INCREMENT,
CourseName varchar(1000) NOT NULL UNIQUE,
PRIMARY KEY (ID)
);

CREATE TABLE CoursesAndDomains(
ID int NOT NULL AUTO_INCREMENT,
IDDomain int NOT NULL,
IDCourse int NOT NULL,
FOREIGN KEY (IDDomain) REFERENCES Domains(ID),
FOREIGN KEY (IDCourse) REFERENCES Courses(ID),
);

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("Bernoulli distribution", "http://www.statlect.com/Bernoulli_distribution.htm", 1, "110101");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("Expectation-maximization algorithm", "http://www.nature.com/nbt/journal/v26/n8/full/nbt1406.html", 1, "110101");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("Visual receptive fields", "http://www.sumanasinc.com/webcontent/animations/content/receptivefields.html", 2, "110101");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("Bipolar Cells", "https://www.youtube.com/watch?v=1D_nIIevdzc ", 2, "110101");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("The Monty Hall Problem in Statistics", "http://ed.ted.com/featured/PWb09pny", 3, "101001");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("A neural portrait of the human mind", "http://www.ted.com/talks/nancy_kanwisher_the_brain_is_a_swiss_army_knife", 2, "101010");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("Normal distribution", "http://www.statlect.com/ucdnrm1.htm", 1, "110101");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("", "", , "");

INSERT INTO RESOURCES (Title, Link, AuthorId, Fingerprint)
VALUES ("", "", , "");



CREATE TABLE UNIVERSITIES(
ID int NOT NULL AUTO_INCREMENT,
Name varchar(1000) NOT NULL,
PRIMARY KEY (ID)
);

INSERT INTO UNIVERSITIES (Name) VALUES ("EPFL");
INSERT INTO UNIVERSITIES (Name) VALUES ("ETHZ");
INSERT INTO UNIVERSITIES (Name) VALUES ("UPB");
INSERT INTO UNIVERSITIES (Name) VALUES ("UniBo");

CREATE TABLE DOMAINS(
ID int NOT NULL AUTO_INCREMENT,
Name varchar(1000) NOT NULL,
PRIMARY KEY (ID)
);

INSERT INTO DOMAINS (Name) VALUES ("Computer science");
INSERT INTO DOMAINS (Name) VALUES ("Mathematics");
INSERT INTO DOMAINS (Name) VALUES ("Physics");
INSERT INTO DOMAINS (Name) VALUES ("Biology");

CREATE TABLE EXAMS(
ID int NOT NULL AUTO_INCREMENT,
Name varchar(1000) NOT NULL,
PRIMARY KEY (ID)
);
