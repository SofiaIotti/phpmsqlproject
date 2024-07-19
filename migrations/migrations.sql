CREATE TABLE Country (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Trip (
    id INT AUTO_INCREMENT PRIMARY KEY,
    available_seats INT NOT NULL
);

CREATE TABLE Trip_Country (
    trip_id INT,
    country_id INT,
    FOREIGN KEY (trip_id) REFERENCES Trip(id) ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES Country(id) ON DELETE CASCADE,
    PRIMARY KEY (trip_id, country_id)
);