PHP & MySQL Project - Start2Impact University

Built with

.![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
.![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

Sul progetto IT 🇮🇹

Il progetto conclude il corso PHP & MySQL seguito con Start2Impact University. 
Il test prevedeva la creazione di API JSON RESTful, in grado di organizzare il lavoro per un’agenzia di viaggi. ✈️

L’obiettivo era quello di organizzare i dati secondo due oggetti: Paesi (Country) e Viaggi (Trip) con un numero di passeggeri definito e collegati tra loro.
per questo motivo ho realizzato 3 tabelle sul database, una per i viaggi, una per i paesi e una terza tabella di collegamento(Trip_Country).

Ho strutturato il codice prevedendo: 
- 📄 un file di ingresso all'applicazione (index.php);
- 📄 un file per le Query di creazione delle tabelle (migrations/migrations.sql);
- 📄 un file per il collegamento al database con l'estensione PDO (src/database.php);
- 📄 due file model per gestire l'accesso ai dati delle tabelle (src/models/country.php - src/models/trip.php);
- 📄 due file controller con le API REST per elaborare le richieste HTTP e restituire i dati.

About the project EN 🇬🇧

The project ends the PHP & MySQL course taken with Start2Impact University. 
The test required the creation of RESTful JSON APIs that could organize the work for a travel agency. ✈️

The goal was to organize the data according to two objects: Countries (Country) and Trips (Trip) with a defined number of passengers and linked together.
For this reason I made 3 tables on the database, one for trips, one for countries and a third linking table(Trip_Country).

I structured the code by providing: 
- 📄 an input file to the application (index.php);
- 📄 a file with the Queries used to create the tables (migrations/migrations.sql);
- 📄 a file to link the database using the PDO extension (src/database.php);
- 📄 two model files to manage access to table data (src/models/country.php - src/models/trip.php);
- 📄 two controller files with the REST API to process HTTP requests and return data.

