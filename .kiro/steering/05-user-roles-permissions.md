---
inclusion: always
priority: 6
---

# User Roles & Permissions

## Role Hierarchy

### 1. Super Admin
- **Full control** over the entire system
- Can create/edit/delete all users including other Admins
- **Only Super Admin can delete other Admins**
- Can edit landing page content
- Can manage everything in CRM
- First admin account is **seeded in the database**

### 2. Admin
- Full control except **cannot delete Super Admin**
- Can create/edit/delete users (except Super Admin)
- Can edit landing page content
- Can manage everything in CRM

### 3. Founder (with sub-roles)
Sub-roles:
- **CEO**: Can view CRM data, CANNOT edit landing page
- **CTO**: Can view CRM data, **CAN edit landing page** (only founder role with this permission)
- **CFO**: Can view CRM data, CANNOT edit landing page

### 4. HR
- Separate role (NOT a founder sub-role)
- Can manage team members (create, edit, deactivate)
- Cannot edit landing page
- Limited CRM access (Phase 2 will define detailed permissions)

### 5. Employee
- **Cannot edit their own profile** — only admins manage team members
- Phase 1: Can view their assigned tasks and time tracking (basic read-only dashboard)
- Cannot edit landing page
- Cannot manage other users
- Specific permissions to be defined in **Phase 2**

## Permission Matrix (Phase 1)

| Action | Super Admin | Admin | CTO | CEO | CFO | HR | Employee |
|--------|-------------|-------|-----|-----|-----|----|----------|
| Delete Super Admin | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Delete Admin | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Create/Edit/Delete Users | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Edit Landing Page | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Manage Blog Posts | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Manage Clients (CRUD) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Manage Projects (CRUD) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| View CRM Data | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ (own tasks) |
| View Contact Submissions | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Edit Own Profile | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

> **Note**: CEO/CFO/CTO CRM management permissions (edit clients, projects) will be defined in **Phase 2**.
> In Phase 1, only Admin/Super Admin can create/edit/delete clients and projects.
> All Founder roles can VIEW CRM data but not modify it (except CTO for landing page).

## Implementation Guidelines

### Backend (Laravel)
- Use **Laravel Policies** for authorization logic
- Use **Laravel Gates** for simple checks
- Check permissions on **every protected route**
- Never rely on frontend-only checks

Example Policy:
```php
public function editLandingPage(User $user): bool
{
    return in_array($user->role, ['super_admin', 'admin']) 
        || ($user->role === 'founder' && $user->founder_sub_role === 'cto');
}

public function deleteAdmin(User $user, User $targetUser): bool
{
    if ($targetUser->role === 'super_admin') {
        return $user->role === 'super_admin' && $user->id !== $targetUser->id;
    }
    return $user->role === 'super_admin';
}
```

### Frontend (Next.js)
- Hide UI elements the user cannot access
- But **backend still enforces** permissions
- Show appropriate error messages for unauthorized actions (403 Forbidden)

### User Creation Fields
- Name (required)
- Email (required, unique)
- Password (required, min 8 chars)
- Role (required dropdown): Admin, Founder, Employee, HR
- If Founder → Sub-role dropdown: CEO, CTO, CFO

### Member Deletion Rules
- When deleting a team member who is a Project Manager → **must choose a new project leader**
- If a member is inactive for **30 days** → automatically marked as inactive
- Inactive members can still log in (logging in reactivates them)
- Admin can manually reactivate a member

### Default Client Protection
- There is a default client **"Tarqumi"** that **CANNOT be removed**
- Delete button is hidden/disabled for this client
- Backend rejects deletion attempts
- Can be edited (email, phone) but NOT renamed

### Project Manager Reassignment
- When deleting a PM, system shows: "This member manages X projects. Please reassign them before deleting."
- Admin must select a new PM for each project
- Only after reassignment can the deletion proceed
