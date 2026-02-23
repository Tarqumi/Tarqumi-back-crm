# Client Management User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-CLIENT-3.1 🔴 Create Client with Complete Information
**As a** Super Admin or Admin, **I want to** create new clients with all relevant information, **so that** I can maintain a comprehensive client database for project management.

**Acceptance Criteria:**
1. Client creation form accessible from Client Management page ("Add Client" button)
2. Required fields: Client Name, Email
3. Optional fields: Company Name, Phone, WhatsApp, Address, Website, Industry, Notes
4. Email validation: proper format, max 255 characters
5. Client Name validation: min 2 characters, max 100 characters
6. Company Name validation: max 150 characters
7. Phone validation: international format support, E.164 format recommended
8. WhatsApp validation: same as phone, can be same or different number
9. Website validation: valid URL format with protocol (http/https)
10. On successful creation, client appears in client list immediately
11. Creation event logged in audit log with creator, timestamp
12. Success message: "Client created successfully"
13. Option to "Create Another" or "View Client" after creation
14. Client status defaults to "Active"
15. Duplicate detection warning if similar name/email exists

**Edge Cases:**
- EC-1: Email already exists → validation error: "A client with this email already exists"
- EC-2: Client name with Arabic characters → accepted and stored correctly with RTL support
- EC-3: Client name with special characters (e.g., &, -, ') → accepted
- EC-4: Phone number without country code → validation warning, suggest adding country code
- EC-5: WhatsApp same as phone → auto-fill option provided
- EC-6: Website without protocol → automatically prepend "https://"
- EC-7: Very long notes (5000+ characters) → validation error: "Notes cannot exceed 5000 characters"
- EC-8: User closes form with unsaved changes → confirmation prompt: "You have unsaved changes. Discard?"
- EC-9: Network timeout during creation → clear error message, form data preserved for retry
- EC-10: Duplicate submission (double-click) → second request rejected, only one client created
- EC-11: Email with uppercase letters → normalized to lowercase before saving
- EC-12: Company name same as existing client → allowed (multiple contacts from same company)

**Validation Rules:**
- Email: RFC 5322 compliant, unique in system
- Client Name: 2-100 characters, required
- Company Name: max 150 characters, optional
- Phone: E.164 format, 7-15 digits, optional
- WhatsApp: E.164 format, 7-15 digits, optional
- Website: valid URL format, max 255 characters, optional
- Address: max 500 characters, optional
- Notes: max 5000 characters, optional
- Industry: predefined list or free text, max 100 characters

**Security Considerations:**
- Client creation requires Admin or Super Admin role
- All input sanitized to prevent XSS attacks
- Email validation prevents injection attacks
- Creation action logged with IP address and user agent
- Duplicate detection uses case-insensitive comparison

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs, large submit button
- Tablet (768px): Two-column layout for related fields (Name/Company, Phone/WhatsApp)
- Desktop (1024px+): Modal dialog (700px width) or side panel

**Performance:**
- Form validation: < 100ms (client-side)
- Client creation API: < 1 second
- Duplicate detection check: < 300ms (debounced)
- Email uniqueness check: < 200ms (debounced)

**UX Considerations:**
- Real-time email availability check (debounced)
- Auto-fill WhatsApp from phone number option
- Industry dropdown with common options + "Other" for custom entry
- Character counter for notes field
- Success message with link to view created client
- Auto-focus on Client Name field when form opens
- Clear button to reset form
- Keyboard shortcuts: Ctrl+S to save, Esc to cancel

---

## US-CLIENT-3.2 🔴 View Client List with Advanced Filtering
**As a** Super Admin, Admin, or Founder, **I want to** view a comprehensive list of all clients with advanced filtering options, **so that** I can quickly find and manage clients.

**Acceptance Criteria:**
1. Client list displays: Client Name, Company, Email, Phone, Status, Projects Count, Last Contact, Actions
2. Default sort: Client Name (A-Z)
3. Pagination: 20 clients per page (configurable: 10, 20, 50, 100)
4. Search functionality: real-time search by name, company, email, phone (debounced 300ms)
5. Filter options:
   - Status: All, Active, Inactive
   - Industry: All, Technology, Finance, Healthcare, Retail, Manufacturing, Other
   - Projects: All, Has Projects, No Projects
   - Last Contact: All, Today, This Week, This Month, 30+ Days Ago, Never
6. Sort options: Name (A-Z, Z-A), Company (A-Z, Z-A), Email (A-Z, Z-A), Projects Count (High-Low, Low-High), Last Contact (Newest, Oldest)
7. Multi-select for bulk actions (checkbox per row)
8. "Select All" checkbox (selects all on current page)
9. Empty state message when no results: "No clients found. Try adjusting your filters."
10. Loading skeleton while fetching data
11. Total count displayed: "Showing 1-20 of 150 clients"
12. Filter and search state preserved in URL (shareable links)
13. "Clear Filters" button to reset all filters
14. Export to CSV button (exports filtered results)
15. Quick actions: View, Edit, Delete (based on permissions)

**Edge Cases:**
- EC-1: Search with no results → empty state with "No matches found for '[query]'"
- EC-2: Filter combination returns zero results → empty state with active filters shown
- EC-3: User on page 5, applies filter that returns only 2 pages → automatically redirected to page 1
- EC-4: Very long client name (100 chars) → truncated with ellipsis, full name in tooltip
- EC-5: Client with no company → displays "—" or "N/A"
- EC-6: Last Contact is null (never contacted) → displays "Never"
- EC-7: Search with special characters → properly escaped, no errors
- EC-8: Rapid filter changes → debounced API calls, previous requests cancelled
- EC-9: User navigates away and returns → filters and page number restored from URL
- EC-10: Export with 1000+ clients → background job, download link sent via email
- EC-11: Client with 50+ projects → count displayed with link to view all projects
- EC-12: Default "Tarqumi" client → displayed with special badge, cannot be deleted

**Validation Rules:**
- Search query: max 255 characters
- Page number: positive integer, within valid range
- Per page: one of allowed values (10, 20, 50, 100)

**Security Considerations:**
- Founder roles can view all clients (read-only)
- HR and Employee roles cannot access client list (403 Forbidden)
- Export action logged in audit log
- Search queries logged for analytics (anonymized)
- Client data filtered based on user permissions

**Responsive Design:**
- Mobile (375px): Card layout instead of table, filters in collapsible drawer
- Tablet (768px): Table with horizontal scroll, sticky header
- Desktop (1024px+): Full table with all columns visible, filters in sidebar

**Performance:**
- Initial page load: < 1 second
- Search/filter response: < 500ms
- Pagination navigation: < 300ms
- Export CSV (100 clients): < 3 seconds
- Real-time search: debounced 300ms to reduce API calls

**UX Considerations:**
- Active filters displayed as removable chips above table
- Sort direction indicator (arrow icon) in column headers
- Hover state on table rows for better readability
- Sticky table header when scrolling
- Keyboard navigation support (arrow keys, Enter to select)
- Loading indicator for async operations
- Success toast when bulk action completes
- Client count badge showing total active/inactive clients

---

## US-CLIENT-3.3 🔴 Edit Client Information
**As a** Super Admin or Admin, **I want to** edit existing client information, **so that** I can keep client data accurate and up-to-date.

**Acceptance Criteria:**
1. Edit button available in client list actions column and client detail page
2. Edit form pre-populated with current client data
3. Editable fields: Client Name, Company, Email, Phone, WhatsApp, Address, Website, Industry, Status, Notes
4. Email uniqueness validated (excluding current client's email)
5. Status change from Active to Inactive triggers confirmation dialog
6. On successful update, client data refreshed in list and detail views
7. Update event logged in audit log with editor, timestamp, changed fields
8. Optimistic UI update (immediate feedback, rollback on error)
9. "Save" and "Cancel" buttons clearly visible
10. Unsaved changes warning if user navigates away
11. Field-level validation with inline error messages
12. Success message: "Client updated successfully"
13. Change history tracked (who changed what and when)
14. Last modified timestamp and user displayed on client detail page

**Edge Cases:**
- EC-1: Email changed to existing email → validation error: "Email already in use by another client"
- EC-2: Client name changed while projects exist → all project references updated automatically
- EC-3: Status changed to Inactive while client has active projects → warning: "This client has [X] active projects"
- EC-4: No changes made, user clicks Save → no API call, message: "No changes to save"
- EC-5: Concurrent edits by two admins → last save wins, warning shown: "This client was recently updated by [Admin Name]"
- EC-6: Network error during save → error message, form data preserved, retry option
- EC-7: Default "Tarqumi" client edit attempt → name and email fields disabled, warning shown
- EC-8: Very long company name (150 chars) → validation enforced
- EC-9: Phone number format changed → automatically formatted to E.164
- EC-10: Website URL without protocol → automatically prepend "https://"
- EC-11: Notes field with line breaks → preserved correctly in database
- EC-12: Client with Arabic name edited → RTL support maintained

**Validation Rules:**
- Same validation rules as client creation (US-CLIENT-3.1)
- Email uniqueness check excludes current client's email
- Default "Tarqumi" client has restricted edit permissions

**Security Considerations:**
- Only Super Admin and Admin can edit clients
- Founder roles have read-only access (edit button hidden)
- All changes logged with before/after values
- Default "Tarqumi" client protected from critical changes
- Input sanitization prevents XSS attacks

**Responsive Design:**
- Mobile (375px): Full-screen edit form, scrollable
- Tablet (768px): Modal dialog (700px width)
- Desktop (1024px+): Side panel or modal dialog

**Performance:**
- Form load with pre-populated data: < 500ms
- Update API response: < 1 second
- Email uniqueness check: < 200ms (debounced)
- Change history load: < 300ms

**UX Considerations:**
- Changed fields highlighted with subtle background color
- Confirmation dialog for status change to Inactive
- Auto-save draft (optional, stored in localStorage)
- Keyboard shortcuts: Ctrl+S to save, Esc to cancel
- Focus management: return focus to edit button after cancel
- Inline validation as user types (debounced)
- "View Change History" link to see all modifications
- Undo option (within 30 seconds) for accidental changes

---

## US-CLIENT-3.4 🟠 Delete Client with Project Preservation Logic
**As a** Super Admin, **I want to** delete clients while preserving their project history, **so that** I can remove outdated clients without losing project data.

**Acceptance Criteria:**
1. Delete button available in client list actions and client detail page (Super Admin only)
2. Delete action requires confirmation dialog with warning message
3. If client has projects (active or completed), warning displayed: "This client has [X] projects. Projects will be preserved."
4. Confirmation dialog shows:
   - Client name and company
   - Number of projects associated
   - Warning that action cannot be undone
   - Checkbox: "I understand projects will be preserved"
5. Deleted client's account deactivated (soft delete, not hard delete)
6. Client's projects remain in system with client reference preserved (historical data)
7. Deleted client marked as "Deleted" in project views (not removed)
8. Deleted client's data retained for audit purposes (90 days minimum)
9. Deletion event logged in audit log with deleter, timestamp, project count
10. Deleted client removed from client list (unless "Show Deleted" filter enabled)
11. Success message: "Client deleted successfully. [X] projects preserved."
12. Default "Tarqumi" client cannot be deleted (delete button disabled)
13. Deleted clients can be restored within 90 days (soft delete recovery)

**Edge Cases:**
- EC-1: Admin tries to delete client → 403 Forbidden: "Only Super Admin can delete clients"
- EC-2: Attempt to delete default "Tarqumi" client → validation error: "Default client cannot be deleted"
- EC-3: Client has 50+ projects → confirmation shows project count, "View Projects" link
- EC-4: User cancels confirmation dialog → deletion cancelled, no changes made
- EC-5: Concurrent deletion attempt → second attempt fails: "Client already deleted"
- EC-6: Network error during deletion → rollback, error message, retry option
- EC-7: Client has no projects → deletion proceeds immediately after confirmation
- EC-8: Deleted client referenced in reports → shown as "[Deleted: Client Name]"
- EC-9: Attempt to create new client with same email as deleted client → validation error or recovery option
- EC-10: Deleted client restored → status set to Inactive, admin must manually activate
- EC-11: Hard delete after 90 days → automated job, projects remain with anonymized client reference
- EC-12: Client deleted while being viewed by another user → viewer sees "Client no longer exists" message

**Validation Rules:**
- Deleter must be Super Admin
- Default "Tarqumi" client cannot be deleted
- Confirmation checkbox must be checked
- Soft delete: data retained for 90 days
- Hard delete: permanent removal after 90 days

**Security Considerations:**
- Deletion is soft delete (data retained for audit)
- Hard delete after 90 days (configurable, automated job)
- Deletion action requires confirmation (prevent accidental deletion)
- All deletion events logged with full context
- Default "Tarqumi" client protected at database level
- Only Super Admin can delete clients

**Responsive Design:**
- Mobile (375px): Full-screen confirmation dialog, scrollable
- Tablet (768px): Modal dialog (600px width)
- Desktop (1024px+): Modal dialog with clear warning message

**Performance:**
- Deletion API (no projects): < 500ms
- Deletion with projects: < 1 second
- Project preservation: < 100ms per project
- Soft delete recovery: < 500ms

**UX Considerations:**
- Clear warning message: "This action cannot be undone"
- Project count displayed prominently
- "View Projects" link to review before deletion
- Confirmation checkbox prevents accidental deletion
- Cancel button prominently displayed
- Success message with undo option (within 30 seconds)
- "Restore Client" option in deleted clients view
- Deleted clients filter in client list for recovery

---

## US-CLIENT-3.5 🔴 Default "Tarqumi" Client Protection
**As a** system, **I want to** protect the default "Tarqumi" client from deletion and critical modifications, **so that** internal projects always have a valid client reference.

**Acceptance Criteria:**
1. Default "Tarqumi" client created during system initialization
2. "Tarqumi" client has special badge/indicator in client list: "Default Client"
3. Delete button disabled for "Tarqumi" client with tooltip: "Default client cannot be deleted"
4. Client Name and Email fields disabled when editing "Tarqumi" client
5. Other fields (Company, Phone, WhatsApp, Address, Website, Notes) remain editable
6. Status change to Inactive blocked with validation error: "Default client must remain active"
7. Attempt to delete via API returns 403 Forbidden: "Default client cannot be deleted"
8. Database constraint prevents deletion of default client
9. Default client always appears first in client list (pinned)
10. Default client automatically selected when creating internal projects
11. System checks for default client existence on startup, creates if missing
12. Default client ID stored in system configuration
13. Admin can change which client is the default (Super Admin only)
14. Only one client can be marked as default at a time

**Edge Cases:**
- EC-1: Default client accidentally deleted from database → recreated automatically on next system startup
- EC-2: Admin tries to change default client name → validation error: "Default client name cannot be changed"
- EC-3: Admin tries to change default client email → validation error: "Default client email cannot be changed"
- EC-4: Super Admin changes default client to another client → old default becomes regular client, new client becomes default
- EC-5: Default client has 100+ projects → all remain associated, no impact
- EC-6: Attempt to create duplicate "Tarqumi" client → validation error: "Default client already exists"
- EC-7: Default client status manually changed in database → system corrects to Active on next check
- EC-8: Default client used in project templates → always available
- EC-9: Default client referenced in reports → always included
- EC-10: System migration → default client preserved and verified
- EC-11: Multi-tenant setup → each tenant has own default client
- EC-12: Default client configuration missing → system creates with predefined values

**Validation Rules:**
- Default client name: "Tarqumi" (unchangeable)
- Default client email: system-defined (unchangeable)
- Default client status: must be Active
- Only one default client allowed per system/tenant
- Default client cannot be deleted (database constraint)

**Security Considerations:**
- Default client protected at application and database levels
- Only Super Admin can change default client designation
- Default client modification attempts logged in audit log
- Database constraint prevents accidental deletion
- System integrity check on startup verifies default client

**Responsive Design:**
- Default client badge visible on all screen sizes
- Disabled fields clearly indicated with visual styling
- Tooltip explanations accessible on mobile (tap to view)

**Performance:**
- Default client check on startup: < 100ms
- Default client creation (if missing): < 500ms
- Default client validation: < 50ms

**UX Considerations:**
- Clear visual indicator (badge, icon) for default client
- Tooltip explanations for disabled fields
- Warning message when attempting restricted actions
- "Learn More" link explaining default client purpose
- Default client pinned to top of client list
- Auto-selection in project creation forms
- System settings page to manage default client (Super Admin)

---
## US-CLIENT-3.6 🟠 Client Status Management (Active/Inactive)
**As a** Super Admin or Admin, **I want to** manage client status between Active and Inactive, **so that** I can organize clients based on current business relationships.

**Acceptance Criteria:**
1. Client status field with two options: Active, Inactive
2. Status toggle available in client list (quick action) and client detail page
3. Status change triggers confirmation dialog: "Change client status to [Inactive/Active]?"
4. Active clients displayed by default in client list
5. Inactive clients hidden by default, shown when "Show Inactive" filter enabled
6. Status badge displayed in client list and detail page (color-coded: green=Active, gray=Inactive)
7. Inactive clients cannot be selected when creating new projects (validation error)
8. Inactive clients with active projects show warning: "This client has [X] active projects"
9. Status change logged in audit log with changer, timestamp, reason (optional)
10. Status change notification option (email to team members)
11. Bulk status change supported (select multiple clients, change status)
12. Last status change date and user displayed on client detail page
13. Status filter in client list: All, Active, Inactive
14. Default "Tarqumi" client cannot be set to Inactive

**Edge Cases:**
- EC-1: Inactive client with active projects → warning shown, status change allowed
- EC-2: Attempt to create project with inactive client → validation error: "Please select an active client"
- EC-3: Client set to Inactive, then project assigned → validation prevents assignment
- EC-4: Bulk status change includes default "Tarqumi" client → excluded with warning
- EC-5: Client status changed while being viewed → viewer sees updated status immediately
- EC-6: Inactive client reactivated → immediately available for project assignment
- EC-7: Status change with reason field → reason stored and displayed in change history
- EC-8: Multiple rapid status changes → all changes logged, last change wins
- EC-9: Inactive client in project dropdown → not shown, only active clients listed
- EC-10: Client with 50+ active projects set to Inactive → confirmation with project count
- EC-11: Status filter combined with other filters → works correctly
- EC-12: Export includes inactive clients when filter applied

**Validation Rules:**
- Status must be Active or Inactive
- Default "Tarqumi" client must remain Active
- Status change reason: max 500 characters (optional)

**Security Considerations:**
- Only Super Admin and Admin can change client status
- Status changes logged in audit log
- Default "Tarqumi" client protected from Inactive status
- Bulk status change requires confirmation

**Responsive Design:**
- Mobile (375px): Status toggle button, full-screen confirmation dialog
- Tablet (768px): Inline status toggle with confirmation modal
- Desktop (1024px+): Quick status toggle with hover confirmation

**Performance:**
- Status change API: < 300ms
- Bulk status change (100 clients): < 5 seconds
- Status filter application: < 500ms

**UX Considerations:**
- Color-coded status badges (green/gray)
- Quick toggle in client list for fast status changes
- Confirmation dialog with reason field (optional)
- Warning for clients with active projects
- Success toast: "Client status changed to [Active/Inactive]"
- Undo option (within 30 seconds)
- Status change history in client detail page

---

## US-CLIENT-3.7 🟠 Client Search and Autocomplete
**As a** Super Admin, Admin, or Founder, **I want to** search for clients with autocomplete suggestions, **so that** I can quickly find clients when creating projects or viewing information.

**Acceptance Criteria:**
1. Search bar prominently displayed in client list page
2. Real-time autocomplete as user types (minimum 2 characters)
3. Autocomplete searches: Client Name, Company Name, Email
4. Autocomplete results show: Client Name, Company (if different), Email, Status badge
5. Maximum 10 autocomplete suggestions displayed
6. Keyboard navigation in autocomplete (arrow keys, Enter to select, Esc to close)
7. Click outside autocomplete closes suggestions
8. Search highlights matching text in results
9. Autocomplete debounced (300ms) to reduce API calls
10. "No results found" message when search returns empty
11. Recent searches saved (last 5) for quick access
12. Search works with partial matches (fuzzy search)
13. Search supports Arabic and English text
14. Clear search button (X icon) to reset search
15. Search results respect user permissions (active clients only for project creation)

**Edge Cases:**
- EC-1: Search with 1 character → no autocomplete, wait for 2+ characters
- EC-2: Search with special characters → properly escaped, no errors
- EC-3: Search returns 100+ results → only top 10 shown, "View all results" link
- EC-4: Search with Arabic text → RTL support, correct matching
- EC-5: Search while typing fast → debounced, only last query sent
- EC-6: Network timeout during search → error message, retry option
- EC-7: Search for deleted client → not shown in results
- EC-8: Search for inactive client → shown with Inactive badge
- EC-9: Autocomplete dropdown extends beyond viewport → scrollable
- EC-10: Search with mixed Arabic/English → both languages matched
- EC-11: Recent searches include deleted clients → removed from recent list
- EC-12: Search on slow connection → loading indicator shown

**Validation Rules:**
- Minimum search length: 2 characters
- Maximum search length: 100 characters
- Search query sanitized to prevent injection

**Security Considerations:**
- Search queries sanitized to prevent XSS
- Search results filtered based on user permissions
- Search queries logged for analytics (anonymized)
- Rate limiting: max 100 searches per minute per user

**Responsive Design:**
- Mobile (375px): Full-width search bar, autocomplete overlay
- Tablet (768px): Search bar in header, dropdown autocomplete
- Desktop (1024px+): Search bar with inline autocomplete dropdown

**Performance:**
- Autocomplete response: < 200ms
- Search debounce: 300ms
- Fuzzy search algorithm: < 100ms
- Recent searches load: < 50ms

**UX Considerations:**
- Search icon and placeholder text: "Search clients..."
- Autocomplete highlights matching text (bold)
- Keyboard shortcuts: Ctrl+K or Cmd+K to focus search
- Recent searches section in autocomplete dropdown
- "Search in all fields" option for advanced search
- Search results count: "Found [X] clients"
- Clear search button visible when text entered
- Focus management: return focus to search after selection

---

## US-CLIENT-3.8 🟡 Client Import from CSV/Excel
**As a** Super Admin or Admin, **I want to** import multiple clients from CSV or Excel files, **so that** I can efficiently migrate existing client data into the system.

**Acceptance Criteria:**
1. Import accessible from Client Management page ("Import Clients" button)
2. Supported file formats: CSV, XLSX, XLS
3. CSV/Excel template downloadable with required columns
4. Template columns: Client Name*, Email*, Company, Phone, WhatsApp, Address, Website, Industry, Notes (* = required)
5. File validation: max 500 clients per import, file size max 5MB
6. Preview screen shows parsed data before confirmation (first 10 rows)
7. Validation errors highlighted per row with specific error messages
8. Option to skip invalid rows and proceed with valid ones
9. Duplicate detection: check against existing clients by email
10. Duplicate handling options: Skip, Update, Create New (with suffix)
11. Progress indicator during import: "Importing 45 of 50 clients..."
12. Summary report after completion:
    - Successfully imported: [X] clients
    - Updated: [Y] clients (if duplicates updated)
    - Failed: [Z] clients (with reasons)
    - Skipped duplicates: [W] clients
13. Failed imports downloadable as CSV for correction and retry
14. All imported clients logged in audit log
15. Import history accessible (last 10 imports with details)

**Edge Cases:**
- EC-1: CSV with duplicate emails within file → first occurrence imported, others skipped
- EC-2: CSV with existing client emails → handled based on duplicate strategy
- EC-3: CSV with invalid email formats → row skipped, error listed
- EC-4: CSV with missing required fields → row skipped, error listed
- EC-5: CSV with 600 rows → validation error: "Maximum 500 clients per import"
- EC-6: CSV with incorrect encoding (non-UTF-8) → parsing error, suggest UTF-8
- EC-7: Excel file with multiple sheets → only first sheet imported, warning shown
- EC-8: File with incorrect format (PDF, DOC) → validation error: "Please upload CSV or Excel format"
- EC-9: Network timeout during import → partial completion, resume option provided
- EC-10: CSV with Arabic characters → correctly parsed and stored with RTL support
- EC-11: Empty CSV uploaded → validation error: "File is empty"
- EC-12: CSV with extra columns → extra columns ignored, warning shown

**Validation Rules:**
- File format: CSV, XLSX, XLS only
- Max rows: 500 per import
- Max file size: 5MB
- Required columns: Client Name, Email
- Email format validation per row
- Phone format validation per row (if provided)
- Website URL validation per row (if provided)

**Security Considerations:**
- File scanned for malicious content
- Import action requires elevated permissions (Admin/Super Admin)
- All imports logged individually in audit log
- Rate limiting: max 3 imports per hour per admin
- Imported data sanitized to prevent XSS

**Responsive Design:**
- Mobile (375px): Full-screen import interface, scrollable preview table
- Tablet (768px): Modal dialog (900px width)
- Desktop (1024px+): Modal dialog with side-by-side upload and preview

**Performance:**
- File parsing (500 rows): < 3 seconds
- Import processing (500 clients): < 2 minutes
- Duplicate detection: < 100ms per client
- Progress updates: real-time (WebSocket or polling)

**UX Considerations:**
- Template download link prominent with instructions
- Drag-and-drop file upload support
- Preview table with sortable columns
- Clear error messages with row numbers
- Duplicate handling strategy selector (radio buttons)
- "Fix and Retry" button for failed imports
- Success message with link to view imported clients
- Import history with download option for past imports

---

## US-CLIENT-3.9 🟡 Client Export and Reporting
**As a** Super Admin or Admin, **I want to** export client data in various formats, **so that** I can create reports and share client information with stakeholders.

**Acceptance Criteria:**
1. Export button available in client list page
2. Export formats: CSV, Excel (XLSX), PDF
3. Export options:
   - All clients or filtered results only
   - Select specific columns to export
   - Include/exclude inactive clients
   - Include/exclude deleted clients (Super Admin only)
4. Column selection: Name, Company, Email, Phone, WhatsApp, Address, Website, Industry, Status, Projects Count, Created Date, Last Modified
5. Export preview before download (first 10 rows)
6. Large exports (500+ clients) processed as background job
7. Background job completion notification (email with download link)
8. Export file naming: "clients_export_YYYY-MM-DD_HH-MM.csv"
9. PDF export includes: header with company logo, export date, page numbers, formatted table
10. Excel export includes: formatted headers, auto-column width, filters enabled
11. CSV export: UTF-8 encoding with BOM for Excel compatibility
12. Export history accessible (last 10 exports with download links)
13. Export action logged in audit log
14. Export files auto-deleted after 7 days (configurable)

**Edge Cases:**
- EC-1: Export with no clients selected → validation error: "No clients to export"
- EC-2: Export 1000+ clients → background job, email notification when ready
- EC-3: Export includes Arabic text → UTF-8 encoding preserved, RTL in PDF
- EC-4: Export with all columns → wide table, landscape orientation in PDF
- EC-5: Export filtered results (10 clients) → immediate download
- EC-6: Network timeout during export → retry option, partial export saved
- EC-7: Export file generation fails → error message, admin notified
- EC-8: Export includes deleted clients → marked with "Deleted" status
- EC-9: Export with custom date range → only clients created/modified in range
- EC-10: Multiple simultaneous exports → queued, processed sequentially
- EC-11: Export file too large (50MB+) → compressed as ZIP
- EC-12: Export history file deleted → "File no longer available" message

**Validation Rules:**
- At least one column must be selected
- Export format must be CSV, XLSX, or PDF
- Max export size: 10,000 clients per export
- Export file retention: 7 days (configurable)

**Security Considerations:**
- Export action requires Admin or Super Admin role
- Export files stored securely with access control
- Export download links include secure token
- Export links expire after 7 days
- All exports logged in audit log
- Sensitive data (notes) optionally excluded from export

**Responsive Design:**
- Mobile (375px): Full-screen export dialog, scrollable options
- Tablet (768px): Modal dialog (700px width)
- Desktop (1024px+): Modal dialog with preview panel

**Performance:**
- Small export (< 100 clients): < 3 seconds
- Medium export (100-500 clients): < 10 seconds
- Large export (500+ clients): background job, < 5 minutes
- Export preview generation: < 1 second

**UX Considerations:**
- Export format selector with icons (CSV, Excel, PDF)
- Column selection with "Select All" / "Deselect All"
- Export preview with sample data
- Progress indicator for large exports
- Success message with immediate download link
- Email notification for background jobs
- Export history with re-download option
- "Schedule Export" option for recurring reports (future)

---

## US-CLIENT-3.10 🟡 Client Relationship Tracking
**As a** Super Admin or Admin, **I want to** track relationships between clients, **so that** I can understand client networks and manage related accounts.

**Acceptance Criteria:**
1. Client detail page includes "Related Clients" section
2. Relationship types: Parent Company, Subsidiary, Partner, Referral Source, Referred By, Competitor, Other
3. Add relationship button opens dialog to select client and relationship type
4. Relationship is bidirectional (automatically creates inverse relationship)
5. Relationship displayed on both clients' detail pages
6. Relationship includes: Type, Related Client Name, Company, Date Added, Added By
7. Remove relationship option (requires confirmation)
8. Relationship history tracked (added, removed, modified)
9. Relationship visualization: simple list or network diagram (optional)
10. Filter clients by relationship type
11. Relationship count displayed in client list (optional column)
12. Relationship export included in client export
13. Relationship search: find all clients related to specific client
14. Circular relationship detection (prevent A→B→C→A loops)

**Edge Cases:**
- EC-1: Add relationship to self → validation error: "Cannot create relationship with same client"
- EC-2: Duplicate relationship → validation error: "Relationship already exists"
- EC-3: Add relationship to deleted client → validation error: "Cannot relate to deleted client"
- EC-4: Remove relationship → inverse relationship also removed
- EC-5: Client with 50+ relationships → paginated list, "View All" link
- EC-6: Relationship type changed → old relationship removed, new one added
- EC-7: Related client deleted → relationship marked as "Deleted Client"
- EC-8: Circular relationship detected → warning shown, relationship allowed
- EC-9: Relationship added while viewing client → real-time update
- EC-10: Relationship visualization with 100+ clients → performance optimized
- EC-11: Export includes relationships → separate column or sheet
- EC-12: Relationship search returns no results → empty state message

**Validation Rules:**
- Relationship type must be from predefined list
- Cannot create relationship with self
- Cannot create duplicate relationships
- Related client must exist and not be deleted

**Security Considerations:**
- Only Admin and Super Admin can manage relationships
- Relationship changes logged in audit log
- Relationship data included in client permissions

**Responsive Design:**
- Mobile (375px): List view of relationships, full-screen add dialog
- Tablet (768px): Grid view with relationship cards
- Desktop (1024px+): Network diagram visualization (optional)

**Performance:**
- Relationship load: < 500ms
- Add relationship: < 300ms
- Remove relationship: < 300ms
- Relationship search: < 1 second

**UX Considerations:**
- Clear relationship type labels with icons
- Bidirectional relationship indicator (↔)
- Quick add relationship from client list
- Relationship timeline view
- Visual relationship network (optional)
- Relationship strength indicator (based on project count)
- "Suggest Relationships" based on common projects (future)

---
## US-CLIENT-3.11 🟡 Client Communication History
**As a** Super Admin or Admin, **I want to** track all communication history with clients, **so that** I can maintain a complete record of interactions and follow-ups.

**Acceptance Criteria:**
1. Client detail page includes "Communication History" section
2. Communication types: Email, Phone Call, Meeting, WhatsApp, Video Call, Note, Other
3. Add communication button opens dialog with fields:
   - Type (dropdown)
   - Subject/Title (required, max 200 chars)
   - Description/Notes (optional, max 2000 chars)
   - Date & Time (defaults to now, editable)
   - Duration (for calls/meetings, optional)
   - Participants (multi-select team members)
   - Attachments (optional, max 5 files, 10MB total)
   - Follow-up required (checkbox)
   - Follow-up date (if checked)
4. Communication history displayed in reverse chronological order
5. Each entry shows: Type icon, Subject, Date, Added by, Participants, Follow-up badge
6. Edit/Delete communication (only by creator or Super Admin)
7. Communication search and filter by type, date range, participant
8. Follow-up reminders: notification sent on follow-up date
9. Communication count displayed in client list (optional column)
10. Communication export included in client export
11. Attach files to communication (images, PDFs, documents)
12. Link communication to specific projects (optional)
13. Communication templates for common interactions
14. Email integration: automatically log sent emails (future)

**Edge Cases:**
- EC-1: Communication date in future → allowed for scheduled communications
- EC-2: Communication with no description → subject required, description optional
- EC-3: Communication with 10+ participants → all listed, scrollable
- EC-4: Communication attachment exceeds 10MB → validation error
- EC-5: Communication linked to deleted project → project shown as "Deleted"
- EC-6: Follow-up date in past → warning shown, allowed
- EC-7: Edit communication after 30 days → warning: "Editing old communication"
- EC-8: Delete communication → confirmation required, soft delete
- EC-9: Client with 500+ communications → paginated, 20 per page
- EC-10: Communication search returns no results → empty state
- EC-11: Attachment file type not allowed → validation error with allowed types
- EC-12: Communication added while viewing client → real-time update

**Validation Rules:**
- Type: must be from predefined list
- Subject: required, 5-200 characters
- Description: optional, max 2000 characters
- Date: valid date/time, not more than 1 year in future
- Duration: positive number, max 24 hours
- Attachments: max 5 files, 10MB total, allowed types: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
- Follow-up date: must be in future if set

**Security Considerations:**
- Only Admin and Super Admin can add/edit communications
- Communication creator can edit own communications
- Super Admin can edit all communications
- All communication changes logged in audit log
- Attachments scanned for malicious content
- Sensitive communications can be marked as private (Super Admin only)

**Responsive Design:**
- Mobile (375px): Timeline view, full-screen add dialog
- Tablet (768px): Card view with expandable details
- Desktop (1024px+): Table view with inline expansion

**Performance:**
- Communication history load (50 entries): < 1 second
- Add communication: < 500ms
- File upload: < 3 seconds per file
- Communication search: < 500ms

**UX Considerations:**
- Timeline visualization with type icons
- Color-coded communication types
- Quick add communication from client list
- Communication templates dropdown
- Follow-up badge with date
- Overdue follow-ups highlighted in red
- Attachment preview (images, PDFs)
- "Mark as Complete" for follow-ups
- Communication statistics (total calls, meetings, etc.)
- Export communication history as PDF

---

## US-CLIENT-3.12 🟠 Duplicate Client Detection
**As a** Super Admin or Admin, **I want to** automatically detect potential duplicate clients, **so that** I can maintain data integrity and avoid redundant entries.

**Acceptance Criteria:**
1. Duplicate detection runs automatically when creating/editing clients
2. Detection criteria:
   - Exact email match (case-insensitive)
   - Similar name match (Levenshtein distance < 3)
   - Same company name + similar contact info
   - Same phone number
3. Duplicate warning shown before saving: "Potential duplicate found"
4. Warning displays: Existing client name, company, email, phone, created date
5. Options: "Save Anyway", "View Existing Client", "Cancel"
6. Duplicate detection report accessible from Client Management
7. Report shows: Potential duplicate groups, similarity score, suggested action
8. Manual duplicate review: mark as "Not Duplicate" or "Merge"
9. Duplicate detection settings configurable (sensitivity level)
10. Duplicate detection can be temporarily disabled (Super Admin only)
11. Duplicate detection history logged in audit log
12. Bulk duplicate detection: scan all clients, generate report
13. Duplicate prevention: block exact email duplicates (hard rule)
14. Similar name warning: soft warning, allow override

**Edge Cases:**
- EC-1: Exact email match → hard block, cannot save
- EC-2: Similar name but different email → soft warning, can override
- EC-3: Same company, different contacts → soft warning, can override
- EC-4: Phone number match but different name → soft warning
- EC-5: Multiple potential duplicates found → all shown in warning
- EC-6: Duplicate detection during import → duplicates flagged in preview
- EC-7: False positive (not actually duplicate) → "Mark as Not Duplicate" option
- EC-8: Duplicate of deleted client → warning includes "Deleted" status
- EC-9: Duplicate detection with Arabic names → Unicode-aware comparison
- EC-10: Bulk scan finds 50+ duplicate groups → paginated report
- EC-11: Duplicate detection disabled → warning shown to admin
- EC-12: Duplicate detection timeout → fallback to basic email check

**Validation Rules:**
- Email match: case-insensitive, exact match
- Name similarity: Levenshtein distance < 3 or 80% similarity
- Phone match: normalized format comparison (remove spaces, dashes)
- Company match: case-insensitive, exact match

**Security Considerations:**
- Duplicate detection respects user permissions
- Duplicate report access restricted to Admin/Super Admin
- Duplicate detection queries optimized to prevent DoS
- Duplicate detection settings change logged in audit log

**Responsive Design:**
- Mobile (375px): Full-screen duplicate warning, scrollable list
- Tablet (768px): Modal dialog with side-by-side comparison
- Desktop (1024px+): Modal dialog with detailed comparison table

**Performance:**
- Duplicate detection on save: < 500ms
- Bulk duplicate scan (1000 clients): < 30 seconds
- Similarity calculation: < 100ms per comparison
- Duplicate report generation: < 5 seconds

**UX Considerations:**
- Clear duplicate warning with similarity score
- Side-by-side comparison of fields
- "View Existing Client" opens in new tab
- Duplicate report with actionable buttons
- Similarity score visualization (percentage)
- "Merge Clients" button (links to merge feature)
- Duplicate detection settings in admin panel
- "Ignore This Warning" checkbox (logged)
- Duplicate detection statistics dashboard

---

## US-CLIENT-3.13 🟡 Client Merge Functionality
**As a** Super Admin, **I want to** merge duplicate clients into a single record, **so that** I can consolidate data and eliminate redundancy.

**Acceptance Criteria:**
1. Merge accessible from duplicate detection report or client detail page
2. Merge wizard with steps:
   - Step 1: Select clients to merge (2-5 clients)
   - Step 2: Choose primary client (data to keep)
   - Step 3: Review field-by-field, select values to keep
   - Step 4: Confirm merge with warning
3. Field selection for each field: keep from Client A, Client B, or merge/combine
4. Projects from all clients consolidated to primary client
5. Communication history from all clients merged
6. Relationships from all clients merged (duplicates removed)
7. Secondary clients marked as "Merged" (soft delete)
8. Merge creates redirect: accessing merged client redirects to primary
9. Merge action logged in audit log with full details
10. Merge history displayed on primary client: "Merged from [X] clients on [Date]"
11. Undo merge option (within 24 hours, Super Admin only)
12. Merge notification sent to team members who created merged clients
13. Merge preview shows before/after comparison
14. Cannot merge default "Tarqumi" client

**Edge Cases:**
- EC-1: Merge clients with conflicting data → manual selection required
- EC-2: Merge clients with 100+ combined projects → all transferred successfully
- EC-3: Merge includes deleted client → validation error: "Cannot merge deleted clients"
- EC-4: Merge attempt on default "Tarqumi" client → validation error
- EC-5: Undo merge after 24 hours → error: "Merge cannot be undone after 24 hours"
- EC-6: Merge clients with different statuses → primary client status kept
- EC-7: Merge clients with circular relationships → relationships deduplicated
- EC-8: Network error during merge → rollback, no partial merge
- EC-9: Concurrent merge attempts → second attempt blocked
- EC-10: Merge clients with same email → email kept, no conflict
- EC-11: Merge clients with different industries → manual selection
- EC-12: Accessing merged client URL → 301 redirect to primary client

**Validation Rules:**
- Minimum 2 clients required for merge
- Maximum 5 clients per merge operation
- Primary client must be selected
- Cannot merge deleted clients
- Cannot merge default "Tarqumi" client
- All selected clients must exist

**Security Considerations:**
- Only Super Admin can merge clients
- Merge action requires confirmation
- All merge operations logged in audit log
- Merge creates audit trail with before/after data
- Undo merge requires Super Admin permission
- Merged clients retained for 90 days before hard delete

**Responsive Design:**
- Mobile (375px): Full-screen wizard, step-by-step navigation
- Tablet (768px): Modal wizard (800px width)
- Desktop (1024px+): Wide modal with side-by-side comparison

**Performance:**
- Merge operation (2 clients, 50 projects): < 3 seconds
- Merge operation (5 clients, 200 projects): < 10 seconds
- Merge preview generation: < 1 second
- Undo merge: < 2 seconds

**UX Considerations:**
- Step-by-step wizard with progress indicator
- Field-by-field comparison with radio buttons
- "Keep All" option for multi-value fields (notes, tags)
- Merge preview with before/after comparison
- Clear warning: "This action cannot be undone after 24 hours"
- Confirmation checkbox: "I understand this will merge [X] clients"
- Success message with link to merged client
- Undo button prominently displayed (within 24 hours)
- Merge history timeline on client detail page

---

## US-CLIENT-3.14 🟡 Client Tags and Categorization
**As a** Super Admin or Admin, **I want to** add tags and categories to clients, **so that** I can organize and filter clients by custom criteria.

**Acceptance Criteria:**
1. Client detail page includes "Tags" section
2. Add tag button opens tag input with autocomplete
3. Tag autocomplete shows existing tags (reuse) or create new
4. Tags displayed as colored badges (color auto-assigned or custom)
5. Remove tag button (X icon) on each tag
6. Tag categories: Industry, Size, Priority, Region, Source, Custom
7. Filter clients by tags in client list
8. Tag management page: view all tags, edit, merge, delete
9. Tag usage count displayed (how many clients have this tag)
10. Bulk tag operations: add/remove tags for multiple clients
11. Tag search: find all clients with specific tag
12. Tag export included in client export
13. Tag colors customizable (16 preset colors)
14. Tag suggestions based on client data (industry, location)
15. Maximum 20 tags per client

**Edge Cases:**
- EC-1: Tag name with special characters → sanitized, alphanumeric + spaces only
- EC-2: Duplicate tag name (case-insensitive) → reuse existing tag
- EC-3: Tag name too long (50+ chars) → validation error: "Max 50 characters"
- EC-4: Client with 20 tags, attempt to add more → validation error: "Maximum 20 tags per client"
- EC-5: Delete tag used by 100+ clients → confirmation: "This tag is used by [X] clients. Remove from all?"
- EC-6: Merge tags → all clients with old tag get new tag
- EC-7: Tag with no clients → shown in tag management as "Unused"
- EC-8: Tag color conflict → auto-assign different color
- EC-9: Tag search returns no clients → empty state
- EC-10: Bulk add tag to 500 clients → background job, progress indicator
- EC-11: Tag autocomplete with 100+ tags → paginated, search to filter
- EC-12: Tag with Arabic name → RTL support, correct display

**Validation Rules:**
- Tag name: 2-50 characters, alphanumeric + spaces
- Tag name: unique (case-insensitive)
- Maximum 20 tags per client
- Tag color: hex color code or preset color name

**Security Considerations:**
- Only Admin and Super Admin can manage tags
- Tag changes logged in audit log
- Tag names sanitized to prevent XSS
- Bulk tag operations require confirmation

**Responsive Design:**
- Mobile (375px): Tag badges wrap, full-screen tag selector
- Tablet (768px): Inline tag badges, dropdown tag selector
- Desktop (1024px+): Inline tag management with autocomplete

**Performance:**
- Add tag: < 200ms
- Remove tag: < 200ms
- Tag search: < 500ms
- Bulk tag operation (100 clients): < 5 seconds
- Tag autocomplete: < 200ms

**UX Considerations:**
- Color-coded tag badges
- Tag autocomplete with usage count
- "Popular Tags" section in tag selector
- Tag cloud visualization (optional)
- Drag-and-drop tag reordering
- Tag templates for common combinations
- Tag statistics: most used, recently added
- "Suggest Tags" based on client data (AI-powered, future)

---

## US-CLIENT-3.15 🟢 Client Notes and Attachments
**As a** Super Admin or Admin, **I want to** add private notes and attachments to client records, **so that** I can store important information and documents.

**Acceptance Criteria:**
1. Client detail page includes "Notes" and "Attachments" sections
2. Add note button opens rich text editor
3. Note editor supports: bold, italic, underline, lists, links
4. Notes displayed in reverse chronological order
5. Each note shows: Content, Created by, Created date, Last edited
6. Edit/Delete note (only by creator or Super Admin)
7. Note visibility: Private (creator only) or Shared (all admins)
8. Note search within client notes
9. Attachments section allows file upload
10. Supported file types: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP
11. Max file size: 10MB per file, 50MB total per client
12. Attachment preview for images and PDFs
13. Attachment download with original filename
14. Attachment delete (requires confirmation)
15. Notes and attachments included in client export (optional)

**Edge Cases:**
- EC-1: Note with no content → validation error: "Note cannot be empty"
- EC-2: Note exceeds 5000 characters → validation error
- EC-3: Private note viewed by other admin → not visible
- EC-4: Edit note after 30 days → warning shown, allowed
- EC-5: Delete note → confirmation required, soft delete
- EC-6: Attachment file type not allowed → validation error
- EC-7: Attachment exceeds 10MB → validation error
- EC-8: Total attachments exceed 50MB → validation error
- EC-9: Attachment with virus → upload blocked, admin notified
- EC-10: Attachment preview fails → download option shown
- EC-11: Client with 100+ notes → paginated, 20 per page
- EC-12: Note with links → links clickable, open in new tab

**Validation Rules:**
- Note content: 1-5000 characters
- Note visibility: Private or Shared
- Attachment file types: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP
- Attachment size: max 10MB per file
- Total attachments: max 50MB per client

**Security Considerations:**
- Private notes only visible to creator and Super Admin
- Attachments scanned for malicious content
- Attachment access requires authentication
- Note/attachment changes logged in audit log
- Sensitive attachments can be encrypted (future)

**Responsive Design:**
- Mobile (375px): Full-screen note editor, list view for attachments
- Tablet (768px): Modal note editor, grid view for attachments
- Desktop (1024px+): Inline note editor, attachment gallery

**Performance:**
- Add note: < 500ms
- Load notes (50 notes): < 1 second
- File upload: < 5 seconds per file
- Attachment preview: < 1 second

**UX Considerations:**
- Rich text editor with formatting toolbar
- Note templates for common scenarios
- Attachment drag-and-drop upload
- Attachment thumbnail preview
- Note pinning (pin important notes to top)
- Note tagging for organization
- "Quick Note" button for fast entry
- Attachment bulk download (ZIP)
- Note export as PDF

---

## US-CLIENT-3.16 🟢 Client Activity Timeline
**As a** Super Admin, Admin, or Founder, **I want to** view a comprehensive activity timeline for each client, **so that** I can see all interactions and changes at a glance.

**Acceptance Criteria:**
1. Client detail page includes "Activity Timeline" section
2. Timeline displays all client-related activities in chronological order
3. Activity types included:
   - Client created/updated/deleted
   - Projects added/removed
   - Communications logged
   - Notes added/edited
   - Attachments uploaded/deleted
   - Status changes
   - Tags added/removed
   - Relationships added/removed
   - Team member assignments
4. Each activity shows: Icon, Description, Actor, Timestamp, Details link
5. Timeline filter by activity type, date range, actor
6. Timeline search by keyword
7. Timeline pagination: 20 activities per page
8. Activity details expandable (click to see full details)
9. Timeline export as PDF or CSV
10. Real-time updates: new activities appear automatically
11. Activity grouping: group similar activities (e.g., "5 projects added")
12. Timeline visualization: vertical timeline with date markers

**Edge Cases:**
- EC-1: Client with 1000+ activities → paginated, performance optimized
- EC-2: Activity by deleted user → shown as "[Deleted User: email]"
- EC-3: Activity referencing deleted project → shown as "[Deleted Project]"
- EC-4: Timeline filter returns no results → empty state
- EC-5: Timeline search with no matches → empty state
- EC-6: Activity timestamp in different timezone → converted to user's timezone
- EC-7: Grouped activities expanded → individual activities shown
- EC-8: Timeline export with 500+ activities → background job
- EC-9: Real-time update while viewing timeline → smooth insertion
- EC-10: Activity details too long → truncated with "Show More"
- EC-11: Timeline with mixed Arabic/English → correct RTL/LTR display
- EC-12: Activity from automated system → shown as "System"

**Validation Rules:**
- Activity type: must be from predefined list
- Date range: start date before end date
- Timeline pagination: 20 activities per page

**Security Considerations:**
- Activity timeline respects user permissions
- Private notes not shown in timeline (except to creator/Super Admin)
- Sensitive activities can be hidden (configurable)
- Timeline access logged in audit log

**Responsive Design:**
- Mobile (375px): Vertical timeline, card view
- Tablet (768px): Vertical timeline with expanded details
- Desktop (1024px+): Vertical timeline with side panel for details

**Performance:**
- Timeline load (100 activities): < 1 second
- Timeline filter: < 500ms
- Timeline search: < 500ms
- Real-time update: < 100ms

**UX Considerations:**
- Visual timeline with icons and colors
- Date markers for easy navigation
- Activity grouping for clarity
- Expandable activity details
- "Jump to Date" quick navigation
- Activity type filter with icons
- Timeline statistics (activities per month)
- "Export Timeline" button
- Real-time activity notifications (optional)

---

## US-CLIENT-3.17 🟡 Client Custom Fields
**As a** Super Admin, **I want to** define custom fields for clients, **so that** I can capture organization-specific information.

**Acceptance Criteria:**
1. Custom fields management accessible from Settings > Client Fields
2. Add custom field button opens configuration dialog
3. Field configuration options:
   - Field Name (required, max 50 chars)
   - Field Type: Text, Number, Date, Dropdown, Checkbox, URL, Email, Phone
   - Required/Optional
   - Default Value (optional)
   - Help Text (optional, max 200 chars)
   - Validation Rules (min/max length, regex pattern)
   - Display Order
4. Dropdown field: define options (comma-separated or list)
5. Custom fields appear in client create/edit forms
6. Custom fields displayed in client detail page
7. Custom fields included in client list (optional columns)
8. Custom fields searchable and filterable
9. Custom fields included in import/export
10. Maximum 20 custom fields per system
11. Edit/Delete custom fields (Super Admin only)
12. Deleting custom field: data preserved but hidden (soft delete)
13. Custom field usage tracking (how many clients have value)
14. Custom field templates for common industries

**Edge Cases:**
- EC-1: Custom field name duplicate → validation error: "Field name already exists"
- EC-2: Delete custom field with data → confirmation: "[X] clients have data in this field"
- EC-3: Change field type (e.g., Text to Number) → data migration warning
- EC-4: Required custom field added → existing clients show validation error until filled
- EC-5: Custom field with 50+ dropdown options → searchable dropdown
- EC-6: Custom field validation regex invalid → validation error
- EC-7: Custom field with Arabic name → RTL support
- EC-8: Maximum 20 custom fields reached → validation error
- EC-9: Custom field reordering → drag-and-drop, saved immediately
- EC-10: Custom field in import CSV → matched by name, imported correctly
- EC-11: Custom field deleted then recreated → treated as new field
- EC-12: Custom field with URL type → clickable link in client detail

**Validation Rules:**
- Field name: 2-50 characters, unique
- Field type: must be from predefined list
- Dropdown options: max 100 options
- Maximum 20 custom fields per system
- Validation regex: must be valid regex pattern

**Security Considerations:**
- Only Super Admin can manage custom fields
- Custom field changes logged in audit log
- Custom field data sanitized to prevent XSS
- Custom field validation enforced on backend

**Responsive Design:**
- Mobile (375px): Full-screen custom field manager
- Tablet (768px): Modal dialog for field configuration
- Desktop (1024px+): Side panel with field list and configuration

**Performance:**
- Add custom field: < 500ms
- Load custom fields: < 300ms
- Custom field validation: < 100ms
- Custom field search: < 500ms

**UX Considerations:**
- Drag-and-drop field reordering
- Field type icons for visual identification
- Field preview before saving
- Field templates for common use cases
- "Clone Field" option for similar fields
- Field usage statistics
- Field validation preview
- "Import Fields" from template

---

## US-CLIENT-3.18 🟢 Client Dashboard and Analytics
**As a** Super Admin, Admin, or Founder, **I want to** view client analytics and insights, **so that** I can understand client trends and make data-driven decisions.

**Acceptance Criteria:**
1. Client dashboard accessible from Client Management page
2. Dashboard widgets:
   - Total Clients (Active/Inactive breakdown)
   - New Clients This Month (with trend)
   - Clients by Industry (pie chart)
   - Clients by Status (bar chart)
   - Clients by Region (map or list)
   - Top Clients by Project Count
   - Recent Client Activity (last 10)
   - Client Growth Over Time (line chart)
   - Clients Without Projects (count and list)
   - Upcoming Follow-ups (from communication history)
3. Date range selector: This Week, This Month, This Quarter, This Year, Custom
4. Dashboard export as PDF or image
5. Widget customization: show/hide, reorder, resize
6. Dashboard refresh button (manual) and auto-refresh (every 5 minutes)
7. Drill-down: click chart to see detailed client list
8. Dashboard filters: Industry, Status, Tags, Date Range
9. Dashboard sharing: generate shareable link (read-only)
10. Dashboard templates: Sales, Management, Executive
11. Real-time updates: dashboard updates when clients added/edited
12. Dashboard comparison: compare current period vs previous period

**Edge Cases:**
- EC-1: No clients in system → empty state with "Add Client" button
- EC-2: Date range with no data → charts show "No data for selected period"
- EC-3: Very large dataset (10,000+ clients) → charts aggregated, performance optimized
- EC-4: Dashboard with all widgets hidden → message: "Add widgets to customize dashboard"
- EC-5: Dashboard export with large charts → high-resolution PDF
- EC-6: Dashboard shared link expired → message: "Link expired"
- EC-7: Dashboard filter returns no clients → empty state
- EC-8: Real-time update while viewing dashboard → smooth chart update
- EC-9: Dashboard on slow connection → loading skeletons shown
- EC-10: Dashboard with custom date range (5 years) → performance warning
- EC-11: Dashboard comparison with no previous data → message shown
- EC-12: Dashboard widgets with Arabic labels → RTL support

**Validation Rules:**
- Date range: start date before end date
- Date range: max 5 years
- Dashboard widgets: at least one widget visible
- Shareable link: expires after 30 days

**Security Considerations:**
- Dashboard access based on user role
- Shareable links include secure token
- Shareable links expire after 30 days
- Dashboard access logged in audit log
- Sensitive data can be hidden in shared dashboards

**Responsive Design:**
- Mobile (375px): Single column widget layout, scrollable
- Tablet (768px): Two-column grid layout
- Desktop (1024px+): Customizable grid layout, drag-and-drop

**Performance:**
- Dashboard load: < 2 seconds
- Chart rendering: < 500ms per chart
- Dashboard refresh: < 1 second
- Dashboard export: < 5 seconds
- Real-time update: < 100ms

**UX Considerations:**
- Interactive charts with tooltips
- Color-coded charts for clarity
- Widget drag-and-drop customization
- Dashboard templates for quick setup
- "Reset to Default" button
- Chart drill-down for details
- Dashboard comparison slider
- Export with company branding
- Dashboard scheduling (email reports, future)
- AI-powered insights (future)

---
## US-CLIENT-3.19 🟡 Client Portal Access (Future Enhancement)
**As a** Super Admin or Admin, **I want to** provide clients with portal access to view their projects, **so that** clients can stay informed and collaborate effectively.

**Acceptance Criteria:**
1. Client portal toggle in client detail page (Enable/Disable)
2. Portal access generates unique login credentials for client
3. Client receives email invitation with portal URL and credentials
4. Client portal features:
   - View assigned projects (status, timeline, budget)
   - View project documents and deliverables
   - Submit feedback and comments
   - View communication history (shared communications only)
   - Update contact information
   - View invoices and payments (future)
5. Portal access permissions configurable per client
6. Portal activity logged in client activity timeline
7. Portal access can be revoked anytime
8. Portal branding: custom logo, colors (system-wide)
9. Portal language: English/Arabic based on client preference
10. Portal notifications: email alerts for project updates
11. Portal security: MFA optional, session timeout 30 minutes
12. Portal analytics: track client engagement (logins, views)

**Edge Cases:**
- EC-1: Client portal disabled → client cannot login, sees "Access disabled" message
- EC-2: Client with no projects → portal shows "No projects assigned"
- EC-3: Client tries to access other client's data → 403 Forbidden
- EC-4: Client password reset → same flow as team member password reset
- EC-5: Client account inactive → portal access disabled
- EC-6: Client views deleted project → shown as "Completed" or "Archived"
- EC-7: Multiple contacts from same company → separate portal accounts
- EC-8: Client portal invitation email bounces → admin notified
- EC-9: Client never activates portal → reminder sent after 7 days
- EC-10: Client portal on mobile → responsive design, touch-optimized
- EC-11: Client uploads document to project → admin notified
- EC-12: Client portal during system maintenance → maintenance page shown

**Validation Rules:**
- Portal access requires valid email
- Portal password: same complexity requirements as team members
- Portal session timeout: 30 minutes (configurable)
- Portal permissions: configurable per client

**Security Considerations:**
- Portal access separate from admin access
- Portal users cannot access admin features
- Portal data filtered to client's projects only
- Portal activity logged in audit log
- Portal MFA recommended for sensitive projects
- Portal access revocable immediately

**Responsive Design:**
- Mobile (375px): Mobile-first portal design
- Tablet (768px): Optimized for tablet viewing
- Desktop (1024px+): Full-featured portal interface

**Performance:**
- Portal login: < 1 second
- Portal dashboard load: < 2 seconds
- Project list load: < 1 second
- Document download: < 3 seconds

**UX Considerations:**
- Simple, client-friendly interface
- Clear navigation and labels
- Project status visualization
- Document preview and download
- Feedback submission form
- Help and support section
- Portal tour for first-time users
- Mobile app (future)

---

## US-CLIENT-3.20 🟢 Client Satisfaction Surveys
**As a** Super Admin or Admin, **I want to** send satisfaction surveys to clients, **so that** I can gather feedback and improve service quality.

**Acceptance Criteria:**
1. Survey management accessible from Client Management
2. Create survey with questions:
   - Question types: Rating (1-5 stars), Multiple Choice, Text, Yes/No
   - Question text (required, max 500 chars)
   - Required/Optional
   - Help text (optional)
3. Survey templates: Project Completion, Quarterly Check-in, Service Feedback
4. Send survey to individual client or bulk clients
5. Survey delivery: Email with unique survey link
6. Survey link expires after 30 days (configurable)
7. Survey responses stored and linked to client
8. Survey results dashboard:
   - Response rate
   - Average ratings
   - Response breakdown by question
   - Comments and feedback
9. Survey reminders: automatic reminder after 7 days if not completed
10. Survey results export as CSV or PDF
11. Survey responses trigger follow-up actions (low rating → notification)
12. Anonymous survey option (responses not linked to specific client)
13. Survey history: view all surveys sent to client
14. Survey analytics: trends over time, comparison across clients

**Edge Cases:**
- EC-1: Survey sent to client with no email → validation error
- EC-2: Survey link clicked twice → second submission updates first response
- EC-3: Survey link expired → message: "Survey expired. Contact us for assistance."
- EC-4: Survey with no questions → validation error: "Add at least one question"
- EC-5: Survey response with very long text (5000+ chars) → validation error
- EC-6: Survey sent to inactive client → allowed, client can still respond
- EC-7: Survey reminder sent but already completed → reminder not sent
- EC-8: Survey results with 0 responses → empty state: "No responses yet"
- EC-9: Anonymous survey → responses not linked to client, aggregated only
- EC-10: Survey with 20+ questions → paginated, progress indicator
- EC-11: Survey in Arabic → RTL support, correct display
- EC-12: Low rating (1-2 stars) → automatic notification to admin

**Validation Rules:**
- Survey name: required, max 100 characters
- Question text: required, max 500 characters
- Survey must have at least one question
- Survey link expiration: 1-90 days
- Text response: max 2000 characters

**Security Considerations:**
- Survey links include secure token
- Survey links expire after configured period
- Survey responses stored securely
- Anonymous surveys truly anonymous (no IP tracking)
- Survey access logged in audit log

**Responsive Design:**
- Mobile (375px): Mobile-optimized survey form
- Tablet (768px): Tablet-friendly survey layout
- Desktop (1024px+): Full-width survey form

**Performance:**
- Survey creation: < 1 second
- Survey send (100 clients): < 30 seconds
- Survey response submission: < 500ms
- Survey results load: < 1 second

**UX Considerations:**
- Survey templates for quick creation
- Drag-and-drop question reordering
- Question preview before sending
- Progress indicator for multi-page surveys
- Thank you message after submission
- Survey branding with company logo
- Survey results visualization (charts)
- "Export Results" button
- Follow-up action automation
- Survey scheduling (send at specific date/time)

---

## Summary

| User Story | Priority | Focus Area |
|------------|----------|------------|
| US-CLIENT-3.1 | 🔴 Critical | Create Client with Complete Information |
| US-CLIENT-3.2 | 🔴 Critical | View Client List with Advanced Filtering |
| US-CLIENT-3.3 | 🔴 Critical | Edit Client Information |
| US-CLIENT-3.4 | 🟠 High | Delete Client with Project Preservation Logic |
| US-CLIENT-3.5 | 🔴 Critical | Default "Tarqumi" Client Protection |
| US-CLIENT-3.6 | 🟠 High | Client Status Management (Active/Inactive) |
| US-CLIENT-3.7 | 🟠 High | Client Search and Autocomplete |
| US-CLIENT-3.8 | 🟡 Medium | Client Import from CSV/Excel |
| US-CLIENT-3.9 | 🟡 Medium | Client Export and Reporting |
| US-CLIENT-3.10 | 🟡 Medium | Client Relationship Tracking |
| US-CLIENT-3.11 | 🟡 Medium | Client Communication History |
| US-CLIENT-3.12 | 🟠 High | Duplicate Client Detection |
| US-CLIENT-3.13 | 🟡 Medium | Client Merge Functionality |
| US-CLIENT-3.14 | 🟡 Medium | Client Tags and Categorization |
| US-CLIENT-3.15 | 🟢 Nice-to-have | Client Notes and Attachments |
| US-CLIENT-3.16 | 🟢 Nice-to-have | Client Activity Timeline |
| US-CLIENT-3.17 | 🟡 Medium | Client Custom Fields |
| US-CLIENT-3.18 | 🟢 Nice-to-have | Client Dashboard and Analytics |
| US-CLIENT-3.19 | 🟡 Medium | Client Portal Access (Future Enhancement) |
| US-CLIENT-3.20 | 🟢 Nice-to-have | Client Satisfaction Surveys |

**Total Stories**: 20 comprehensive user stories covering all aspects of client management.

**Priority Breakdown**:
- 🔴 Critical: 4 stories (core client management functionality)
- 🟠 High: 4 stories (important features for efficient client management)
- 🟡 Medium: 8 stories (enhanced functionality and data management)
- 🟢 Nice-to-have: 4 stories (future enhancements and optional features)

---

## Key Client Management Principles

1. **Data Integrity**: Duplicate detection, merge functionality, and validation ensure clean client data
2. **Default Client Protection**: System-level protection for the "Tarqumi" default client prevents data loss
3. **Project Preservation**: Client deletion preserves project history and maintains data continuity
4. **Comprehensive Tracking**: Communication history, activity timeline, and relationship tracking provide complete client context
5. **Flexible Organization**: Tags, custom fields, and categories enable organization-specific client management
6. **Advanced Search**: Autocomplete, filters, and search capabilities enable quick client discovery
7. **Data Portability**: Import/export functionality supports data migration and reporting
8. **Status Management**: Active/Inactive status helps organize current vs. past clients
9. **Relationship Mapping**: Track client networks and related accounts for better account management
10. **Analytics and Insights**: Dashboard and reporting provide data-driven client insights

---

## Role Permission Matrix Reference

| Permission | Super Admin | Admin | Founder (CTO) | Founder (CEO/CFO) | HR | Employee |
|------------|-------------|-------|---------------|-------------------|----|----|
| Create Clients | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| View Client List | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |
| Edit Clients | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| Delete Clients | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| Manage Status | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| Import/Export | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| Merge Clients | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| Manage Tags | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| View Analytics | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |
| Manage Custom Fields | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| Client Portal Access | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |
| Send Surveys | ✓ | ✓ | ✗ | ✗ | ✗ | ✗ |

**Notes**:
- Founder roles have read-only access to client data
- Default "Tarqumi" client has special protection rules
- HR and Employee roles cannot access client management

---

## Integration Points

### Authentication & Security (US-AUTH)
- Client management actions logged in security audit log (US-AUTH-1.12)
- Role-based permissions enforced for all client operations (US-AUTH-1.5)
- Client data access controlled by RBAC (US-AUTH-1.5)

### Team Management (US-TEAM)
- Team members can be assigned as project managers for clients
- Client communication history includes team member interactions
- Client activity timeline shows team member actions

### Project Management (US-PROJ)
- Clients assigned to projects (multiple clients per project supported)
- Client deletion preserves project references
- Default "Tarqumi" client used for internal projects
- Client status affects project creation (inactive clients cannot be assigned)

### Landing Page CMS (US-CMS)
- Client showcase projects displayed on public landing page
- Client testimonials and case studies (future)

### Contact Form (US-CONTACT)
- Contact form submissions can create new client records
- Contact submissions linked to existing clients by email

---

## Data Validation Rules Reference

| Field | Validation Rules |
|-------|-----------------|
| Client Name | Required, 2-100 characters, letters/spaces/hyphens/apostrophes |
| Email | Required, RFC 5322 compliant, unique, max 255 characters |
| Company Name | Optional, max 150 characters |
| Phone | Optional, E.164 format, 7-15 digits |
| WhatsApp | Optional, E.164 format, 7-15 digits |
| Website | Optional, valid URL with protocol, max 255 characters |
| Address | Optional, max 500 characters |
| Industry | Optional, max 100 characters |
| Notes | Optional, max 5000 characters |
| Status | Required, Active or Inactive |
| Tags | Max 20 tags per client, 2-50 characters per tag |
| Custom Fields | Varies by field type, max 20 custom fields |

---

## Business Rules Reference

1. **Default Client Rule**: "Tarqumi" client cannot be deleted or set to inactive
2. **Email Uniqueness**: Each client must have a unique email address
3. **Project Preservation**: Deleting a client preserves all associated projects
4. **Soft Delete**: Deleted clients retained for 90 days before permanent deletion
5. **Status Restriction**: Inactive clients cannot be assigned to new projects
6. **Duplicate Prevention**: System warns when creating similar clients
7. **Merge Limitation**: Maximum 5 clients can be merged at once
8. **Tag Limit**: Maximum 20 tags per client
9. **Custom Field Limit**: Maximum 20 custom fields system-wide
10. **Attachment Limit**: Maximum 50MB total attachments per client

---

## Performance Benchmarks

| Operation | Target Performance |
|-----------|-------------------|
| Client list load (100 clients) | < 1 second |
| Client creation | < 1 second |
| Client update | < 500ms |
| Client deletion | < 1 second |
| Client search/autocomplete | < 200ms |
| Duplicate detection | < 500ms |
| Client import (500 clients) | < 2 minutes |
| Client export (100 clients) | < 3 seconds |
| Client merge (2 clients) | < 3 seconds |
| Dashboard load | < 2 seconds |
| Activity timeline load | < 1 second |
| Communication history load | < 1 second |

---

## Responsive Design Breakpoints

- **Mobile**: 375px - 767px (single column, touch-optimized, card layouts)
- **Tablet**: 768px - 1023px (two-column, hybrid touch/mouse, table with scroll)
- **Desktop**: 1024px - 1439px (multi-column, mouse-optimized, full tables)
- **Large Desktop**: 1440px+ (full-width, enhanced features, side panels)

---

## Accessibility Considerations

1. **WCAG AA Compliance**: All client management interfaces meet WCAG 2.1 AA standards
2. **Keyboard Navigation**: Full keyboard support for all actions (Tab, Enter, Esc, Arrow keys)
3. **Screen Reader Support**: Proper ARIA labels, semantic HTML, descriptive alt text
4. **Color Contrast**: Minimum 4.5:1 contrast ratio for text, 3:1 for UI components
5. **Focus Indicators**: Clear focus states for all interactive elements (2px outline)
6. **Error Messages**: Clear, descriptive error messages with recovery suggestions
7. **Form Labels**: All form fields have associated labels (visible or aria-label)
8. **Status Badges**: Color + icon + text for status (not color alone)
9. **Loading States**: Clear loading indicators with descriptive text
10. **Touch Targets**: Minimum 44x44px touch targets on mobile devices

---

## Internationalization (i18n)

1. **Language Support**: English and Arabic (RTL support for Arabic)
2. **Date/Time Formatting**: Localized based on user's timezone and locale
3. **Number Formatting**: Localized number formats (commas, decimals)
4. **Phone Numbers**: International format support (E.164)
5. **Text Direction**: Automatic RTL/LTR switching based on language
6. **Translations**: All UI text translatable, no hardcoded strings
7. **Locale-Specific Validation**: Phone numbers, addresses validated per locale
8. **Currency**: Multi-currency support for future financial features
9. **Search**: Support for Arabic and English text in search queries
10. **Sorting**: Locale-aware sorting (Arabic alphabetical order)

---

## Data Retention and Privacy

1. **Soft Delete**: Clients soft-deleted, retained for 90 days
2. **Hard Delete**: Permanent deletion after 90 days (automated job)
3. **GDPR Compliance**: Right to be forgotten, data export, consent management
4. **Data Minimization**: Only necessary data collected and stored
5. **Audit Log Retention**: 1 year minimum, configurable up to 7 years
6. **Activity Log Retention**: 90 days default, configurable
7. **Personal Data Encryption**: Sensitive fields encrypted at rest
8. **Data Anonymization**: Deleted clients' data anonymized in logs after hard delete
9. **Client Portal Data**: Portal access logs retained for 1 year
10. **Survey Responses**: Retained indefinitely unless client requests deletion

---

## Testing Considerations

1. **Unit Tests**: All client management functions have unit tests
2. **Integration Tests**: API endpoints tested with various scenarios
3. **E2E Tests**: Critical user flows tested end-to-end (create, edit, delete, merge)
4. **Performance Tests**: Load testing with 10,000+ clients
5. **Security Tests**: Permission checks, injection prevention, XSS prevention
6. **Accessibility Tests**: Automated and manual accessibility testing
7. **Responsive Tests**: Testing across all breakpoints and devices
8. **Browser Tests**: Cross-browser testing (Chrome, Firefox, Safari, Edge)
9. **Duplicate Detection Tests**: Test various similarity scenarios
10. **Import/Export Tests**: Test with large files, various formats, edge cases

---

## Future Enhancements

1. **AI-Powered Insights**: Predictive analytics, churn risk, upsell opportunities
2. **Advanced Relationship Mapping**: Visual network diagrams, relationship strength
3. **Client Segmentation**: Automated segmentation based on behavior and attributes
4. **Email Integration**: Sync emails with communication history automatically
5. **Calendar Integration**: Sync meetings and appointments
6. **Mobile App**: Native mobile app for client management on the go
7. **Voice Notes**: Record and attach voice notes to client records
8. **Client Health Score**: Automated scoring based on engagement and satisfaction
9. **Workflow Automation**: Automated actions based on client events (e.g., new client onboarding)
10. **Integration Hub**: Integrations with CRM systems (Salesforce, HubSpot), email (Gmail, Outlook), calendar (Google Calendar, Outlook Calendar)

---

## Error Handling and Edge Cases Summary

### Common Error Scenarios
1. **Network Errors**: Timeout, connection lost → Retry option, data preserved
2. **Validation Errors**: Invalid input → Inline error messages, field highlighting
3. **Permission Errors**: Unauthorized access → 403 Forbidden with clear message
4. **Duplicate Errors**: Duplicate email → Warning with option to view existing client
5. **Not Found Errors**: Client deleted/not found → 404 with helpful message
6. **Conflict Errors**: Concurrent edits → Last save wins, warning shown
7. **File Upload Errors**: Size/type/virus → Clear error with requirements
8. **Import Errors**: Invalid data → Row-by-row error report, skip invalid option
9. **Export Errors**: Large dataset → Background job, email notification
10. **Merge Errors**: Invalid selection → Validation with clear requirements

### Edge Case Handling
1. **Default Client**: Special protection, cannot delete, limited edits
2. **Deleted Clients**: Soft delete, 90-day retention, restore option
3. **Inactive Clients**: Cannot assign to new projects, warning shown
4. **Arabic Text**: Full RTL support, correct display and search
5. **Large Datasets**: Pagination, background jobs, performance optimization
6. **Concurrent Operations**: Conflict detection, last save wins, warnings
7. **Missing Data**: Graceful degradation, default values, clear indicators
8. **Long Text**: Truncation with ellipsis, tooltips, "Show More" options
9. **Special Characters**: Proper escaping, sanitization, validation
10. **Timezone Differences**: Conversion to user's timezone, clear display

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Total User Stories**: 20  
**Total Acceptance Criteria**: 280+  
**Total Edge Cases**: 240+

