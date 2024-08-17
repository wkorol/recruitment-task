# Recruitment Task for cgrd company
A project providing an API with login page and news edditing.
 
## Running the development environment using Docker
To run the project, you will need Docker (tested on version 27.1.1, build 6312585) and Docker Compose (tested on version v2.29.1), as well as Make (GNU Make - 4.3-4.1build1) and Git (running on version 2.34.1).

## Ports
Please check that you don't have reserved ports by any application: `8000 and 5432` 


## Starting project
To start project just run command `make start` and if you have good prepared environment - everything will be created automatically

## Starting and stopping project
To start project after you built it just run command `make up` to start containers and to stop them just use `make down`, for rest commands just check `cat Makefile`

Sure! Here is a simple API documentation for the `LoginController` class based on the provided PHP code. This documentation assumes a RESTful API and covers the endpoints `login`, `logout`, and `isLoggedIn`.

## More Makefile Commands
If you want to check the other commands, just check [Makefile](Makefile)

---

# **LoginController API Documentation**

## **Endpoints**

### 1. **Login**
**URL:** `/login`

**Method:** `POST`

**Description:**  
Authenticates a user by validating login credentials. Upon successful authentication, a session is started, and a session cookie is set.

**Request Body:**
- `login` (string, required) - The username or login identifier of the user.
- `password` (string, required) - The user's password.

**Responses:**

- **200 OK**
    - **Description:** Login successful.
    - **Example Response:**
      ```json
      {
        "message": "Login successful"
      }
      ```
- **401 Unauthorized**
    - **Description:** Wrong login data (invalid credentials).
    - **Example Response:**
      ```json
      {
        "error": "Wrong login data."
      }
      ```
- **400 Bad Request**
    - **Description:** No credentials provided.
    - **Example Response:**
      ```json
      {
        "error": "No credentials provided"
      }
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred during database access.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```

**Authentication:**
- After successful login, the user session is initiated, and a session cookie (`session_id`) is set.

---

### 2. **Logout**
**URL:** `/logout`

**Method:** `POST`

**Description:**  
Logs out the currently authenticated user by destroying their session and clearing the session cookie.

**Request Body:**  
None

**Responses:**

- **200 OK**
    - **Description:** Logout successful.
    - **Example Response:**
      ```json
      {
        "message": "Logout successful"
      }
      ```
- **400 Bad Request**
    - **Description:** No user is logged in.
    - **Example Response:**
      ```json
      {
        "error": "Nobody is logged in"
      }
      ```

**Authentication:**
- Requires the user to be logged in. If no user is logged in, a `400` error is returned.

---

### 3. **IsLoggedIn**
**URL:** `/isLoggedIn`

**Method:** `GET`

**Description:**  
Checks whether the user is currently logged in.

**Request Body:**  
None

**Responses:**

- **200 OK**
    - **Description:** The user is logged in.
    - **Example Response:**
      ```json
      {
        "loggedIn": true
      }
      ```
- **200 OK**
    - **Description:** The user is not logged in.
    - **Example Response:**
      ```json
      {
        "loggedIn": false
      }
      ```

**Authentication:**
- No authentication is required to check the login status.

---

## **Session and Authentication**

- **Session Handling:**  
  This API uses PHP sessions to manage user authentication. Upon successful login, a session is created, and a session cookie (`session_id`) is set.

---

# **NewsController API Documentation**

## **Endpoints**

### 1. **Get News by ID**
**URL:** `/news/{id}`

**Method:** `GET`

**Description:**  
Retrieves a single news item by its ID.

**URL Parameters:**
- `{id}` (integer, required) - The ID of the news item to retrieve.

**Responses:**

- **200 OK**
    - **Description:** The news item is successfully retrieved.
    - **Example Response:**
      ```json
      [
        {
          "id": 1,
          "title": "Sample News",
          "description": "This is a sample news description."
        }
      ]
      ```
