CREATE TABLE questions (
id INTEGER PRIMARY KEY,
question TEXT NOT NULL,
answer   TEXT NOT NULL,
score_qa INTEGER NOT NULL,
score_aq INTEGER NOT NULL,
aq_enabled_yn TEXT NOT NULL DEFAULT 'N'
);
