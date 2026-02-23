# Project Management User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-PROJECT-4.1 🔴 Create Internal Project with Complete Information
**As a** Super Admin or Admin, **I want to** create new internal projects with all relevant information, **so that** I can track project progress, budget, and team assignments effectively.

**Acceptance Criteria:**
1. Project creation form accessible from Project Management page ("Create Project" button)
2. Required fields: Project Name, Client(s), Project Manager, Start Date, Status
3. Optional fields: Description, Budget, Currency, Priority, End Date, Estimated Hours, Tags
4. Project Name validation: min 3 characters, max 150 characters, unique per client
5. Description: rich text editor with formatting (bold, italic, lists, links), max 10,000 characters
6. Client selection: multi-select dropdown, minimum 1 client required, default "Tarqumi" pre-selected
7. Project Manager: dropdown of active team members with PM permissions (Admin, Super Admin, Founder-CTO)
8. Budget: numeric field, min 0, max 999,999,999, decimal support (2 places)
9. Currency: dropdown (USD, EUR, SAR, AED, EGP), default SAR
10. Priority: dropdown (Low, Medium, High, Critical), default Medium
11. Start Date: date picker, cannot be in past (warning only, not blocking)
12. End Date: date picker, must be after Start Date, optional
13. Status: auto-set to "Planning" (first SDLC phase) on creation
14. Estimated Hours: numeric field, min 0, max 100,000, optional
15. Tags: multi-select or free text with comma separation, max 10 tags, each max 30 characters

**Edge Cases:**
- EC-1: Project name already exists for same client → validation error
- EC-2: Project name with Arabic characters → accepted with RTL support
- EC-3: End Date before Start Date → validation error
- EC-4: Start Date in past → warning shown but creation allowed
- EC-5: Budget without currency → validation error
- EC-6: Multiple clients selected (5+) → all stored, displayed as comma-separated list
- EC-7: Project Manager not selected → validation error
- EC-8: Selected PM is inactive → validation error
- EC-9: User closes form with unsaved changes → confirmation prompt
- EC-10: Network timeout during creation → error message, form data preserved
- EC-11: Duplicate submission (double-click) → second request rejected
- EC-12: Very long description (10,000+ characters) → validation error with character counter

**Security Considerations:**
- Project creation requires Admin or Super Admin role
- All input sanitized to prevent XSS attacks
- Rich text editor sanitizes HTML to prevent script injection
- Creation action logged with IP address and user agent
- Budget values encrypted at rest

**Responsive Design:**
- Mobile (375px): Single column form, full-width inputs, scrollable, sticky save button
- Tablet (768px): Two-column layout for related fields
- Desktop (1024px+): Modal dialog (900px width) or full-page form

**Performance:**
- Form validation: < 100ms (client-side)
- Project creation API: < 1.5 seconds
- Project name uniqueness check: < 300ms (debounced)

---

## US-PROJECT-4.2 🔴 View Project List with Advanced Filtering and Sorting
**As a** Super Admin, Admin, or Founder, **I want to** view a comprehensive list of all projects with advanced filtering and sorting options, **so that** I can quickly find and manage projects based on various criteria.

**Acceptance Criteria:**
1. Project list displays: Project Code, Name, Client(s), PM, Status, Priority, Budget, Start Date, End Date, Progress %, Actions
2. Default sort: Start Date (Newest first)
3. Pagination: 25 projects per page (configurable: 10, 25, 50, 100)
4. Search functionality: real-time search by project name, code, client name, PM name (debounced 300ms)
5. Filter options:
   - Status: All, Planning, Design, Development, Testing, Deployment, Maintenance
   - Priority: All, Low, Medium, High, Critical
   - Project Manager: All, [List of PMs]
   - Client: All, [List of clients]
   - Budget Range: All, <10K, 10K-50K, 50K-100K, 100K-500K, 500K+, Custom Range
   - Date Range: All, This Week, This Month, This Quarter, This Year, Custom Range
   - Active Status: All, Active, Inactive, Archived
   - Progress: All, Not Started (0%), In Progress (1-99%), Completed (100%)
6. Sort options: Name (A-Z, Z-A), Code (Newest, Oldest), Client (A-Z, Z-A), PM (A-Z, Z-A), Status, Priority, Budget, Start Date, End Date, Progress
7. Multi-select for bulk actions (checkbox per row)
8. "Select All" checkbox (selects all on current page)
9. Empty state message when no results
10. Loading skeleton while fetching data
11. Total count displayed: "Showing 1-25 of 150 projects"
12. Filter and search state preserved in URL (shareable links)
13. "Clear Filters" button to reset all filters
14. Export to CSV/Excel button (exports filtered results)
15. Quick actions: View, Edit, Duplicate, Archive, Delete (based on permissions)

**Edge Cases:**
- EC-1: Search with no results → empty state with message
- EC-2: Filter combination returns zero results → empty state with active filters shown
- EC-3: User on page 5, applies filter that returns only 2 pages → redirected to page 1
- EC-4: Very long project name (150 chars) → truncated with ellipsis, full name in tooltip
- EC-5: Project with multiple clients (5+) → displayed as "Client1, Client2, +3 more"
- EC-6: Project with no end date → displays "Ongoing"
- EC-7: Project with no budget → displays "—"
- EC-8: Search with special characters → properly escaped
- EC-9: Rapid filter changes → debounced API calls, previous requests cancelled
- EC-10: User navigates away and returns → filters restored from URL
- EC-11: Export with 1000+ projects → background job, download link sent via email
- EC-12: Project overdue by 30+ days → red highlight with "Overdue" badge

**Security Considerations:**
- Founder roles can view all projects (read-only)
- HR and Employee roles see only projects they're assigned to
- Export action logged in audit log
- Budget information visible only to Admin, Super Admin, Founders

**Responsive Design:**
- Mobile (375px): Card layout, filters in collapsible drawer, swipe actions
- Tablet (768px): Table with horizontal scroll, sticky header, filters in sidebar
- Desktop (1024px+): Full table with all columns visible, filters in left sidebar

**Performance:**
- Initial page load: < 1.5 seconds
- Search/filter response: < 700ms
- Pagination navigation: < 400ms
- Export CSV (100 projects): < 5 seconds

---

## US-PROJECT-4.3 🔴 View Project Details with Comprehensive Information
**As a** Super Admin, Admin, Founder, or assigned team member, **I want to** view complete project details including all information, timeline, budget tracking, and team assignments, **so that** I have full visibility into project status and progress.

**Acceptance Criteria:**
1. Project detail page accessible by clicking project name in list or via direct URL
2. **Header Section:**
   - Project Code (e.g., PROJ-2024-0001)
   - Project Name (large, prominent)
   - Status badge (color-coded by SDLC phase)
   - Priority badge (color-coded)
   - Active/Inactive/Archived indicator
   - Quick actions: Edit, Duplicate, Archive, Delete
3. **Overview Tab:**
   - Description (rich text display)
   - Client(s) with links to client detail pages
   - Project Manager with avatar and contact info
   - Start Date and End Date
   - Duration (calculated: X days/weeks/months)
   - Days remaining or Days overdue
   - Progress percentage with visual progress bar
   - Tags (clickable, filter projects by tag)
4. **Budget Tab:**
   - Total Budget with currency
   - Spent Amount (tracked from tasks/expenses)
   - Remaining Budget (calculated)
   - Budget utilization percentage with visual indicator
   - Budget alerts if over threshold
   - Budget history chart (spending over time)
   - Expense breakdown by category
5. **Timeline Tab:**
   - Visual timeline/Gantt chart showing project phases
   - SDLC phase indicators
   - Milestones with dates
   - Phase duration and progress
   - Critical path highlighting
   - Timeline zoom controls
6. **Team Tab:**
   - Project Manager details
   - Assigned team members with roles
   - Team member avatars and contact info
   - Workload per team member
   - Add/remove team members (Admin only)
7. **Activity Log Tab:**
   - Chronological list of all project changes
   - Who made the change, what changed, when
   - Status transitions
   - Budget updates
   - Team assignments
   - Filterable by event type and date range
8. Breadcrumb navigation: Projects > [Client Name] > [Project Name]
9. "Back to Projects" link
10. Last updated timestamp and user
11. Print-friendly view option
12. Share project link (generates shareable URL)

