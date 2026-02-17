# GameHub API 🎮

This is the core API for **GameHub**, a video game marketplace and e-learning platform developed as a Final Degree Project (TFG). The architecture is designed as a **RESTful API** using **Symfony 7** and **PHP 8.2+**.

## 🚀 Key Features
- **API-First Architecture**: Pure JSON responses; no Twig or HTML templates are used.
- **Data Model**: Relational entities (User, Game, Course) with `ManyToOne` relationships.
- **Persistence**: MySQL database managed via Doctrine ORM.
- **Environment Security**: Professional environment variable configuration (`.env`).

## 🛠️ Tech Stack
- **Framework**: Symfony 7
- **Language**: PHP 8.2
- **Database**: MySQL
- **Tools**: Doctrine (Migrations & ORM), Serializer Pack.

## 📁 Project Structure (API Controllers)
The controllers are refactored to process JSON requests and return standardized REST responses:

- `GameController`: Handles the video game marketplace logic.
- `CourseController`: Manages the educational training courses.



## 🚦 Main Endpoints

### Games (`/game`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/game` | List all games |
| **POST** | `/game/new` | Create a new game (JSON body) |
| **GET** | `/game/{id}` | Get game details |
| **PUT** | `/game/{id}/edit` | Update an existing game |
| **DELETE** | `/game/{id}` | Remove a game |

### Courses (`/course`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/course` | List all courses |
| **POST** | `/course/new` | Create a new course (JSON body) |
| **PUT** | `/course/{id}/edit` | Update an existing course |
| **DELETE** | `/course/{id}` | Remove a course |
