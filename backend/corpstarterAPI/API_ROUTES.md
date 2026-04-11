# API Routes Documentation

## Authentication Routes

### POST /auth/register
**Status Code**: 201 Created | 400 | 409

Create a new user account.

**Request Body**:
```json
{
  "username": "string",
  "last_name": "string",
  "first_name": "string",
  "password": "string",
  "email": "string",
  "user_type": "User|Admin"
}
```

**Response** (201):
```json
{
  "id": 1,
  "token": "token_string",
  "message": "User registered successfully. Please verify your email."
}
```

**Error Responses**:
- 400: Invalid email
- 409: Email already exists

---

### PATCH /auth/verify-email
**Status Code**: 200 OK | 400 | 404

Verify user email with token.

**Request Body**:
```json
{
  "token": "string"
}
```

**Response** (200):
```json
{
  "message": "Email verified successfully"
}
```

**Error Responses**:
- 400: Invalid token
- 404: User not found

---

### POST /auth/login
**Status Code**: 200 OK | 401

Login user and get authentication token.

**Request Body**:
```json
{
  "email": "string",
  "password": "string"
}
```

**Response** (200):
```json
{
  "token": "token_string",
  "user_id": 1,
  "message": "Logged in successfully"
}
```

**Error Response**: 401 Unauthorized (Wrong credentials / Email not confirmed / Terms not accepted)

---

## Project Routes

### POST /projects
**Status Code**: 201 Created | 400 | 401

Create a new project. **Requires Authentication**.

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body**:
```json
{
  "name": "string",
  "requested_budget": "string",
  "illustration_path": "string"
}
```

**Response** (201):
```json
{
  "id": 1,
  "name": "Project Name",
  "message": "Project created successfully"
}
```

**Error Responses**:
- 400: Missing project name
- 401: Not logged in

---

### GET /projects
**Status Code**: 200 OK | 401

Get all projects with optional filters. **Requires Authentication**.

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters** (all optional):
- `status`: Filter by status name
- `name`: Filter by project name (like)
- `created_before`: Filter by creation date before (YYYY-MM-DD)
- `created_after`: Filter by creation date after (YYYY-MM-DD)
- `requester_name`: Filter by requester name (like)
- `approver_name`: Filter by approver name (like)

**Response** (200):
```json
{
  "total": 2,
  "projects": [
    {
      "id": 1,
      "name": "Project Name",
      "requested_budget": "1000.00",
      "allocated_budget": "800.00",
      "illustration_path": "/path/to/image",
      "creation_date": "2026-04-09 10:30:00",
      "status": "Pending",
      "requester": "username",
      "approver": null
    }
  ]
}
```

**Error Response**: 401 Unauthorized

---

### PUT /projects/{id}
**Status Code**: 200 OK | 403 | 404 | 422

Update project details. **Requires Authentication** and ownership.

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body** (all fields optional):
```json
{
  "name": "string",
  "requested_budget": "string",
  "illustration_path": "string"
}
```

**Response** (200):
```json
{
  "message": "Project updated successfully"
}
```

**Error Responses**:
- 403: Not the owner
- 404: Project not found
- 422: Project already validated

---

### DELETE /projects/{id}
**Status Code**: 204 No Content | 403 | 404 | 422

Delete a project. **Requires Authentication** and ownership.

**Headers**:
```
Authorization: Bearer {token}
```

**Response**: 204 No Content (empty body)

**Error Responses**:
- 403: Not the owner
- 404: Project not found
- 422: Project already validated

---

### POST /projects/{id}/join
**Status Code**: 200 OK | 404 | 409

Join a project as attendee. **Requires Authentication**.

**Headers**:
```
Authorization: Bearer {token}
```

**Response** (200):
```json
{
  "message": "Joined project successfully"
}
```

**Error Responses**:
- 404: Project not found
- 409: Already joined or project rejected

---

## Admin Routes

### PATCH /admin/projects/{id}
**Status Code**: 200 OK | 400 | 403 | 404

Update project status and/or allocate budget. **Requires Authentication** and admin privileges.

**Headers**:
```
Authorization: Bearer {token}
```

**Request Body** (at least one field required):
```json
{
  "status": "string (Pending|Approved|Rejected|In Progress|Completed)",
  "allocated_budget": "string (positive number)"
}
```

**Response** (200):
```json
{
  "message": "Project updated successfully"
}
```

**Error Responses**:
- 400: Budget < 0 or Status invalid
- 403: Forbidden/Not Admin
- 404: Project not found or Status ID invalid

---

## Authentication Token

All protected routes require an `Authorization` header with a Bearer token:

```
Authorization: Bearer {token_obtained_from_login_or_register}
```

The token is generated during registration and login, and should be stored client-side for subsequent authenticated requests.
