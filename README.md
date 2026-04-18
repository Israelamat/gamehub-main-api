# 🎮 GameHub API: High-Performance Gaming & E-Learning Engine

**GameHub API** is a robust, production-ready RESTful service designed to power a dual-purpose ecosystem: a high-traffic video game marketplace and a comprehensive educational training platform. Built with **Symfony 7** and **PHP 8.2**, it follows strict **SOLID** principles and a modern **API-First** approach.

---

## 🏗️ Technical Architecture

This API is architected for total separation of concerns, ensuring scalability and clean data flow:

* **Pure REST Service:** Optimized for JSON-only communication, eliminating server-side rendering for maximum frontend flexibility.
* **Complex Data Modeling:** Advanced relational architecture using **Doctrine ORM** to manage users, sales, and community feedback.
* **Microservices Integration:** Dedicated bridge to an external **AI Recommendation Engine** based on **TF-IDF (NLP)**.

---

## 🛠️ Tech Stack & Methodology

| Component | Technology | Role |
| :--- | :--- | :--- |
| **Backend** | **Symfony 7** | Core Framework & Dependency Injection. |
| **Language** | **PHP 8.2+** | Attributes, Typed Properties, and Enums. |
| **ORM** | **Doctrine** | Mapping entities (`User`, `Order`, `Review`, etc.) with migrations. |
| **AI Integration** | **TF-IDF Engine** | Connection with external Python/ML recommendation API. |

---

## 🚦 Strategic Endpoints (Full CRUD Support)


## 🧠 AI Recommendation Logic (TF-IDF)
The `/game/recommend` endpoint acts as a client for a specialized recommendation service. It processes game metadata using **Term Frequency-Inverse Document Frequency (TF-IDF)** to calculate text similarity, providing personalized content discovery.

---

### 📦 Marketplace Hub (`/game`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/game` | Catalog retrieval & broad discovery. |
| **POST** | `/game/new` | Ingest new titles into the ecosystem. |
| **GET** | `/game/recommend` | **Smart Discovery:** AI-driven suggestions (TF-IDF). |
| **PUT** | `/game/{id}/edit` | Precise resource updates. |
| **DELETE** | `/game/{id}` | Controlled resource removal. |

### 🎓 Training Center (`/course`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/course` | Educational program listings. |
| **POST** | `/course/new` | Course creation and content deployment. |
| **PUT** | `/course/{id}/edit` | Curriculum modification. |

### 🛒 Sales & Orders (`/order`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/order` | Transaction history logs. |
| **POST** | `/order/new` | Process new purchase/enrollment. |
| **GET** | `/order/{id}` | Specific transaction details. |

### 👤 User Management (`/user`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/user` | User directory (Admin only). |
| **PUT** | `/user/{id}/edit` | Profile updates and account management. |

### ⭐ Community Reviews (`/review`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **GET** | `/review` | List all feedback and ratings. |
| **POST** | `/review/new` | Submit new user evaluation. |
| **DELETE** | `/review/{id}` | Moderation: Remove inappropriate content. |
---