**Edge Cases:**
- EC-1: Project with no end date → "Ongoing" displayed
- EC-2: Project overdue → "Overdue by X days" in red
- EC-3: Project with no budget → Budget tab shows "Budget not set"
- EC-4: Project with multiple clients (10+) → scrollable client list
- EC-5: Very long description → scrollable with "Read More" expansion
- EC-6: Project with no team members → "No team members assigned yet"
- EC-7: Activity log with 1000+ entries → paginated, 50 per page
- EC-8: Timeline extends beyond viewport → horizontal scroll with zoom
- EC-9: Budget exceeded → red alert banner
- EC-10: User without permission to view budget → Budget tab hidden
- EC-11: Inactive PM assigned → warning badge
- EC-12: Project archived → read-only mode, edit actions disabled

**Security Considerations:**
- Access control based on user role and project assignment
- Budget information restricted to Admin, Super Admin, Founders
- Activity log shows only events user has permission to see
- Shareable links include access token with expiration

**Responsive Design:**
- Mobile (375px): Stacked layout, tabs as accordion, scrollable sections
- Tablet (768px): Two-column layout, tabs as horizontal navigation
- Desktop (1024px+): Full-width layout with sidebar navigation

**Performance:**
- Page load: < 1.5 seconds
- Tab switching: < 300ms
- Timeline chart render: < 1 second
- Budget chart render: < 800ms
- Activity log load: < 500ms (first 50 entries)

---

## US-PROJECT-4.4 🔴 Edit Project Information with Validation
**As a** Super Admin or Admin, **I want to** edit existing project information with proper validation, **so that** I can keep project data accurate and up-to-date throughout the project lifecycle.

**Acceptance Criteria:**
1. Edit button available in project list actions column and project detail page
2. Edit form pre-populated with current project data
3. Editable fields: Project Name, Description, Client(s), Project Manager, Budget, Currency, Priority, Start Date, End Date, Estimated Hours, Tags, Active Status
4. Status field editable separately (see US-PROJECT-4.6 for status workflow)
5. Project Code not editable (auto-generated, immutable)
6. Project Name uniqueness validated (excluding current project)
7. Client(s) modification: add/remove clients, minimum 1 required
8. Project Manager reassignment triggers notification to old and new PM
9. Budget changes logged in budget history
10. Date changes validated: End Date must be after Start Date
11. On successful update, project data refreshed in list and detail views
12. Update event logged in audit log with editor, timestamp, changed fields (before/after values)
13. Optimistic UI update (immediate feedback, rollback on error)
14. "Save" and "Cancel" buttons clearly visible
15. Unsaved changes warning if user navigates away

**Edge Cases:**
- EC-1: Project name changed to existing name for same client → validation error
- EC-2: Project name changed while tasks exist → all task references updated automatically
- EC-3: PM changed while project is active → notification sent to both old and new PM
- EC-4: Budget increased/decreased significantly (>50%) → confirmation dialog required
- EC-5: End Date changed to past date while project status is not Completed → warning shown
- EC-6: No changes made, user clicks Save → no API call, message shown
- EC-7: Concurrent edits by two admins → last save wins, warning shown
- EC-8: Network error during save → error message, form data preserved, retry option
- EC-9: Client removed while project has tasks assigned to that client → warning shown
- EC-10: Start Date changed to after current End Date → validation error
- EC-11: PM changed to inactive team member → validation error
- EC-12: Budget currency changed → confirmation required

**Security Considerations:**
- Only Super Admin and Admin can edit projects
- Founder roles have read-only access (edit button hidden)
- All changes logged with before/after values
- Budget changes require additional audit logging
- Input sanitization prevents XSS attacks
- Concurrent edit detection prevents data loss

**Responsive Design:**
- Mobile (375px): Full-screen edit form, scrollable, sticky save button
- Tablet (768px): Modal dialog (900px width)
- Desktop (1024px+): Side panel or modal dialog with live preview

**Performance:**
- Form load with pre-populated data: < 700ms
- Update API response: < 1.5 seconds
- Project name uniqueness check: < 300ms (debounced)
- Change history load: < 400ms

---

## US-PROJECT-4.5 🔴 Multiple Clients per Project Management
**As a** Super Admin or Admin, **I want to** assign multiple clients to a single project, **so that** I can manage projects that involve collaboration between multiple client organizations.

**Acceptance Criteria:**
1. Client selection field supports multi-select (dropdown with checkboxes)
2. Minimum 1 client required per project
3. Maximum 10 clients per project (configurable)
4. Client search/filter within multi-select dropdown
5. Selected clients displayed as chips/tags with remove (X) button
6. Client order can be rearranged (drag-and-drop or up/down arrows)
7. Primary client designation (first client in list or explicitly marked)
8. Project appears in all associated clients' project lists
9. Client removal validation: cannot remove last client
10. Client removal warning if client has associated tasks/documents
11. Adding client triggers notification to client contact (if configured)
12. Client list displayed in project detail page with links to client profiles
13. Project list can be filtered by any associated client
14. Export includes all client names (comma-separated)
15. Audit log tracks client additions and removals

**Edge Cases:**
- EC-1: Attempt to remove last client → validation error
- EC-2: Add duplicate client → prevented with message
- EC-3: Add 11th client → validation error
- EC-4: Remove client with associated tasks → warning shown
- EC-5: Client becomes inactive after being added → project shows client with "(Inactive)" badge
- EC-6: Client deleted from system → project retains reference as "[Deleted: Client Name]"
- EC-7: Primary client removed → next client becomes primary automatically
- EC-8: Client order changed → order preserved in all views
- EC-9: Search for project by client name → returns projects with that client
- EC-10: Export with multiple clients → all clients listed, comma-separated
- EC-11: Very long client list (10 clients) → scrollable, truncated in list view with "+X more"
- EC-12: Client added while project is archived → allowed, audit logged

**Security Considerations:**
- Client addition/removal logged in audit log
- Only Admin and Super Admin can modify client assignments
- Client visibility based on user permissions
- Notifications sent only if client contact configured

**Responsive Design:**
- Mobile (375px): Full-screen multi-select with search, selected clients as chips below
- Tablet (768px): Dropdown multi-select with inline chips
- Desktop (1024px+): Dropdown multi-select with drag-and-drop reordering

**Performance:**
- Client search in dropdown: < 200ms
- Add/remove client: < 300ms
- Reorder clients: < 200ms (optimistic update)
- Load project with 10 clients: < 500ms

---

## US-PROJECT-4.6 🔴 Project Status Workflow with 6 SDLC Phases
**As a** Super Admin or Admin, **I want to** manage project status through a defined 6-phase SDLC workflow, **so that** project progress is tracked consistently and team members understand the current project stage.

**Acceptance Criteria:**
1. **Six SDLC Phases (in order):**
   - **Planning**: Initial project setup, requirements gathering, resource allocation
   - **Design**: Architecture design, UI/UX design, technical specifications
   - **Development**: Coding, implementation, feature development
   - **Testing**: QA testing, bug fixing, user acceptance testing
   - **Deployment**: Production deployment, go-live, launch
   - **Maintenance**: Post-launch support, bug fixes, enhancements
2. Status change dropdown shows all 6 phases
3. Status transitions can move forward or backward (flexible workflow)
4. Status change triggers confirmation dialog
5. Status change reason field (optional, max 500 characters)
6. Status badge color-coded by phase:
   - Planning: Blue
   - Design: Purple
   - Development: Orange
   - Testing: Yellow
   - Deployment: Green
   - Maintenance: Gray
7. Status change logged in audit log with changer, timestamp, reason, previous status
8. Status change notification sent to PM and team members (configurable)
9. Status timeline visualization showing time spent in each phase
10. Automatic status suggestions based on project progress
11. Status change triggers workflow actions (e.g., deployment checklist)
12. Project list filterable by status/phase
13. Dashboard shows project count per phase
14. Status history accessible in project detail page
15. Bulk status change supported

**Edge Cases:**
- EC-1: Move from Deployment back to Development → confirmation required
- EC-2: Move from Planning directly to Deployment → warning about skipping phases
- EC-3: Project in Maintenance for 6+ months → suggestion to archive
- EC-4: Status change without reason → allowed, but reason field highlighted
- EC-5: Multiple rapid status changes → all changes logged
- EC-6: Status changed while team member viewing project → real-time update shown
- EC-7: Bulk status change includes projects in different phases → confirmation shows affected projects
- EC-8: Status change triggers automated tasks → tasks created automatically
- EC-9: Project archived while in active phase → warning shown
- EC-10: Status change by PM vs Admin → both allowed, logged differently
- EC-11: Status timeline shows phase duration → helps identify bottlenecks
- EC-12: Custom status added (future) → maintains workflow order

