# CorpStarter Backend API

Welcome to the **CorpStarter Backend API**! This API powers the backend of the CorpStarter application, built using Symfony. Below, you'll find detailed documentation for all available routes, including their parameters, responses, and error codes.

---

## Table of Contents

1. [Authentication Routes](#authentication-routes)
2. [Project Routes](#project-routes)
3. [Admin Routes](#admin-routes)

---

## Authentication Routes

### **POST** `/api/auth/signup`
**Description:** Create a new user.

#### Parameters:
- `username` (string, required): The username of the user.
- `email` (string, required): The email of the user.
- `password` (string, required): The password of the user.
- `first_name` (string, required): The first name of the user.
- `last_name` (string, required): The last name of the user.
- `terms_accepted` (boolean, optional): Whether the user accepted the terms.

#### Responses:
- **201 Created:**
  ```json
  {
    "message": "User created successfully",
    "user": {
      "id": 1,
      "username": "example",
      "email": "example@example.com"
    }
  }
  ```
- **400 Bad Request:** Missing required fields.
- **409 Conflict:** User already exists.

---

### **POST** `/api/auth/signin`
**Description:** Authenticate a user and generate a token.

#### Parameters:
- `email` (string, required): The email of the user.
- `password` (string, required): The password of the user.

#### Responses:
- **200 OK:**
  ```json
  {
    "message": "Sign in successful",
    "user": {
      "id": 1,
      "username": "example",
      "email": "example@example.com",
      "first_name": "John",
      "last_name": "Doe",
      "token": "generated_token"
    }
  }
  ```
- **400 Bad Request:** Missing email or password.
- **401 Unauthorized:** Invalid credentials.
- **403 Forbidden:** Email not verified.

---

### **POST** `/api/auth/verify-email`
**Description:** Verify a user's email.

#### Parameters:
- `user_id` (integer, required): The ID of the user.

#### Responses:
- **200 OK:** Email verified successfully.
- **400 Bad Request:** Missing user ID.
- **404 Not Found:** User not found.

---

## Project Routes

### **GET** `/api/projects`
**Description:** List all projects for a user.

#### Parameters:
- `token` (string, required): The authentication token.
- `user_id` (integer, optional): The ID of the user to filter projects.

#### Responses:
- **200 OK:**
  ```json
  {
    "projects": [
      {
        "id": 1,
        "name": "Project Name",
        "requested_budget": 1000,
        "allocated_budget": 500
      }
    ]
  }
  ```
- **400 Bad Request:** Token required.
- **401 Unauthorized:** Invalid or expired token.

---

### **POST** `/api/projects`
**Description:** Create a new project.

#### Parameters:
- `token` (string, required): The authentication token.
- `name` (string, required): The name of the project.
- `user_id` (integer, required): The ID of the user creating the project.
- `requested_budget` (float, optional): The requested budget for the project.
- `illustration_path` (string, optional): Path to the project's illustration.

#### Responses:
- **201 Created:** Project created successfully.
- **400 Bad Request:** Missing required fields or token.
- **401 Unauthorized:** Invalid or expired token.

---

### **PUT/PATCH** `/api/projects/{id}`
**Description:** Edit a project.

#### Parameters:
- `token` (string, required): The authentication token.
- `name` (string, optional): The new name of the project.
- `requested_budget` (float, optional): The new requested budget.
- `illustration_path` (string, optional): The new illustration path.

#### Responses:
- **200 OK:** Project updated successfully.
- **400 Bad Request:** Token required.
- **401 Unauthorized:** Invalid or expired token.
- **404 Not Found:** Project not found.

---

### **DELETE** `/api/projects/{id}`
**Description:** Delete a project.

#### Parameters:
- `token` (string, required): The authentication token.

#### Responses:
- **200 OK:** Project deleted successfully.
- **400 Bad Request:** Token required.
- **401 Unauthorized:** Invalid or expired token.
- **404 Not Found:** Project not found.

---

## Admin Routes

### **PATCH** `/api/admin/projects/{id}/status`
**Description:** Change the status of a project.

#### Parameters:
- `token` (string, required): The authentication token.
- `admin_id` (integer, required): The ID of the admin.
- `status_id` (integer, required): The new status ID.

#### Responses:
- **200 OK:** Project status updated successfully.
- **400 Bad Request:** Missing required fields or token.
- **401 Unauthorized:** Invalid or expired token.
- **403 Forbidden:** Unauthorized access.
- **404 Not Found:** Project not found.

---

### **PATCH** `/api/admin/projects/{id}/budget`
**Description:** Allocate a budget to a project.

#### Parameters:
- `token` (string, required): The authentication token.
- `admin_id` (integer, required): The ID of the admin.
- `allocated_budget` (float, required): The allocated budget.

#### Responses:
- **200 OK:** Budget allocated successfully.
- **400 Bad Request:** Missing required fields or token.
- **401 Unauthorized:** Invalid or expired token.
- **403 Forbidden:** Unauthorized access.
- **404 Not Found:** Project not found.

---

### Thank You for Using the CorpStarter API!
