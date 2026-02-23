# Module 6: Project Management CRUD Operations

## Overview
Complete CRUD operations for internal project management including creation with multiple clients, listing with advanced filtering, editing, deletion, status workflow management, and budget tracking.

---

## Task 6.1: Create Project API Endpoint with Multiple Clients

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 2.3  
**Assigned To:** Backend Developer

**Objective:**
Implement API endpoint for creating projects with support for multiple clients, budget tracking, and comprehensive validation.

**Detailed Steps:**

1. **Create ProjectController:**
   ```bash
   php artisan make:controller Api/V1/ProjectController --resource
   ```

2. **Create StoreProjectRequest with validation:**
   - Name: required, 3-150 characters, unique per client
   - Description: optional, rich text, max 10,000 characters
   - Clients: required, array, min 1, max 10 clients
   - Manager: required, must be active team member
   - Budget: optional, numeric, min 0
   - Currency: required if budget set
   - Priority: required, 1-10
   - Start Date: required, cannot be far in past
   - End Date: optional, must be after start date
   - Status: defaults to 'planning'

3. **Implement ProjectService for business logic:**
   - Auto-generate project code (PROJ-YYYY-####)
   - Attach multiple clients
   - Set primary client (first in list)
   - Default to "Tarqumi" if no client specified

4. **Create ProjectResource for API responses**

5. **Add route with role middleware**

6. **Create comprehensive tests**

**Acceptance Criteria:**
- [ ] Project creation works with all fields
- [ ] Multiple clients can be attached (1-10)
- [ ] Project code auto-generated
- [ ] Primary client designation works
- [ ] Default "Tarqumi" client used if none specified
- [ ] Budget with currency validation
- [ ] Priority 1-10 enforced
- [ ] Date validation works
- [ ] Tests pass

**Files Created:**
- `app/Http/Controllers/Api/V1/ProjectController.php`
- `app/Http/Requests/StoreProjectRequest.php`
- `app/Services/ProjectService.php`
- `app/Http/Resources/ProjectResource.php`
- `tests/Feature/ProjectManagementTest.php`

---

## Task 6.2: List Projects with Advanced Filtering

**Priority:** 🔴 Critical  
**Estimated Time:** 5 hours  
**Dependencies:** Task 6.1  
**Assigned To:** Backend Developer

**Objective:**
Implement comprehensive project listing with pagination, search, multiple filters, and sorting.

**Detailed Steps:**

1. **Create IndexProjectRequest**

2. **Implement filtering:**
   - Status: All, Planning, Analysis, Design, Implementation, Testing, Deployment
   - Priority: All, Low (1-3), Medium (4-6), High (7-8), Critical (9-10)
   - Project Manager: All, [list of PMs]
   - Client: All, [list of clients]
   - Budget Range: All, <10K, 10K-50K, 50K-100K, 100K-500K, 500K+, Custom
   - Date Range: All, This Week, This Month, This Quarter, This Year, Custom
   - Active Status: All, Active, Inactive
   - Progress: All, Not Started (0%), In Progress (1-99%), Completed (100%)

3. **Implement search:**
   - Search by name, code, client name, PM name
   - Real-time with debounce

4. **Implement sorting:**
   - Sort by: Name, Code, Client, PM, Status, Priority, Budget, Start Date, End Date, Progress
   - Sort order: ASC, DESC

5. **Implement pagination:**
   - 25 projects per page (configurable)

6. **Add export functionality**

7. **Create comprehensive tests**

**Acceptance Criteria:**
- [ ] All filters work independently and combined
- [ ] Search works across multiple fields
- [ ] Sorting works for all columns
- [ ] Pagination works correctly
- [ ] Export generates valid CSV
- [ ] Performance optimized (< 700ms)
- [ ] Tests pass

---

## Task 6.3: Project Detail View with Comprehensive Information

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 6.2  
**Assigned To:** Backend Developer

**Objective:**
Implement detailed project view with all information, timeline, budget tracking, and team assignments.

**Detailed Steps:**

1. **Create show endpoint with eager loading:**
   - All project fields
   - All associated clients
   - Project manager details
   - Budget information
   - Timeline data
   - Activity log

2. **Calculate computed fields:**
   - Days remaining/overdue
   - Progress percentage
   - Budget utilization
   - Duration

3. **Include activity log:**
   - Status changes
   - Budget updates
   - Team assignments
   - Client changes

4. **Optimize query performance**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Detail view returns all data
- [ ] Computed fields calculated correctly
- [ ] Activity log included
- [ ] Performance optimized (< 500ms)
- [ ] Tests pass

---

## Task 6.4: Edit Project Information

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 6.3  
**Assigned To:** Backend Developer

**Objective:**
Implement project editing with validation, change tracking, and client management.

**Detailed Steps:**

1. **Create UpdateProjectRequest**

2. **Implement update method:**
   - All fields editable except code
   - Client list can be modified
   - Primary client can be changed
   - PM can be reassigned

3. **Add validation:**
   - Name uniqueness (excluding current project)
   - Date validation
   - Budget validation
   - Client count (1-10)

4. **Track changes:**
   - Log all field changes
   - Track who made changes
   - Store before/after values

5. **Handle PM reassignment:**
   - Notify old and new PM
   - Update workload

6. **Create tests**

**Acceptance Criteria:**
- [ ] Project update works correctly
- [ ] All validations enforced
- [ ] Client list can be modified
- [ ] PM reassignment works
- [ ] Changes tracked in activity log
- [ ] Tests pass

---

## Task 6.5: Project Status Workflow Management

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 6.4  
**Assigned To:** Backend Developer

**Objective:**
Implement 6-phase SDLC status workflow with validation and notifications.

**Detailed Steps:**

1. **Create status change endpoint**

2. **Implement 6 SDLC phases:**
   - Planning (blue, 10% progress)
   - Analysis (purple, 25% progress)
   - Design (indigo, 40% progress)
   - Implementation (orange, 60% progress)
   - Testing (yellow, 80% progress)
   - Deployment (green, 100% progress)

3. **Add status change validation:**
   - Can move forward or backward
   - Confirmation required for backward movement
   - Optional reason field

4. **Send notifications:**
   - Notify PM and team members
   - Configurable per user

5. **Track status history**

6. **Create tests**

**Acceptance Criteria:**
- [ ] All 6 phases implemented
- [ ] Status can move forward/backward
- [ ] Confirmation required for backward movement
- [ ] Notifications sent
- [ ] Status history tracked
- [ ] Tests pass

---

## Task 6.6: Active/Inactive Project Management

**Priority:** 🟠 High  
**Estimated Time:** 2 hours  
**Dependencies:** Task 6.5  
**Assigned To:** Backend Developer

**Objective:**
Implement active/inactive status management independent of SDLC phase.

**Detailed Steps:**

1. **Add active status toggle endpoint**

2. **Implement validation:**
   - Warn if project has ongoing tasks
   - Inactive projects hidden from default views

3. **Add bulk status change**

4. **Track status changes**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Active status toggle works
- [ ] Warnings shown appropriately
- [ ] Bulk status change works
- [ ] Inactive projects filtered correctly
- [ ] Tests pass

---

## Task 6.7: Budget Tracking and Alerts

**Priority:** 🟠 High  
**Estimated Time:** 4 hours  
**Dependencies:** Task 6.1  
**Assigned To:** Backend Developer

**Objective:**
Implement budget tracking with utilization calculation and automated alerts.

**Detailed Steps:**

1. **Create budget tracking system:**
   - Total budget
   - Spent amount (from expenses/tasks)
   - Remaining budget
   - Utilization percentage

2. **Implement budget alerts:**
   - Thresholds: 50%, 75%, 90%, 100%
   - Notify PM and Admin
   - Alert banner on project detail

3. **Add budget history:**
   - Track all budget changes
   - Show spending over time

4. **Create budget reports**

5. **Add tests**

**Acceptance Criteria:**
- [ ] Budget tracking works correctly
- [ ] Utilization calculated accurately
- [ ] Alerts triggered at thresholds
- [ ] Budget history tracked
- [ ] Reports generated
- [ ] Tests pass

---

## Task 6.8: Project Manager Reassignment

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 6.4  
**Assigned To:** Backend Developer

**Objective:**
Implement PM reassignment with notifications and workload updates.

**Detailed Steps:**

1. **Create PM reassignment endpoint**

2. **Implement validation:**
   - New PM must be active
   - New PM must have appropriate role

3. **Send notifications:**
   - Notify old PM (removal)
   - Notify new PM (assignment)
   - Include project details

4. **Update workload:**
   - Decrease old PM's project count
   - Increase new PM's project count

5. **Track reassignment history**

6. **Create tests**

**Acceptance Criteria:**
- [ ] PM reassignment works
- [ ] Notifications sent to both PMs
- [ ] Workload updated correctly
- [ ] History tracked
- [ ] Tests pass

---

## Task 6.9: Project Timeline Visualization Data

**Priority:** 🟡 Medium  
**Estimated Time:** 3 hours  
**Dependencies:** Task 6.3  
**Assigned To:** Backend Developer

**Objective:**
Provide API endpoints for timeline/Gantt chart visualization.

**Detailed Steps:**

1. **Create timeline data endpoint**

2. **Calculate timeline data:**
   - Project start/end dates
   - Phase durations
   - Milestones
   - Current progress
   - Critical path

3. **Format data for Gantt chart**

4. **Add export to image/PDF**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Timeline data endpoint works
- [ ] Data formatted for Gantt chart
- [ ] Export functionality works
- [ ] Tests pass

---

## Task 6.10: Project Duplication

**Priority:** 🟡 Medium  
**Estimated Time:** 2 hours  
**Dependencies:** Task 6.1  
**Assigned To:** Backend Developer

**Objective:**
Implement project duplication for creating similar projects quickly.

**Detailed Steps:**

1. **Create duplicate endpoint**

2. **Copy project data:**
   - All fields except code
   - Clients
   - Budget
   - Priority
   - Adjust dates to current

3. **Generate new project code**

4. **Add " (Copy)" suffix to name**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Duplication works correctly
- [ ] New project code generated
- [ ] All data copied except code
- [ ] Name has " (Copy)" suffix
- [ ] Tests pass

---

## Task 6.11: Project Search and Autocomplete

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 6.2  
**Assigned To:** Backend Developer

**Objective:**
Implement fast project search with autocomplete.

**Detailed Steps:**

1. **Create search endpoint**

2. **Implement fuzzy search:**
   - Search by name, code, client
   - Partial matches
   - Arabic and English

3. **Optimize performance:**
   - Database indexes
   - Query optimization
   - Caching

4. **Add recent searches**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Autocomplete works (< 200ms)
- [ ] Fuzzy search returns relevant results
- [ ] Supports both languages
- [ ] Recent searches saved
- [ ] Tests pass

---

## Task 6.12: Project Analytics Dashboard

**Priority:** 🟢 Nice-to-have  
**Estimated Time:** 4 hours  
**Dependencies:** Task 6.2  
**Assigned To:** Backend Developer

**Objective:**
Create analytics dashboard showing project statistics and trends.

**Detailed Steps:**

1. **Calculate statistics:**
   - Total projects
   - Projects by status
   - Projects by priority
   - Overdue projects
   - Budget utilization
   - Average project duration

2. **Create charts:**
   - Projects over time
   - Status distribution
   - Budget vs actual
   - PM workload

3. **Add export**

4. **Create tests**

**Acceptance Criteria:**
- [ ] Statistics calculated correctly
- [ ] Charts display properly
- [ ] Analytics exportable
- [ ] Tests pass

---
