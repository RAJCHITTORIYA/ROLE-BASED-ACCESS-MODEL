# ROLE-BASED-ACCESS-MODEL
The Role-Based Access Model (RBAM) is a web-based system designed to provide secure and controlled access to different features of an application based on the user’s role.

A lightweight role-based authentication and authorization system built using PHP, MySQL, and Sessions, allowing different users to access different features based on their assigned role.
Currently, the application contains two user roles: Admin and User.

🔗 Live Demo: https://rolebasedmodel.infinityfreeapp.com

⭐ Overview

This project implements a secure role-based access architecture where each user is assigned a role.
The role decides what the user can view and what operations can be performed.

Current Roles:

Admin – Full privileges (CRUD operations on users)

User – Limited privileges (View own dashboard only)

The system restricts page access both through UI and direct URL attempts.


🗂 Folder Structure
📦 /project-root
│
├── admin.php
├── auth.php
├── client_secret.json
├── config.php
├── google_login.php
├── index.php
├── login.php
├── logout.php
├── register.php
├── styles.css
├── user.php
├── user_dashboard.php
├── user-delete.php
├── user-edit.php
└── welcome.php


This structure reflects the current live deployment.

🔐 Features

✔ Login & logout system
✔ Role-based redirection
✔ Admin Panel
✔ User Dashboard
✔ Add/Edit/Delete Users (Admin only)
✔ View users list (Admin only)
✔ Authentication middleware (auth.php)
✔ Secure Session validation
✔ Prevent direct URL access without login
✔ Google OAuth Login support

💻 Tech Stack

Frontend:

HTML

CSS

Backend:

PHP

MySQL

Other:

Sessions

Google OAuth API

API

🛢 Database Structure
Table: users
Field	Type
id	int
name	varchar
email	varchar
password	varchar
role	enum('admin','user')

)

🚀 Setup Instructions

Download or clone repository

Import SQL file into phpMyAdmin

Configure DB credentials in config.php

Upload to hosting or run in localhost

Open browser and login

Admin login is pre-seeded or can be created manually through DB.


🔑 Admin Capabilities

Create user

Edit user

Delete user

View all users

Promote/demote roles

👤 User Capabilities

Login

View own dashboard only


The project focuses on:

Authentication (secure login system)

Authorization (permission-based feature access)

Session handling and security

Restricting pages and operations per role

Logging and tracking user actions

The core objective is to create a structured and secure hierarchy that simplifies management, improves data privacy, and prevents unauthorized operations.

Key Features

Multi-role login system
Dynamic dashboard based on role
Add/Edit/Delete permissions for roles
Navigation and menu visibility control
Restrict sensitive pages based on user type
Centralized user and role management
Data protection through controlled access

Outcome
This model ensures:
Security of sensitive modules
Proper segregation of access
Easy role management
Better organizational control


📜 License

Free for educational & project demonstration purposes.