- **404 Not Found**
    - **Description:** The news item was not found.
    - **Example Response:**
      ```json
      {
        "error": "News not found"
      }
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred while retrieving the news.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```
- **405 Method Not Allowed**
    - **Description:** Invalid HTTP request method.
    - **Example Response:**
      ```json
      {
        "error": "Invalid request method"
      }
      ```

---

### 2. **Delete News by ID**
**URL:** `/news/{id}`

**Method:** `DELETE`

**Description:**  
Deletes a single news item by its ID.

**URL Parameters:**
- `{id}` (integer, required) - The ID of the news item to delete.

**Responses:**

- **200 OK**
    - **Description:** The news item is successfully deleted.
    - **Example Response:**
      ```json
      {
        "message": "News deleted"
      }
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred while deleting the news.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```
- **405 Method Not Allowed**
    - **Description:** Invalid HTTP request method.
    - **Example Response:**
      ```json
      {
        "error": "Invalid request method"
      }
      ```

---

### 3. **Get All News**
**URL:** `/news`

**Method:** `GET`

**Description:**  
Retrieves a list of all news items.

**Responses:**

- **200 OK**
    - **Description:** A list of all news items is successfully retrieved.
    - **Example Response:**
      ```json
      [
        {
          "id": 1,
          "title": "Sample News 1",
          "description": "This is a sample news description."
        },
        {
          "id": 2,
          "title": "Sample News 2",
          "description": "This is another sample news description."
        }
      ]
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred while retrieving the news.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```
- **405 Method Not Allowed**
    - **Description:** Invalid HTTP request method.
    - **Example Response:**
      ```json
      {
        "error": "Invalid request method"
      }
      ```

---

### 4. **Create News**
**URL:** `/news`

**Method:** `POST`

**Description:**  
Creates a new news item.

**Request Body:**
- `title` (string, required) - The title of the news item.
- `description` (string, required) - The description of the news item.

**Request Example:**
```json
{
  "title": "New News Title",
  "description": "This is the description for the new news."
}
```

**Responses:**

- **200 OK**
    - **Description:** The news item is successfully created.
    - **Example Response:**
      ```json
      {
        "message": "News with id 3 has been created"
      }
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred while creating the news.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```
- **405 Method Not Allowed**
    - **Description:** Invalid HTTP request method.
    - **Example Response:**
      ```json
      {
        "error": "Invalid request method"
      }
      ```

---

### 5. **Update News**
**URL:** `/news/{id}`

**Method:** `PUT`

**Description:**  
Updates an existing news item by its ID.

**URL Parameters:**
- `{id}` (integer, required) - The ID of the news item to update.

**Request Body:**
- `title` (string, required) - The new title of the news item.
- `description` (string, required) - The new description of the news item.

**Request Example:**
```json
{
  "title": "Updated News Title",
  "description": "This is the updated description for the news."
}
```

**Responses:**

- **200 OK**
    - **Description:** The news item is successfully updated.
    - **Example Response:**
      ```json
      {
        "message": "News with id 3 has been updated"
      }
      ```
- **400 Bad Request**
    - **Description:** The news ID is invalid.
    - **Example Response:**
      ```json
      {
        "error": "Invalid news id"
      }
      ```
- **500 Internal Server Error**
    - **Description:** An error occurred while updating the news.
    - **Example Response:**
      ```json
      {
        "error": "Database error message"
      }
      ```
- **405 Method Not Allowed**
    - **Description:** Invalid HTTP request method.
    - **Example Response:**
      ```json
      {
        "error": "Invalid request method"
      }
      ```

---

## APP Preview
![image](https://github.com/user-attachments/assets/935683a6-0f9d-4bd8-a5ed-54ff7a86ddab)
![image](https://github.com/user-attachments/assets/b811cf3b-eef9-474d-a4e6-d846cbeb5f45)

## Authors
[Wiktor Korol](https://github.com/wkorol)


