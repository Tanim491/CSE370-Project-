# Digital Lost & Found System

Digital Lost & Found System is a database-driven platform that helps users report lost items and submit found item reports within a campus or organization. The platform stores all reports in a structured database and allows users to claim found items through a verification process.

The system helps reduce confusion and improves the process of returning lost belongings to their rightful owners.

---

## Features

#### Report Lost Items
Users can report items they have lost by providing information such as item name, category, color, brand, location, and the date it was lost.

---

#### Report Found Items
Users who find items can submit a report including details of the item and the location where it was found.

This allows the system to match lost and found items.

---

#### Item Categorization
Items are organized into different categories.

Example categories include:

- **Electronics**
  - Mobile phones
  - Earbuds
  - Chargers

- **Documents**
  - Student ID cards
  - NID cards
  - Passports

- **Accessories**
  - Watches
  - Wallets
  - Keys

- **Personal Items**
  - Bags
  - Water bottles
  - Umbrellas

---

#### Claim Request System
If a user believes a found item belongs to them, they can submit a **claim request**.

The system keeps track of all claim attempts.

---

#### Claim Verification
Administrators review claim requests before approving them.

This verification process helps reduce false ownership claims.

---

#### Item Status Tracking
Each item report has a status.

Possible statuses include:

- Lost
- Found
- Claim Pending
- Returned

This allows users to track the progress of their items.

---

#### Search and Filtering
Users can search lost and found reports by category, brand, or location.

This makes it easier to find possible matches.

---

#### Return Record Management
Once a claim is approved, the system records the item return.

This creates a clear history of returned items.

---

## Technology Stack

| Layer | Technology |
|------|------------|
| Database | MySQL |
| Backend | PHP / Node.js |
| Frontend | HTML, CSS, JavaScript |







Study Group Finder System
Overview

The Study Group Finder System is a database-driven platform designed to help university students find or create study groups based on courses, schedules, and academic interests.

Students often struggle to find peers for collaborative learning. This platform allows students to create course-specific study groups, join existing groups, schedule study sessions, and share learning resources.

By organizing study groups in a centralized system, the platform improves collaboration, productivity, and academic performance among students.

Core Features
Student Registration

Students create an account using:

Name

Student ID

Department

Semester

Email

This information helps the system organize students based on academic background.

Course-Based Group Creation

Students can create study groups for specific courses.

Example courses:

CSE110 – Programming

CSE220 – Data Structures

MAT120 – Calculus

The student who creates the group becomes the group administrator.

Join or Request to Join Groups

Students can join groups in two ways:

Join open groups directly

Send join requests to private groups

Group admins can approve or reject requests.

Study Schedule Management

Each group can create study session schedules.

Example:

Monday – 7 PM (Online)

Wednesday – 5 PM (Library)

Members can view upcoming sessions and plan accordingly.

Skill Level Matching

Students can specify their skill level:

Beginner

Intermediate

Advanced

This helps create balanced and effective study groups.

Unique Features
Smart Study Group Recommendation

The system recommends study groups based on:

Same course

Same semester

Similar schedule availability

Example recommendation:

“Students taking CSE220 Data Structures may want to join Group A.”

Location-Based Study Groups

Students can choose meeting locations such as:

Library

Cafeteria

Study room

Online (Zoom / Google Meet)

Group Discussion Board

Group members can communicate through a discussion board.

They can:

Ask questions

Share notes

Discuss assignments

Resource Sharing

Members can upload and share:

Lecture notes

PDFs

Slides

Practice problems

Study Session Attendance

The system tracks which members attend each study session.

This helps measure active participation.

Group Rating System

Members can rate study groups.

Example:

⭐ ⭐ ⭐ ⭐ ⭐

This helps new students choose active and effective groups.

Group Activity Score

The system tracks activity such as:

Number of study sessions

Resources shared

Member participation

More active groups appear higher in search results.

Deadline & Exam Reminder

Groups can set reminders for:

Assignment deadlines

Midterms

Final exams

Members receive notifications.

Study Partner Matching

Students can find one-to-one study partners based on:

Course

Availability

Skill level

Possible Database Entities

Students

Courses

Study_Groups

Group_Members

Study_Sessions

Locations

Resources

Messages

Join_Requests

Ratings

Notifications

Technology Stack
Layer	Technology
Database	MySQL
Backend	PHP / Node.js
Frontend	HTML, CSS, JavaScript