**Security Considerations:**
- Only Admin, Super Admin, and assigned PM can change status
- All status changes logged in audit log
- Status change notifications configurable per user
- Bulk status change requires confirmation

**Responsive Design:**
- Mobile (375px): Full-screen status change dialog with phase descriptions
- Tablet (768px): Modal dialog with status timeline visualization
- Desktop (1024px+): Inline status dropdown with hover preview

**Performance:**
- Status change API: < 400ms
- Status timeline calculation: < 200ms
- Bulk status change (50 projects): < 10 seconds
- Real-time status update notification: < 1 second

---

## US-PROJECT-4.7 🟠 Active/Inactive Project Management
**As a** Super Admin or Admin, **I want to** manage project active status independently from SDLC phase, **so that** I can temporarily pause projects or mark them as inactive without losing project data.

**Acceptance Criteria:**
1. Project active status field with three options: Active, Inactive, Archived
2. Status toggle available in project list (quick action) and project detail page
3. Active status change triggers confirmation dialog
4. Active projects displayed by default in project list
5. Inactive projects hidden by default, shown when "Show Inactive" filter enabled
6. Archived projects hidden by default, shown when "Show Archived" filter enabled
7. Status badge displayed (color-coded: green=Active, gray=Inactive, blue=Archived)
8. Inactive projects cannot have new tasks assigned (validation error)
9. Inactive projects with ongoing tasks show warning
10. Archived projects are read-only (no edits allowed except unarchive)
11. Active status change logged in audit log with changer, timestamp, reason
12. Active status change notification option (email to PM and team)
13. Bulk active status change supported
14. Last active status change date and user displayed
15. Active status filter in project list: All, Active, Inactive, Archived
16. Dashboard shows count of Active/Inactive/Archived projects

**Edge Cases:**
- EC-1: Inactive project with ongoing tasks → warning shown, status change allowed
- EC-2: Attempt to create task for inactive project → validation error
- EC-3: Project set to Inactive, then task assigned → validation prevents assignment
- EC-4: Bulk status change includes archived projects → confirmation required
- EC-5: Project status changed while being viewed → viewer sees updated status immediately
- EC-6: Inactive project reactivated → immediately available for task assignment
- EC-7: Active status change with reason field → reason stored and displayed
- EC-8: Multiple rapid status changes → all changes logged
- EC-9: Archived project edit attempt → error message shown
- EC-10: Project with 50+ ongoing tasks set to Inactive → confirmation with task count
- EC-11: Active status filter combined with SDLC phase filter → works correctly
- EC-12: Export includes inactive/archived projects when filter applied

**Security Considerations:**
- Only Super Admin and Admin can change active status
- Active status changes logged in audit log
- Archived projects protected from accidental modification
- Bulk status change requires confirmation

**Responsive Design:**
- Mobile (375px): Status toggle button, full-screen confirmation dialog
- Tablet (768px): Inline status toggle with confirmation modal
- Desktop (1024px+): Quick status toggle with hover confirmation

**Performance:**
- Active status change API: < 400ms
- Bulk status change (100 projects): < 8 seconds
- Active status filter application: < 600ms

---

## US-PROJECT-4.8 🟠 Project Timeline Visualization with Gantt Chart
**As a** Super Admin, Admin, Founder, or PM, **I want to** visualize project timelines with a Gantt chart, **so that** I can understand project schedules, dependencies, and critical paths at a glance.

**Acceptance Criteria:**
1. Timeline tab in project detail page displays Gantt chart
2. Gantt chart shows:
   - Project start and end dates (main bar)
   - SDLC phases as sub-bars with different colors
   - Milestones as diamond markers
   - Current date indicator (vertical line)
   - Progress overlay on bars (filled portion = completed %)
3. Time scale options: Day, Week, Month, Quarter view
4. Zoom controls: Zoom in, Zoom out, Fit to screen
5. Horizontal scroll for long timelines
6. Hover tooltip shows: Phase name, Start date, End date, Duration, Progress %
7. Phase bars color-coded matching status badges
8. Overdue phases highlighted in red
9. Critical path highlighted (phases that affect project end date)
10. Dependencies between phases shown as arrows (optional)
11. Today marker (vertical line) clearly visible
12. Timeline legend explaining colors and symbols
13. Export timeline as image (PNG) or PDF
14. Print-friendly timeline view
15. Responsive timeline (adapts to screen size)

**Edge Cases:**
- EC-1: Project with no end date → timeline extends to "Ongoing" with dashed line
- EC-2: Project with very long duration (2+ years) → month/quarter view recommended
- EC-3: Project with very short duration (1 week) → day view recommended
- EC-4: Phase dates overlap → bars stacked vertically
- EC-5: Milestone on same date as phase end → milestone marker offset slightly
- EC-6: Timeline extends beyond viewport → horizontal scroll with minimap
- EC-7: Gantt chart fails to render → fallback to table view with dates
- EC-8: No phases defined → shows only project start/end bar
- EC-9: Phase start date before project start date → validation warning
- EC-10: Phase end date after project end date → validation warning, extends project bar
- EC-11: Multiple projects timeline view → shows all projects stacked
- EC-12: Timeline with 20+ phases → scrollable with fixed header

**Security Considerations:**
- Timeline visible to users with project view permission
- Export action logged in audit log
- Timeline data filtered based on user permissions

**Responsive Design:**
- Mobile (375px): Simplified timeline, vertical layout, swipe to scroll
- Tablet (768px): Horizontal timeline with touch zoom/pan
- Desktop (1024px+): Full Gantt chart with all features

**Performance:**
- Timeline render: < 1.5 seconds
- Zoom/pan operations: < 200ms (smooth 60fps)
- Export to image: < 3 seconds
- Export to PDF: < 5 seconds
- Real-time updates: < 500ms

---

## US-PROJECT-4.9 🟠 Budget Tracking and Alerts
**As a** Super Admin, Admin, or Founder, **I want to** track project budget utilization with automated alerts, **so that** I can prevent budget overruns and make informed financial decisions.

**Acceptance Criteria:**
1. Budget tab in project detail page shows:
   - Total Budget (with currency)
   - Spent Amount (tracked from expenses/tasks)
   - Remaining Budget (calculated: Total - Spent)
   - Budget Utilization % (calculated: Spent / Total * 100)
   - Visual progress bar (color-coded: green <80%, yellow 80-95%, red >95%)
2. Budget alerts configured at thresholds: 50%, 75%, 90%, 100%
3. Alert notification sent to PM and Admin when threshold reached
4. Alert banner displayed on project detail page when threshold exceeded
5. Budget history chart showing spending over time (line or bar chart)
6. Expense breakdown by category (if categories configured)
7. Budget vs Actual comparison table
8. Projected budget completion date based on current burn rate
9. Budget adjustment history (all budget changes logged)
10. Budget export to CSV/Excel with detailed breakdown
11. Budget alerts configurable per project (enable/disable, custom thresholds)
12. Budget currency conversion support (if multi-currency expenses)
13. Budget forecast based on historical spending patterns
14. Budget variance analysis (planned vs actual)
15. Budget approval workflow (if budget increase requested)

**Edge Cases:**
- EC-1: Budget not set → Budget tab shows "Budget not configured"
- EC-2: Spent amount exceeds budget → red alert shown
- EC-3: Budget increased mid-project → history shows adjustment, alerts recalculated
- EC-4: Budget decreased mid-project → confirmation required if spent > new budget
- EC-5: Expense added without category → categorized as "Uncategorized"
- EC-6: Multi-currency expenses → converted to project currency
- EC-7: Budget alert threshold reached → notification sent once
- EC-8: Budget utilization exactly 100% → alert triggered
- EC-9: Negative remaining budget → displayed in red with "Over Budget" label
- EC-10: Budget chart with no expenses → shows flat line at zero
- EC-11: Budget forecast with insufficient data → message shown
- EC-12: Budget approval pending → budget changes locked until approved

**Security Considerations:**
- Budget information visible only to Admin, Super Admin, Founders
- Budget changes logged in audit log with before/after values
- Budget alerts sent only to authorized users
- Budget export action logged

**Responsive Design:**
- Mobile (375px): Stacked layout, scrollable charts, simplified view
- Tablet (768px): Two-column layout, charts side-by-side
- Desktop (1024px+): Full dashboard with multiple charts and tables

**Performance:**
- Budget calculation: < 200ms
- Budget chart render: < 1 second
- Budget history load: < 500ms
- Budget export: < 3 seconds
- Real-time budget updates: < 500ms

