# Authentication System - Implementation Guide

## Overview

A complete authentication system with AJAX-based login for the Hospital Management System.

## Files Created

### 1. **AuthService** (`app/Services/AuthService.php`)

Service class handling authentication business logic:

-   `login()` - Authenticates user with credentials
-   `logout()` - Logs out current user
-   `validateLoginData()` - Validates login input
-   `getRedirectUrl()` - Returns role-based redirect URL
-   `getUserByEmail()` - Retrieves user by email
-   `isUserActive()` - Checks if user account is active

### 2. **AuthController** (`app/Http/Controllers/Auth/AuthController.php`)

Controller handling authentication HTTP requests:

-   `showLoginForm()` - Displays login page
-   `login()` - Processes AJAX login request
-   `logout()` - Handles logout request
-   `checkAuth()` - Checks authentication status

### 3. **CheckRole Middleware** (`app/Http/Middleware/CheckRole.php`)

Middleware for role-based access control:

-   Verifies user authentication
-   Checks user role permissions
-   Redirects unauthorized users

### 4. **Updated Login View** (`resources/views/auth/login.blade.php`)

Enhanced with:

-   AJAX form submission
-   Real-time validation
-   Error handling
-   Loading states
-   Success/error alerts

## Routes Added

```php
// Authentication
GET  /login              - Show login form
POST /login              - Process login (AJAX)
POST /logout             - Logout user
GET  /check-auth         - Check auth status

// Protected Routes (with role middleware)
/admin/*      - Admin access only
/doctor/*     - Doctor access only
/frontdesk/*  - Frontdesk access only
/patient/*    - Patient access only
```

## How to Use

### 1. Login Form

The login form at `/login` uses AJAX to submit credentials:

```javascript
// Form data submitted via fetch API
{
    email: "user@example.com",
    password: "password123",
    remember: true/false
}
```

### 2. Response Format

**Success Response (200):**

```json
{
    "success": true,
    "message": "Login successful!",
    "redirect_url": "/admin/dashboard",
    "user": {
        "name": "John Doe",
        "email": "john@example.com",
        "role": "admin"
    }
}
```

**Validation Error (422):**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

**General Error (500):**

```json
{
    "success": false,
    "message": "An error occurred during login. Please try again.",
    "error": "Detailed error message (debug mode only)"
}
```

### 3. Role-Based Redirects

After successful login, users are redirected based on their role:

-   **Admin** → `/admin/dashboard`
-   **Doctor** → `/doctor/dashboard`
-   **Frontdesk** → `/frontdesk/dashboard`
-   **Patient** → `/patient/dashboard`

### 4. Protected Routes

All role-specific routes are protected with middleware:

```php
// Example: Only admin can access
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});
```

## Features

### ✅ AJAX Login

-   No page reload on submit
-   Real-time validation feedback
-   Loading spinner during request
-   Auto-redirect on success

### ✅ Security

-   CSRF token protection
-   Password hashing (bcrypt)
-   Session regeneration on login
-   Role-based access control

### ✅ User Experience

-   Inline error messages
-   Field-level validation
-   Success/error alerts
-   Auto-dismiss notifications
-   Remember me functionality

### ✅ Error Handling

-   Validation errors displayed per field
-   General error messages in alert
-   Graceful failure recovery
-   Debug mode support

## Testing

### Test User Accounts

After seeding the database, you can use:

```
Admin:
Email: admin@hospital.com
Password: password

Doctor:
Email: doctor@hospital.com
Password: password

Frontdesk:
Email: frontdesk@hospital.com
Password: password

Patient:
Email: patient@hospital.com
Password: password
```

## Integration with Existing Code

### Using Auth in Controllers

```php
// Get current user
$user = auth()->user();

// Check role
if ($user->isAdmin()) {
    // Admin-specific logic
}

// Get user's profile
$doctorProfile = $user->doctorProfile;
$patientProfile = $user->patientProfile;
```

### Using Auth in Blade Views

```blade
@auth
    <p>Welcome, {{ auth()->user()->full_name }}</p>
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest

{{-- Role-specific content --}}
@if(auth()->check() && auth()->user()->isDoctor())
    <p>Doctor-only content</p>
@endif
```

## API Endpoints for Frontend

### Check Authentication Status

```javascript
fetch("/check-auth", {
    headers: {
        Accept: "application/json",
    },
})
    .then((res) => res.json())
    .then((data) => {
        if (data.authenticated) {
            console.log("User:", data.user);
        }
    });
```

### Logout

```javascript
fetch("/logout", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        Accept: "application/json",
    },
})
    .then((res) => res.json())
    .then((data) => {
        if (data.success) {
            window.location.href = data.redirect_url;
        }
    });
```

## Next Steps

1. **Add Logout Button** to dashboards
2. **Create Password Reset** functionality
3. **Add Email Verification**
4. **Implement Remember Me** token cleanup
5. **Add Rate Limiting** to prevent brute force
6. **Session Timeout** handling
7. **Two-Factor Authentication** (optional)

## Notes

-   All passwords are hashed using bcrypt
-   Sessions are stored in the database (see `sessions` table)
-   CSRF protection is enabled by default
-   Inactive users cannot login (status check)
-   All authentication events can be logged to `audit_logs` table
