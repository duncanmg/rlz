UPDATE question SET source_id = 1 WHERE source_id IS NULL;

PRAGMA foreign_keys = ON;

CREATE TABLE tmp AS SELECT * FROM question;

DROP TABLE question;

CREATE TABLE question (
id INTEGER PRIMARY KEY,
question TEXT NOT NULL,
answer   TEXT NOT NULL,
aq_enabled_yn TEXT NOT NULL DEFAULT 'N',
source_id INTEGER NOT NULL,
FOREIGN KEY(source_id) REFERENCES source(id)
);

INSERT INTO question SELECT * FROM tmp;



