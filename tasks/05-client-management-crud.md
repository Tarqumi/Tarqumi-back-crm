# Module 5: Client Management CRUD Operations

## Overview
Complete CRUD operations for client management including creation, listing with advanced filtering, editing, deletion with project preservation, and default client protection.

---

## Task 5.1: Create Client API Endpoint with Validation

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 2.2  
**Assigned To:** Backend Developer

**Objective:**
Implement API endpoint for creating new clients with comprehensive validation, duplicate detection, and audit logging.

**Detailed Steps:**

1. **Create ClientController:**
   ```bash
   php artisan make:controller Api/V1/ClientController --resource
   ```

2. **Create StoreClientRequest:**
   ```bash
   php artisan make:request StoreClientRequest
   ```

3. **Implement StoreClientRequest with validation:**
   - Name: required, 2-100 characters
   - Email: required, unique, valid format
   - Company: optional, max 150 characters
   - Phone: optional, E.164 format
   - WhatsApp: optional, E.164 format
   - Address: optional, max 500 characters
   - Website: optional, valid URL
   - Industry: optional, max 100 characters
   - Notes: optional, max 5000 characters

4. **Create ClientService for business logic**

5. **Implement store method in ClientController**

6. **Create ClientResource for API responses**

7. **Add route with role middleware (admin/super_admin only)**

8. **Create unit tests for client creation**

9. **Test duplicate email detection**

10. **Test default client protection**

**Acceptance Criteria:**
- [ ] Client creation endpoint works
- [ ] All validation rules enforced
- [ ] Email uniqueness checked
- [ ] Duplicate detection warns user
- [ ] Default client "Tarqumi" cannot be duplicated
- [ ] Audit log tracks creation
- [ ] Tests pass successfully

**Files Created:**
- `app/Http/Controllers/Api/V1/ClientController.php`
- `app/Http/Requests/StoreClientRequest.php`
- `app/Services/ClientService.php`
- `app/Http/Resources/ClientResource.php`
- `tests/Feature/ClientManagementTest.php`

---

## Task 5.2: List Clients with Advanced Filtering

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 5.1  
**Assigned To:** Backend Developer

**Objective:**
Implement comprehensive client listing with pagination, search, filters, and sorting.

**Detailed Steps:**

1. **Create IndexClientRequest for query validation**

2. **Implement filtering:**
   - Status: All, Active, Inactive
   - Industry: All, [list of industries]
   - Projects: All, Has Projects, No Projects
   - Last Contact: date ranges

3. **Implement search:**
   - Search by name, company, email, phone
   - Real-time with debounce
   - Highlight matching terms

4. **Implement sorting:**
   - Sort by: Name, Company, Email, Projects Count, Last Contact
   - Sort order: ASC, DESC

5. **Implement pagination:**
   - 20 clients per page (configurable: 10, 20, 50, 100)
   - Page metadata included

6. **Add export functionality (CSV)**

7. **Create tests for all filter combinations**

**Acceptance Criteria:**
- [ ] Pagination works correctly
- [ ] Search filters by multiple fields
- [ ] All filters work independently and combined
- [ ] Sorting works for all columns
- [ ] Export generates valid CSV
- [ ] Performance optimized (< 500ms)
- [ ] Tests pass

---

## Task 5.3: Edit Client Information

**Priority:** 🔴 Critical  
**Estimated Time:** 3 hours  
**Dependencies:** Task 5.2  
**Assigned To:** Backend Developer

**Objective:**
Implement client editing with validation, change tracking, and default client protection.

**Detailed Steps:**

1. **Create UpdateClientRequest**

2. **Implement update method in ClientService**

3. **Add change history tracking**

4. **Protect default "Tarqumi" client:**
   - Name and email cannot be changed
   - Other fields editable
   - Status cannot be set to inactive

5. **Implement optimistic UI updates**

6. **Add concurrent edit detection**

7. **Create tests for all edit scenarios**

**Acceptance Criteria:**
- [ ] Client update works correctly
- [ ] Email uniqueness validated (excluding current client)
- [ ] Default client protected from critical changes
- [ ] Change history tracked
- [ ] Concurrent edits handled
- [ ] Tests pass

---

## Task 5.4: Delete Client with Project Preservation

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 5.3  
**Assigned To:** Backend Developer

**Objective:**
Implement client deletion with soft delete, project preservation, and default client protection.

**Detailed Steps:**

1. **Implement soft delete for clients**

2. **Protect default "Tarqumi" client:**
   - Delete button disabled
   - API returns 403 if attempted
   - Database constraint prevents deletion

3. **Preserve project relationships:**
   - Projects keep client reference
   - Deleted client shown as "[Deleted: Client Name]"

4. **Implement restore functionality**

5. **Add permanent delete after 90 days (automated job)**

6. **Create tests for deletion scenarios**

