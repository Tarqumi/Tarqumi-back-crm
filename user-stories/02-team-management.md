# Team Management User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-TEAM-2.1 🔴 Create Team Member with Role Assignment
**As a** Super Admin or Admin, **I want to** create new team members with specific roles, **so that** I can onboard users and grant them appropriate access to the CRM system.

**Acceptance Criteria:**
1. Team member creation form accessible from Team Management page
2. Required fields: Full Name, Email, Role, Status (Active/Inactive)
3. Optional fields: Phone, WhatsApp, Department, Job Title, Profile Picture
4. Available roles: Super Admin, Admin, Founder (CTO), Founder (CEO/CFO), HR, Employee
5. Email must be unique (no duplicate team members)
6. Email validation: proper format, max 255 characters
7. Name validation: min 2 characters, max 100 characters, no special characters except spaces, hyphens, apostrophes
8. Phone validation: international format support, optional country code
9. On successful creation, team member receives welcome email with login credentials
10. New team member appears in team list immediately
11. Creation event logged in audit log with creator, timestamp, assigned role
12. Default password generated automatically (meets complexity requirements) or sent via secure link
13. New team member forced to change password on first login
14. Profile picture upload: max 5MB, formats: JPG, PNG, WebP, auto-resized to 400x400px

**Edge Cases:**
- EC-1: Email already exists → validation error: "This email is already registered"
- EC-2: Super Admin creates another Super Admin → allowed (no restriction)
- EC-3: Admin tries to create Super Admin → 403 Forbidden: "Only Super Admin can create Super Admin accounts"
- EC-4: HR tries to create Admin → 403 Forbidden: "HR role cannot create Admin accounts"
- EC-5: Email with uppercase letters → normalized to lowercase before saving
- EC-6: Name with Arabic characters → accepted and stored correctly
- EC-7: Profile picture exceeds 5MB → validation error with file size shown
- EC-8: Profile picture in unsupported format (e.g., GIF) → validation error listing supported formats
- EC-9: Welcome email fails to send → team member still created, admin notified of email failure
- EC-10: User closes form with unsaved changes → confirmation prompt: "You have unsaved changes. Discard?"
- EC-11: Network timeout during creation → clear error message, form data preserved for retry
- EC-12: Duplicate submission (double-click) → second request rejected, only one account created

**Validation Rules:**
- Email: RFC 5322 compliant, unique in system
- Name: 2-100 characters, letters, spaces, hyphens, apostrophes only
- Phone: E.164 format recommended, 7-15 digits
- WhatsApp: same as phone validation
- Role: must be one of predefined roles
- Status: Active or Inactive only

**Security Considerations:**
- Role assignment validated on backend (frontend selection cannot be bypassed)
- Only authorized roles can create team members (RBAC enforced)
- Password generation uses cryptographically secure random generator
- Welcome email sent from verified domain (SPF, DKIM configured)
- Creation action logged with IP address and user agent

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs, large submit button
- Tablet (768px): Two-column layout for name/email fields
- Desktop (1024px+): Modal dialog (600px width) or side panel

**Performance:**
- Form validation: < 100ms (client-side)
- Team member creation API: < 1 second
- Welcome email delivery: < 30 seconds
- Profile picture upload and resize: < 2 seconds

