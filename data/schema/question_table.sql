CREATE TABLE question (
id INTEGER PRIMARY KEY,
question TEXT NOT NULL,
answer   TEXT NOT NULL,
aq_enabled_yn TEXT NOT NULL DEFAULT 'N'
);