**Acceptance Criteria:**
- [ ] Soft delete works correctly
- [ ] Default client cannot be deleted
- [ ] Projects preserved after client deletion
- [ ] Restore functionality works
- [ ] Permanent delete after 90 days
- [ ] Tests pass

---

## Task 5.5: Client Status Management

**Priority:** 🟠 High  
**Estimated Time:** 2 hours  
**Dependencies:** Task 5.4  
**Assigned To:** Backend Developer

**Objective:**
Implement active/inactive status management with validation and project impact warnings.

**Detailed Steps:**

1. **Add status toggle endpoint**

2. **Implement status change validation:**
   - Default client must remain active
   - Warn if client has active projects

3. **Add bulk status change**

4. **Track status change history**

5. **Create tests for status changes**

**Acceptance Criteria:**
- [ ] Status toggle works
- [ ] Default client cannot be set inactive
- [ ] Warnings shown for clients with projects
- [ ] Bulk status change works
- [ ] Tests pass

---

## Task 5.6: Client Search and Autocomplete

**Priority:** 🟠 High  
**Estimated Time:** 3 hours  
**Dependencies:** Task 5.2  
**Assigned To:** Backend Developer

**Objective:**
Implement fast client search with autocomplete for project creation forms.

**Detailed Steps:**

1. **Create search endpoint with autocomplete**

2. **Implement fuzzy search:**
   - Search by name, company, email
   - Partial matches supported
   - Arabic and English text

3. **Optimize search performance:**
   - Database indexes
   - Query optimization
   - Response caching

4. **Add recent searches**

5. **Create tests for search functionality**

**Acceptance Criteria:**
- [ ] Autocomplete works (< 200ms)
- [ ] Fuzzy search returns relevant results
- [ ] Supports Arabic and English
- [ ] Recent searches saved
- [ ] Tests pass

---

## Task 5.7: Client Import from CSV

**Priority:** 🟡 Medium  
**Estimated Time:** 4 hours  
**Dependencies:** Task 5.1  
**Assigned To:** Backend Developer

**Objective:**
Implement bulk client import from CSV with validation and duplicate handling.

**Detailed Steps:**

1. **Create import endpoint**

2. **Implement CSV parsing and validation:**
   - Max 500 clients per import
   - Validate all fields
   - Detect duplicates

3. **Add duplicate handling options:**
   - Skip
   - Update
   - Create with suffix

4. **Generate import summary report**

5. **Create downloadable template**

6. **Add import history tracking**

7. **Create tests for import scenarios**

**Acceptance Criteria:**
- [ ] CSV import works correctly
- [ ] Validation errors highlighted per row
- [ ] Duplicate handling works
- [ ] Summary report generated
- [ ] Template downloadable
- [ ] Tests pass

---

## Task 5.8: Client Export Functionality

**Priority:** 🟡 Medium  
**Estimated Time:** 2 hours  
**Dependencies:** Task 5.2  
**Assigned To:** Backend Developer

**Objective:**
Implement client export to CSV/Excel with filtering support.

**Detailed Steps:**

1. **Create export endpoint**

2. **Support filtered exports:**
   - Export respects current filters
   - Export includes all fields

3. **Handle large exports:**
   - Background job for 1000+ clients
   - Email download link when ready

4. **Add export history**

5. **Create tests for export**

**Acceptance Criteria:**
- [ ] Export generates valid CSV
- [ ] Filtered exports work
- [ ] Large exports handled via background job
- [ ] Export history tracked
- [ ] Tests pass

---

## Task 5.9: Client Analytics Dashboard

**Priority:** 🟢 Nice-to-have  
**Estimated Time:** 3 hours  
**Dependencies:** Task 5.2  
**Assigned To:** Backend Developer

**Objective:**
Create analytics dashboard showing client statistics and trends.

**Detailed Steps:**

1. **Calculate client statistics:**
   - Total clients
   - Active vs inactive
   - Clients by industry
   - Clients with/without projects
   - New clients this month

2. **Create charts:**
   - Clients over time
   - Industry distribution
   - Project count per client

3. **Add export for analytics**

4. **Create tests for analytics**

**Acceptance Criteria:**
- [ ] Statistics calculated correctly
- [ ] Charts display properly
- [ ] Analytics exportable
- [ ] Tests pass

---

## Task 5.10: Client Detail View API

**Priority:** 🟠 High  
**Estimated Time:** 2 hours  
**Dependencies:** Task 5.1  
**Assigned To:** Backend Developer

**Objective:**
Implement comprehensive client detail view with related data.

**Detailed Steps:**

1. **Create show endpoint**

2. **Include related data:**
   - All client fields
   - Associated projects
   - Contact history
   - Change history
   - Created/updated by

3. **Optimize query performance**

4. **Create tests for detail view**

**Acceptance Criteria:**
- [ ] Detail view returns all data
- [ ] Related data loaded efficiently
- [ ] Performance optimized (< 500ms)
- [ ] Tests pass

---
