# Module 9: Contact Form & Email System

## Overview
Complete contact form implementation with email delivery, SMTP configuration, rate limiting, submission management, and email queue system.

---

## Task 9.1: Contact Form Submission API

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 1.3  
**Assigned To:** Backend Developer

**Objective:**
Implement contact form submission API with comprehensive validation, rate limiting, and spam protection.

**Detailed Steps:**

1. **Create contact_submissions table:**
   - name
   - email
   - phone
   - subject
   - message
   - ip_address
   - user_agent
   - status (new/read/replied/archived/spam)
   - created_at

2. **Create ContactSubmissionController**

3. **Implement validation:**
   - Name: required, 2-100 characters
   - Email: required, valid format
   - Phone: optional, E.164 format
   - Subject: optional, max 150 characters
   - Message: required, 10-2000 characters
   - Privacy policy: must be checked

4. **Add spam protection:**
   - Honeypot field
   - Submission time tracking (min 3 seconds)
   - IP-based blocking
   - Content filtering

5. **Implement rate limiting:**
   - 5 submissions per IP per minute
   - 429 response on limit exceeded

6. **Add to email queue**

7. **Store in database**

8. **Create tests**

**Acceptance Criteria:**
- [ ] Form submission works
- [ ] All validation enforced
- [ ] Spam protection works
- [ ] Rate limiting works (5/min)
- [ ] Submissions stored in DB
- [ ] Emails queued
- [ ] Tests pass

**Files Created:**
- `database/migrations/YYYY_MM_DD_create_contact_submissions_table.php`
- `app/Models/ContactSubmission.php`
- `app/Http/Controllers/Api/V1/ContactSubmissionController.php`
- `app/Http/Requests/StoreContactSubmissionRequest.php`
- `tests/Feature/ContactFormTest.php`

---

## Task 9.2: SMTP Configuration Management

**Priority:** 🔴 Critical  
**Estimated Time:** 5 hours  
**Dependencies:** Task 9.1  
**Assigned To:** Backend Developer

**Objective:**
Implement SMTP configuration management with support for multiple providers and testing tools.

**Detailed Steps:**

1. **Create smtp_settings table:**
   - provider (gmail/sendgrid/ses/mailgun/custom)
   - host
   - port
   - username
   - password (encrypted)
   - encryption (none/ssl/tls)
   - from_address
   - from_name
   - is_active

2. **Create SMTPSettingsController**

3. **Implement provider presets:**
   - Gmail (smtp.gmail.com:587)
   - SendGrid (smtp.sendgrid.net:587)
   - AWS SES (region-specific)
   - Mailgun (smtp.mailgun.org:587)
   - Custom SMTP

4. **Add password encryption:**
   - Encrypt before storage
   - Decrypt for use
   - Never expose in API

5. **Implement test email:**
   - Send test email button
   - Verify connection
   - Show detailed errors

6. **Add connection status indicator**

7. **Create tests**

**Acceptance Criteria:**
- [ ] SMTP settings configurable
- [ ] Provider presets work
- [ ] Password encrypted
- [ ] Test email works
- [ ] Connection status shown
- [ ] Tests pass

---

## Task 9.3: Email Queue System

**Priority:** 🔴 Critical  
**Estimated Time:** 6 hours  
**Dependencies:** Task 9.2  
**Assigned To:** Backend Developer

**Objective:**
Implement email queue system with retry logic, failure handling, and monitoring.

**Detailed Steps:**

1. **Create email_queue table:**
   - recipient_email
   - subject
   - body
   - status (pending/sending/sent/failed)
   - attempts
   - last_attempt_at
   - error_message
   - priority (high/normal/low)
   - created_at

2. **Create EmailQueueService**

3. **Implement queue processing:**
   - Background job runs every 1 minute
   - Process up to 50 emails per batch
   - FIFO order

4. **Add retry logic:**
   - Retry up to 5 times
   - Intervals: 1min, 5min, 15min, 1hr, 6hr
   - Move to dead letter queue after 5 failures

5. **Implement failure handling:**
   - Log all errors
   - Notify admin of permanent failures
   - Categorize error types

6. **Add monitoring dashboard:**
   - Queue status widget
   - Failed emails list
   - Retry button
   - Clear queue button

7. **Create tests**

**Acceptance Criteria:**
- [ ] Email queue works
- [ ] Retry logic works (5 attempts)
- [ ] Failed emails tracked
- [ ] Monitoring dashboard works
- [ ] Tests pass

---

## Task 9.4: Email Recipient Management

**Priority:** 🔴 Critical  
**Estimated Time:** 4 hours  
**Dependencies:** Task 9.1  
**Assigned To:** Backend Developer

