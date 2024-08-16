CREATE TABLE IF NOT EXISTS news (
                                      id SERIAL PRIMARY KEY,
                                      title VARCHAR(255) NOT NULL ,
                                      description VARCHAR(500) NOT NULL
);