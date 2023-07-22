# testTaskPopulation

CREATE TABLE cities (
city_id INT AUTO_INCREMENT PRIMARY KEY,
city_name VARCHAR(255) NOT NULL,
UNIQUE KEY unique_city (city_name,)
);

CREATE TABLE population (
population_id INT AUTO_INCREMENT PRIMARY KEY,
city_id INT NOT NULL,
year INT NOT NULL,
population_count INT NOT NULL,
FOREIGN KEY (city_id) REFERENCES cities(city_id),
UNIQUE KEY unique_population (city_id, year)
);