**Objective:**
Implement email recipient management for routing contact form submissions.

**Detailed Steps:**

1. **Create email_recipients table:**
   - email
   - name
   - role
   - is_active
   - is_primary
   - notification_preference (immediate/daily/weekly)

2. **Create EmailRecipientController**

3. **Implement recipient management:**
   - Add/edit/delete recipients
   - Set primary recipient
   - Enable/disable recipients
   - Minimum 1 active recipient required

4. **Add subject-based routing:**
   - Map subjects to specific recipients
   - Fallback to primary recipient

5. **Implement notification preferences:**
   - Immediate: send email immediately
   - Daily digest: send once per day
   - Weekly digest: send once per week

6. **Create tests**

**Acceptance Criteria:**
- [ ] Recipient management works
- [ ] Subject-based routing works
- [ ] Notification preferences work
- [ ] At least 1 active recipient required
- [ ] Tests pass

---

## Task 9.5: Contact Submission Management (Admin)

**Priority:** 🔴 Critical  
**Estimated Time:** 5 hours  
**Dependencies:** Task 9.1  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement admin interface for viewing and managing contact form submissions.

**Detailed Steps:**

1. **Create submission list view:**
   - Display all submissions
   - Pagination (25 per page)
   - Search by name, email, message
   - Filter by status, date range, subject

2. **Implement status management:**
   - New (unread)
   - Read
   - Replied
   - Archived
   - Spam

3. **Add submission detail view:**
   - All submission fields
   - IP address and user agent
   - Email delivery status
   - Reply button (opens email client)
   - Mark as spam button
   - Archive button
   - Delete button

4. **Implement bulk actions:**
   - Mark as read
   - Mark as replied
   - Archive
   - Delete
   - Mark as spam

5. **Add export functionality**

6. **Create tests**

**Acceptance Criteria:**
- [ ] Submission list works
- [ ] Search and filters work
- [ ] Status management works
- [ ] Detail view shows all info
- [ ] Bulk actions work
- [ ] Export works
- [ ] Tests pass

---

## Task 9.6: Email Template System

**Priority:** 🟠 High  
**Estimated Time:** 5 hours  
**Dependencies:** Task 9.2  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement email template system for customizing notification emails.

**Detailed Steps:**

1. **Create email_templates table:**
   - name
   - subject (bilingual)
   - body (bilingual, HTML)
   - variables (JSON)
   - is_active

2. **Create EmailTemplateController**

3. **Implement template editor:**
   - HTML editor with preview
   - Variable insertion
   - Bilingual support

4. **Add template variables:**
   - {name}, {email}, {phone}
   - {subject}, {message}
   - {date}, {time}
   - {ip}, {language}

5. **Create default templates:**
   - Contact form notification
   - Auto-reply to submitter
   - Daily digest
   - Weekly digest

6. **Implement template preview**

7. **Create tests**

**Acceptance Criteria:**
- [ ] Template management works
- [ ] Template editor functional
- [ ] Variables work correctly
- [ ] Preview shows actual output
- [ ] Default templates created
- [ ] Tests pass

---

## Task 9.7: Auto-Reply System

**Priority:** 🟠 High  
**Estimated Time:** 4 hours  
**Dependencies:** Task 9.6  
**Assigned To:** Backend Developer

**Objective:**
Implement auto-reply system to send confirmation emails to form submitters.

**Detailed Steps:**

1. **Add auto-reply settings:**
   - Enable/disable auto-reply
   - Subject line (bilingual)
   - Message body (bilingual)
   - From address
   - From name

2. **Implement auto-reply sending:**
   - Send immediately after submission
   - Use configured template
   - Include submission details (optional)

3. **Add rate limiting:**
   - Max 1 auto-reply per email per hour
   - Prevent spam

4. **Track auto-reply delivery**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Auto-reply configurable
- [ ] Auto-reply sends correctly
- [ ] Rate limiting works
- [ ] Delivery tracked
- [ ] Tests pass

---

## Task 9.8: Email Delivery Monitoring

**Priority:** 🟠 High  
**Estimated Time:** 4 hours  
**Dependencies:** Task 9.3  
**Assigned To:** Backend Developer

**Objective:**
Implement email delivery monitoring and alerting system.

**Detailed Steps:**

1. **Create email_logs table:**
   - email_queue_id
   - status
   - error_message
   - sent_at
   - opened_at (optional)
   - clicked_at (optional)

2. **Implement logging:**
   - Log all email attempts
   - Log success/failure
   - Log detailed errors

3. **Add monitoring dashboard:**
   - Emails sent today/week/month
   - Success rate
   - Failure rate
   - Average delivery time
   - Failed emails list

