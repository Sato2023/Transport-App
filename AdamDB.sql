CREATE USER 'Adam'@'localhost' IDENTIFIED BY 'Adam1';
GRANT ALL ON *.* TO 'Adam'@'localhost';

DROP SCHEMA IF EXISTS adamdb;
CREATE SCHEMA adamdb;

USE adamdb;

CREATE TABLE Bruker
(
Brukernavn VARCHAR(30),
Passord VARCHAR(100),
Fornavn VARCHAR(100),
Etternavn VARCHAR(100),
Sikkerhet INTEGER(1),
CONSTRAINT BrukerPK PRIMARY KEY (Brukernavn)
);

CREATE TABLE Rute
(
RuteID int AUTO_INCREMENT,
Dato DATE,
Latitude FLOAT,
Longitude FLOAT,
Brukernavn VARCHAR(30),
CONSTRAINT RutePK PRIMARY KEY (RuteID),
CONSTRAINT RuteBrukerFK FOREIGN KEY (Brukernavn) REFERENCES Bruker(Brukernavn)
);

CREATE TABLE Arbeidstid
(
ArbeidstidID int AUTO_INCREMENT,
Dato DATE,
ArbeidsTimer VARCHAR(100),
Brukernavn VARCHAR(30),
CONSTRAINT ArbeidstidPK PRIMARY KEY (ArbeidstidID),
CONSTRAINT ArbeidstidBrukerFK FOREIGN KEY (Brukernavn) REFERENCES Bruker(Brukernavn)
);

INSERT INTO rute (dato, latitude,longitude, brukernavn) values ("2023-05-13","","","men");

SELECT * from bruker;
SELECT * FROM rute;
select * from arbeidstid;