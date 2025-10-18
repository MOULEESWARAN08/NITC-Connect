# NITCConnect 

##  Welcome!

This is a complete Platform Developed By 

MOULEESWARAN C Fullstack, AI & ML

ASHWIN RAJU P - Frontend

RITHISH NATARAJ R - Backend

SARAN CM - Database 

,student campus platform with MySQL backend integration.

This website can run only in local host kindly follow the instruction given below.

##  Package Contents

- **1 HTML file** - Complete frontend (index.html)
- **11 PHP files** - Full backend API system
- **1 SQL file** - Database schema with sample data
- **3 Documentation files** - Installation guides

##  Quick Start (5 Minutes)

### Step 1: Install XAMPP

### Step 2: Start Services

### Step 3: Copy Files
Navigate to:
- **Windows**: `C:\xampp\htdocs\`
- **Mac**: `/Applications/XAMPP/htdocs/`
- **Linux**: `/opt/lampp/htdocs/`

### Step 4: Setup Database
1. Open browser: http://localhost/phpmyadmin
2. Click **"Import"** tab
3. Click **"Choose File"**
4. Select `database_setup.sql`
5. Click **"Go"**
6. Wait for success message

### Step 5: Launch!
Open browser and go to:
**http://localhost/nitcconnect/index.html**

##  Features

✅ **User Authentication** - Secure signup/login with password hashing

✅ **Club Management** - Join clubs, earn 20 points

✅ **Event Registration** - RSVP for events, earn 15 points


✅ **Hostel Tickets** - Report and track issues

✅ **Leaderboard** - Points ranking system

✅ **Dashboard** - Personal stats and activity feed

✅ **Study Materials** - Upload/download resources, earn 10 points

✅ **Security** - SQL injection protection, session management

## Database

**Database Name**: `nitcconnect`

**9 Tables**:
- users (student accounts)
- clubs (campus clubs)
- club_members (membership tracking)
- events (campus events)
- event_registrations (RSVP tracking)
- hostel_tickets (issue tracking)
- study_materials (educational resources)
- leaderboard (points system)
- ai_recommendations (AI suggestions)

**Sample Data Included**:
- 4 Clubs
- 3 Events

## Configuration

Default settings (in `db_config.php`):
```php
Server: localhost
Username: root
Password:
Database: nitcconnect
```
##  File Structure

```
nitcconnect/
├── index.html              (Frontend)
├── db_config.php          (Database config)
├── login.php              (Authentication)
├── signup.php             (Registration)
├── logout.php             (Logout)
├── check_session.php      (Session check)
├── clubs.php              (Clubs API)
├── events.php             (Events API)
├── tickets.php            (Tickets API)
├── leaderboard.php        (Rankings API)
├── dashboard.php          (Dashboard API)
├── study_materials.php    (Materials API)
└── database_setup.sql     (Database)
```

##  Security

- Password hashing (bcrypt)
- Prepared statements (SQL injection prevention)
- Input sanitization
- Session management
- XSS protection

##  Access URLs

- **Website**: http://localhost/nitcconnect/index.html
- **Database**: http://localhost/phpmyadmin
- **XAMPP**: http://localhost

##  Points System

- Join Club: **+20 points**
- Register Event: **+15 points**
- Upload Material: **+10 points**

## Requirements

- XAMPP (or WAMP/MAMP)
- Web browser
- 100MB free space

---

**Version**: 1.0

**Created**: October 18, 2025

**Tech Stack**: HTML + PHP + MySQL + JavaScript

Reference

1. w3schoools for javascript
2. 
3. Perplexity 
3. https://seek.onlinedegree.iitm.ac.in/courses/ns_25t3_ds500?id=5&type=lesson&tab=courses&unitId=11

---