4. **Implement alerts:**
   - Alert if failure rate > 10%
   - Alert if queue size > 1000
   - Alert if emails stuck in sending

5. **Add health checks:**
   - SMTP connection check
   - Queue health check
   - Delivery rate check

6. **Create tests**

**Acceptance Criteria:**
- [ ] Email logging works
- [ ] Monitoring dashboard displays correctly
- [ ] Alerts trigger appropriately
- [ ] Health checks work
- [ ] Tests pass

---

## Task 9.9: Spam Detection and Blocking

**Priority:** 🟠 High  
**Estimated Time:** 5 hours  
**Dependencies:** Task 9.1  
**Assigned To:** Backend Developer

**Objective:**
Implement comprehensive spam detection and IP blocking system.

**Detailed Steps:**

1. **Create spam_patterns table:**
   - pattern (regex)
   - type (keyword/url/email)
   - is_active

2. **Create blocked_ips table:**
   - ip_address
   - reason
   - blocked_until
   - is_permanent

3. **Implement spam detection:**
   - Keyword matching
   - URL pattern matching
   - Email pattern matching
   - Submission time check
   - Honeypot check

4. **Add IP blocking:**
   - Auto-block after 5 spam submissions
   - Manual block/unblock
   - Temporary vs permanent blocks

5. **Create spam management UI:**
   - View spam submissions
   - Mark as not spam (false positive)
   - Manage spam patterns
   - Manage blocked IPs

6. **Create tests**

**Acceptance Criteria:**
- [ ] Spam detection works
- [ ] IP blocking works
- [ ] Auto-block after 5 spam submissions
- [ ] Spam management UI works
- [ ] Tests pass

---

## Task 9.10: Contact Form Analytics

**Priority:** 🟡 Medium  
**Estimated Time:** 4 hours  
**Dependencies:** Task 9.5  
**Assigned To:** Backend Developer

**Objective:**
Implement analytics for contact form submissions and performance.

**Detailed Steps:**

1. **Calculate statistics:**
   - Total submissions
   - Submissions by status
   - Submissions by subject
   - Submissions over time
   - Response rate
   - Average response time

2. **Create charts:**
   - Submissions over time (line chart)
   - Status distribution (pie chart)
   - Subject distribution (bar chart)
   - Response time trend

3. **Add conversion tracking:**
   - Form views
   - Form submissions
   - Conversion rate

4. **Implement export**

5. **Create tests**

**Acceptance Criteria:**
- [ ] Statistics calculated correctly
- [ ] Charts display properly
- [ ] Conversion tracking works
- [ ] Export works
- [ ] Tests pass

---

## Task 9.11: Email Digest System

**Priority:** 🟡 Medium  
**Estimated Time:** 5 hours  
**Dependencies:** Task 9.4  
**Assigned To:** Backend Developer

**Objective:**
Implement daily and weekly email digest system for contact submissions.

**Detailed Steps:**

1. **Create digest generation:**
   - Collect new submissions
   - Format as HTML email
   - Include summary statistics

2. **Implement scheduling:**
   - Daily digest: 9 AM
   - Weekly digest: Monday 9 AM
   - Configurable time

3. **Add digest preferences:**
   - Enable/disable per recipient
   - Choose daily or weekly
   - Set timezone

4. **Create digest template:**
   - Professional design
   - Responsive HTML
   - Summary + details

5. **Add unsubscribe option**

6. **Create tests**

**Acceptance Criteria:**
- [ ] Digest generation works
- [ ] Scheduling works correctly
- [ ] Preferences configurable
- [ ] Template looks professional
- [ ] Unsubscribe works
- [ ] Tests pass

---

## Task 9.12: Contact Form A/B Testing

**Priority:** 🟢 Nice-to-have  
**Estimated Time:** 6 hours  
**Dependencies:** Task 9.1  
**Assigned To:** Full-Stack Developer

**Objective:**
Implement A/B testing system for contact form variations.

**Detailed Steps:**

1. **Create form_variations table:**
   - name
   - fields (JSON)
   - layout
   - is_active
   - traffic_percentage

2. **Implement variation serving:**
   - Randomly assign variation
   - Track which variation shown
   - Store variation ID with submission

3. **Add performance tracking:**
   - Views per variation
   - Submissions per variation
   - Conversion rate per variation

4. **Create A/B test dashboard:**
   - Compare variations
   - Statistical significance
   - Winner declaration

5. **Implement winner selection:**
   - Automatically promote winner
   - Archive losing variations

6. **Create tests**

**Acceptance Criteria:**
- [ ] Variations can be created
- [ ] Variations served randomly
- [ ] Performance tracked
- [ ] Dashboard shows comparison
- [ ] Winner selection works
- [ ] Tests pass

---
