# SQL Helper – Natural Language to MySQL Queries System

## Overview

This project is a web-based application that allows users to ask business-related questions in simple English and receive results generated using MySQL queries.

The system converts natural language input into SQL queries, executes them on a database, and displays the results in a structured format.

This application is built using Laravel and MySQL and is designed to simplify data querying for non-technical users.

---

## Live Demo

[Click here to open the application](https://sql-helper-production.up.railway.app/)

---

## Purpose of the Project

The main purpose of this project is to:

- Allow users to interact with data without writing SQL
- Convert human language into database queries
- Provide quick insights from business data
- Improve accessibility for non-technical users

---

## Who This Project Is For

This system can be useful for:

- Business analysts
- Non-technical users
- Beginners learning SQL concepts
- Anyone who wants quick insights from data without coding 

---

## Features

- Accepts natural language queries
- Detects intent such as:
  - product type
  - category (skincare, haircare)
  - time range (days)
  - season (summer, winter)
  - actions like count and compare
- Converts input into MYSQL queries which can be directly copied and pasted.
- Displays:
  - Generated SQL
  - Query results in JSON format
- Supports comparison queries
- Provides suggested queries
- Includes a "View Data" option to see database tables

---

## Tech Stack

- Backend: Laravel (PHP)
- Database: MySQL
- Frontend: Blade (Laravel templates), Bootstrap
- Hosting: Railway
- Version Control: Git and GitHub

---

## Database Structure

The project uses the following main tables:

### 1. products
- id
- name
- type (cream, serum, shampoo, etc.)
- category (skincare, haircare)
- price
- season (summer, winter)

### 2. orders
- id
- created_at

### 3. order_items
- id
- order_id
- product_id
- quantity

---

## How to Use the Application

1. Open the website
2. Enter a query in the input field
3. Click submit
4. The system will:
   - detect the intent
   - generate SQL query
   - execute it
   - display results

---

## Example Queries and Results

### Example 1

Input:
How many skincare products sold in last 20 days

Output:
- SQL query is generated
- Total quantity is returned

---

### Example 2

Input:
Compare skincare and haircare sales


Output:
- Sales for both categories
- Message showing which performed better

---

## Suggested Queries

Users can also use suggested queries available on the UI:

- How many skincare products sold in last 20 days
- Compare skincare and haircare sales
- Moisturiser sales in last 14 days

---

## View Data Feature

The application includes a button:

"View Available Data"

This allows users to:
- View product data
- View orders
- Understand the structure of the database

This helps users form better queries.

---

## Special Logic Implemented

- Synonym mapping:
  - moisturiser → cream
  - hair → haircare
  - skin → skincare

- Flexible query detection:
  - Works even if user does not use exact keywords
  - Example:
    - "hair" works instead of "haircare"

---

## Limitations

- Works only with predefined patterns
- Does not support very complex queries
- Requires valid keywords to detect intent

---

## Future Improvements

- Add AI-based NLP for better understanding
- Support more query types
- Add charts and visualizations
- Add user authentication
- Improve error handling
- Expand dataset and categories

---

## Setup Instructions (Local)

1. Clone the repository

git clone <your-repo-link>


2. Install dependencies

composer install


3. Configure `.env`
- Add database credentials

4. Generate app key

php artisan key:generate


5. Run migrations

php artisan migrate


6. Start server

php artisan serve


---

## Deployment

The project is deployed using Railway.

Note:
Deployment required proper configuration of:
- environment variables
- database connection
- migrations

---

## Conclusion

This project demonstrates how natural language can be converted into SQL queries to make data access easier.

It shows the integration of:
- backend logic
- database querying
- user-friendly interface

---

## Author

Harshitha
