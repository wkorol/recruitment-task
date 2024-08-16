CREATE TABLE IF NOT EXISTS "user" (
                                      id SERIAL PRIMARY KEY,
                                      login VARCHAR(255) NOT NULL UNIQUE,
                                      password VARCHAR(255) NOT NULL
);

INSERT INTO "user" (login, password)
VALUES ('admin', '$2y$10$sT3Ofw7gAercuu/ZwPX8keJX9HG44GeHdjAcxIQCDGFR09ujyNyQq');