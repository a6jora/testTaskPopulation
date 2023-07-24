# Test Task Ikonnikov Alexander
https://gist.github.com/katzueno/5b6789ad52d444e68524814ecd53eb21
# Description
This is a simple application for selecting Japan's prefecture and year to display its population data. It allows loading data from a CSV file directly or by providing a URL to download the CSV file.

The application is developed using the Yii2 (basic) framework and includes the following components:
- Console controller
- Site controller
- Database migration file
- Models: Prefectures, Years, Population
- Services for working with the database and fetching population data
- Docker Compose file

# Application Deployment

To deploy the application on your local machine, you will need Docker. Follow the instructions below for setting up the local application:

1. Start Docker Compose:
    ```
    docker compose up -d
    ```

2. Obtain the IP address of the database container using the following command:
    ```
    docker inspect population-mysql-1 | grep IPAddress
    ```
   The output will be similar to this:
    ```  
       "IPAddress": "",
            "IPAddress": "172.19.0.2",
    ```

   Copy the IP address to population/config/db.php:
    ```
   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=<PASTE_IP_HERE>;port=3306;dbname=testDb',
       'username' => 'root',
       'password' => '',
       'charset' => 'utf8',
    ];
   ```

3. Enter the application container:
    ```
    docker exec -ti population-php-1 bash
    ```

4. Inside the container, install the necessary dependencies by executing the command:
    ```
    composer install
    ```

5. Run the database migration:
    ```
    php yii migrate
    ```

6. After creating the tables, load the default data obtained from the address "https://www.e-stat.go.jp/en/stat-search/file-download?&statInfId=000031288682&fileKind=1" by running:
    ```
    php yii console/load
    ```

7. The application is ready to use. Access it by navigating to "http://localhost:8000/".

# Models

### Table: prefectures

| Column Name     | Data Type         | Constraints          |
|-----------------|-------------------|----------------------|
| prefecture_id   | INT               | AUTO_INCREMENT, PK   |
| prefecture_name | VARCHAR(100)      | NOT NULL             |

### Table: years

| Column Name | Data Type  | Constraints          |
|-------------|------------|----------------------|
| year_id     | INT        | AUTO_INCREMENT, PK   |
| year        | INT        | NOT NULL             |

### Table: populations

| Column Name  | Data Type | Constraints               |
|--------------|-----------|---------------------------|
| population_id | INT      | AUTO_INCREMENT, PK        |
| prefecture_id | INT      |                           |
| year_id       | INT      |                           |
| population    | INT      |                           |
|              |           | FOREIGN KEY (prefecture_id) REFERENCES prefectures(prefecture_id) |
|              |           | FOREIGN KEY (year_id) REFERENCES years(year_id)                 |


# Endpoints

- **GET** `/` or `/site/index`: View the application.

- **GET** `/site/load-by-url`: Endpoint to load CSV data from a URL for download.

- **POST** `/site/upload`: Endpoint to upload a CSV document directly as a file.

- **GET** `/site/population`: Endpoint to retrieve population data.
