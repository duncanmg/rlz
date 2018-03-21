ALTER TABLE question ADD source_id INTEGER;

CREATE TABLE source (
id INTEGER PRIMARY KEY,
description TEXT NOT NULL
);

INSERT INTO source
(description)
VALUES
('Colins Gem Phrase Finder. ISBN 0-00-470285-9');

