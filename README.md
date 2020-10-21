# Jamun Search

Jamun Search is a search engine to search for sites and images.
It is a simple search engine that uses the %LIKE% operator in MySQL to search for sites and images.

## Installation
- Clone the repository
- Create a database and import the `jamun.sql` file
- Edit the `config.php` file and add your database credentials
- Run the `index.php` file

## Usage
- Add sites to the database by running the `crawl.php` file with the URL of the site as the value for `$startUrl` variable
- It will crawl the site and add the url links and image links to the database

## License
[MIT](https://choosealicense.com/licenses/mit/)

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## What is the purpose of this project?
- I created this project to learn how to create a search engine
- I also created this project to learn how to crawl a website and add the links to the database
- I learnt more about MySQL and PHP

## What is the future of this project?
- The development of this project is stopped
- For any bugs or issues, please open an issue. I will try to fix the bugs and issues
- If you want to contribute to this project, please open a pull request
- I've made an exact copy of this project in Node.js with Express.js and MongoDB. You can find the project [here](https://github.com/tharunoptimus/search-engine)