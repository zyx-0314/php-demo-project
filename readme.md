<a name="readme-top">

<br/>

<br />
<div align="center">
  <a href="https://github.com/zyx-0314/">
    <img src="./assets/img/nyebe_white.png" alt="Nyebe" width="130" height="100">
  </a>
  <h3 align="center">Schedule Arranger</h3>
</div>
<div align="center">
This is sample system for schedule Arranger.
</div>

<br />


![](https://visit-counter.vercel.app/counter.png?page=zyx-0314/php-demo-project)

---

<br />
<br />

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#overview">Overview</a>
      <ol>
        <li>
          <a href="#key-components">Key Components</a>
        </li>
        <li>
          <a href="#technology">Technology</a>
        </li>
      </ol>
    </li>
    <li>
      <a href="#rule,-practices-and-principles">Rules, Practices and Principles</a>
    </li>
    <li>
      <a href="#resources">Resources</a>
    </li>
  </ol>
</details>

---

## Overview

Schedule Arranger is a lightweight web application designed to simplify the creation and management of work schedules for two distinct user roles: Setters (who define and publish schedules) and Employees (who view their assignments). It addresses common coordination challenges—like overlapping shifts, last-minute changes, and missed reminders—by providing:

- Secure access (login/logout) for both roles
- Real-time chat for on-the-fly adjustments and clarifications
- Dynamic scheduling engine that supports one-off and recurring events
- Automated reminders to keep everyone on track

**Target Users**:
- HR managers or team leads (Setters) who need to assign shifts or tasks
- Staff members (Employees) who need clear, up-to-date schedules and timely notifications

**Primary Use Cases**:
- Weekly shift planning: A Setter drafts the upcoming week’s rota and publishes it.
- Last-minute swap: Two Employees negotiate a shift swap via the built-in chat; the Setter then approves it.
- Automated reminders: Employees receive notifications (email or in-app) 30 minutes before their next assignment.

### Key Components

| Component             | Purpose                                                               | Technologies & Interactions                                                                 |
| --------------------- | --------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| **Auth Module**       | Handles user registration, login, logout, and role-based access.      | PHP sessions, PostgreSQL (user table); middleware checks on each page/request.              |
| **Chat Service**      | Enables live messaging between Setters and Employees.                 | WebSockets (Ratchet PHP), MongoDB (chat history); integrates with frontend via JavaScript.  |
| **Scheduler Engine**  | Creates, edits, and resolves conflicts in shift schedules.            | PHP cron jobs, custom algorithm; stores events in MongoDB; triggers reminder service.       |
| **Reminder Service**  | Sends notifications to Employees before their scheduled events.       | PHP background worker, email via SMTP or in-app push; reads from MongoDB events collection. |
| **Frontend UI**       | Responsive pages for both roles: dashboard, calendar view, chat pane. | HTML/CSS/Tailwind, JavaScript (vanilla or small libs), fetch/AJAX to backend endpoints.     |
| **Data Access Layer** | Abstracts database operations for schedules, users, and messages.     | PHP data utilities (`.util.php`), uses PDO for PostgreSQL and MongoDB PHP library.          |


### Technology

#### Language
![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

#### Framework/Library
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)

#### Databases
![MongoDB](https://img.shields.io/badge/MongoDB-47A248?style=for-the-badge&logo=mongodb&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-336791?style=for-the-badge&logo=postgresql&logoColor=white)

## Rules, Practices and Principles


1. Always use `AD-` in the front of the Title of the Project for the Subject followed by your custom naming.
2. Do not rename `.php` files if they are pages; always use `index.php` as the filename.
3. Add `.component` to the `.php` files if they are components code; example: `footer.component.php`.
4. Add `.util` to the `.php` files if they are utility codes; example: `account.util.php`.
5. Place Files in their respective folders.
6. Different file naming Cases
   | Naming Case | Type of code         | Example                           |
   | ----------- | -------------------- | --------------------------------- |
   | Pascal      | Utility              | Accoun.util.php                   |
   | Camel       | Components and Pages | index.php or footer.component.php |
8. Renaming of Pages folder names are a must, and relates to what it is doing or data it holding.
9. Use proper label in your github commits: `feat`, `fix`, `refactor` and `docs`
10. File Structure to follow below.

```
AD-ProjectName
└─ assets
|   └─ css
|   |   └─ name.css
|   └─ img
|   |   └─ name.jpeg/.jpg/.webp/.png
|   └─ js
|       └─ name.js
└─ components
|   └─ name.component.php
|   └─ templates
|      └─ name.component.php
└─ handlers
|   └─ name.handler.php
└─ layout
|   └─ name.layout.php
└─ pages
|  └─ pageName
|     └─ assets
|     |  └─ css
|     |  |  └─ name.css
|     |  └─ img
|     |  |  └─ name.jpeg/.jpg/.webp/.png
|     |  └─ js
|     |     └─ name.js
|     └─ index.php
└─ staticData
|  └─ name.staticdata.php
└─ utils
|   └─ name.utils.php
└─ vendor
└─ .gitignore
└─ bootstrap.php
└─ composer.json
└─ composer.lock
└─ index.php
└─ readme.md
└─ router.php
```
> The following should be renamed: name.css, name.js, name.jpeg/.jpg/.webp/.png, name.component.php(but not the part of the `component.php`), Name.utils.php(but not the part of the `utils.php`)

## Resources

| Title                   | Purpose                                                               | Link                                                                       |
| ----------------------- | --------------------------------------------------------------------- | -------------------------------------------------------------------------- |
| ChatGPT                 | General AI assistance for planning application architecture and docs. | [https://chat.openai.com](https://chat.openai.com)                         |
| GitHub Copilot          | In-IDE code suggestions and boilerplate generation.                   | [https://github.com/features/copilot](https://github.com/features/copilot) |
| YouTube “UI/UX Design”  | Video tutorials on modern web interface layouts and patterns.         | [https://www.youtube.com](https://www.youtube.com)                         |
| Pinterest Design Boards | Inspiration for color schemes, typography, and component layouts.     | [https://www.pinterest.com](https://www.pinterest.com)                     |
| Google Photos (Assets)  | Stock imagery and graphics used in UI mockups and documentation.      | [https://photos.google.com](https://photos.google.com)                     |
| System Documentation    | Internal docs from PHP, MongoDB, and PostgreSQL used in development.  | — (see `/docs` folder in repo)                                             |
