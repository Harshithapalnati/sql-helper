# SQL Helper – Natural Language to SQL Analytics System

## Overview

This project is a web-based application that allows users to ask business-related questions in simple English and receive results generated using SQL queries.

The system converts natural language input into SQL queries, executes them on a database, and displays the results in a structured format.

This application is built using Laravel and MySQL and is designed to simplify data querying for non-technical users.

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
- Managers
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
- Converts input into SQL queries
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