---

## US-PROJECT-4.10 🟠 Project Manager Reassignment with Notification
**As a** Super Admin or Admin, **I want to** reassign project managers with proper notification and handover process, **so that** project ownership transitions smoothly without disruption.

**Acceptance Criteria:**
1. PM reassignment available in project edit form and project detail page
2. PM dropdown shows active team members with PM role
3. PM change triggers confirmation dialog
4. Reassignment reason field (optional, max 500 characters)
5. Notification sent to old PM about removal
6. Notification sent to new PM about assignment
7. Notification includes project details and link to project page
8. PM change logged in audit log with changer, timestamp, reason, old PM, new PM
9. PM change reflected immediately in project list and detail views
10. PM change history accessible in project detail page
11. Old PM retains view access to project (unless permissions changed)
12. New PM gains full PM permissions
13. Bulk PM reassignment supported
14. PM workload updated (project count, hours assigned)
15. PM reassignment triggers handover checklist (optional)

**Edge Cases:**
- EC-1: Reassign to same PM → validation error
- EC-2: Reassign to inactive PM → validation error
- EC-3: Old PM has ongoing tasks → tasks remain assigned
- EC-4: New PM already managing 20+ projects → warning shown
- EC-5: PM reassignment during active phase → confirmation required
- EC-6: Bulk reassignment includes projects with different PMs → confirmation shows affected projects
- EC-7: PM reassignment while old PM viewing project → real-time notification shown
- EC-8: PM reassignment with reason → reason displayed in change history
- EC-9: PM becomes inactive after assignment → project shows warning badge
- EC-10: PM deleted from system → project shows "[Deleted PM]", reassignment required
- EC-11: PM reassignment triggers handover checklist → checklist created automatically
- EC-12: PM reassignment notification fails → retry mechanism, logged as error

**Security Considerations:**
- Only Super Admin and Admin can reassign PM
- PM reassignment logged in audit log
- Notifications sent via secure email
- Old PM permissions reviewed

**Responsive Design:**
- Mobile (375px): Full-screen PM selection with search
- Tablet (768px): Modal dialog with PM details
- Desktop (1024px+): Inline PM dropdown with confirmation modal

**Performance:**
- PM reassignment API: < 500ms
- Notification delivery: < 5 seconds
- Bulk reassignment (50 projects): < 15 seconds
- PM workload recalculation: < 300ms

---

## US-PROJECT-4.11 🟠 Project Search and Advanced Filtering
**As a** Super Admin, Admin, or Founder, **I want to** search for projects with advanced filtering capabilities, **so that** I can quickly find specific projects based on multiple criteria.

**Acceptance Criteria:**
1. Search bar prominently displayed in project list page
2. Real-time autocomplete as user types (minimum 2 characters)
3. Autocomplete searches: Project Name, Project Code, Client Name, PM Name, Tags
4. Autocomplete results show: Project Name, Code, Client(s), Status badge, Priority badge
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
15. Search results respect user permissions

**Edge Cases:**
- EC-1: Search with 1 character → no autocomplete, wait for 2+ characters
- EC-2: Search with special characters → properly escaped, no errors
- EC-3: Search returns 100+ results → only top 10 shown, "View all results" link
- EC-4: Search with Arabic text → RTL support, correct matching
- EC-5: Search while typing fast → debounced, only last query sent
- EC-6: Network timeout during search → error message, retry option
- EC-7: Search for archived project → shown with Archived badge
- EC-8: Search for inactive project → shown with Inactive badge
- EC-9: Autocomplete dropdown extends beyond viewport → scrollable
- EC-10: Search with mixed Arabic/English → both languages matched
- EC-11: Recent searches include deleted projects → removed from recent list
- EC-12: Search on slow connection → loading indicator shown

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

---

## US-PROJECT-4.12 🟠 Project Archiving and Restoration
**As a** Super Admin or Admin, **I want to** archive completed or cancelled projects, **so that** I can keep the active project list clean while preserving historical data.

**Acceptance Criteria:**
1. Archive action available in project list and project detail page
2. Archive triggers confirmation dialog: "Archive [Project Name]?"
3. Archive reason field (optional, max 500 characters)
4. Archived projects removed from default project list
5. Archived projects accessible via "Show Archived" filter
6. Archived projects are read-only (no edits except unarchive)
7. Archived project badge displayed (blue "Archived" badge)
8. Archive action logged in audit log with archiver, timestamp, reason
9. Archive notification sent to PM and team members (optional)
10. Bulk archive supported (select multiple projects, archive all)
11. Unarchive action available for archived projects
12. Unarchive triggers confirmation dialog
13. Unarchived project status set to Inactive (must be manually activated)
14. Archive history accessible in project detail page
15. Archived projects included in reports when explicitly requested

**Edge Cases:**
- EC-1: Archive active project → confirmation required with warning
- EC-2: Archive project with ongoing tasks → warning shown, archive allowed
- EC-3: Archived project edit attempt → error: "Archived projects are read-only"
- EC-4: Bulk archive includes active projects → confirmation shows affected projects
- EC-5: Archived project viewed by team member → read-only banner shown
- EC-6: Unarchive project → status set to Inactive, manual activation required
- EC-7: Archive reason provided → reason displayed in archive history
- EC-8: Multiple archive/unarchive cycles → all actions logged
- EC-9: Archived project in reports → shown with "(Archived)" label
- EC-10: Search includes archived projects → shown with Archived badge
- EC-11: Export includes archived projects when filter applied
- EC-12: Archived project referenced in other projects → reference preserved

**Security Considerations:**
- Only Super Admin and Admin can archive/unarchive projects
- Archive actions logged in audit log
- Archived projects protected from modification
- Bulk archive requires confirmation

**Responsive Design:**
- Mobile (375px): Archive button, full-screen confirmation dialog
- Tablet (768px): Inline archive action with confirmation modal
- Desktop (1024px+): Quick archive action with hover confirmation

**Performance:**
- Archive API: < 500ms
- Bulk archive (50 projects): < 10 seconds
- Unarchive API: < 500ms
- Archive filter application: < 600ms

---

## US-PROJECT-4.13 🟡 Project Duplication for Quick Setup
**As a** Super Admin or Admin, **I want to** duplicate existing projects, **so that** I can quickly create similar projects without re-entering all information.

**Acceptance Criteria:**
1. Duplicate action available in project list and project detail page
2. Duplicate triggers dialog: "Duplicate [Project Name]?"
3. Duplication options:
   - Copy project name (with " - Copy" suffix)
   - Copy description
   - Copy client(s)
   - Copy budget and currency
   - Copy priority
   - Copy tags
   - Copy team assignments (optional checkbox)
   - Copy milestones (optional checkbox)
4. New project name editable before creation
5. New project code auto-generated (unique)
6. New project status set to "Planning"
7. New project start date set to today
8. New project end date cleared (must be set manually)
9. Duplicate action logged in audit log
10. Success message: "Project duplicated successfully" with link to new project
11. Option to "Edit Now" or "View Project" after duplication
12. Bulk duplication not supported (one at a time)
13. Duplication preserves custom fields (if configured)
14. Duplication notification sent to PM (optional)
15. Duplicate count tracked (original project shows "Duplicated X times")

**Edge Cases:**
- EC-1: Duplicate project with same name → " - Copy" suffix added automatically
- EC-2: Duplicate project with inactive PM → warning shown, PM must be reassigned
- EC-3: Duplicate project with inactive clients → warning shown, clients included
- EC-4: Duplicate archived project → new project created as Inactive
- EC-5: Duplicate project with 10 clients → all clients copied
- EC-6: Duplicate project with team assignments → team members notified
- EC-7: Duplicate project with large description → description copied fully
- EC-8: Duplicate project with budget → budget copied, spent amount reset to 0
- EC-9: Duplicate project multiple times → each gets unique " - Copy (2)", " - Copy (3)" suffix
- EC-10: Network error during duplication → error message, retry option
- EC-11: Duplicate project with custom fields → all custom fields copied
- EC-12: Duplicate project with milestones → milestones copied with adjusted dates

**Security Considerations:**
- Only Super Admin and Admin can duplicate projects
- Duplication action logged in audit log
- Duplicated project inherits original project permissions
- Budget information copied only if user has budget view permission

**Responsive Design:**
- Mobile (375px): Full-screen duplication dialog with options
- Tablet (768px): Modal dialog (700px width) with checkboxes
- Desktop (1024px+): Modal dialog with side-by-side preview

