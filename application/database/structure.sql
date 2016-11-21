CREATE TABLE users(
	userId INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, 
	name TEXT UNIQUE NOT NULL, 
	password TEXT NOT NULL, 
	email TEXT UNIQUE NOT NULL, 
	level INTEGER NOT NULL
);

CREATE TABLE articles(
	articleId INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, 
	userId INTEGER NOT NULL, 
	title TEXT UNIQUE NOT NULL, 
	content TEXT NOT NULL, 
	slug TEXT UNIQUE NOT NULL, 
	cdate TEXT NOT NULL, 
	state INTEGER NOT NULL, 
	tags TEXT,
	type TEXT,
	FOREIGN KEY(userId) REFERENCES users(userId)
);