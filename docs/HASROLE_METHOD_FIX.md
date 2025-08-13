# HasRole Method Fix Documentation

## Problem

The application was throwing a `BadMethodCallException: Call to undefined method App\Models\User::hasRole()` error. This occurred because the code was trying to use the `hasRole()` method from the Spatie Laravel Permission package, but the application uses a custom role system.

## Root Cause

The application has a custom role system where:

-   Roles are stored in the `peran` table (not Spatie's `roles` table)
-   Role codes are stored in the `kode_peran` field (e.g., 'ADMIN', 'PETUGAS', 'KEPSEK')
-   Users are linked to roles via the `peran_id` foreign key
-   The `User` model has a `role()` relationship method

However, the code in `AbsensiPengunjungController` was calling `auth()->user()->hasRole('ADMIN')`, which expects the Spatie Permission package's `hasRole` method.

## Solution

Added a custom `hasRole` method to the `User` model that works with the existing custom role system.

### Changes Made

#### 1. Updated `app/Models/User.php`

**Added the following methods:**

```php
/**
 * Check if user has a specific role
 */
public function hasRole($role)
{
    if (!$this->role) {
        return false;
    }

    // Check by role code (kode_peran)
    if (is_string($role)) {
        return $this->role->kode_peran === strtoupper($role);
    }

    // Check by role ID
    if (is_numeric($role)) {
        return $this->role->id == $role;
    }

    // Check by role object
    if (is_object($role) && method_exists($role, 'getKey')) {
        return $this->role->id == $role->getKey();
    }

    return false;
}

/**
 * Check if user has any of the given roles
 */
public function hasAnyRole($roles)
{
    foreach ($roles as $role) {
        if ($this->hasRole($role)) {
            return true;
        }
    }
    return false;
}

/**
 * Check if user has all of the given roles
 */
public function hasAllRoles($roles)
{
    foreach ($roles as $role) {
        if (!$this->hasRole($role)) {
            return false;
        }
    }
    return true;
}
```

### How the Fix Works

1. **String Role Check**: When `hasRole('ADMIN')` is called, it converts the string to uppercase and compares it with the `kode_peran` field in the role table.

2. **Numeric Role Check**: When `hasRole(1)` is called, it compares the numeric ID with the role's ID.

3. **Object Role Check**: When `hasRole($roleObject)` is called, it compares the role object's key with the user's role ID.

4. **Null Safety**: The method returns `false` if the user has no role assigned.

### Usage Examples

```php
// Check by role code
$user->hasRole('ADMIN');        // true if kode_peran = 'ADMIN'
$user->hasRole('PETUGAS');      // true if kode_peran = 'PETUGAS'
$user->hasRole('KEPSEK');       // true if kode_peran = 'KEPSEK'

// Check by role ID
$user->hasRole(1);              // true if role ID = 1

// Check multiple roles
$user->hasAnyRole(['ADMIN', 'PETUGAS']);  // true if user has either role
$user->hasAllRoles(['ADMIN', 'PETUGAS']); // true if user has both roles
```

### Files Affected

-   `app/Models/User.php` - Added custom `hasRole`, `hasAnyRole`, and `hasAllRoles` methods

### Testing

The fix should resolve the `BadMethodCallException` in the following locations:

-   `app/Http/Controllers/AbsensiPengunjungController.php` (lines 52, 85, 151)

### Compatibility

This fix maintains compatibility with:

-   Existing custom role system
-   Current database structure
-   Existing role-based logic in the application
-   Spatie Permission package (for permissions, not roles)

### Notes

-   The Spatie Permission package is still used for permission management
-   The custom role system remains unchanged
-   All existing role checks using `isAdmin()`, `isPetugas()`, etc. continue to work
-   The new `hasRole` method provides a more flexible way to check roles