**Performance:**
- Duplication API: < 2 seconds
- Duplication with team assignments: < 3 seconds
- Duplication with milestones: < 3 seconds

---

## US-PROJECT-4.14 🟡 Project Templates for Standardization
**As a** Super Admin or Admin, **I want to** create and use project templates, **so that** I can standardize project setup and ensure consistency across similar projects.

**Acceptance Criteria:**
1. "Save as Template" option available when creating/editing project
2. Template creation dialog with fields:
   - Template Name (required, max 100 characters)
   - Template Description (optional, max 500 characters)
   - Template Category (optional: Web Development, Mobile App, Marketing, etc.)
   - Include fields: Name pattern, Description, Budget range, Priority, Estimated hours, Tags, Milestones
3. Template library accessible from project creation page
4. Template list shows: Template Name, Category, Description, Created by, Created date, Usage count
5. Template search and filter by category
6. "Use Template" button loads template data into project creation form
7. Template data pre-fills form fields (user can modify before saving)
8. Template usage tracked (count increments each time used)
9. Template edit available (updates template, not existing projects)
10. Template delete available (with confirmation, doesn't affect existing projects)
11. Template export/import for sharing across systems
12. Default templates provided (Web Project, Mobile App, Marketing Campaign, etc.)
13. Template permissions (who can create, edit, delete templates)
14. Template versioning (track template changes over time)
15. Template preview before using

**Edge Cases:**
- EC-1: Save project as template with same name as existing template → validation error
- EC-2: Use template with inactive PM → PM field cleared, must be selected
- EC-3: Use template with deleted clients → clients removed from selection
- EC-4: Template with budget range → user enters specific budget within range
- EC-5: Template with milestones → milestone dates adjusted based on project start date
- EC-6: Delete template used by 50+ projects → confirmation with usage count
- EC-7: Edit template → existing projects not affected, only new projects
- EC-8: Template with custom fields → custom fields included in template
- EC-9: Import template with duplicate name → option to rename or overwrite
- EC-10: Template export → JSON format with all template data
- EC-11: Template with Arabic content → RTL support maintained
- EC-12: Template usage by different admins → usage tracked per admin

**Security Considerations:**
- Only Super Admin and Admin can create/edit/delete templates
- Template creation logged in audit log
- Template usage tracked for analytics
- Template export includes only non-sensitive data

**Responsive Design:**
- Mobile (375px): Full-screen template library, card layout
- Tablet (768px): Grid layout with template cards
- Desktop (1024px+): Sidebar template library with preview pane

**Performance:**
- Template load: < 500ms
- Template save: < 1 second
- Template list load: < 700ms
- Template usage (pre-fill form): < 300ms

---

## US-PROJECT-4.15 🟠 Project Priority Management with Visual Indicators
**As a** Super Admin, Admin, or PM, **I want to** manage project priorities with clear visual indicators, **so that** team members can focus on high-priority projects first.

**Acceptance Criteria:**
1. Priority field with four levels: Low, Medium, High, Critical
2. Priority badge color-coded:
   - Low: Green
   - Medium: Yellow
   - High: Orange
   - Critical: Red
3. Priority change available in project edit form and quick action in list
4. Priority change triggers confirmation dialog for Critical priority
5. Priority change logged in audit log
6. Priority change notification sent to PM and team (optional)
7. Project list sortable by priority (Critical first, then High, Medium, Low)
8. Dashboard shows project count per priority level
9. High and Critical priority projects highlighted in list
10. Priority filter in project list
11. Priority badge displayed prominently in project detail page
12. Bulk priority change supported
13. Priority escalation rules (optional: auto-escalate if overdue)
14. Priority change history accessible
15. Priority-based email alerts (Critical projects get immediate alerts)

**Edge Cases:**
- EC-1: Change priority to Critical → confirmation required with reason
- EC-2: Multiple projects set to Critical → all shown with red badge
- EC-3: Priority changed while project viewed → real-time update shown
- EC-4: Bulk priority change includes Critical → confirmation shows affected projects
- EC-5: Priority escalation rule triggered → automatic priority increase logged
- EC-6: Critical project overdue → additional alert sent to management
- EC-7: Priority change without reason → allowed, but reason field recommended
- EC-8: Priority filter combined with status filter → works correctly
- EC-9: Export includes priority → priority shown as text (Low/Medium/High/Critical)
- EC-10: Priority change by PM vs Admin → both allowed, logged differently
- EC-11: Archived project priority change → not allowed, error shown
- EC-12: Priority-based sorting with same priority → secondary sort by start date

**Security Considerations:**
- Priority change logged in audit log
- Critical priority changes require confirmation
- Priority-based alerts sent only to authorized users
- Bulk priority change requires confirmation

**Responsive Design:**
- Mobile (375px): Priority badge prominent, quick priority change
- Tablet (768px): Priority badge with inline change dropdown
- Desktop (1024px+): Priority badge with hover quick-change menu

**Performance:**
- Priority change API: < 300ms
- Bulk priority change (100 projects): < 5 seconds
- Priority filter application: < 500ms
- Priority-based sorting: < 400ms

---

## US-PROJECT-4.16 🟠 Project Progress Tracking and Reporting
**As a** Super Admin, Admin, Founder, or PM, **I want to** track project progress with visual indicators and reports, **so that** I can monitor project health and identify issues early.

**Acceptance Criteria:**
1. Progress percentage field (0-100%) displayed in project list and detail page
2. Progress bar visual indicator (color-coded: red <30%, yellow 30-70%, green >70%)
3. Progress calculation methods:
   - Manual entry by PM
   - Automatic based on completed tasks (if task management integrated)
   - Automatic based on SDLC phase completion
4. Progress update available in project edit form and quick action
5. Progress update logged in audit log with updater, timestamp, previous value
6. Progress history chart showing progress over time
7. Progress milestone markers (25%, 50%, 75%, 100%)
8. Progress alerts when project falls behind schedule
9. Progress comparison: planned vs actual
10. Progress report exportable (PDF, Excel)
11. Dashboard shows average progress across all projects
12. Progress filter in project list (Not Started, In Progress, Completed)
13. Progress-based project health indicator (On Track, At Risk, Behind Schedule)
14. Progress update notification sent to stakeholders (optional)
15. Progress forecast based on current velocity

**Edge Cases:**
- EC-1: Progress set to 100% but status not Deployment/Maintenance → warning shown
- EC-2: Progress decreased (e.g., 80% to 60%) → confirmation required with reason
- EC-3: Progress updated manually while auto-calculation enabled → manual override logged
- EC-4: Progress 0% for project past start date → "Not Started" indicator shown
- EC-5: Progress 100% for project before end date → "Completed Early" badge
- EC-6: Progress chart with no updates → flat line shown
- EC-7: Progress forecast with insufficient data → message shown
- EC-8: Bulk progress update → confirmation required
- EC-9: Progress alert threshold reached → notification sent once
- EC-10: Progress comparison shows significant deviation → "At Risk" indicator
- EC-11: Archived project progress → frozen, no updates allowed
- EC-12: Progress update by team member vs PM → both allowed, logged differently

**Security Considerations:**
- Progress updates logged in audit log
- Only PM, Admin, Super Admin can update progress
- Progress reports filtered based on user permissions
- Progress alerts sent only to authorized users

**Responsive Design:**
- Mobile (375px): Progress bar prominent, simplified progress chart
- Tablet (768px): Progress bar with inline update, progress chart
- Desktop (1024px+): Full progress dashboard with charts and metrics

**Performance:**
- Progress update API: < 300ms
- Progress chart render: < 800ms
- Progress report generation: < 3 seconds
- Progress forecast calculation: < 500ms

---

## US-PROJECT-4.17 🟡 Project Tags and Categorization
**As a** Super Admin, Admin, or PM, **I want to** organize projects using tags and categories, **so that** I can group related projects and improve searchability.

**Acceptance Criteria:**
1. Tags field in project creation and edit forms
2. Tag input supports:
   - Free text entry with comma separation
   - Autocomplete from existing tags
   - Multi-select from tag library
3. Maximum 10 tags per project
4. Each tag max 30 characters
5. Tag validation: alphanumeric, spaces, hyphens allowed
6. Tags displayed as colored chips in project list and detail page
7. Click tag to filter projects by that tag
8. Tag management page (Admin only):
   - View all tags with usage count
   - Rename tags (updates all projects)
   - Merge tags (combines two tags into one)
   - Delete tags (removes from all projects with confirmation)
9. Tag color customization (optional)
10. Tag categories (optional: Technology, Industry, Type, etc.)
11. Popular tags widget showing most used tags
12. Tag cloud visualization
13. Tag-based project grouping in dashboard
14. Tag export/import for standardization
15. Tag search in project list

**Edge Cases:**
- EC-1: Add 11th tag → validation error: "Maximum 10 tags per project"
- EC-2: Tag with special characters → special chars removed automatically
- EC-3: Tag longer than 30 characters → truncated with warning
- EC-4: Duplicate tag in same project → prevented automatically
- EC-5: Tag with Arabic characters → accepted with RTL support
- EC-6: Rename tag used by 100+ projects → confirmation with usage count
- EC-7: Merge tags → all projects updated, old tag removed
- EC-8: Delete tag → removed from all projects, action logged
- EC-9: Tag autocomplete with partial match → shows matching tags
- EC-10: Tag color conflict → system assigns unique color
- EC-11: Export projects with tags → tags included as comma-separated list
- EC-12: Tag search with multiple tags → shows projects with ANY of the tags

**Security Considerations:**
- Tag management requires Admin or Super Admin role
- Tag changes logged in audit log
- Tag-based filtering respects user permissions
- Tag deletion requires confirmation

**Responsive Design:**
- Mobile (375px): Tags as chips, scrollable horizontally
- Tablet (768px): Tags with inline add/remove
- Desktop (1024px+): Tag input with autocomplete dropdown

**Performance:**
- Tag autocomplete: < 150ms
- Tag filter application: < 500ms
- Tag management page load: < 700ms
- Tag rename (100 projects): < 3 seconds

---

## US-PROJECT-4.18 🟡 Project Export and Reporting
**As a** Super Admin, Admin, or Founder, **I want to** export project data in various formats, **so that** I can analyze data externally and share reports with stakeholders.

**Acceptance Criteria:**
1. Export button available in project list page
2. Export format options: CSV, Excel (XLSX), PDF
3. Export scope options:
   - Current page only
   - All filtered results
   - All projects
   - Selected projects only
4. Export field selection (choose which columns to include)
5. Export includes:
   - Project Code, Name, Description
   - Client(s), Project Manager
   - Status, Priority, Active Status
   - Budget, Currency, Spent Amount, Remaining Budget
   - Start Date, End Date, Duration
   - Progress %, Tags
   - Created Date, Last Modified Date
6. CSV export: UTF-8 encoding, comma-separated
7. Excel export: formatted with headers, filters, freeze panes
8. PDF export: formatted report with logo, page numbers, date
9. Export progress indicator for large datasets
10. Export history (last 10 exports with download links)
11. Export action logged in audit log
12. Export email notification when ready (for large exports)
13. Export file naming: "Projects_Export_YYYY-MM-DD_HHMMSS.csv"
14. Export respects user permissions (only exports visible projects)
15. Scheduled exports (optional: daily, weekly, monthly)

**Edge Cases:**
- EC-1: Export 1000+ projects → background job, email notification when ready
- EC-2: Export with no projects selected → validation error
- EC-3: Export with Arabic content → UTF-8 encoding preserves Arabic text
- EC-4: Export with special characters in project names → properly escaped
- EC-5: Export PDF with very long descriptions → text wrapped, paginated
- EC-6: Export Excel with formulas → budget calculations included
- EC-7: Export filtered results → only filtered projects included
- EC-8: Export with deleted clients → shown as "[Deleted: Client Name]"
- EC-9: Network timeout during export → retry mechanism
- EC-10: Export file too large (>50MB) → split into multiple files
- EC-11: Export with custom fields → custom fields included
- EC-12: Scheduled export fails → error notification sent to admin

**Security Considerations:**
- Export action requires appropriate permissions
- Export logged in audit log with user, timestamp, scope
- Budget information included only if user has budget view permission
- Export files stored securely with expiration (7 days)
- Export download links include access token

**Responsive Design:**
- Mobile (375px): Export dialog full-screen, simplified options
- Tablet (768px): Export dialog modal with all options
- Desktop (1024px+): Export dialog with preview pane

**Performance:**
- Export CSV (100 projects): < 3 seconds
- Export Excel (100 projects): < 5 seconds
- Export PDF (100 projects): < 8 seconds
- Export 1000+ projects: background job, < 2 minutes

---

## US-PROJECT-4.19 🟠 Project Dashboard with Analytics
**As a** Super Admin, Admin, or Founder, **I want to** view a comprehensive project dashboard with analytics, **so that** I can get insights into project portfolio health and performance.

**Acceptance Criteria:**
1. Dashboard accessible from main navigation
2. **Key Metrics Section:**
   - Total Projects count
   - Active Projects count
   - Completed Projects count (100% progress)
   - Overdue Projects count
   - Total Budget (sum of all project budgets)
   - Total Spent (sum of all spent amounts)
   - Average Progress % across all projects
3. **Projects by Status Chart:**
   - Pie or donut chart showing project count per SDLC phase
   - Color-coded by phase
   - Click slice to filter projects by that status
4. **Projects by Priority Chart:**
   - Bar chart showing project count per priority level
   - Color-coded by priority
   - Click bar to filter projects by that priority
5. **Budget Overview Chart:**
   - Stacked bar chart showing Total Budget vs Spent vs Remaining
   - Budget utilization percentage
   - Projects over budget highlighted
6. **Timeline View:**
   - Horizontal timeline showing all active projects
   - Project bars color-coded by status
   - Overdue projects highlighted in red
   - Zoom and pan controls
7. **Recent Activity Feed:**
   - Last 20 project activities (created, updated, status changed, etc.)
   - Real-time updates
   - Click activity to view project
8. **Top Projects Widget:**
   - Highest budget projects
   - Most overdue projects
   - Recently completed projects
9. **PM Workload Chart:**
   - Bar chart showing project count per PM
   - Color-coded by workload (green <5, yellow 5-10, red >10)
10. **Client Projects Chart:**
    - Bar chart showing project count per client
    - Top 10 clients by project count
11. Dashboard filters: Date range, Status, Priority, PM, Client
12. Dashboard export to PDF
13. Dashboard refresh button (manual refresh)
14. Dashboard auto-refresh (every 5 minutes)
15. Dashboard customization (show/hide widgets)

**Edge Cases:**
- EC-1: No projects exist → empty state with "Create First Project" button
- EC-2: All projects completed → congratulations message shown
- EC-3: Chart with single data point → chart still renders correctly
- EC-4: Very large numbers (1000+ projects) → numbers formatted with commas
- EC-5: Budget in multiple currencies → converted to default currency for totals
- EC-6: Dashboard with slow data load → loading skeletons shown
- EC-7: Chart rendering fails → fallback to table view
- EC-8: Real-time update while viewing dashboard → smooth transition
- EC-9: Dashboard filter returns no results → empty state shown
- EC-10: Export dashboard with charts → charts included as images in PDF
- EC-11: Dashboard on mobile → simplified view with key metrics only
- EC-12: Dashboard customization saved per user → preferences persisted

**Security Considerations:**
- Dashboard data filtered based on user permissions
- Budget information visible only to authorized roles
- Dashboard export logged in audit log
- Real-time updates use secure WebSocket connection

**Responsive Design:**
- Mobile (375px): Stacked widgets, simplified charts, swipe navigation
- Tablet (768px): 2-column grid layout, interactive charts
- Desktop (1024px+): 3-column grid layout, full-featured charts

**Performance:**
- Dashboard initial load: < 2 seconds
- Chart render: < 1 second per chart
- Real-time update: < 500ms
- Dashboard export: < 5 seconds
- Auto-refresh: < 1 second (incremental update)

---

## US-PROJECT-4.20 🟠 Project Notifications and Alerts
**As a** PM, Admin, or team member, **I want to** receive notifications about important project events, **so that** I stay informed and can take timely action.

**Acceptance Criteria:**
1. **Notification Types:**
   - Project created (PM and team members)
   - Project updated (PM and team members)
   - Status changed (PM and team members)
   - Priority changed to Critical (PM, Admin, management)
   - PM reassigned (old PM, new PM)
   - Budget threshold reached (PM, Admin, Founders)
   - Project overdue (PM, Admin)
   - Project completed (PM, Admin, client contact)
   - Team member added/removed (affected team member)
   - Milestone reached (PM and team members)
2. **Notification Channels:**
   - In-app notifications (bell icon with badge count)
   - Email notifications (configurable per user)
   - Push notifications (if mobile app)
3. **Notification Preferences:**
   - User can enable/disable each notification type
   - User can choose notification channels per type
   - User can set quiet hours (no notifications during specified times)
   - User can set notification frequency (immediate, daily digest, weekly digest)
4. In-app notification center:
   - List of all notifications (newest first)
   - Unread count badge
   - Mark as read/unread
   - Mark all as read
   - Delete notification
   - Filter by type, project, date
   - Pagination (20 per page)
5. Email notifications:
   - Clear subject line with project name
   - Formatted HTML email with project details
   - Direct link to project
   - Unsubscribe link
6. Notification templates customizable (Admin only)
7. Notification history accessible (last 90 days)
8. Notification delivery status tracking
9. Failed notification retry mechanism
10. Notification grouping (multiple updates to same project)

**Edge Cases:**
- EC-1: User disabled all notifications → no notifications sent
- EC-2: Email notification fails → retry 3 times, then log error
- EC-3: User in quiet hours → notifications queued, sent after quiet hours
- EC-4: Multiple rapid updates to same project → notifications grouped into single digest
- EC-5: Notification for deleted project → notification includes "[Deleted]" label
- EC-6: User unsubscribes from emails → in-app notifications still sent
- EC-7: Notification with very long project name → truncated in subject line
- EC-8: Notification to inactive user → not sent, logged
- EC-9: Notification delivery during system maintenance → queued, sent after maintenance
- EC-10: User has 100+ unread notifications → "Mark all as read" option prominent
- EC-11: Notification with Arabic content → RTL support in email
- EC-12: Notification preferences not set → default preferences applied

**Security Considerations:**
- Notifications sent only to authorized users
- Notification content filtered based on user permissions
- Email notifications use secure SMTP
- Notification links include access tokens with expiration
- Notification delivery logged for audit

**Responsive Design:**
- Mobile (375px): Notification center full-screen, swipe to delete
- Tablet (768px): Notification center as sidebar panel
- Desktop (1024px+): Notification center as dropdown from bell icon

**Performance:**
- Notification delivery: < 5 seconds
- In-app notification load: < 500ms
- Email notification send: < 10 seconds
- Notification center load: < 700ms
- Mark as read: < 200ms

---

## US-PROJECT-4.21 🟡 Project Milestones and Deliverables
**As a** Super Admin, Admin, or PM, **I want to** define and track project milestones and deliverables, **so that** I can monitor key project achievements and ensure timely delivery.

**Acceptance Criteria:**
1. Milestones tab in project detail page
2. Add milestone button opens milestone creation form
3. Milestone fields:
   - Milestone Name (required, max 150 characters)
   - Description (optional, max 1000 characters)
   - Due Date (required)
   - Status (Not Started, In Progress, Completed, Delayed)
   - Deliverables (optional, list of deliverable items)
   - Assigned To (optional, team member)
   - Priority (Low, Medium, High, Critical)
4. Milestone list displays: Name, Due Date, Status, Assigned To, Actions
5. Milestone status badge color-coded
6. Overdue milestones highlighted in red
7. Milestone completion triggers notification to PM and team
8. Milestone edit and delete actions available
9. Milestone reordering (drag-and-drop)
10. Milestone timeline visualization in Gantt chart
11. Milestone progress tracking (% complete)
12. Milestone dependencies (optional: Milestone B depends on Milestone A)
13. Milestone templates for common project types
14. Milestone export to CSV/Excel
15. Milestone alerts (due date approaching, overdue)

**Edge Cases:**
- EC-1: Milestone due date before project start date → validation warning
- EC-2: Milestone due date after project end date → validation warning
- EC-3: Milestone marked complete before due date → "Completed Early" badge
- EC-4: Milestone overdue by 7+ days → escalation alert sent
- EC-5: Delete milestone with dependencies → warning shown, dependencies removed
- EC-6: Milestone assigned to inactive team member → warning shown
- EC-7: Project completed but milestone not completed → warning shown
- EC-8: Milestone with very long name → truncated with ellipsis, full name in tooltip
- EC-9: Milestone reordering → order preserved in timeline
- EC-10: Milestone template applied → milestones created with adjusted dates
- EC-11: Milestone export with deliverables → deliverables included as sub-items
- EC-12: Milestone notification fails → retry mechanism, logged

**Security Considerations:**
- Only PM, Admin, Super Admin can create/edit/delete milestones
- Milestone changes logged in audit log
- Milestone notifications sent only to authorized users
- Milestone data filtered based on user permissions

**Responsive Design:**
- Mobile (375px): Milestone list as cards, full-screen milestone form
- Tablet (768px): Milestone list as table, modal milestone form
- Desktop (1024px+): Milestone list with inline edit, side panel form

**Performance:**
- Milestone creation: < 500ms
- Milestone list load: < 600ms
- Milestone update: < 400ms
- Milestone timeline render: < 800ms

---

## US-PROJECT-4.22 🟡 Project Collaboration and Comments
**As a** team member, PM, Admin, or Founder, **I want to** collaborate on projects through comments and discussions, **so that** team communication is centralized and project-related conversations are preserved.

**Acceptance Criteria:**
1. Comments tab in project detail page
2. Comment input field with rich text editor (bold, italic, lists, links, mentions)
3. Comment submission creates new comment with:
   - Author name and avatar
   - Timestamp (relative: "2 hours ago")
   - Comment content (formatted)
   - Edit and Delete actions (for author only)
4. Comments displayed in chronological order (newest first or oldest first, user preference)
5. Comment mentions: @username triggers notification to mentioned user
6. Comment attachments: upload files (images, documents) with comment
7. Comment reactions: like, helpful, resolved (emoji reactions)
8. Comment threading: reply to specific comment creates nested thread
9. Comment editing: edit within 15 minutes, shows "Edited" badge
10. Comment deletion: soft delete, shows "[Comment deleted]" placeholder
11. Comment search and filter by author, date, keyword
12. Comment count badge on Comments tab
13. Comment notifications: new comment, mention, reply
14. Comment export to PDF (full conversation history)
15. Comment permissions: who can view, create, edit, delete comments

**Edge Cases:**
- EC-1: Comment with @mention of inactive user → mention shown but no notification sent
- EC-2: Comment with very long text (5000+ characters) → scrollable with "Read More"
- EC-3: Comment with attachment (10MB file) → upload progress shown
- EC-4: Comment edit after 15 minutes → edit disabled, delete still available
- EC-5: Comment deleted by author → shows "[Comment deleted by author]"
- EC-6: Comment deleted by admin → shows "[Comment deleted by admin]"
- EC-7: Comment thread with 50+ replies → paginated, "Load more" button
- EC-8: Comment with broken attachment link → shows "Attachment unavailable"
- EC-9: Comment with Arabic text → RTL support maintained
- EC-10: Comment search with no results → empty state shown
- EC-11: Comment export with attachments → attachments listed with download links
- EC-12: Comment notification fails → retry mechanism, logged

**Security Considerations:**
- Comments visible based on project access permissions
- Comment editing restricted to author (or admin)
- Comment deletion logged in audit log
- Attachment uploads scanned for malware
- Comment content sanitized to prevent XSS

**Responsive Design:**
- Mobile (375px): Full-width comments, stacked layout, simplified editor
- Tablet (768px): Comments with inline replies, rich text editor
- Desktop (1024px+): Full-featured comments with threading and attachments

**Performance:**
- Comment submission: < 500ms
- Comment load (50 comments): < 800ms
- Comment edit: < 400ms
- Comment search: < 600ms
- Attachment upload (5MB): < 10 seconds

---

---

## Summary

This document contains **22 comprehensive user stories** covering all aspects of project management in the Tarqumi CRM system. The stories are organized to cover the complete project lifecycle from creation to completion, archiving, and reporting.

### User Stories by Priority

| Priority | Count | User Stories |
|----------|-------|--------------|
| 🔴 Critical | 6 | US-PROJECT-4.1, 4.2, 4.3, 4.4, 4.5, 4.6 |
| 🟠 High | 10 | US-PROJECT-4.7, 4.8, 4.9, 4.10, 4.11, 4.12, 4.15, 4.16, 4.19, 4.20 |
| 🟡 Medium | 6 | US-PROJECT-4.13, 4.14, 4.17, 4.18, 4.21, 4.22 |
| 🟢 Nice-to-have | 0 | - |
| **Total** | **22** | |

### Functional Coverage

#### Core Project Management (Critical Priority)
1. **US-PROJECT-4.1**: Create Internal Project with Complete Information
2. **US-PROJECT-4.2**: View Project List with Advanced Filtering and Sorting
3. **US-PROJECT-4.3**: View Project Details with Comprehensive Information
4. **US-PROJECT-4.4**: Edit Project Information with Validation
5. **US-PROJECT-4.5**: Multiple Clients per Project Management
6. **US-PROJECT-4.6**: Project Status Workflow with 6 SDLC Phases

#### Project Status and Lifecycle (High Priority)
7. **US-PROJECT-4.7**: Active/Inactive Project Management
8. **US-PROJECT-4.8**: Project Timeline Visualization with Gantt Chart
9. **US-PROJECT-4.9**: Budget Tracking and Alerts
10. **US-PROJECT-4.10**: Project Manager Reassignment with Notification
11. **US-PROJECT-4.11**: Project Search and Advanced Filtering
12. **US-PROJECT-4.12**: Project Archiving and Restoration
13. **US-PROJECT-4.15**: Project Priority Management with Visual Indicators
14. **US-PROJECT-4.16**: Project Progress Tracking and Reporting
15. **US-PROJECT-4.19**: Project Dashboard with Analytics
16. **US-PROJECT-4.20**: Project Notifications and Alerts

#### Advanced Features (Medium Priority)
17. **US-PROJECT-4.13**: Project Duplication for Quick Setup
18. **US-PROJECT-4.14**: Project Templates for Standardization
19. **US-PROJECT-4.17**: Project Tags and Categorization
20. **US-PROJECT-4.18**: Project Export and Reporting
21. **US-PROJECT-4.21**: Project Milestones and Deliverables
22. **US-PROJECT-4.22**: Project Collaboration and Comments

### Key Project Management Principles

1. **Comprehensive Data Capture**: All relevant project information captured including clients, budget, timeline, team, and progress
2. **Flexible Workflow**: 6-phase SDLC workflow with flexible transitions (forward and backward)
3. **Multi-Client Support**: Projects can be associated with multiple clients (up to 10)
4. **Budget Management**: Complete budget tracking with alerts, forecasting, and variance analysis
5. **Timeline Visualization**: Gantt chart with phases, milestones, dependencies, and critical path
6. **Progress Tracking**: Multiple progress calculation methods with visual indicators and forecasting
7. **Team Collaboration**: Comments, mentions, attachments, and real-time notifications
8. **Audit Trail**: Complete history of all project changes with before/after values
9. **Access Control**: Role-based permissions for viewing, editing, and managing projects
10. **Reporting and Analytics**: Comprehensive dashboard, exports, and custom reports

### Integration Points with Other Modules

1. **Client Management Module**:
   - Projects linked to one or more clients
   - Client status affects project client selection
   - Client deletion preserves project references

2. **Team Management Module**:
   - Project Manager assignment from active team members
   - Team member assignments to projects
   - PM workload tracking across projects
   - Team member notifications for project events

3. **Authentication & Security Module**:
   - Role-based access control (RBAC)
   - Audit logging for all project actions
   - Secure data encryption for sensitive information

4. **Landing Page CMS Module** (Future):
   - Showcase projects on public website
   - Project portfolio display
   - Client testimonials linked to projects

5. **Blog System Module** (Future):
   - Project case studies as blog posts
   - Project success stories
   - Behind-the-scenes project content

### SDLC Workflow

The system implements a 6-phase Software Development Lifecycle (SDLC) workflow:

```
Planning → Design → Development → Testing → Deployment → Maintenance
```

**Phase Descriptions:**
- **Planning**: Initial project setup, requirements gathering, resource allocation
- **Design**: Architecture design, UI/UX design, technical specifications
- **Development**: Coding, implementation, feature development
- **Testing**: QA testing, bug fixing, user acceptance testing
- **Deployment**: Production deployment, go-live, launch
- **Maintenance**: Post-launch support, bug fixes, enhancements

**Workflow Characteristics:**
- Flexible transitions (can move forward or backward)
- Status change requires confirmation
- Optional reason field for status changes
- Automated workflow actions (e.g., deployment checklist)
- Status timeline visualization showing time in each phase
- Phase-specific color coding for visual identification

### Budget Calculation Formulas

1. **Budget Utilization %** = (Spent Amount / Total Budget) × 100
2. **Remaining Budget** = Total Budget - Spent Amount
3. **Budget Variance** = Actual Spent - Planned Spent
4. **Burn Rate** = Spent Amount / Days Elapsed
5. **Projected Completion Date** = Current Date + (Remaining Budget / Burn Rate)
6. **Budget Health Indicator**:
   - Green: Utilization < 80%
   - Yellow: Utilization 80-95%
   - Red: Utilization > 95%

### Timeline Calculation Logic

1. **Project Duration** = End Date - Start Date (in days/weeks/months)
2. **Days Remaining** = End Date - Current Date
3. **Days Overdue** = Current Date - End Date (if past end date)
4. **Phase Duration** = Phase End Date - Phase Start Date
5. **Progress Velocity** = Progress % / Days Elapsed
6. **Estimated Completion Date** = Current Date + ((100 - Progress %) / Progress Velocity)
7. **Timeline Health**:
   - On Track: Progress % ≥ Expected Progress %
   - At Risk: Progress % < Expected Progress % (within 10%)
   - Behind Schedule: Progress % < Expected Progress % (more than 10%)

### Performance Benchmarks

| Operation | Target Performance | Notes |
|-----------|-------------------|-------|
| Project Creation | < 1.5 seconds | Including validation and audit logging |
| Project List Load | < 1.5 seconds | 25 projects per page with filters |
| Project Detail Load | < 1.5 seconds | Including all tabs data |
| Project Update | < 1.5 seconds | With optimistic UI update |
| Search/Filter | < 700ms | Real-time with debouncing |
| Status Change | < 400ms | Including notifications |
| Budget Calculation | < 200ms | Real-time updates |
| Timeline Render | < 1.5 seconds | Gantt chart with all phases |
| Dashboard Load | < 2 seconds | All widgets and charts |
| Export (100 projects) | < 5 seconds | CSV format |
| Export (1000+ projects) | < 2 minutes | Background job |
| Notification Delivery | < 5 seconds | In-app and email |

### Responsive Design Breakpoints

| Breakpoint | Width | Layout Adjustments |
|------------|-------|-------------------|
| Mobile | 375px | Single column, stacked layout, simplified views |
| Tablet | 768px | Two-column layout, horizontal scrolling tables |
| Desktop | 1024px | Three-column layout, full-featured interface |
| Large Desktop | 1440px+ | Expanded layout, additional sidebar panels |

### Security Considerations

1. **Authentication**: All project operations require authenticated user
2. **Authorization**: Role-based access control (RBAC) enforced on all endpoints
3. **Input Validation**: All user input validated and sanitized
4. **XSS Prevention**: Rich text content sanitized, output encoding applied
5. **SQL Injection Prevention**: Parameterized queries, ORM usage
6. **CSRF Protection**: CSRF tokens on all state-changing operations
7. **Audit Logging**: All project actions logged with user, timestamp, IP address
8. **Data Encryption**: Sensitive data (budget, financial info) encrypted at rest
9. **Access Tokens**: Shareable links use time-limited access tokens
10. **Rate Limiting**: API rate limits to prevent abuse

### Accessibility Considerations

1. **Keyboard Navigation**: All interactive elements accessible via keyboard
2. **Screen Reader Support**: Proper ARIA labels and semantic HTML
3. **Color Contrast**: WCAG AA compliant color contrast ratios
4. **Focus Indicators**: Clear focus indicators for keyboard navigation
5. **Alt Text**: All images and icons have descriptive alt text
6. **Form Labels**: All form fields have associated labels
7. **Error Messages**: Clear, descriptive error messages
8. **Skip Links**: Skip to main content links for screen readers

### Internationalization (i18n)

1. **Bilingual Support**: Arabic and English language support
2. **RTL/LTR**: Automatic layout direction based on language
3. **Date Formats**: Localized date formats (DD/MM/YYYY for Arabic, MM/DD/YYYY for English)
4. **Number Formats**: Localized number formats with proper separators
5. **Currency Display**: Currency symbols and formatting based on locale
6. **Text Direction**: RTL support for Arabic content in all fields
7. **Language Switching**: Seamless language switching without data loss

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Total User Stories**: 22  
**Total Acceptance Criteria**: 330+  
**Total Edge Cases**: 264+