**UX Considerations:**
- Real-time email availability check (debounced)
- Password strength indicator if manual password entry allowed
- Role selection with descriptions (tooltip explaining each role's permissions)
- Success message with option to "Create Another" or "View Team Member"
- Auto-focus on first field when form opens
- Clear button to reset form

---

## US-TEAM-2.2 🔴 View Team List with Pagination, Search, Filter, and Sort
**As a** Super Admin, Admin, or HR, **I want to** view a comprehensive list of all team members with advanced filtering options, **so that** I can quickly find and manage team members.

**Acceptance Criteria:**
1. Team list displays: Profile Picture, Name, Email, Role, Department, Status, Last Active, Actions
2. Default sort: Name (A-Z)
3. Pagination: 20 members per page (configurable: 10, 20, 50, 100)
4. Search functionality: real-time search by name, email, phone (debounced 300ms)
5. Filter options:
   - Role: All, Super Admin, Admin, Founder (CTO), Founder (CEO/CFO), HR, Employee
   - Status: All, Active, Inactive, Suspended
   - Department: All, Engineering, Sales, Marketing, HR, Finance, Operations, Other
   - Last Active: All, Today, This Week, This Month, 30+ Days Ago
6. Sort options: Name (A-Z, Z-A), Email (A-Z, Z-A), Role, Status, Last Active (Newest, Oldest)
7. Multi-select for bulk actions (checkbox per row)
8. "Select All" checkbox (selects all on current page)
9. Empty state message when no results: "No team members found. Try adjusting your filters."
10. Loading skeleton while fetching data
11. Total count displayed: "Showing 1-20 of 150 team members"
12. Filter and search state preserved in URL (shareable links)
13. "Clear Filters" button to reset all filters
14. Export to CSV button (exports filtered results)

**Edge Cases:**
- EC-1: Search with no results → empty state with "No matches found for '[query]'"
- EC-2: Filter combination returns zero results → empty state with active filters shown
- EC-3: User on page 5, applies filter that returns only 2 pages → automatically redirected to page 1
- EC-4: Very long name (100 chars) → truncated with ellipsis, full name in tooltip
- EC-5: Team member with no profile picture → default avatar with initials
- EC-6: Last Active is null (never logged in) → displays "Never"
- EC-7: Search with special characters → properly escaped, no errors
- EC-8: Rapid filter changes → debounced API calls, previous requests cancelled
- EC-9: User navigates away and returns → filters and page number restored from URL
- EC-10: Export with 1000+ members → background job, download link sent via email
- EC-11: Multiple users viewing list → real-time updates when team member added/edited/deleted (optional)
- EC-12: User with slow connection → pagination controls disabled until data loads

**Validation Rules:**
- Search query: max 255 characters
- Page number: positive integer, within valid range
- Per page: one of allowed values (10, 20, 50, 100)

**Security Considerations:**
- HR can only view team members, not Super Admins (filtered out)
- Employee can only view their own profile (redirected if accessing team list)
- Founder roles can view all team members (read-only)
- Export action logged in audit log
- Search queries logged for analytics (anonymized)

**Responsive Design:**
- Mobile (375px): Card layout instead of table, filters in collapsible drawer
- Tablet (768px): Table with horizontal scroll, sticky header
- Desktop (1024px+): Full table with all columns visible, filters in sidebar

**Performance:**
- Initial page load: < 1 second
- Search/filter response: < 500ms
- Pagination navigation: < 300ms
- Export CSV (100 members): < 3 seconds
- Real-time search: debounced 300ms to reduce API calls

**UX Considerations:**
- Active filters displayed as removable chips above table
- Sort direction indicator (arrow icon) in column headers
- Hover state on table rows for better readability
- Sticky table header when scrolling
- Keyboard navigation support (arrow keys, Enter to select)
- Loading indicator for async operations
- Success toast when bulk action completes

---

## US-TEAM-2.3 🔴 Edit Team Member Details
**As a** Super Admin or Admin, **I want to** edit existing team member information, **so that** I can keep team data accurate and up-to-date.

**Acceptance Criteria:**
1. Edit button available in team list actions column and team member detail page
2. Edit form pre-populated with current team member data
3. Editable fields: Full Name, Email, Phone, WhatsApp, Role, Status, Department, Job Title, Profile Picture
4. Email uniqueness validated (excluding current member's email)
5. Role change triggers permission update immediately
6. Status change from Active to Inactive triggers 30-day countdown (if applicable)
7. On successful update, team member data refreshed in list and detail views
8. Update event logged in audit log with editor, timestamp, changed fields
9. Team member receives email notification if email or role changed
10. Optimistic UI update (immediate feedback, rollback on error)
11. "Save" and "Cancel" buttons clearly visible
12. Unsaved changes warning if user navigates away
13. Field-level validation with inline error messages
14. Success message: "Team member updated successfully"

**Edge Cases:**
- EC-1: Admin tries to edit Super Admin → 403 Forbidden (only Super Admin can edit Super Admin)
- EC-2: Admin tries to change own role to Super Admin → 403 Forbidden
- EC-3: Last Super Admin tries to change own role → validation error: "Cannot change role. At least one Super Admin required."
- EC-4: Email changed to existing email → validation error: "Email already in use"
- EC-5: Role changed from Admin to Employee → permissions immediately revoked, user logged out if active
- EC-6: Status changed to Inactive while user is logged in → user's session terminated immediately
- EC-7: Profile picture changed → old picture deleted from storage, new picture uploaded
- EC-8: No changes made, user clicks Save → no API call, message: "No changes to save"
- EC-9: Concurrent edits by two admins → last save wins, warning shown: "This member was recently updated by [Admin Name]"
- EC-10: Network error during save → error message, form data preserved, retry option
- EC-11: HR tries to change role to Admin → 403 Forbidden: "HR cannot assign Admin role"
- EC-12: User edits own profile (limited fields) → allowed, role change not permitted

**Validation Rules:**
- Same validation rules as team member creation (US-TEAM-2.1)
- Email uniqueness check excludes current member's email
- Role change validated based on editor's permissions

**Security Considerations:**
- Role change authorization checked on backend
- Only Super Admin can edit Super Admin accounts
- Admin cannot escalate own privileges
- Last Super Admin cannot be demoted or deactivated
- All changes logged with before/after values
- Sensitive changes (role, status) trigger email notifications

**Responsive Design:**
- Mobile (375px): Full-screen edit form, scrollable
- Tablet (768px): Modal dialog (600px width)
- Desktop (1024px+): Side panel or modal dialog

**Performance:**
- Form load with pre-populated data: < 500ms
- Update API response: < 1 second
- Profile picture upload: < 2 seconds
- Email uniqueness check: < 200ms (debounced)

**UX Considerations:**
- Changed fields highlighted with subtle background color
- Confirmation dialog for critical changes (role, status)
- Auto-save draft (optional, stored in localStorage)
- Keyboard shortcuts: Ctrl+S to save, Esc to cancel
- Focus management: return focus to edit button after cancel
- Inline validation as user types (debounced)

---

## US-TEAM-2.4 🟠 Delete Team Member with Project Reassignment
**As a** Super Admin, **I want to** delete team members and reassign their projects, **so that** I can remove users while preserving project continuity.

**Acceptance Criteria:**
1. Delete button available in team list actions and team member detail page
2. Delete action requires confirmation dialog with warning message
3. If team member is Project Manager on active projects, reassignment required before deletion
4. Reassignment dialog shows:
   - List of affected projects (name, status, client)
   - Dropdown to select new Project Manager for each project
   - Option to "Reassign all to [Admin Name]"
5. If team member has no active projects, deletion proceeds immediately after confirmation
6. Deleted team member's account deactivated (soft delete, not hard delete)
7. Deleted team member cannot login (credentials invalidated)
8. Deleted team member's data retained for audit purposes (90 days minimum)
9. Deletion event logged in audit log with deleter, timestamp, reassignment details
10. Projects successfully reassigned to new Project Manager
11. New Project Manager receives email notification of assigned projects
12. Deleted team member removed from team list
13. Success message: "Team member deleted and [X] projects reassigned to [New PM]"

**Edge Cases:**
- EC-1: Admin tries to delete Super Admin → 403 Forbidden: "Only Super Admin can delete Super Admin accounts"
- EC-2: Last Super Admin tries to delete self → validation error: "Cannot delete last Super Admin"
- EC-3: Team member is PM on 20+ projects → reassignment dialog paginated or searchable
- EC-4: No other team members available for reassignment → error: "Cannot delete. No available Project Managers for reassignment."
- EC-5: User cancels reassignment dialog → deletion cancelled, no changes made
- EC-6: Team member deleted while logged in → session terminated immediately, redirected to login
- EC-7: Concurrent deletion attempt → second attempt fails: "Team member already deleted"
- EC-8: Network error during deletion → rollback, error message, retry option
- EC-9: Team member has completed projects only → deletion proceeds without reassignment
- EC-10: Team member is assigned to tasks (not PM) → tasks remain assigned (historical record)
- EC-11: HR tries to delete team member → 403 Forbidden: "HR role cannot delete team members"
- EC-12: Admin tries to delete self → confirmation: "Are you sure you want to delete your own account?"

**Validation Rules:**
- Deleter must have delete permission (Super Admin or Admin)
- Cannot delete last Super Admin
- All active projects must be reassigned before deletion
- New Project Manager must have appropriate role (Admin, Super Admin, or Founder)

**Security Considerations:**
- Deletion is soft delete (data retained for audit)
- Hard delete after 90 days (configurable, automated job)
- Deletion action requires confirmation (prevent accidental deletion)
- All deletion events logged with full context
- Deleted user's sessions immediately invalidated
- Deleted user's API tokens revoked

**Responsive Design:**
- Mobile (375px): Full-screen reassignment dialog, scrollable project list
- Tablet (768px): Modal dialog (700px width)
- Desktop (1024px+): Modal dialog with side-by-side project list and reassignment options

**Performance:**
- Deletion API (no projects): < 500ms
- Deletion with reassignment (10 projects): < 2 seconds
- Project reassignment per project: < 200ms
- Session invalidation: < 100ms

**UX Considerations:**
- Clear warning message: "This action cannot be undone"
- Project count displayed: "This member is PM on [X] active projects"
- Bulk reassignment option: "Reassign all to [dropdown]"
- Progress indicator during reassignment
- Confirmation checkbox: "I understand this will reassign [X] projects"
- Cancel button prominently displayed
- Success message with undo option (within 30 seconds)

---

## US-TEAM-2.5 🟠 30-Day Auto-Inactive Functionality
**As a** system, **I want to** automatically mark team members as inactive after 30 days of no login activity, **so that** inactive accounts are identified and security is maintained.

**Acceptance Criteria:**
1. System tracks "Last Login" timestamp for each team member
2. Automated daily job runs at 2:00 AM (configurable) to check for inactive accounts
3. Team members with no login for 30+ days automatically marked as "Inactive"
4. Status change from "Active" to "Inactive" logged in audit log
5. Admin receives weekly email report of newly inactive accounts
6. Inactive team members cannot login (credentials disabled)
7. Inactive team members displayed with "Inactive" badge in team list
8. Admin can manually reactivate inactive accounts
9. Reactivation triggers password reset (security measure)
10. Reactivated team member receives email with password reset link
11. Super Admin and Admin accounts exempt from auto-inactive (configurable)
12. Warning email sent to team member at 25 days of inactivity: "Your account will be deactivated in 5 days due to inactivity"
13. Warning email includes "Click here to stay active" link (logs them in, resets counter)
14. Auto-inactive feature can be enabled/disabled in system settings

**Edge Cases:**
- EC-1: Team member logs in on day 29 → counter resets, account remains active
- EC-2: Team member clicks "stay active" link in warning email → counter resets
- EC-3: Inactive team member tries to login → error: "Your account has been deactivated due to inactivity. Contact administrator."
- EC-4: Admin manually marks team member as inactive before 30 days → auto-inactive job skips this account
- EC-5: Team member reactivated, then doesn't login for 30 days → marked inactive again
- EC-6: Super Admin never logs in for 60 days → remains active (exempt from auto-inactive)
- EC-7: Team member on vacation (30+ days) → marked inactive, admin can reactivate upon return
- EC-8: Automated job fails to run → alert sent to Super Admin, job retried
- EC-9: Multiple team members become inactive same day → batch email report sent to admin
- EC-10: Team member deleted before 30 days → excluded from auto-inactive check
- EC-11: Warning email fails to send → logged, admin notified, retry attempted
- EC-12: Team member's email bounces → marked in system, admin notified

**Validation Rules:**
- Last Login timestamp must be valid date
- Inactive threshold: 30 days (configurable in settings)
- Warning threshold: 25 days (configurable in settings)
- Exempted roles: configurable list (default: Super Admin, Admin)

**Security Considerations:**
- Inactive accounts cannot be used for unauthorized access
- Reactivation requires password reset (prevents stale credentials)
- Auto-inactive events logged for security audit
- "Stay active" link is single-use, expires after 24 hours
- "Stay active" link includes secure token (prevents abuse)

**Responsive Design:**
- Warning email responsive, readable on all devices
- Admin report email formatted for mobile and desktop

**Performance:**
- Daily automated job: < 5 minutes for 1000 team members
- Inactive status update per member: < 100ms
- Warning email delivery: < 30 seconds per member
- Admin report generation: < 1 minute

**UX Considerations:**
- Warning email clear and actionable
- "Stay active" link prominent in email
- Admin report includes reactivation instructions
- Inactive badge color-coded (gray) in team list
- Reactivation process simple (one-click from admin panel)
- Success message after reactivation: "Team member reactivated. Password reset email sent."

---

## US-TEAM-2.6 🟠 Bulk Invite Team Members
**As a** Super Admin or Admin, **I want to** invite multiple team members at once via CSV upload or manual entry, **so that** I can efficiently onboard large teams.

**Acceptance Criteria:**
1. Bulk invite accessible from Team Management page ("Bulk Invite" button)
2. Two input methods:
   - CSV file upload (template downloadable)
   - Manual entry (textarea with one email per line)
3. CSV template includes columns: Name, Email, Role, Department, Job Title
4. CSV validation: max 100 members per upload, file size max 1MB
5. Preview screen shows parsed data before confirmation
6. Validation errors highlighted per row (e.g., "Row 5: Invalid email format")
7. Option to skip invalid rows and proceed with valid ones
8. Bulk invite sends welcome emails to all valid members
9. Progress indicator during processing: "Inviting 45 of 50 members..."
10. Summary report after completion:
    - Successfully invited: [X] members
    - Failed: [Y] members (with reasons)
    - Duplicate emails skipped: [Z] members
11. Failed invites downloadable as CSV for correction and retry
12. All invited members appear in team list with "Pending" status until first login
13. Bulk invite event logged in audit log with inviter, count, timestamp

**Edge Cases:**
- EC-1: CSV with duplicate emails → duplicates skipped, warning shown
- EC-2: CSV with existing team member emails → skipped, listed in summary
- EC-3: CSV with invalid role names → default to "Employee", warning shown
- EC-4: CSV with missing required fields (Name or Email) → row skipped, error listed
- EC-5: CSV with 150 rows → validation error: "Maximum 100 members per upload"
- EC-6: CSV with incorrect encoding (non-UTF-8) → parsing error, clear message
- EC-7: Email delivery fails for some members → marked as failed, admin notified
- EC-8: User uploads Excel file instead of CSV → validation error: "Please upload CSV format"
- EC-9: Network timeout during bulk invite → partial completion, resume option provided
- EC-10: Admin tries to bulk invite Super Admins → role downgraded to Admin, warning shown
- EC-11: HR tries to bulk invite → 403 Forbidden: "HR role cannot bulk invite"
- EC-12: Empty CSV uploaded → validation error: "CSV file is empty"

**Validation Rules:**
- CSV format: UTF-8 encoding, comma-separated
- Max rows: 100 per upload
- Max file size: 1MB
- Required columns: Name, Email
- Optional columns: Role, Department, Job Title, Phone, WhatsApp
- Email format validation per row
- Role validation: must be valid role name

**Security Considerations:**
- CSV file scanned for malicious content
- Role assignment validated (cannot bulk create Super Admins)
- Bulk invite action requires elevated permissions
- All invites logged individually in audit log
- Rate limiting: max 3 bulk invites per hour per admin

**Responsive Design:**
- Mobile (375px): Full-screen upload interface, scrollable preview table
- Tablet (768px): Modal dialog (800px width)
- Desktop (1024px+): Modal dialog with side-by-side upload and preview

**Performance:**
- CSV parsing (100 rows): < 1 second
- Bulk invite processing (100 members): < 30 seconds
- Email delivery (100 members): < 2 minutes (queued)
- Progress updates: real-time (WebSocket or polling)

**UX Considerations:**
- CSV template download link prominent
- Drag-and-drop file upload support
- Preview table with sortable columns
- Clear error messages with row numbers
- "Fix and Retry" button for failed invites
- Success message with link to view invited members
- Option to send custom welcome message in bulk invite

---

## US-TEAM-2.7 🟠 Bulk Role Change
**As a** Super Admin, **I want to** change roles for multiple team members at once, **so that** I can efficiently manage permissions during organizational changes.

**Acceptance Criteria:**
1. Bulk role change accessible from team list (multi-select enabled)
2. Select multiple team members via checkboxes
3. "Change Role" button appears in bulk actions toolbar when members selected
4. Role selection dropdown shows available roles
5. Confirmation dialog shows:
   - Count of selected members
   - Current roles of selected members
   - New role to be assigned
   - Warning if permissions will be reduced
6. On confirmation, all selected members' roles updated simultaneously
7. Role change triggers immediate permission update for active sessions
8. Each role change logged individually in audit log
9. Affected team members receive email notification of role change
10. Success message: "[X] team members' roles updated to [Role]"
11. Team list refreshes to show updated roles
12. Bulk role change event logged with initiator, count, old/new roles

**Edge Cases:**
- EC-1: Selection includes Super Admin → Super Admin excluded, warning shown: "Super Admin roles cannot be changed in bulk"
- EC-2: Admin tries to bulk assign Super Admin role → 403 Forbidden
- EC-3: Selection includes self → confirmation: "This will change your own role. Continue?"
- EC-4: All selected members already have target role → message: "No changes needed. All selected members already have [Role]."
- EC-5: Some members have target role, others don't → only members with different roles updated
- EC-6: Network error during bulk update → partial completion, retry option for failed updates
- EC-7: Concurrent role change by another admin → conflict detected, user prompted to refresh
- EC-8: Selected members logged in with active sessions → sessions updated immediately or terminated based on role change
- EC-9: Role change from Admin to Employee → permissions immediately revoked, active sessions terminated
- EC-10: HR tries to bulk change roles → 403 Forbidden: "HR role cannot change roles"
- EC-11: Selection includes inactive members → inactive members excluded, warning shown
- EC-12: Very large selection (100+ members) → confirmation: "This will update [X] members. This may take a few minutes."

**Validation Rules:**
- At least one member must be selected
- Target role must be valid role
- Cannot bulk assign Super Admin role (security restriction)
- Cannot change last Super Admin's role
- Initiator must have permission to assign target role

**Security Considerations:**
- Role change authorization validated per member
- Super Admin roles protected from bulk changes
- Last Super Admin cannot be demoted
- All role changes logged individually
- Active sessions updated or terminated based on new permissions
- Email notifications sent for audit trail

**Responsive Design:**
- Mobile (375px): Bulk actions toolbar sticky at bottom, full-screen confirmation dialog
- Tablet (768px): Bulk actions toolbar at top of table
- Desktop (1024px+): Bulk actions toolbar with inline role selector

**Performance:**
- Bulk role change (10 members): < 2 seconds
- Bulk role change (100 members): < 10 seconds
- Session permission update: < 500ms per active session
- Email notifications: queued, delivered within 1 minute

**UX Considerations:**
- Selected count displayed: "[X] members selected"
- "Select All" and "Deselect All" buttons
- Role change preview before confirmation
- Progress indicator for large bulk operations
- Undo option (within 30 seconds) for accidental changes
- Clear success/error messages with details
- Option to export list of updated members

---

## US-TEAM-2.8 🔴 View Team Member Profile
**As a** Super Admin, Admin, HR, or Founder, **I want to** view detailed team member profiles, **so that** I can access comprehensive information about each team member.

**Acceptance Criteria:**
1. Profile accessible by clicking team member name in team list
2. Profile displays:
   - Profile picture (large, 200x200px)
   - Full name, email, phone, WhatsApp
   - Role, department, job title
   - Status (Active/Inactive/Suspended) with badge
   - Account created date
   - Last login date and time
   - Last active date and time
   - Total projects managed (if PM)
   - Current active projects (if PM)
   - Login history (last 10 logins with IP, device, location)
   - Activity log (last 20 actions)
3. Action buttons based on viewer's permissions:
   - Edit (Super Admin, Admin)
   - Delete (Super Admin, Admin)
   - Reset Password (Super Admin, Admin)
   - Deactivate/Activate (Super Admin, Admin)
   - Send Message (all roles)
4. Projects section shows projects where member is PM (with status, client, dates)
5. Activity timeline shows recent actions (created project, edited client, etc.)
6. Profile URL shareable: `/team/[member-id]`
7. Breadcrumb navigation: Team Management > [Member Name]
8. "Back to Team List" button
9. Profile data refreshes when navigating between members (no stale data)

**Edge Cases:**
- EC-1: Team member has no profile picture → default avatar with initials displayed
- EC-2: Team member never logged in → "Last Login: Never" displayed
- EC-3: Team member has no projects → "No projects assigned" message
- EC-4: Team member has 50+ projects → projects paginated, "View All Projects" link
- EC-5: Activity log empty → "No recent activity" message
- EC-6: Login history empty → "No login history available"
- EC-7: HR views Super Admin profile → limited information shown (no login history, no activity log)
- EC-8: Employee views own profile → full information shown, no action buttons
- EC-9: Deleted team member profile accessed → 404 error: "Team member not found"
- EC-10: Profile URL with invalid ID → 404 error
- EC-11: Very long name or email → truncated with ellipsis, full text in tooltip
- EC-12: Team member from different timezone → timestamps displayed in viewer's timezone

**Validation Rules:**
- Member ID must be valid UUID or integer
- Viewer must have permission to view profiles
- HR cannot view Super Admin profiles

**Security Considerations:**
- Profile access logged in audit log
- Sensitive information (login history, activity log) restricted based on viewer role
- Profile URLs not guessable (use UUID instead of sequential ID)
- No sensitive data exposed in profile (no password hash, no tokens)

**Responsive Design:**
- Mobile (375px): Single column layout, stacked sections, collapsible activity log
- Tablet (768px): Two-column layout (info left, activity right)
- Desktop (1024px+): Three-column layout or side panel

**Performance:**
- Profile load: < 1 second
- Activity log load: < 500ms
- Projects list load: < 500ms
- Login history load: < 500ms

**UX Considerations:**
- Profile picture clickable to view full size
- Copy email/phone buttons for quick access
- Status badge color-coded (green: Active, gray: Inactive, red: Suspended)
- Activity timeline with icons for different action types
- "Send Message" opens email client with pre-filled recipient
- Keyboard navigation: arrow keys to navigate between profiles
- Print-friendly profile view (optional)

---

## US-TEAM-2.9 🔴 Permission Matrix Display and Management
**As a** Super Admin, **I want to** view and manage the permission matrix for all roles, **so that** I understand and control what each role can access.

**Acceptance Criteria:**
1. Permission matrix accessible from Settings > Roles & Permissions
2. Matrix displays all roles (columns) and all permissions (rows)
3. Permissions grouped by module:
   - Team Management (Create, Read, Update, Delete, Bulk Actions)
   - Client Management (Create, Read, Update, Delete, Import/Export)
   - Project Management (Create, Read, Update, Delete, Assign PM)
   - Landing Page CMS (Edit Content, Edit SEO, Manage Media)
   - Blog System (Create, Edit, Delete, Publish)
   - Contact Submissions (View, Delete, Export)
   - System Settings (View, Edit, Manage Users, Manage Roles)
4. Each cell shows checkmark (✓) if role has permission, empty if not
5. Super Admin can toggle permissions by clicking cells
6. Permission changes take effect immediately for all users with that role
7. Active sessions updated with new permissions (or terminated if critical permission removed)
8. Permission change logged in audit log with admin, role, permission, action (granted/revoked)
9. "Reset to Default" button to restore default permission matrix
10. Export permission matrix to CSV or PDF
11. Permission matrix includes descriptions for each permission
12. Visual indicators for inherited permissions (if role hierarchy implemented)

**Edge Cases:**
- EC-1: Super Admin tries to remove own critical permissions → warning: "This will affect your own access. Continue?"
- EC-2: Last Super Admin tries to revoke Super Admin permissions → validation error: "Cannot revoke permissions from last Super Admin"
- EC-3: Permission revoked for role with active users → users' sessions updated immediately
- EC-4: Conflicting permissions (e.g., Delete without Read) → validation warning shown
- EC-5: Permission matrix very large (50+ permissions) → scrollable with sticky headers
- EC-6: Concurrent permission changes by two admins → last change wins, conflict notification
- EC-7: Permission change fails to apply → rollback, error message, retry option
- EC-8: Admin (non-Super Admin) tries to access permission matrix → 403 Forbidden
- EC-9: Permission matrix export with 100+ permissions → generated in background, download link provided
- EC-10: User's role changed while viewing permission matrix → matrix refreshes automatically
- EC-11: Permission descriptions very long → truncated with "Read More" link
- EC-12: Mobile device with small screen → matrix scrollable horizontally and vertically

**Validation Rules:**
- Only Super Admin can modify permission matrix
- Cannot remove all permissions from a role
- Cannot create role with no permissions
- Super Admin role must always have all permissions

**Security Considerations:**
- Permission changes logged with full context
- Critical permission changes (e.g., delete users) require confirmation
- Permission matrix changes trigger email notification to all Super Admins
- Active sessions updated within 1 minute of permission change
- Permission matrix stored in database with version history

**Responsive Design:**
- Mobile (375px): Accordion layout, one role at a time, expandable permission groups
- Tablet (768px): Scrollable table with sticky headers
- Desktop (1024px+): Full matrix view with fixed headers and columns

**Performance:**
- Permission matrix load: < 1 second
- Permission toggle: < 200ms
- Active session update: < 1 minute (background job)
- Matrix export: < 3 seconds

**UX Considerations:**
- Color-coded cells (green: granted, gray: denied)
- Hover tooltip with permission description
- "Select All" / "Deselect All" per role or per permission
- Search/filter permissions by name or module
- Confirmation dialog for critical permission changes
- Undo option (within 30 seconds) for accidental changes
- Visual diff showing what changed after update

---

## US-TEAM-2.10 🟠 Track Team Member Activity
**As a** Super Admin or Admin, **I want to** track and view team member activity, **so that** I can monitor productivity and identify issues.

**Acceptance Criteria:**
1. Activity tracking for all team members (automatic, no manual logging)
2. Tracked activities:
   - Login/logout events
   - Projects created, edited, deleted
   - Clients created, edited, deleted
   - Team members created, edited, deleted
   - Landing page content changes
   - Blog posts created, edited, published
   - Contact submissions viewed
   - Settings changes
3. Activity log displays: Timestamp, Team Member, Action, Resource, Details
4. Activity log accessible from:
   - Team member profile (individual activity)
   - Activity Dashboard (all team activity)
5. Filter options:
   - Team member: All or specific member
   - Action type: All, Create, Edit, Delete, View, Login/Logout
   - Module: All, Team, Clients, Projects, CMS, Blog, Settings
   - Date range: Today, This Week, This Month, Custom Range
6. Search functionality: search by resource name or details
7. Pagination: 50 activities per page
8. Export activity log to CSV
9. Activity retention: 90 days (configurable)
10. Real-time activity feed (optional, WebSocket-based)

**Edge Cases:**
- EC-1: Team member performs 100+ actions in one day → all logged, no limit
- EC-2: Activity log query spans 1 year → performance warning, suggest narrower date range
- EC-3: Deleted team member's activities → preserved with "[Deleted User]" label
- EC-4: Activity details very long (e.g., full blog post content) → truncated, "View Full Details" link
- EC-5: No activities match filters → empty state: "No activities found for selected filters"
- EC-6: Export with 10,000+ activities → background job, download link sent via email
- EC-7: Real-time feed with high activity volume → throttled to prevent UI overload
- EC-8: Activity timestamp in different timezone → displayed in viewer's timezone
- EC-9: Concurrent activities by same user → all logged with precise timestamps (milliseconds)
- EC-10: Activity log database table grows very large → archived to separate table after 90 days
- EC-11: HR views activity log → can only see team-related activities, not all modules
- EC-12: Employee tries to access activity log → 403 Forbidden

**Validation Rules:**
- Date range: start date must be before end date
- Date range: max 1 year span
- Export: max 50,000 activities per export

**Security Considerations:**
- Activity log access restricted to Super Admin and Admin
- Sensitive activities (password changes, role changes) highlighted
- Activity log cannot be modified or deleted
- Activity log includes IP address and user agent for security audit
- Suspicious activity patterns trigger alerts (e.g., 100 deletions in 1 hour)

**Responsive Design:**
- Mobile (375px): Card layout, collapsible activity details
- Tablet (768px): Table with horizontal scroll
- Desktop (1024px+): Full table with all columns visible

**Performance:**
- Activity log load (50 activities): < 1 second
- Activity log filter/search: < 500ms
- Real-time activity feed: < 100ms latency
- Export (1,000 activities): < 5 seconds

**UX Considerations:**
- Activity icons for different action types (create, edit, delete, view)
- Color-coded activities (green: create, blue: edit, red: delete)
- Relative timestamps (e.g., "2 hours ago") with absolute time in tooltip
- "Load More" button for infinite scroll (optional)
- Activity details expandable inline
- Quick filters (e.g., "My Activity", "Today", "This Week")
- Activity feed auto-refreshes every 30 seconds (if real-time not enabled)

---

## US-TEAM-2.11 🟠 Manage Team Member Status (Active/Inactive/Suspended)
**As a** Super Admin or Admin, **I want to** manage team member status with different states, **so that** I can control access based on employment status and security needs.

**Acceptance Criteria:**
1. Three status states: Active, Inactive, Suspended
2. **Active**: Full access, can login and perform all permitted actions
3. **Inactive**: Cannot login, typically for terminated employees or long-term leave
4. **Suspended**: Temporary access restriction, typically for security or policy violations
5. Status change accessible from:
   - Team member profile (status dropdown)
   - Team list (quick action menu)
   - Bulk actions (change status for multiple members)
6. Status change requires reason (dropdown + optional notes):
   - Inactive reasons: Terminated, Resigned, Long-term Leave, Other
   - Suspended reasons: Security Violation, Policy Violation, Investigation, Other
7. Status change confirmation dialog with warning about access impact
8. Status change takes effect immediately (active sessions terminated)
9. Status change logged in audit log with changer, reason, notes, timestamp
10. Team member receives email notification of status change (except for security suspensions)
11. Suspended members can be auto-reactivated after specified duration (optional)
12. Status history tracked (all status changes with dates and reasons)
13. Status badge displayed in team list and profile (color-coded)

**Edge Cases:**
- EC-1: Last Super Admin tries to change own status to Inactive → validation error: "Cannot deactivate last Super Admin"
- EC-2: Admin tries to suspend Super Admin → 403 Forbidden
- EC-3: Team member suspended while logged in → session terminated immediately, redirected to login with message
- EC-4: Suspended member tries to login → error: "Your account has been suspended. Contact administrator."
- EC-5: Inactive member tries to login → error: "Your account is inactive. Contact administrator."
- EC-6: Status changed from Inactive to Active → password reset required before first login
- EC-7: Status changed from Suspended to Active → email notification sent with explanation
- EC-8: Bulk status change includes self → confirmation: "This will change your own status. Continue?"
- EC-9: Auto-reactivation date passes → status automatically changed to Active, email sent
- EC-10: Status change reason not provided → validation error: "Please select a reason"
- EC-11: HR tries to suspend team member → 403 Forbidden: "HR role cannot suspend members"
- EC-12: Concurrent status change by two admins → last change wins, conflict notification

**Validation Rules:**
- Status must be one of: Active, Inactive, Suspended
- Reason required for Inactive and Suspended status
- Cannot change last Super Admin to Inactive or Suspended
- Auto-reactivation date must be in future (if provided)

**Security Considerations:**
- Status change authorization validated
- Suspended members' sessions immediately terminated
- Suspended members' API tokens revoked
- Security suspension doesn't send email notification (prevent alerting malicious user)
- All status changes logged with full context
- Status history immutable (cannot be edited or deleted)

**Responsive Design:**
- Mobile (375px): Full-screen status change dialog
- Tablet (768px): Modal dialog (500px width)
- Desktop (1024px+): Modal dialog with status history sidebar

**Performance:**
- Status change: < 500ms
- Session termination: < 100ms
- Email notification: < 30 seconds
- Status history load: < 300ms

**UX Considerations:**
- Status badge color-coded (green: Active, gray: Inactive, red: Suspended)
- Status change confirmation with clear impact description
- Reason dropdown with common reasons pre-populated
- Optional notes field for additional context
- Status history timeline in profile
- Quick status toggle in team list (Active ↔ Inactive)
- Bulk status change with reason applied to all selected members

---

## US-TEAM-2.12 🟡 Team Member Import from External Systems
**As a** Super Admin, **I want to** import team members from external HR systems or directories, **so that** I can synchronize user data and avoid manual entry.

**Acceptance Criteria:**
1. Import accessible from Team Management > Import
2. Supported import sources:
   - CSV file upload
   - LDAP/Active Directory integration
   - Google Workspace integration
   - Microsoft Azure AD integration
3. CSV import:
   - Template downloadable with required columns
   - Validation before import (same as bulk invite)
   - Mapping interface to match CSV columns to system fields
   - Preview before final import
4. LDAP/AD import:
   - Connection settings: Server URL, Port, Base DN, Bind DN, Password
   - Test connection button
   - User filter (e.g., memberOf=CN=CRM Users)
   - Attribute mapping (LDAP attributes to system fields)
   - Sync schedule: Manual, Daily, Weekly
5. Google Workspace import:
   - OAuth authentication
   - Organization unit selection
   - Field mapping
   - Sync schedule
6. Azure AD import:
   - OAuth authentication
   - Group selection
   - Field mapping
   - Sync schedule
7. Import options:
   - Create new members only
   - Update existing members
   - Create and update (upsert)
8. Conflict resolution:
   - Skip duplicates
   - Update duplicates
   - Prompt for each duplicate
9. Import summary report:
   - Total records processed
   - Successfully imported
   - Updated
   - Skipped (duplicates)
   - Failed (with reasons)
10. Import history: list of all imports with date, source, count, status

**Edge Cases:**
- EC-1: LDAP connection fails → clear error message with troubleshooting tips
- EC-2: OAuth authentication fails → re-authentication prompt
- EC-3: Import includes 500+ members → background job, progress notifications
- EC-4: Imported member has no email → skipped, listed in failed records
- EC-5: Imported member's role not recognized → default to Employee, warning shown
- EC-6: Sync schedule runs but source unavailable → retry 3 times, then alert admin
- EC-7: Imported member already exists with different role → conflict resolution based on settings
- EC-8: LDAP attribute mapping incomplete → validation error before import
- EC-9: Import interrupted (network error) → partial import, resume option
- EC-10: Imported members exceed license limit → import stopped, upgrade prompt
- EC-11: CSV encoding issues (non-UTF-8) → auto-detect encoding or prompt user
- EC-12: Scheduled sync creates 100+ new members → alert sent to admin for review

**Validation Rules:**
- Connection settings validated before saving
- Attribute mapping validated (required fields must be mapped)
- Import source must be configured before import
- Sync schedule must be valid cron expression

**Security Considerations:**
- LDAP/AD credentials encrypted at rest
- OAuth tokens stored securely
- Import action logged in audit log
- Imported members' passwords not imported (generated or SSO-based)
- Connection test doesn't expose sensitive data
- Import limited to authorized roles (Super Admin only)

**Responsive Design:**
- Mobile (375px): Multi-step wizard, one step per screen
- Tablet (768px): Modal dialog with progress sidebar
- Desktop (1024px+): Full-screen import interface with preview panel

**Performance:**
- CSV import (100 members): < 30 seconds
- LDAP sync (100 members): < 1 minute
- Connection test: < 5 seconds
- Import preview generation: < 2 seconds

**UX Considerations:**
- Step-by-step wizard for complex imports
- Connection test with success/failure feedback
- Attribute mapping with drag-and-drop interface
- Import preview with sample data
- Progress bar during import
- Detailed error messages with row numbers
- "Retry Failed" button to re-import only failed records
- Import history with downloadable reports

---

## US-TEAM-2.13 🟡 Team Member Export and Reporting
**As a** Super Admin or Admin, **I want to** export team member data and generate reports, **so that** I can analyze team composition and share data with stakeholders.

**Acceptance Criteria:**
1. Export accessible from Team Management > Export
2. Export formats: CSV, Excel (XLSX), PDF
3. Export options:
   - All team members
   - Filtered results (based on current filters)
   - Selected members (multi-select)
4. Field selection: choose which fields to include in export
5. Available fields:
   - Basic: Name, Email, Phone, WhatsApp
   - Role & Status: Role, Status, Department, Job Title
   - Dates: Created Date, Last Login, Last Active
   - Activity: Total Projects, Active Projects, Total Actions
6. Export templates: save field selections as templates for reuse
7. Report types:
   - Team Roster (all members with basic info)
   - Role Distribution (count by role, pie chart)
   - Activity Report (activity metrics per member)
   - Inactive Members Report (members inactive 30+ days)
   - New Members Report (members added in date range)
8. Report scheduling: schedule reports to be emailed daily/weekly/monthly
9. Export history: list of all exports with date, type, count, download link
10. Export includes metadata: export date, exported by, filter criteria

**Edge Cases:**
- EC-1: Export with 1,000+ members → background job, download link sent via email
- EC-2: Export includes deleted members → option to include/exclude deleted
- EC-3: PDF export with 100+ members → paginated, table of contents
- EC-4: Excel export with special characters → proper encoding, no corruption
- EC-5: Export with no members (empty filter) → validation warning before export
- EC-6: Scheduled report has no data → email sent with "No data" message
- EC-7: Export download link expires after 7 days → re-generate option
- EC-8: Export includes sensitive data (phone, email) → confirmation required
- EC-9: HR tries to export Super Admin data → Super Admins excluded from export
- EC-10: Export during high server load → queued, processed when resources available
- EC-11: Export template with invalid fields → validation error, template not saved
- EC-12: Concurrent exports by multiple admins → all processed independently

**Validation Rules:**
- At least one field must be selected for export
- Export format must be valid (CSV, XLSX, PDF)
- Date range for reports must be valid
- Scheduled report frequency must be valid

**Security Considerations:**
- Export action logged in audit log
- Exported files stored securely with access control
- Download links expire after 7 days
- Sensitive data export requires confirmation
- Export limited to authorized roles
- Exported files encrypted if containing sensitive data

**Responsive Design:**
- Mobile (375px): Simplified export interface, essential options only
- Tablet (768px): Modal dialog with field selection checklist
- Desktop (1024px+): Full export interface with preview panel

**Performance:**
- CSV export (100 members): < 3 seconds
- Excel export (100 members): < 5 seconds
- PDF export (100 members): < 10 seconds
- Report generation: < 30 seconds
- Export download: < 5 seconds

**UX Considerations:**
- Field selection with "Select All" / "Deselect All"
- Export preview showing first 10 rows
- Template management (save, edit, delete templates)
- One-click export with default template
- Progress indicator for large exports
- Success message with download button
- Export history with re-download option
- Scheduled reports with preview before scheduling

---

## US-TEAM-2.14 🟡 Team Member Search with Advanced Filters
**As a** Super Admin, Admin, or HR, **I want to** search team members with advanced filters and saved searches, **so that** I can quickly find specific members or groups.

**Acceptance Criteria:**
1. Search bar prominent at top of team list
2. Real-time search (debounced 300ms) across:
   - Name (first name, last name, full name)
   - Email
   - Phone
   - WhatsApp
   - Department
   - Job Title
3. Advanced filters panel (collapsible):
   - Role (multi-select)
   - Status (multi-select)
   - Department (multi-select)
   - Date Created (date range)
   - Last Login (date range)
   - Last Active (date range)
   - Projects Count (range: min-max)
4. Filter combinations with AND/OR logic
5. Saved searches:
   - Save current search/filter combination
   - Name saved search
   - Quick access to saved searches (dropdown)
   - Edit/delete saved searches
6. Search suggestions as user types (autocomplete)
7. Search history (last 10 searches)
8. "Clear All Filters" button
9. Active filters displayed as removable chips
10. Search results count: "Found [X] members"
11. Export search results
12. Share search URL (filters encoded in URL)

**Edge Cases:**
- EC-1: Search with no results → empty state: "No members found for '[query]'"
- EC-2: Search with special characters → properly escaped, no errors
- EC-3: Search with very long query (500+ chars) → truncated, warning shown
- EC-4: Saved search with deleted department → filter ignored, warning shown
- EC-5: Multiple filters result in zero results → suggestion to remove some filters
- EC-6: Search query matches 500+ members → pagination applied, "Showing first 100 results"
- EC-7: Autocomplete with 100+ suggestions → limited to 10, "View all results" option
- EC-8: Saved search name already exists → validation error: "Search name already exists"
- EC-9: User has 50+ saved searches → paginated or searchable list
- EC-10: Filter by date range with invalid dates → validation error
- EC-11: HR searches for Super Admins → no results (Super Admins hidden from HR)
- EC-12: Search while filters active → search applied within filtered results

**Validation Rules:**
- Search query: max 500 characters
- Saved search name: 3-50 characters, unique per user
- Date ranges: start date before end date
- Number ranges: min less than or equal to max

**Security Considerations:**
- Search queries logged for analytics (anonymized)
- Saved searches private to user (not shared)
- Search results respect role-based access control
- No sensitive data exposed in search suggestions
- Search URL encoding prevents injection attacks

**Responsive Design:**
- Mobile (375px): Search bar full-width, filters in drawer
- Tablet (768px): Search bar with inline quick filters
- Desktop (1024px+): Search bar with advanced filters sidebar

**Performance:**
- Search response: < 300ms
- Autocomplete suggestions: < 200ms
- Filter application: < 500ms
- Saved search load: < 100ms
- Search with 10,000+ members: < 1 second (indexed)

**UX Considerations:**
- Search bar with clear icon (X) to reset
- Autocomplete with keyboard navigation (arrow keys, Enter)
- Filter panel with collapsible sections
- Active filters highlighted
- Saved searches with star icon
- Search history with clock icon
- "Did you mean?" suggestions for typos
- Search tips tooltip (e.g., "Use quotes for exact match")

---

## US-TEAM-2.15 🟢 Team Member Profile Customization
**As a** team member, **I want to** customize my profile with additional information, **so that** other team members can learn more about me.

**Acceptance Criteria:**
1. Profile customization accessible from "My Profile" menu
2. Customizable fields:
   - Bio (max 500 characters)
   - Skills (tags, max 10)
   - Interests (tags, max 10)
   - Social links (LinkedIn, Twitter, GitHub, personal website)
   - Preferred name (if different from legal name)
   - Pronouns (dropdown: he/him, she/her, they/them, custom)
   - Timezone (auto-detected, editable)
   - Language preference (English, Arabic)
   - Work hours (start time, end time)
   - Availability status (Available, Busy, Away, Do Not Disturb)
3. Profile visibility settings:
   - Public (visible to all team members)
   - Private (visible to admins only)
   - Custom (choose which fields are visible)
4. Profile customization saved automatically (debounced)
5. Profile preview before saving
6. Profile completeness indicator (e.g., "80% complete")
7. Customized profile displayed in team directory
8. Profile changes logged in activity log

**Edge Cases:**
- EC-1: Bio exceeds 500 characters → character counter, validation error
- EC-2: Invalid URL in social links → validation error with format example
- EC-3: Skills/interests exceed 10 → validation error: "Maximum 10 tags allowed"
- EC-4: Custom pronouns field empty → validation error if "custom" selected
- EC-5: Work hours end time before start time → validation error
- EC-6: Timezone not in list → fallback to UTC, warning shown
- EC-7: Profile visibility set to Private → profile hidden from team directory
- EC-8: User uploads very large profile picture → resized automatically, warning if quality reduced
- EC-9: Social link to malicious site → URL validation, warning shown
- EC-10: Availability status not updated for 24 hours → auto-reset to "Available"
- EC-11: Profile customization conflicts with admin-set fields → admin fields take precedence
- EC-12: User deletes all custom fields → profile reverts to basic information

**Validation Rules:**
- Bio: max 500 characters, plain text only
- Skills/Interests: max 10 tags, each tag max 30 characters
- Social links: valid URL format
- Pronouns: one of predefined options or custom (max 20 characters)
- Work hours: valid time format (HH:MM)
- Timezone: valid timezone identifier

**Security Considerations:**
- Social links validated to prevent XSS
- Bio sanitized to prevent HTML injection
- Profile visibility settings enforced
- Profile changes logged for audit
- Malicious URLs blocked

**Responsive Design:**
- Mobile (375px): Single column form, collapsible sections
- Tablet (768px): Two-column layout
- Desktop (1024px+): Side-by-side form and preview

**Performance:**
- Profile load: < 500ms
- Auto-save: debounced 2 seconds
- Profile update: < 300ms
- Profile preview: instant (client-side)

**UX Considerations:**
- Character counter for bio field
- Tag input with autocomplete for skills/interests
- Timezone auto-detection with manual override
- Profile completeness progress bar
- Profile preview updates in real-time
- "Suggest skills" based on role (optional)
- Profile visibility toggle with clear explanations
- Success message after save

---

## US-TEAM-2.16 🟢 Team Directory with Organizational Chart
**As a** team member, **I want to** view a team directory with organizational chart, **so that** I can understand team structure and find colleagues.

**Acceptance Criteria:**
1. Team directory accessible from main navigation
2. Two view modes:
   - List view (similar to team list)
   - Org chart view (hierarchical tree)
3. List view displays:
   - Profile picture, name, role, department, email, phone
   - Availability status (if set)
   - "View Profile" button
4. Org chart view displays:
   - Hierarchical structure based on reporting relationships
   - Super Admin at top, then Admins, then other roles
   - Expandable/collapsible branches
   - Profile picture and name in each node
   - Click node to view full profile
5. Search and filter in directory (same as team list)
6. Directory respects role-based visibility:
   - All roles can view directory
   - HR cannot see Super Admin profiles
   - Employees see all team members
7. Directory shows only active members by default
8. Option to include inactive members (checkbox)
9. Export directory to PDF (with org chart)
10. Print-friendly directory view

**Edge Cases:**
- EC-1: No reporting relationships defined → flat list in org chart view
- EC-2: Circular reporting relationships → detected and broken, warning shown
- EC-3: Very large team (500+ members) → org chart paginated or zoomable
- EC-4: Team member with no department → listed under "Unassigned"
- EC-5: Org chart too wide for screen → horizontal scroll or zoom controls
- EC-6: Profile picture missing → default avatar with initials
- EC-7: Team member name very long → truncated in org chart, full name in tooltip
- EC-8: Multiple Super Admins → all shown at top level
- EC-9: Inactive members included → displayed with gray overlay
- EC-10: Search in org chart view → matching nodes highlighted
- EC-11: Mobile device with small screen → org chart switches to list view automatically
- EC-12: Org chart export with 100+ members → multi-page PDF

**Validation Rules:**
- Reporting relationships must not create cycles
- Each member can have only one direct manager
- Super Admin cannot report to anyone

**Security Considerations:**
- Directory access logged
- Profile visibility settings respected
- No sensitive data exposed in directory
- Export action logged

**Responsive Design:**
- Mobile (375px): List view only, org chart not available
- Tablet (768px): Both views available, org chart scrollable
- Desktop (1024px+): Full org chart with zoom and pan controls

**Performance:**
- Directory load (100 members): < 1 second
- Org chart render (100 members): < 2 seconds
- Search in directory: < 300ms
- Export to PDF: < 10 seconds

**UX Considerations:**
- Toggle between list and org chart views
- Org chart with zoom controls (+/-)
- Org chart with pan/drag support
- Search highlights matching members
- Availability status color-coded
- "Report to" relationship shown in profiles
- Org chart legend explaining symbols
- Print-friendly layout with page breaks

---

## US-TEAM-2.17 🟡 Team Member Onboarding Checklist
**As a** Super Admin or Admin, **I want to** assign onboarding checklists to new team members, **so that** they complete all necessary setup tasks.

**Acceptance Criteria:**
1. Onboarding checklist template configurable in settings
2. Default checklist items:
   - Complete profile information
   - Upload profile picture
   - Set password (if not using SSO)
   - Enable MFA (if required)
   - Read company policies
   - Complete training modules
   - Set up development environment (for developers)
   - Schedule 1:1 with manager
3. Checklist automatically assigned to new team members on creation
4. Checklist visible in team member's dashboard
5. Team member can check off completed items
6. Admin can view checklist completion status for all members
7. Checklist progress indicator (e.g., "5 of 8 tasks completed")
8. Reminder emails sent for incomplete checklists:
   - Day 3: "You have [X] onboarding tasks remaining"
   - Day 7: "Please complete your onboarding checklist"
   - Day 14: Admin notified of incomplete checklist
9. Checklist completion tracked in activity log
10. Custom checklists per role (e.g., developer checklist vs. HR checklist)
11. Checklist items can have due dates
12. Checklist items can have links to resources (e.g., policy documents)

**Edge Cases:**
- EC-1: Team member completes all items → congratulations message, admin notified
- EC-2: Checklist item has due date in past → highlighted in red
- EC-3: Team member skips optional items → allowed, progress calculated without optional items
- EC-4: Admin adds new checklist item to template → existing members' checklists not affected
- EC-5: Team member marked inactive before completing checklist → checklist paused
- EC-6: Team member reactivated → checklist resumed with reminder email
- EC-7: Checklist item link broken → warning shown, admin notified
- EC-8: Team member completes checklist in 1 day → early completion badge (optional)
- EC-9: Reminder email fails to send → retry 3 times, then log error
- EC-10: Multiple checklists assigned (onboarding + role-specific) → combined view
- EC-11: Admin manually marks checklist as complete → all items checked, completion logged
- EC-12: Team member unchecks completed item → allowed, progress updated

**Validation Rules:**
- Checklist template must have at least one item
- Checklist item title: 5-100 characters
- Due date must be in future (if set)
- Resource link must be valid URL (if provided)

**Security Considerations:**
- Checklist completion logged in audit log
- Resource links validated to prevent malicious content
- Checklist access restricted to team member and admins
- Reminder emails sent from verified domain

**Responsive Design:**
- Mobile (375px): Checklist in card layout, one item per card
- Tablet (768px): Checklist in list layout with progress bar
- Desktop (1024px+): Checklist in sidebar or modal

**Performance:**
- Checklist load: < 500ms
- Item check/uncheck: < 200ms
- Progress calculation: instant (client-side)
- Reminder email delivery: < 30 seconds

**UX Considerations:**
- Progress bar showing completion percentage
- Checklist items with checkboxes
- Completed items with strikethrough
- Overdue items highlighted in red
- Resource links open in new tab
- "Mark all as complete" button for admin
- Celebration animation on checklist completion
- Checklist printable for offline reference

---

## US-TEAM-2.18 🟢 Team Member Performance Metrics (Future Enhancement)
**As a** Super Admin or Admin, **I want to** view performance metrics for team members, **so that** I can evaluate productivity and identify training needs.

**Acceptance Criteria:**
1. Performance metrics dashboard accessible from team member profile
2. Metrics tracked:
   - Projects completed (count, on-time percentage)
   - Tasks completed (count, on-time percentage)
   - Average project duration
   - Client satisfaction ratings (if available)
   - Activity level (actions per day/week/month)
   - Login frequency
   - Response time to assignments
3. Metrics displayed with charts:
   - Line chart: activity over time
   - Bar chart: projects completed per month
   - Pie chart: time distribution across projects
4. Date range selector: This Week, This Month, This Quarter, This Year, Custom
5. Comparison view: compare member's metrics to team average
6. Export metrics to PDF or Excel
7. Metrics calculated automatically (no manual input)
8. Metrics refresh daily (cached for performance)
9. Metrics visible to:
   - Super Admin and Admin (all members)
   - Team member (own metrics only)
10. Performance trends: improving, stable, declining (with indicators)

**Edge Cases:**
- EC-1: New team member with no data → "Not enough data yet" message
- EC-2: Team member with no projects → projects metrics hidden
- EC-3: Date range with no activity → empty state: "No activity in selected period"
- EC-4: Metrics calculation fails → cached data shown with warning
- EC-5: Very high activity (1000+ actions/day) → chart scaled appropriately
- EC-6: Comparison to team average with only 2 team members → comparison not meaningful, warning shown
- EC-7: Metrics export with 1 year of data → large file, background job
- EC-8: Deleted projects included in metrics → option to include/exclude
- EC-9: Team member role changed → metrics recalculated based on new role expectations
- EC-10: Metrics dashboard slow to load → loading skeleton, progressive loading
- EC-11: HR tries to view performance metrics → 403 Forbidden (future: HR may have limited access)
- EC-12: Metrics used for performance review → export includes disclaimer about data limitations

**Validation Rules:**
- Date range: start date before end date
- Date range: max 2 years
- Metrics must be calculated from actual data (no manual overrides)

**Security Considerations:**
- Metrics access restricted to authorized roles
- Metrics export logged in audit log
- No personally identifiable information in exported metrics
- Metrics calculation doesn't expose sensitive data

**Responsive Design:**
- Mobile (375px): Stacked charts, one per screen, swipeable
- Tablet (768px): Grid layout, 2 charts per row
- Desktop (1024px+): Dashboard layout with multiple charts visible

**Performance:**
- Metrics dashboard load: < 2 seconds
- Metrics calculation: daily background job
- Chart rendering: < 500ms per chart
- Export: < 10 seconds

**UX Considerations:**
- Interactive charts (hover for details)
- Date range selector with presets
- Comparison toggle (show/hide team average)
- Trend indicators (up/down arrows)
- Color-coded metrics (green: good, yellow: average, red: needs improvement)
- Tooltips explaining each metric
- "Share metrics" button (generates shareable link)
- Print-friendly metrics report

---

## US-TEAM-2.19 🟡 Team Member Notifications and Preferences
**As a** team member, **I want to** configure my notification preferences, **so that** I receive relevant updates without being overwhelmed.

**Acceptance Criteria:**
1. Notification preferences accessible from "My Profile" > "Notifications"
2. Notification channels:
   - Email
   - In-app notifications (bell icon)
   - Browser push notifications (optional)
   - SMS (optional, for critical alerts)
3. Notification categories:
   - Account & Security (password changes, login alerts, MFA)
   - Team Updates (new members, role changes, status changes)
   - Projects (assigned as PM, project status changes, deadlines)
   - Clients (new clients, client updates)
   - System (maintenance, updates, announcements)
   - Activity (mentions, comments, assignments)
4. Granular control per category:
   - All notifications
   - Important only
   - None
5. Notification frequency:
   - Real-time (immediate)
   - Digest (daily summary at specified time)
   - Weekly summary
6. Quiet hours: disable notifications during specified hours
7. Do Not Disturb mode: temporarily disable all notifications
8. Notification preview before saving preferences
9. Default preferences based on role (customizable)
10. Notification history: view last 30 days of notifications
11. Mark notifications as read/unread
12. Clear all notifications button

**Edge Cases:**
- EC-1: All notifications disabled → warning: "You won't receive any updates"
- EC-2: Critical security notification with all channels disabled → sent anyway, cannot be disabled
- EC-3: Quiet hours span midnight → handled correctly (e.g., 10 PM - 6 AM)
- EC-4: Do Not Disturb mode enabled for 7+ days → reminder to disable
- EC-5: Email notifications with invalid email → error logged, admin notified
- EC-6: Browser push notifications not supported → option hidden
- EC-7: SMS notifications without phone number → validation error
- EC-8: Digest email with no notifications → email not sent
- EC-9: Notification preferences conflict (e.g., real-time + digest) → real-time takes precedence
- EC-10: Team member deletes account → notification preferences deleted
- EC-11: Notification history exceeds 1000 items → paginated
- EC-12: Notification delivery fails → retry 3 times, then log error

**Validation Rules:**
- At least one notification channel must be enabled for critical alerts
- Quiet hours: start time must be different from end time
- Digest time: valid time format (HH:MM)
- Do Not Disturb duration: max 30 days

**Security Considerations:**
- Notification preferences stored securely
- Security notifications cannot be completely disabled
- Notification content doesn't expose sensitive data
- Notification links include secure tokens
- SMS notifications rate-limited to prevent abuse

**Responsive Design:**
- Mobile (375px): Stacked preference sections, toggle switches
- Tablet (768px): Two-column layout
- Desktop (1024px+): Sidebar with categories, main panel with settings

**Performance:**
- Preferences load: < 500ms
- Preferences save: < 300ms
- Notification delivery: < 5 seconds (real-time)
- Notification history load: < 1 second

**UX Considerations:**
- Toggle switches for easy enable/disable
- Category icons for visual identification
- Notification preview with sample notification
- "Test notification" button to verify settings
- Preset templates (e.g., "Minimal", "Balanced", "All")
- Notification sound settings (optional)
- Badge count on notification icon
- Notification grouping (e.g., "5 new project updates")

---

## US-TEAM-2.20 🟡 Team Member Audit Trail and Compliance
**As a** Super Admin, **I want to** view comprehensive audit trails for team member actions, **so that** I can ensure compliance and investigate issues.

**Acceptance Criteria:**
1. Audit trail accessible from Settings > Audit Log
2. Audit trail records:
   - All team member CRUD operations (create, read, update, delete)
   - Role changes (who changed, from what to what, for whom)
   - Status changes (active, inactive, suspended)
   - Permission changes
   - Login/logout events
   - Failed login attempts
   - Password changes and resets
   - MFA enable/disable
   - Profile updates
   - Bulk operations
   - Export/import operations
3. Audit log entry includes:
   - Timestamp (ISO 8601 with timezone)
   - Actor (who performed the action)
   - Action type (create, update, delete, etc.)
   - Resource (team member affected)
   - Before/after values (for updates)
   - IP address
   - User agent
   - Result (success/failure)
   - Reason (if provided)
4. Audit log filtering:
   - Date range
   - Actor (team member who performed action)
   - Action type
   - Resource (team member affected)
   - Result (success/failure)
5. Audit log search: full-text search across all fields
6. Audit log export: CSV, JSON, PDF
7. Audit log retention: 1 year minimum (configurable)
8. Audit log immutable: cannot be edited or deleted
9. Audit log compliance reports:
   - Access report (who accessed what)
   - Change report (what changed and when)
   - Security report (failed logins, suspicious activity)
10. Automated alerts for suspicious patterns:
    - Multiple failed logins
    - Bulk deletions
    - Role escalations
    - After-hours access

**Edge Cases:**
- EC-1: Audit log query spans 2 years → performance warning, suggest narrower range
- EC-2: Audit log with 100,000+ entries → pagination enforced, max 1000 per page
- EC-3: Audit log export with 50,000+ entries → background job, download link emailed
- EC-4: Deleted team member in audit log → displayed as "[Deleted User: email@example.com]"
- EC-5: Audit log entry with very long before/after values → truncated, "View Full" link
- EC-6: Audit log search with no results → empty state with search tips
- EC-7: Concurrent audit log queries → all processed independently
- EC-8: Audit log storage approaching limit → alert sent to admin, archival recommended
- EC-9: Audit log accessed by non-Super Admin → 403 Forbidden
- EC-10: Audit log entry for automated system action → actor shown as "System"
- EC-11: Audit log timezone different from user's timezone → converted and displayed in user's timezone
- EC-12: Suspicious activity detected → real-time alert sent to all Super Admins

**Validation Rules:**
- Date range: start date before end date
- Date range: max 2 years per query
- Export: max 100,000 entries per export
- Audit log entries cannot be modified

**Security Considerations:**
- Audit log access restricted to Super Admin only
- Audit log stored in separate database or table
- Audit log encrypted at rest
- Audit log access itself is logged (meta-audit)
- Audit log backup separate from main database backup
- Audit log tampering detection (checksums)
- Compliance with regulations (GDPR, SOC 2, etc.)

**Responsive Design:**
- Mobile (375px): Card layout, collapsible entry details
- Tablet (768px): Table with horizontal scroll
- Desktop (1024px+): Full table with all columns, expandable rows for details

**Performance:**
- Audit log load (100 entries): < 1 second
- Audit log search: < 2 seconds (indexed)
- Audit log filter: < 1 second
- Audit log export (1,000 entries): < 5 seconds
- Audit log export (100,000 entries): < 5 minutes (background)

**UX Considerations:**
- Timeline view option (chronological visualization)
- Color-coded action types (green: create, blue: update, red: delete)
- Expandable rows for detailed before/after comparison
- Quick filters (e.g., "My Actions", "Today", "Failed Actions")
- Audit log entry permalink (shareable URL)
- Diff view for before/after values (side-by-side comparison)
- Export with custom date range and filters
- Compliance report templates (pre-configured filters)
- Real-time audit log updates (WebSocket, optional)
- Audit log statistics dashboard (actions per day, top actors, etc.)

---

## Summary

| User Story | Priority | Focus Area |
|------------|----------|------------|
| US-TEAM-2.1 | 🔴 Critical | Create Team Member with Role Assignment |
| US-TEAM-2.2 | 🔴 Critical | View Team List with Pagination, Search, Filter, Sort |
| US-TEAM-2.3 | 🔴 Critical | Edit Team Member Details |
| US-TEAM-2.4 | 🟠 High | Delete Team Member with Project Reassignment |
| US-TEAM-2.5 | 🟠 High | 30-Day Auto-Inactive Functionality |
| US-TEAM-2.6 | 🟠 High | Bulk Invite Team Members |
| US-TEAM-2.7 | 🟠 High | Bulk Role Change |
| US-TEAM-2.8 | 🔴 Critical | View Team Member Profile |
| US-TEAM-2.9 | 🔴 Critical | Permission Matrix Display and Management |
| US-TEAM-2.10 | 🟠 High | Track Team Member Activity |
| US-TEAM-2.11 | 🟠 High | Manage Team Member Status (Active/Inactive/Suspended) |
| US-TEAM-2.12 | 🟡 Medium | Team Member Import from External Systems |
| US-TEAM-2.13 | 🟡 Medium | Team Member Export and Reporting |
| US-TEAM-2.14 | 🟡 Medium | Team Member Search with Advanced Filters |
| US-TEAM-2.15 | 🟢 Nice-to-have | Team Member Profile Customization |
| US-TEAM-2.16 | 🟢 Nice-to-have | Team Directory with Organizational Chart |
| US-TEAM-2.17 | 🟡 Medium | Team Member Onboarding Checklist |
| US-TEAM-2.18 | 🟢 Nice-to-have | Team Member Performance Metrics (Future) |
| US-TEAM-2.19 | 🟡 Medium | Team Member Notifications and Preferences |
| US-TEAM-2.20 | 🟡 Medium | Team Member Audit Trail and Compliance |

**Total Stories**: 20 comprehensive user stories covering all aspects of team management.

**Priority Breakdown**:
- 🔴 Critical: 5 stories (core team management functionality)
- 🟠 High: 7 stories (important features for efficient team management)
- 🟡 Medium: 6 stories (enhanced functionality and reporting)
- 🟢 Nice-to-have: 3 stories (future enhancements and optional features)

---

## Key Team Management Principles

1. **Role-Based Access Control**: Granular permissions ensure each role has appropriate access levels
2. **Audit Trail**: All team member actions logged for compliance and security
3. **Bulk Operations**: Efficient management of multiple team members simultaneously
4. **Status Management**: Clear distinction between Active, Inactive, and Suspended states
5. **Project Continuity**: Team member deletion requires project reassignment to prevent data loss
6. **Security First**: MFA support, password policies, session management, and security logging
7. **Automation**: Auto-inactive functionality, scheduled reports, and automated notifications
8. **Flexibility**: Import/export capabilities, custom fields, and configurable workflows
9. **User Experience**: Intuitive interfaces, real-time search, and responsive design
10. **Compliance**: Comprehensive audit trails, data retention policies, and regulatory compliance

---

## Role Permission Matrix Reference

| Permission | Super Admin | Admin | Founder (CTO) | Founder (CEO/CFO) | HR | Employee |
|------------|-------------|-------|---------------|-------------------|----|----|
| Create Team Members | ✓ | ✓ | ✗ | ✗ | ✓ | ✗ |
| View Team List | ✓ | ✓ | ✓ | ✓ | ✓ | Own Only |
| Edit Team Members | ✓ | ✓ | ✗ | ✗ | ✓ | Own Profile |
| Delete Team Members | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| Change Roles | ✓ | ✓* | ✗ | ✗ | ✗ | ✗ |
| Bulk Operations | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| View Profiles | ✓ | ✓ | ✓ | ✓ | ✓** | Own Only |
| Manage Permissions | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| View Activity Log | ✓ | ✓ | ✗ | ✗ | ✓*** | ✗ |
| Manage Status | ✓ | ✓ | ✗ | ✗ | ✓ | ✗ |
| Import/Export | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| View Audit Trail | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |

**Notes**:
- \* Admin cannot assign Super Admin role or change Super Admin roles
- \*\* HR cannot view Super Admin profiles
- \*\*\* HR can only view team-related activities, not all system activities

---

## Integration Points

### Authentication & Security (US-AUTH)
- Team member creation triggers account setup (US-AUTH-1.1)
- Role assignment determines permissions (US-AUTH-1.5)
- MFA can be enforced per role (US-AUTH-1.2)
- Password policies apply to all team members (US-AUTH-1.11)
- Security audit logging tracks team member actions (US-AUTH-1.12)

### Project Management (US-PROJ)
- Team members assigned as Project Managers
- Project reassignment required before team member deletion
- Team member activity includes project-related actions
- Project count displayed in team member profiles

### Client Management (US-CLIENT)
- Team member activity includes client-related actions
- Client creation/updates logged per team member

### Landing Page CMS (US-CMS)
- Founder (CTO) role has CMS edit permissions
- CMS changes logged in team member activity

### Blog System (US-BLOG)
- Blog authors are team members
- Blog post creation/editing logged in activity

---

## Performance Benchmarks

| Operation | Target Performance |
|-----------|-------------------|
| Team list load (100 members) | < 1 second |
| Team member creation | < 1 second |
| Team member update | < 500ms |
| Team member deletion | < 2 seconds |
| Bulk invite (100 members) | < 30 seconds |
| Bulk role change (100 members) | < 10 seconds |
| Search/filter | < 500ms |
| Profile load | < 1 second |
| Activity log load | < 1 second |
| Export (100 members) | < 5 seconds |
| Import (100 members) | < 1 minute |
| Audit trail query | < 2 seconds |

---

## Responsive Design Breakpoints

- **Mobile**: 375px - 767px (single column, touch-optimized)
- **Tablet**: 768px - 1023px (two-column, hybrid touch/mouse)
- **Desktop**: 1024px - 1439px (multi-column, mouse-optimized)
- **Large Desktop**: 1440px+ (full-width, enhanced features)

---

## Accessibility Considerations

1. **WCAG AA Compliance**: All team management interfaces meet WCAG 2.1 AA standards
2. **Keyboard Navigation**: Full keyboard support for all actions
3. **Screen Reader Support**: Proper ARIA labels and semantic HTML
4. **Color Contrast**: Minimum 4.5:1 contrast ratio for text
5. **Focus Indicators**: Clear focus states for all interactive elements
6. **Error Messages**: Clear, descriptive error messages with recovery suggestions
7. **Form Labels**: All form fields have associated labels
8. **Alternative Text**: Profile pictures have descriptive alt text

---

## Internationalization (i18n)

1. **Language Support**: English and Arabic (RTL support)
2. **Date/Time Formatting**: Localized based on user's timezone and locale
3. **Number Formatting**: Localized number formats
4. **Currency**: Multi-currency support for salary/budget fields (future)
5. **Text Direction**: Automatic RTL/LTR switching based on language
6. **Translations**: All UI text translatable, no hardcoded strings
7. **Locale-Specific Validation**: Phone numbers, addresses validated per locale

---

## Data Retention and Privacy

1. **Soft Delete**: Team members soft-deleted, retained for 90 days
2. **Hard Delete**: Permanent deletion after 90 days (automated job)
3. **GDPR Compliance**: Right to be forgotten, data export, consent management
4. **Data Minimization**: Only necessary data collected and stored
5. **Audit Log Retention**: 1 year minimum, configurable up to 7 years
6. **Activity Log Retention**: 90 days default, configurable
7. **Personal Data Encryption**: Sensitive fields encrypted at rest
8. **Data Anonymization**: Deleted users' data anonymized in logs

---

## Testing Considerations

1. **Unit Tests**: All team management functions have unit tests
2. **Integration Tests**: API endpoints tested with various scenarios
3. **E2E Tests**: Critical user flows tested end-to-end
4. **Performance Tests**: Load testing with 1,000+ team members
5. **Security Tests**: Permission checks, injection prevention, XSS prevention
6. **Accessibility Tests**: Automated and manual accessibility testing
7. **Responsive Tests**: Testing across all breakpoints and devices
8. **Browser Tests**: Cross-browser testing (Chrome, Firefox, Safari, Edge)

---

## Future Enhancements

1. **Advanced Analytics**: Team productivity analytics, trends, insights
2. **Skills Matrix**: Team skills inventory and gap analysis
3. **Succession Planning**: Identify backup PMs and critical role coverage
4. **Performance Reviews**: Structured performance review workflow
5. **Time Tracking**: Integration with time tracking for project hours
6. **Capacity Planning**: Team capacity vs. project demand analysis
7. **Team Collaboration**: Internal messaging, team chat, collaboration tools
8. **Mobile App**: Native mobile app for team management on the go
9. **AI Recommendations**: AI-powered role suggestions, skill recommendations
10. **Integration Hub**: Integrations with Slack, Microsoft Teams, JIRA, etc.

