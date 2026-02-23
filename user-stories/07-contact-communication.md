# Contact Form & Communication User Stories

> **Format**: Each story follows the pattern:
> `As a [role], I want to [action], so that [benefit].`
>
> **Priority Levels**: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Nice-to-have
>
> **Acceptance Criteria (AC)**: Numbered conditions that MUST be true for the story to be complete.
>
> **Edge Cases (EC)**: Scenarios that must be handled gracefully.

---

## US-CONTACT-7.1 🔴 Submit Contact Form from Public Website
**As a** website visitor, **I want to** submit a contact form with my inquiry, **so that** I can reach Tarqumi for business inquiries, project requests, or general questions.

**Acceptance Criteria:**
1. Contact form accessible at `/en/contact` and `/ar/contact`
2. **Form Fields:**
   - Full Name (required, text input, max 100 characters)
   - Email Address (required, email input, max 255 characters)
   - Phone Number (optional, tel input, international format support)
   - Subject (optional, dropdown or text input, max 150 characters)
   - Message (required, textarea, min 10 characters, max 2000 characters)
   - Privacy Policy Checkbox (required): "I agree to the privacy policy"
3. **Subject Options (if dropdown):**
   - General Inquiry
   - Project Request
   - Partnership Opportunity
   - Technical Support
   - Career Inquiry
   - Other
4. **Real-time Validation:**
   - Name: min 2 characters, max 100, letters and spaces only
   - Email: valid email format (RFC 5322 compliant)
   - Phone: valid international format (E.164), optional country code
   - Message: min 10 characters, max 2000 characters
   - All required fields validated before submission
   - Inline error messages displayed below each field
5. **Character Counters:**
   - Message field shows character count: "X / 2000 characters"
   - Color coding: green (< 80%), yellow (80-95%), red (> 95%)
6. **Form Submission:**
   - "Send Message" button with loading spinner during submission
   - Button disabled during submission to prevent double-submit
   - Form data sent via POST to `/api/v1/contact/submit`
   - CSRF token included in request
7. **Success Response:**
   - Success message displayed: "Thank you! Your message has been sent successfully. We'll get back to you soon."
   - Form fields cleared after successful submission
   - Success message auto-dismisses after 10 seconds
   - Option to "Send Another Message"
8. **Error Handling:**
   - Network error: "Unable to send message. Please check your connection and try again."
   - Server error: "Something went wrong. Please try again later."
   - Validation errors: Specific error per field
   - Rate limit error: "Too many messages sent. Please wait a few minutes and try again."
9. **Email Delivery:**
   - Email sent to all configured recipient addresses
   - Email subject: "[Tarqumi Contact Form] [Subject] - from [Name]"
   - Email body includes: Name, Email, Phone, Subject, Message, Submission Date/Time, IP Address (for spam tracking)
   - Email sent via SMTP (configurable: Gmail, SendGrid, AWS SES, Mailgun)
   - Email delivery confirmation logged
10. **Database Storage:**
    - All submissions stored in `contact_submissions` table
    - Fields stored: name, email, phone, subject, message, ip_address, user_agent, status (new/read/replied/archived), created_at, updated_at
    - Submission ID generated (UUID or auto-increment)
11. **Rate Limiting:**
    - Maximum 5 submissions per IP address per minute
    - 6th submission within 1 minute returns 429 Too Many Requests
    - Rate limit counter resets after 1 minute
    - Rate limit bypass for whitelisted IPs (admin office IP)
12. **Spam Protection:**
    - Honeypot field (hidden from users, bots fill it)
    - Submission time tracking (too fast = bot)
    - IP-based blocking for known spam IPs
    - Content filtering for spam keywords
    - No CAPTCHA required (per project requirements)
13. **Accessibility:**
    - All form fields have proper labels
    - Error messages announced to screen readers
    - Keyboard navigation support (Tab, Enter)
    - Focus management (focus on first error field)
    - ARIA labels and roles properly set
14. **Bilingual Support:**
    - All labels, placeholders, error messages in Arabic and English
    - RTL layout for Arabic
    - Validation messages in user's selected language
15. **Analytics Tracking:**
    - Form view event tracked
    - Form submission event tracked
    - Conversion tracking (submission success rate)
    - Field interaction tracking (which fields users focus on)


**Edge Cases:**
- EC-1: Name with Arabic characters → accepted and stored correctly with RTL support
- EC-2: Name with special characters (e.g., O'Brien, José) → accepted
- EC-3: Email with uppercase letters → normalized to lowercase before storage
- EC-4: Email with plus addressing (user+tag@domain.com) → accepted as valid
- EC-5: Phone number without country code → accepted, warning shown suggesting country code
- EC-6: Phone number with spaces, dashes, parentheses → normalized to E.164 format
- EC-7: Message with URLs → accepted, validated for spam patterns
- EC-8: Message with email addresses → accepted
- EC-9: Message with line breaks → preserved in database and email
- EC-10: Message with HTML tags → sanitized, tags stripped
- EC-11: Message with emojis → accepted and stored correctly (UTF-8)
- EC-12: Very long name (100+ characters) → truncated at 100, validation error shown
- EC-13: Empty required fields → inline validation errors shown
- EC-14: Invalid email format → validation error: "Please enter a valid email address"
- EC-15: Message too short (< 10 characters) → validation error: "Message must be at least 10 characters"
- EC-16: Message too long (> 2000 characters) → validation error with character count
- EC-17: Privacy policy not checked → validation error: "You must agree to the privacy policy"
- EC-18: Honeypot field filled (bot detection) → submission silently rejected, no error shown
- EC-19: Submission too fast (< 3 seconds) → flagged as potential bot, requires review
- EC-20: 6th submission within 1 minute → 429 error: "Too many messages. Please wait."
- EC-21: Network timeout during submission → error message, form data preserved
- EC-22: SMTP server unavailable → submission stored in DB, email queued for retry
- EC-23: Invalid recipient email configured → error logged, admin notified
- EC-24: Duplicate submission (same content within 5 minutes) → prevented with warning
- EC-25: Submission from known spam IP → blocked silently or requires manual review
- EC-26: XSS attempt in any field → sanitized, logged as security event
- EC-27: SQL injection attempt → parameterized queries prevent, logged as security event
- EC-28: Form submitted with JavaScript disabled → server-side validation still works
- EC-29: Form submitted from mobile device → responsive layout, touch-friendly
- EC-30: Form submitted with screen reader → accessible, all errors announced

**Validation Rules:**
- Name: required, 2-100 characters, letters, spaces, hyphens, apostrophes only
- Email: required, valid RFC 5322 format, max 255 characters
- Phone: optional, E.164 format, 7-15 digits
- Subject: optional, max 150 characters
- Message: required, 10-2000 characters
- Privacy Policy: must be checked
- Honeypot: must be empty
- Submission time: must be > 3 seconds from page load

**Security Considerations:**
- All input sanitized to prevent XSS attacks
- SQL injection prevented with parameterized queries (Eloquent ORM)
- CSRF token validated on every submission
- Rate limiting prevents spam and abuse
- Honeypot field catches simple bots
- IP address logged for spam tracking and blocking
- Email addresses validated to prevent header injection
- Content filtered for spam keywords and patterns
- Submission time tracked to detect bots
- User agent logged for bot detection
- No sensitive data exposed in error messages
- Email delivery failures logged but not exposed to user
- All submissions logged in audit trail

**Email Template:**
```
Subject: [Tarqumi Contact Form] [Subject] - from [Name]

You have received a new contact form submission from the Tarqumi website.

Name: [Full Name]
Email: [Email Address]
Phone: [Phone Number]
Subject: [Subject]

Message:
[Message content]

---
Submission Details:
Date/Time: [YYYY-MM-DD HH:MM:SS]
IP Address: [XXX.XXX.XXX.XXX]
User Agent: [Browser/Device Info]
Language: [Arabic/English]
Page URL: [Contact Page URL]

---
To reply to this inquiry, please respond directly to [Email Address]
To view all submissions, visit: [Admin Panel URL]

This is an automated message from the Tarqumi CRM system.
```

**Responsive Design:**
- Mobile (375px): Single column, full-width inputs, large touch-friendly buttons (min 44px height), stacked labels
- Tablet (768px): Single column, optimized input sizes, comfortable spacing
- Desktop (1024px+): Max form width 600px, centered, ample white space

**Performance:**
- Form load: < 500ms
- Validation response: < 100ms (client-side)
- Submission API: < 2 seconds (including email send)
- Email delivery: < 30 seconds
- Database insert: < 200ms
- Rate limit check: < 50ms

**UX Considerations:**
- Clear, friendly form labels
- Helpful placeholder text (e.g., "Tell us about your project...")
- Inline validation with green checkmarks for valid fields
- Error messages in red with icon
- Success message in green with checkmark icon
- Loading spinner on submit button
- Form fields auto-focus on first field
- Tab order logical and intuitive
- Enter key submits form (when all fields valid)
- Character counter updates in real-time
- Privacy policy link opens in new tab
- "Required field" indicator (asterisk) on labels
- Smooth scroll to first error on validation failure
- Auto-save draft in localStorage (optional, cleared on success)
- Estimated response time displayed: "We typically respond within 24 hours"

---

## US-CONTACT-7.2 🔴 View and Manage Contact Submissions (Admin)
**As a** Super Admin or Admin, **I want to** view and manage all contact form submissions, **so that** I can respond to inquiries and track communication with potential clients.

**Acceptance Criteria:**
1. Contact submissions accessible from CRM dashboard → Communications → Contact Submissions
2. **Submissions List View:**
   - Table displays: Status Badge, Name, Email, Subject, Message Preview (first 100 chars), Submission Date, Actions
   - Default sort: Newest First
   - Pagination: 25 submissions per page (configurable: 10, 25, 50, 100)
3. **Status Management:**
   - Status options: New (unread), Read, Replied, Archived, Spam
   - Status badge color-coded: New (blue), Read (gray), Replied (green), Archived (yellow), Spam (red)
   - Click submission → auto-marks as "Read"
   - Status can be changed manually via dropdown
   - Bulk status change supported
4. **Search Functionality:**
   - Real-time search by name, email, phone, subject, message content
   - Search debounced (300ms)
   - Search highlights matching terms in results
   - "Clear search" button
5. **Filter Options:**
   - Status: All, New, Read, Replied, Archived, Spam
   - Date Range: All, Today, Yesterday, Last 7 Days, Last 30 Days, This Month, Last Month, Custom Range
   - Subject: All, [Subject 1], [Subject 2], etc.
   - Language: All, Arabic, English
6. **Sort Options:**
   - Sort by: Date (Newest/Oldest), Name (A-Z/Z-A), Email (A-Z/Z-A), Status
7. **Submission Detail View:**
   - Click submission → opens detail modal or side panel
   - Displays all fields: Name, Email, Phone, Subject, Message (full text), Status
   - Submission metadata: Date/Time, IP Address, User Agent, Language, Page URL
   - Email delivery status: Sent to [recipient1@example.com, recipient2@example.com] at [timestamp]
   - Email delivery errors (if any)
   - "Reply via Email" button (opens default email client with pre-filled to/subject)
   - "Mark as Spam" button
   - "Archive" button
   - "Delete" button (soft delete)
   - "Create Client" button (converts submission to client record)
   - "Create Project" button (if client exists)
   - Notes section: Admin can add internal notes about the submission
   - Activity log: Status changes, notes added, emails sent
8. **Quick Actions:**
   - Hover over submission → quick action buttons appear
   - Mark as Read/Unread (toggle)
   - Reply (opens email client)
   - Archive
   - Delete
9. **Bulk Actions:**
   - Select multiple submissions via checkboxes
   - "Select All" checkbox (selects all on current page)
   - Bulk actions: Mark as Read, Mark as Replied, Archive, Delete, Mark as Spam
   - Bulk action confirmation dialog
10. **Reply Functionality:**
    - "Reply" button opens default email client (mailto: link)
    - Pre-filled: To (submitter email), Subject ("Re: [original subject]"), Body (quoted original message)
    - After sending reply externally, admin manually marks as "Replied"
    - Future enhancement: In-app email composer (Phase 2)
11. **Export Options:**
    - Export filtered submissions to CSV or Excel
    - Export includes all fields and metadata
    - Date range selection for export
    - Max 10,000 submissions per export
12. **Email Notification:**
    - Admin receives email notification for each new submission (configurable)
    - Notification includes: Name, Email, Subject, Message preview, Link to view in admin panel
    - Notification can be enabled/disabled per admin user
13. **Spam Management:**
    - "Mark as Spam" moves submission to spam folder
    - Spam submissions hidden from main list (unless "Show Spam" filter enabled)
    - Spam IP addresses can be blocked automatically
    - Spam patterns learned and applied to future submissions (optional)
14. **Submission Statistics:**
    - Dashboard widget shows: Total submissions, New submissions, Replied submissions, Response rate
    - Chart showing submissions over time (last 30 days)
    - Average response time
    - Conversion rate (submissions that became clients)
15. **Auto-Archive:**
    - Submissions older than 90 days auto-archived (configurable)
    - Archived submissions can be restored
    - Permanent deletion after 1 year (configurable)


**Edge Cases:**
- EC-1: No submissions yet → empty state: "No contact submissions yet. Submissions will appear here."
- EC-2: Search returns no results → empty state: "No submissions match your search"
- EC-3: Filter combination returns no results → empty state with active filters shown
- EC-4: User on page 5, applies filter that returns only 2 pages → redirected to page 1
- EC-5: Very long message (2000 chars) → preview truncated, full text in detail view
- EC-6: Message with line breaks → preserved and displayed correctly
- EC-7: Message with URLs → clickable links in detail view
- EC-8: Message with email addresses → clickable mailto links
- EC-9: Submission from deleted/blocked IP → shown with warning badge
- EC-10: Email delivery failed → error badge shown, retry option available
- EC-11: Multiple admins viewing same submission → real-time status updates (optional)
- EC-12: Admin marks as replied but didn't actually reply → no validation (trust-based)
- EC-13: Submission marked as spam → can be unmarked if false positive
- EC-14: Bulk action on 100+ submissions → progress indicator shown
- EC-15: Export with 10,000+ submissions → background job, download link emailed
- EC-16: Submission with no phone number → phone field shows "—" or "Not provided"
- EC-17: Submission with no subject → subject shows "No subject"
- EC-18: Concurrent status change by two admins → last change wins, no conflict
- EC-19: Delete submission while another admin viewing → viewer sees "Submission deleted" message
- EC-20: Create client from submission with existing email → links to existing client
- EC-21: Reply button on mobile → opens mobile email app
- EC-22: Very old submission (2+ years) → archived, can be viewed but not edited
- EC-23: Submission from bot (honeypot triggered) → automatically marked as spam
- EC-24: IP address shows as "Unknown" → submission from proxy or VPN
- EC-25: User agent shows unusual browser → flagged for review

**Validation Rules:**
- Status: must be one of predefined statuses
- Notes: max 2000 characters
- Export date range: valid start and end dates
- Bulk action: at least 1 submission must be selected

**Security Considerations:**
- Only Super Admin and Admin can view submissions
- Founder roles have read-only access (no delete, no mark as spam)
- HR and Employee roles cannot access submissions (403 Forbidden)
- IP addresses visible only to Super Admin
- Email addresses not exposed in exports (optional masking)
- All actions logged in audit log
- Soft delete prevents accidental data loss
- Export action logged with user and timestamp
- Spam submissions isolated from main list
- Blocked IPs cannot submit new forms

**Responsive Design:**
- Mobile (375px): Card layout, swipe actions, bottom sheet for detail view
- Tablet (768px): Table with horizontal scroll, modal for detail view
- Desktop (1024px+): Full table, side panel for detail view

**Performance:**
- Submissions list load: < 1 second for 25 submissions
- Search/filter response: < 500ms
- Pagination navigation: < 300ms
- Detail view load: < 500ms
- Bulk action (100 submissions): < 5 seconds
- Export CSV (1000 submissions): < 5 seconds
- Real-time status updates: < 1 second (if enabled)

**UX Considerations:**
- Unread submissions highlighted with bold text
- New submissions badge in sidebar navigation
- Hover effects on table rows
- Smooth transitions between views
- Loading skeleton while fetching data
- Confirmation dialogs for destructive actions
- Toast notifications for all actions
- Keyboard shortcuts: N (mark as new), R (mark as read), A (archive), D (delete)
- "Back to list" button in detail view
- Breadcrumb navigation: Communications > Contact Submissions > [Submission ID]
- Auto-refresh list every 60 seconds (optional)
- Sound notification for new submissions (optional, user preference)

---

## US-CONTACT-7.3 🟠 Configure Contact Form Email Recipients
**As a** Super Admin or Admin, **I want to** configure which email addresses receive contact form submissions, **so that** inquiries are routed to the appropriate team members.

**Acceptance Criteria:**
1. Email configuration accessible from CRM dashboard → Settings → Contact Form Settings
2. **Email Recipients Management:**
   - Add multiple recipient email addresses
   - Each recipient has: Email Address, Name (optional), Role/Department (optional), Active Status
   - Primary recipient designation (receives all submissions)
   - Secondary recipients (can be filtered by subject)
   - Minimum 1 active recipient required at all times
3. **Recipient Configuration:**
   - Email address validation (RFC 5322 compliant)
   - Name field: max 100 characters
   - Role/Department: dropdown (Sales, Support, HR, Management, Other)
   - Active toggle: enable/disable recipient without deleting
   - Subject filter: select which subjects this recipient receives (optional)
   - Notification preference: Immediate, Daily Digest, Weekly Digest, None
4. **Subject-Based Routing:**
   - Map subjects to specific recipients
   - Example: "Project Request" → sales@tarqumi.com
   - Example: "Technical Support" → support@tarqumi.com
   - Example: "Career Inquiry" → hr@tarqumi.com
   - Fallback to primary recipient if no mapping exists
5. **Email Template Customization:**
   - Customize email subject line template
   - Customize email body template (with variables)
   - Available variables: {name}, {email}, {phone}, {subject}, {message}, {date}, {time}, {ip}, {language}
   - Preview email template before saving
   - Reset to default template option
6. **Auto-Reply Configuration:**
   - Enable/disable auto-reply to submitter
   - Auto-reply subject line (bilingual)
   - Auto-reply message (bilingual, rich text editor)
   - Auto-reply from address (defaults to primary recipient)
   - Auto-reply from name (defaults to "Tarqumi Team")
   - Include submission details in auto-reply (optional)
7. **SMTP Configuration:**
   - SMTP provider selection: Gmail, SendGrid, AWS SES, Mailgun, Custom SMTP
   - SMTP host, port, username, password (encrypted storage)
   - Encryption: None, SSL, TLS
   - Test email button (sends test email to verify configuration)
   - Connection status indicator (connected/disconnected)
   - Email sending logs (last 100 emails sent)
8. **Notification Settings:**
   - Enable/disable email notifications for new submissions
   - Notification frequency: Immediate, Hourly Digest, Daily Digest
   - Notification recipients: select from configured recipients
   - Notification template customization
   - Quiet hours: disable notifications during specific hours (e.g., 10 PM - 8 AM)
9. **Spam Protection Settings:**
   - Enable/disable honeypot field
   - Enable/disable submission time tracking
   - Minimum submission time (seconds, default 3)
   - Maximum submissions per IP per minute (default 5)
   - Blocked IP addresses list (add/remove IPs)
   - Spam keyword list (add/remove keywords)
   - Auto-block IPs after X spam submissions (configurable)
10. **Form Field Configuration:**
    - Enable/disable optional fields (Phone, Subject)
    - Make Phone field required (toggle)
    - Subject field type: Dropdown or Text Input
    - Subject dropdown options (add/edit/remove/reorder)
    - Message field character limits (min/max)
    - Privacy policy link URL
11. **Success Message Customization:**
    - Customize success message (bilingual)
    - Success message display duration (seconds)
    - Redirect after submission (optional, URL)
    - Show "Send Another Message" button (toggle)
12. **Analytics Integration:**
    - Google Analytics tracking ID
    - Facebook Pixel ID
    - Custom conversion tracking code
    - Track form views, submissions, success rate
13. **Backup and Restore:**
    - Export configuration as JSON
    - Import configuration from JSON
    - Configuration version history
    - Restore previous configuration
14. **Testing Tools:**
    - Send test submission (fills form with dummy data)
    - Test email delivery to all recipients
    - Test auto-reply functionality
    - Test spam protection (honeypot, rate limiting)
    - View test results and logs
15. Save triggers configuration update, no page revalidation needed

**Edge Cases:**
- EC-1: Remove last active recipient → validation error: "At least 1 active recipient required"
- EC-2: Add recipient with invalid email → validation error with format example
- EC-3: Add duplicate recipient email → validation error: "This email is already configured"
- EC-4: SMTP configuration invalid → test email fails, error message shown
- EC-5: SMTP password changed → re-enter password, test connection
- EC-6: Auto-reply enabled but no message configured → validation error
- EC-7: Subject routing configured but subject field disabled → warning shown
- EC-8: Recipient email bounces → marked as inactive, admin notified
- EC-9: All recipients inactive → warning: "No active recipients. Submissions will be stored but not emailed."
- EC-10: Rate limit set to 0 → validation error: "Rate limit must be at least 1"
- EC-11: Minimum submission time set to 0 → warning: "Bots may bypass this check"
- EC-12: Blocked IP list exceeds 1000 IPs → warning: "Large IP list may affect performance"
- EC-13: Spam keyword list empty → warning: "No spam keywords configured"
- EC-14: Email template with invalid variables → validation error listing invalid variables
- EC-15: Auto-reply from address not verified → warning: "Emails may be marked as spam"
- EC-16: Notification quiet hours overlap 24 hours → validation error
- EC-17: Import configuration with incompatible version → error with migration instructions
- EC-18: Test email fails → detailed error message with troubleshooting steps
- EC-19: Gmail SMTP with 2FA enabled → requires App Password, instructions shown
- EC-20: SendGrid API key invalid → test fails, error message with link to SendGrid docs

**Validation Rules:**
- Email Address: required, RFC 5322 compliant, max 255 characters
- Name: optional, max 100 characters
- Role/Department: optional, predefined list
- SMTP Host: required if custom SMTP, valid hostname or IP
- SMTP Port: required if custom SMTP, 1-65535
- SMTP Username: required if authentication enabled
- SMTP Password: required if authentication enabled
- Rate Limit: 1-100 submissions per minute
- Minimum Submission Time: 0-60 seconds
- Message Character Limits: min 1-100, max 100-10000
- Subject Options: max 20 options, each max 150 characters

**Security Considerations:**
- Only Super Admin and Admin can configure email settings
- SMTP passwords encrypted at rest (AES-256)
- SMTP passwords never displayed (show as *****)
- Test emails sent only to configured recipients (prevent abuse)
- Configuration changes logged in audit log
- Email logs show only metadata (no message content)
- Blocked IP list not publicly accessible
- Spam keywords not exposed in frontend
- Auto-reply rate limited (prevent abuse)
- Email templates sanitized to prevent injection

**Responsive Design:**
- Mobile (375px): Single column, collapsible sections, full-width inputs
- Tablet (768px): Two-column layout for related settings
- Desktop (1024px+): Multi-column layout, side-by-side preview

**Performance:**
- Settings page load: < 1 second
- Save configuration: < 500ms
- Test email send: < 5 seconds
- SMTP connection test: < 3 seconds
- Configuration export: < 1 second
- Configuration import: < 2 seconds

**UX Considerations:**
- Collapsible sections for better organization
- Tooltips explaining each setting
- "Save Changes" button sticky at bottom
- Unsaved changes warning when navigating away
- Success toast after saving
- Test buttons next to relevant settings
- Real-time validation for email addresses
- SMTP configuration wizard for common providers
- Template editor with syntax highlighting
- Variable autocomplete in template editor
- Preview pane for email templates
- "Reset to Default" buttons for customizable fields
- Import/Export buttons prominently displayed
- Configuration change history with diff view

---

## US-CONTACT-7.4 🟠 Email Delivery Queue and Retry Logic
**As a** system, **I want to** queue contact form emails and retry failed deliveries, **so that** no inquiries are lost due to temporary email server issues.

**Acceptance Criteria:**
1. **Email Queue System:**
   - All contact form emails added to queue immediately after submission
   - Queue stored in database table: `email_queue`
   - Queue fields: id, recipient_email, subject, body, status, attempts, last_attempt_at, created_at, updated_at
   - Status options: Pending, Sending, Sent, Failed, Cancelled
2. **Queue Processing:**
   - Background job processes queue every 1 minute
   - Processes up to 50 emails per batch
   - Emails sent in order of creation (FIFO)
   - Failed emails moved to end of queue for retry
3. **Retry Logic:**
   - Failed emails automatically retried up to 5 times
   - Retry intervals: 1 min, 5 min, 15 min, 1 hour, 6 hours
   - After 5 failed attempts, email marked as "Failed" permanently
   - Admin notified of permanently failed emails
4. **Failure Handling:**
   - SMTP connection errors → retry
   - Invalid recipient email → mark as failed, no retry
   - Timeout errors → retry
   - Authentication errors → mark as failed, admin notified immediately
   - Rate limit errors → retry after delay
5. **Email Logs:**
   - All email attempts logged in `email_logs` table
   - Log fields: id, email_queue_id, status, error_message, sent_at, created_at
   - Logs retained for 90 days
   - Admin can view logs in CRM dashboard
6. **Monitoring Dashboard:**
   - Queue status widget: Pending (X), Sending (Y), Sent (Z), Failed (W)
   - Chart showing emails sent over time
   - Failed emails list with error messages
   - Retry button for failed emails
   - Clear queue button (admin only)
7. **Priority Queue:**
   - High priority emails sent first (e.g., auto-replies)
   - Normal priority for notification emails
   - Low priority for digest emails
8. **Rate Limiting:**
   - Respect SMTP provider rate limits
   - Gmail: 500 emails/day, 100 emails/hour
   - SendGrid: based on plan
   - AWS SES: based on sending limits
   - Queue pauses when rate limit reached, resumes after cooldown
9. **Dead Letter Queue:**
   - Permanently failed emails moved to dead letter queue
   - Admin can manually retry or delete
   - Dead letter queue cleared after 30 days
10. **Health Checks:**
    - Queue health check runs every 5 minutes
    - Alerts if queue size exceeds 1000 emails
    - Alerts if emails stuck in "Sending" status for > 10 minutes
    - Alerts if failure rate exceeds 10%
11. **Manual Controls:**
    - Admin can pause/resume queue processing
    - Admin can cancel pending emails
    - Admin can manually trigger queue processing
    - Admin can view queue in real-time
12. **Performance Optimization:**
    - Batch email sending (up to 50 per batch)
    - Connection pooling for SMTP
    - Async processing (non-blocking)
    - Queue indexed for fast retrieval


**Edge Cases:**
- EC-1: Queue grows to 10,000+ emails → warning alert, investigate issue
- EC-2: SMTP server down for extended period → emails queued, retried when server back up
- EC-3: All retry attempts exhausted → email moved to dead letter queue, admin notified
- EC-4: Email stuck in "Sending" status → timeout after 10 minutes, marked as failed
- EC-5: Duplicate emails in queue → deduplication logic prevents sending twice
- EC-6: Queue processing job crashes → auto-restart, resume from last processed email
- EC-7: Admin pauses queue → all pending emails remain in queue, no processing
- EC-8: Admin cancels email → removed from queue, marked as cancelled
- EC-9: Recipient email bounces → marked as failed, no retry
- EC-10: SMTP authentication fails → all emails fail, admin notified immediately
- EC-11: Rate limit reached → queue pauses, resumes after cooldown period
- EC-12: High priority email added → jumps to front of queue
- EC-13: Queue cleared by admin → all pending emails deleted, cannot be recovered
- EC-14: Email logs exceed 1 million records → old logs archived or deleted
- EC-15: Dead letter queue exceeds 1000 emails → alert sent to admin

**Validation Rules:**
- Recipient email: valid format
- Subject: max 255 characters
- Body: max 100,000 characters
- Attempts: 0-5
- Priority: high, normal, low

**Security Considerations:**
- Queue accessible only to system and admin
- Email content encrypted in queue
- SMTP credentials never logged
- Failed email details logged without sensitive content
- Admin actions on queue logged in audit log

**Responsive Design:**
- Queue dashboard accessible on all devices
- Mobile view shows simplified queue status
- Desktop view shows detailed queue and logs

**Performance:**
- Queue processing: < 5 seconds per batch (50 emails)
- Email send: < 1 second per email
- Queue query: < 100ms
- Log query: < 500ms

**UX Considerations:**
- Real-time queue status updates
- Visual indicators for queue health
- Clear error messages for failed emails
- One-click retry for failed emails
- Bulk actions for queue management
- Export queue and logs to CSV

---

## US-CONTACT-7.5 🟠 Auto-Reply to Contact Form Submissions
**As a** website visitor, **I want to** receive an automatic confirmation email after submitting the contact form, **so that** I know my message was received and when to expect a response.

**Acceptance Criteria:**
1. **Auto-Reply Trigger:**
   - Auto-reply sent immediately after successful form submission
   - Auto-reply sent only if enabled in settings
   - Auto-reply sent to submitter's email address
2. **Auto-Reply Email Content:**
   - Subject: "Thank you for contacting Tarqumi" (bilingual)
   - From: Configured sender name and email (e.g., "Tarqumi Team <noreply@tarqumi.com>")
   - To: Submitter's email address
   - Body includes:
     - Personalized greeting: "Dear [Name],"
     - Confirmation message: "Thank you for reaching out to us. We have received your message and will get back to you soon."
     - Submission summary: Subject, Message (optional)
     - Expected response time: "We typically respond within 24 hours."
     - Contact information: Phone, Email, Office hours
     - Social media links (optional)
     - Company logo and branding
3. **Bilingual Support:**
   - Auto-reply sent in language of form submission (Arabic or English)
   - Separate templates for Arabic and English
   - RTL formatting for Arabic emails
4. **Template Customization:**
   - Admin can customize auto-reply template
   - Rich text editor for email body
   - Available variables: {name}, {email}, {phone}, {subject}, {message}, {date}, {time}
   - Preview template before saving
   - Reset to default template option
5. **Conditional Auto-Reply:**
   - Auto-reply can be disabled for specific subjects (e.g., spam)
   - Auto-reply can be disabled during business hours (optional)
   - Auto-reply can include different messages based on subject
6. **Rate Limiting:**
   - Max 1 auto-reply per email address per hour (prevent abuse)
   - Duplicate submissions within 1 hour don't trigger auto-reply
7. **Delivery Tracking:**
   - Auto-reply delivery status tracked
   - Failed auto-replies logged
   - Admin can view auto-reply logs
8. **Unsubscribe Option:**
   - Auto-reply includes unsubscribe link (optional)
   - Unsubscribed emails don't receive future auto-replies
9. **Attachments:**
   - Auto-reply can include attachments (e.g., company brochure, PDF)
   - Max 5MB total attachment size
   - Supported formats: PDF, JPG, PNG
10. **Email Formatting:**
    - HTML email with fallback to plain text
    - Responsive email design (mobile-friendly)
    - Proper email headers (Reply-To, Return-Path)
    - SPF, DKIM, DMARC configured for deliverability

**Edge Cases:**
- EC-1: Auto-reply disabled → no email sent
- EC-2: Submitter email invalid → auto-reply fails, logged
- EC-3: Submitter email bounces → marked as invalid, no future auto-replies
- EC-4: Auto-reply sent twice within 1 hour → second attempt blocked
- EC-5: Auto-reply template with invalid variables → variables shown as-is
- EC-6: Auto-reply with large attachment → email may be rejected by recipient server
- EC-7: Auto-reply from address not verified → may be marked as spam
- EC-8: Auto-reply during SMTP server downtime → queued for retry
- EC-9: Auto-reply to no-reply email address → sent anyway (no validation)
- EC-10: Auto-reply with broken HTML → fallback to plain text

**Validation Rules:**
- From email: valid format, verified sender
- Subject: max 255 characters
- Body: max 100,000 characters
- Attachments: max 5MB total, PDF/JPG/PNG only

**Security Considerations:**
- Auto-reply rate limited to prevent abuse
- Email content sanitized to prevent injection
- Attachments scanned for malware
- Unsubscribe link includes secure token
- Auto-reply logs don't expose sensitive data

**Responsive Design:**
- Email responsive for mobile, tablet, desktop
- Images scale appropriately
- Text readable without zooming

**Performance:**
- Auto-reply sent within 5 seconds of submission
- Email delivery within 30 seconds
- Template rendering < 500ms

**UX Considerations:**
- Professional email design with branding
- Clear call-to-action (e.g., "Visit our website")
- Social media icons linked
- Contact information prominently displayed
- Friendly, welcoming tone
- Mobile-friendly layout

---

## US-CONTACT-7.6 🟡 Contact Form Analytics and Reporting
**As a** Super Admin, Admin, or Founder, **I want to** view analytics and reports for contact form submissions, **so that** I can understand inquiry trends and optimize our response process.

**Acceptance Criteria:**
1. **Analytics Dashboard:**
   - Accessible from CRM dashboard → Communications → Analytics
   - Overview metrics: Total submissions, New submissions, Response rate, Average response time
   - Date range selector: Last 7 days, Last 30 days, Last 90 days, This year, Custom range
2. **Submission Trends:**
   - Line chart showing submissions over time
   - Compare multiple time periods
   - Breakdown by status (New, Read, Replied, Archived)
   - Breakdown by subject
   - Breakdown by language (Arabic/English)
3. **Subject Analysis:**
   - Bar chart showing submissions per subject
   - Percentage distribution
   - Trend over time per subject
   - Most popular subjects
4. **Response Metrics:**
   - Average response time (time from submission to first reply)
   - Response rate (% of submissions replied to)
   - Response time by subject
   - Response time by team member (if tracked)
   - SLA compliance (if SLA configured)
5. **Conversion Tracking:**
   - Submissions converted to clients
   - Submissions converted to projects
   - Conversion rate by subject
   - Revenue attributed to contact form (if tracked)
6. **Traffic Sources:**
   - Submissions by referrer (if tracked)
   - Direct, organic search, social media, referral
   - Landing page before contact form
   - UTM parameter tracking
7. **Geographic Analysis:**
   - Submissions by country (based on IP)
   - Submissions by city
   - Map visualization
8. **Device and Browser:**
   - Submissions by device type (mobile, tablet, desktop)
   - Submissions by browser
   - Submissions by operating system
9. **Time Analysis:**
   - Submissions by hour of day
   - Submissions by day of week
   - Peak submission times
   - Quiet hours
10. **Spam Analysis:**
    - Spam submissions blocked
    - Spam rate over time
    - Top spam sources (IPs, keywords)
    - Honeypot effectiveness
11. **Email Delivery:**
    - Email delivery success rate
    - Failed email deliveries
    - Bounce rate
    - Average delivery time
12. **Form Abandonment:**
    - Users who started form but didn't submit
    - Field-level abandonment (which field users quit on)
    - Abandonment rate
    - Reasons for abandonment (if tracked)
13. **A/B Testing:**
    - Compare different form versions
    - Conversion rate by version
    - Field configuration impact
    - Success message impact
14. **Export and Reporting:**
    - Export analytics data to CSV/Excel
    - Scheduled reports (daily, weekly, monthly)
    - Email reports to stakeholders
    - Custom report builder
15. **Benchmarking:**
    - Compare current period to previous period
    - Industry benchmarks (if available)
    - Goal setting and tracking

**Edge Cases:**
- EC-1: No submissions in selected date range → empty state with message
- EC-2: Date range too large (> 2 years) → warning about performance
- EC-3: Chart with too many data points → aggregated by week/month
- EC-4: Export with 100,000+ submissions → background job, email download link
- EC-5: Geographic data unavailable → shown as "Unknown"
- EC-6: Referrer data blocked by browser → shown as "Direct"
- EC-7: Form abandonment tracking disabled → section hidden
- EC-8: A/B testing not configured → section hidden
- EC-9: Conversion tracking not enabled → section hidden
- EC-10: Real-time analytics unavailable → data refreshed hourly

**Validation Rules:**
- Date range: valid start and end dates
- Export limit: max 100,000 records
- Report schedule: valid cron expression

**Security Considerations:**
- Analytics visible only to authorized roles
- IP addresses anonymized in reports (last octet masked)
- No PII in exported data
- Export action logged in audit log

**Responsive Design:**
- Mobile (375px): Stacked charts, simplified metrics
- Tablet (768px): 2-column layout
- Desktop (1024px+): Multi-column dashboard with interactive charts

**Performance:**
- Dashboard load: < 2 seconds
- Chart render: < 1 second
- Export: < 10 seconds for 10,000 records
- Real-time updates: < 1 second (if enabled)

**UX Considerations:**
- Interactive charts with tooltips
- Drill-down capability (click chart to see details)
- Comparison mode (compare two periods)
- Customizable dashboard (drag-and-drop widgets)
- Saved views (save filter combinations)
- Color-coded metrics (green for good, red for bad)
- Trend indicators (up/down arrows)
- Goal progress bars

---

## US-CONTACT-7.7 🟡 Contact Form A/B Testing
**As a** Super Admin or Admin, **I want to** run A/B tests on the contact form, **so that** I can optimize conversion rates and user experience.

**Acceptance Criteria:**
1. **A/B Test Creation:**
   - Create new A/B test from Settings → Contact Form → A/B Testing
   - Test name and description
   - Start and end dates
   - Traffic split: 50/50, 60/40, 70/30, 80/20, 90/10
2. **Variant Configuration:**
   - Variant A (Control): Current form configuration
   - Variant B (Test): Modified form configuration
   - Configurable elements:
     - Form fields (add/remove/reorder)
     - Field labels and placeholders
     - Button text and color
     - Success message
     - Form layout (single column, two column)
     - Validation messages
3. **Test Metrics:**
   - Primary metric: Conversion rate (submissions / form views)
   - Secondary metrics: Form abandonment rate, time to complete, field errors
   - Statistical significance calculator
   - Confidence level: 95%, 99%
4. **Traffic Allocation:**
   - Random assignment of visitors to variants
   - Consistent experience (same visitor always sees same variant)
   - Cookie-based tracking (30-day expiration)
5. **Test Monitoring:**
   - Real-time test results dashboard
   - Submissions per variant
   - Conversion rate per variant
   - Statistical significance indicator
   - Winner declaration (when significance reached)
6. **Test Actions:**
   - Pause test (stop assigning new visitors)
   - Resume test
   - End test early
   - Declare winner manually
   - Apply winner to all traffic
7. **Historical Tests:**
   - View past A/B tests
   - Test results and insights
   - Winning variants
   - Lessons learned notes
8. **Multi-Variant Testing:**
   - Test up to 5 variants simultaneously
   - Traffic split evenly or custom
   - Compare all variants
9. **Segmentation:**
   - Test specific segments (e.g., mobile vs desktop)
   - Test specific traffic sources
   - Test specific languages
10. **Integration:**
    - Google Optimize integration (optional)
    - Custom analytics integration
    - Webhook for test events

**Edge Cases:**
- EC-1: Test with insufficient traffic → warning about time to significance
- EC-2: Test ended before significance → results marked as inconclusive
- EC-3: Variant performs significantly worse → option to end test early
- EC-4: Cookie deleted → visitor may see different variant
- EC-5: Test overlaps with another test → warning about interaction effects
- EC-6: Variant configuration invalid → validation error before test starts
- EC-7: Traffic split doesn't add to 100% → validation error
- EC-8: Test duration too short (< 7 days) → warning about seasonality
- EC-9: Test duration too long (> 90 days) → warning about external factors
- EC-10: Winner declared but not applied → reminder notification

**Validation Rules:**
- Test name: required, max 100 characters
- Start date: must be in future or today
- End date: must be after start date
- Traffic split: must add to 100%
- Variants: minimum 2, maximum 5

**Security Considerations:**
- Only Super Admin and Admin can create tests
- Test results visible to authorized roles only
- Visitor tracking respects privacy settings
- No PII in test data

**Responsive Design:**
- Test dashboard responsive on all devices
- Variant preview on mobile, tablet, desktop

**Performance:**
- Variant assignment: < 50ms
- Test results calculation: < 1 second
- Dashboard load: < 2 seconds

**UX Considerations:**
- Visual variant comparison
- Clear winner indication
- Statistical significance explained
- Recommendations based on results
- One-click winner application

---

## US-CONTACT-7.8 🟢 Contact Form Integrations (Phase 2)
**As a** Super Admin or Admin, **I want to** integrate the contact form with third-party tools, **so that** submissions are automatically synced to our CRM, marketing, and support systems.

**Acceptance Criteria:**
1. **CRM Integrations:**
   - Salesforce: Create lead/contact from submission
   - HubSpot: Create contact and deal
   - Zoho CRM: Create lead
   - Pipedrive: Create person and deal
   - Custom CRM via API
2. **Marketing Integrations:**
   - Mailchimp: Add to mailing list
   - SendGrid: Add to contact list
   - ActiveCampaign: Create contact and trigger automation
   - ConvertKit: Add subscriber
3. **Support Integrations:**
   - Zendesk: Create ticket
   - Freshdesk: Create ticket
   - Intercom: Create conversation
   - Help Scout: Create conversation
4. **Slack Integration:**
   - Send notification to Slack channel
   - Customizable message format
   - Include submission details
   - Link to view in admin panel
5. **Webhook Integration:**
   - Send submission data to custom webhook URL
   - Configurable HTTP method (POST, PUT)
   - Custom headers and authentication
   - Retry logic for failed webhooks
6. **Zapier Integration:**
   - Trigger Zap on new submission
   - Connect to 3000+ apps
   - Pass submission data to Zap
7. **Google Sheets Integration:**
   - Append submission to Google Sheet
   - Real-time sync
   - Configurable column mapping
8. **Integration Configuration:**
   - Enable/disable each integration
   - API key/credentials management
   - Field mapping (form fields to integration fields)
   - Test integration button
   - Integration logs and error handling
9. **Conditional Integrations:**
   - Trigger integration based on subject
   - Trigger integration based on field values
   - Multiple integrations per submission
10. **Data Transformation:**
    - Transform data before sending to integration
    - Custom field mapping
    - Data enrichment (e.g., add company info from email domain)


**Edge Cases:**
- EC-1: Integration API down → submission stored, integration queued for retry
- EC-2: Integration API rate limit reached → queued for retry after cooldown
- EC-3: Integration authentication fails → admin notified, integration disabled
- EC-4: Field mapping incomplete → default values used or integration skipped
- EC-5: Duplicate submission to integration → deduplication logic prevents
- EC-6: Integration returns error → logged, admin notified, retry attempted
- EC-7: Webhook URL unreachable → retry up to 5 times, then mark as failed
- EC-8: Google Sheets quota exceeded → queued for next day
- EC-9: Slack channel deleted → integration fails, admin notified
- EC-10: Multiple integrations configured → all triggered in parallel

**Validation Rules:**
- API keys: required for each integration
- Webhook URL: valid HTTPS URL
- Field mapping: at least 1 field mapped
- Integration name: max 100 characters

**Security Considerations:**
- API keys encrypted at rest
- API keys never exposed in logs or UI
- Webhook payloads signed for verification
- Integration data transmitted over HTTPS only
- Integration logs sanitized (no sensitive data)

**Responsive Design:**
- Integration settings accessible on all devices
- Mobile view shows simplified configuration

**Performance:**
- Integration trigger: < 2 seconds per integration
- Parallel integrations: all triggered simultaneously
- Retry logic: exponential backoff

**UX Considerations:**
- One-click integration setup
- Pre-configured templates for popular integrations
- Test button for each integration
- Integration status indicators
- Clear error messages with troubleshooting steps
- Integration logs with filtering

---

## US-CONTACT-7.9 🟢 Multi-Step Contact Form (Phase 2)
**As a** website visitor, **I want to** fill out a multi-step contact form, **so that** the form feels less overwhelming and I can provide more detailed information.

**Acceptance Criteria:**
1. **Form Steps:**
   - Step 1: Basic Information (Name, Email, Phone)
   - Step 2: Inquiry Details (Subject, Message)
   - Step 3: Additional Information (Company, Budget, Timeline)
   - Step 4: Review and Submit
2. **Step Navigation:**
   - "Next" button to proceed to next step
   - "Back" button to return to previous step
   - Step indicator showing current step (e.g., "Step 2 of 4")
   - Progress bar showing completion percentage
3. **Step Validation:**
   - Each step validated before proceeding
   - Cannot proceed to next step with errors
   - Errors shown inline on current step
4. **Data Persistence:**
   - Form data saved in localStorage as user progresses
   - Data restored if user navigates away and returns
   - Data cleared after successful submission
5. **Conditional Steps:**
   - Show/hide steps based on previous answers
   - Example: If subject is "Project Request", show budget and timeline step
6. **Step Customization:**
   - Admin can add/remove/reorder steps
   - Admin can configure fields per step
   - Admin can set conditional logic
7. **Mobile Optimization:**
   - One step per screen on mobile
   - Swipe gestures to navigate steps
   - Touch-friendly buttons
8. **Accessibility:**
   - Keyboard navigation between steps
   - Screen reader announces current step
   - Focus management on step change
9. **Analytics:**
   - Track step completion rate
   - Identify drop-off points
   - Average time per step
10. **A/B Testing:**
    - Test different step configurations
    - Test single-step vs multi-step

**Edge Cases:**
- EC-1: User refreshes page mid-form → data restored from localStorage
- EC-2: User closes browser → data persisted for 24 hours
- EC-3: User submits with JavaScript disabled → fallback to single-step form
- EC-4: Conditional step hidden → data from that step cleared
- EC-5: User clicks "Back" multiple times → returns to first step
- EC-6: User clicks "Next" on last step → submits form
- EC-7: Validation error on previous step → cannot proceed until fixed
- EC-8: localStorage full → warning shown, data not persisted
- EC-9: User has multiple tabs open → data synced across tabs
- EC-10: Form configuration changed while user filling → user sees old version

**Validation Rules:**
- Each step has its own validation rules
- All steps must be valid before final submission
- Required fields enforced per step

**Security Considerations:**
- localStorage data not sensitive (no passwords)
- Data cleared after submission
- CSRF token validated on final submission

**Responsive Design:**
- Mobile: Full-screen steps, swipe navigation
- Tablet: Centered steps, button navigation
- Desktop: Centered steps with progress sidebar

**Performance:**
- Step transition: < 200ms
- Data persistence: < 50ms
- Form load: < 1 second

**UX Considerations:**
- Smooth step transitions
- Clear progress indication
- Helpful step titles and descriptions
- "Save and Continue Later" option
- Estimated time to complete
- Step summary on review step

---

## US-CONTACT-7.10 🟢 Contact Form Chatbot Integration (Phase 2)
**As a** website visitor, **I want to** interact with a chatbot before filling the contact form, **so that** I can get instant answers to common questions and only submit the form if needed.

**Acceptance Criteria:**
1. **Chatbot Widget:**
   - Floating chat icon in bottom-right corner
   - Click to open chat window
   - Minimizable and closable
   - Unread message indicator
2. **Chatbot Conversation:**
   - Welcome message: "Hi! How can we help you today?"
   - Pre-defined quick replies (e.g., "I have a question", "I want a quote")
   - Natural language processing for user messages
   - Contextual responses based on user input
3. **FAQ Integration:**
   - Chatbot answers common questions from FAQ database
   - Suggests related articles
   - Links to relevant pages
4. **Form Handoff:**
   - If chatbot can't answer, offers to connect with human
   - "Fill out contact form" button
   - Pre-fills form with conversation context
5. **Live Chat Option:**
   - If admin online, offer live chat
   - Real-time messaging with admin
   - Typing indicators
   - Read receipts
6. **Chatbot Configuration:**
   - Admin can configure chatbot responses
   - Add/edit/delete FAQ entries
   - Set business hours for live chat
   - Customize chatbot personality and tone
7. **Conversation History:**
   - Conversations saved in database
   - Admin can view conversation history
   - Conversations linked to contact submissions
8. **Analytics:**
   - Track chatbot usage
   - Common questions asked
   - Resolution rate (questions answered by bot)
   - Handoff rate (conversations escalated to form/human)
9. **Multilingual Support:**
   - Chatbot responds in user's language
   - Language detection from browser or user selection
10. **Integration:**
    - Integrate with Intercom, Drift, Crisp, or custom chatbot
    - Webhook for chatbot events
    - API for custom chatbot logic

**Edge Cases:**
- EC-1: Chatbot doesn't understand question → offers to connect with human
- EC-2: User asks same question multiple times → chatbot rephrases answer
- EC-3: User asks off-topic question → politely redirects to contact form
- EC-4: Chatbot offline (business hours) → shows offline message, offers form
- EC-5: User closes chat mid-conversation → conversation saved, can resume
- EC-6: User opens chat on multiple devices → conversation synced
- EC-7: Chatbot API down → fallback to contact form
- EC-8: User sends very long message → truncated with warning
- EC-9: User sends spam/abusive message → blocked, admin notified
- EC-10: Chatbot suggests wrong answer → user can provide feedback

**Validation Rules:**
- User message: max 1000 characters
- Conversation history: retained for 90 days

**Security Considerations:**
- User messages sanitized to prevent XSS
- Chatbot responses sanitized
- No sensitive data in chatbot logs
- Rate limiting to prevent abuse

**Responsive Design:**
- Mobile: Full-screen chat window
- Tablet: Floating chat window
- Desktop: Floating chat window with larger size

**Performance:**
- Chatbot response: < 1 second
- Message send: < 500ms
- Conversation load: < 500ms

**UX Considerations:**
- Friendly, conversational tone
- Quick replies for common questions
- Typing indicators for bot responses
- Smooth animations
- Emoji support
- File attachment support (optional)
- Voice input support (optional)

---

## Summary

The Contact Form & Communication module includes 10 comprehensive user stories covering all aspects of contact form functionality, email delivery, analytics, and advanced features:

| Story | Priority | Description |
|-------|----------|-------------|
| US-CONTACT-7.1 | 🔴 Critical | Submit contact form from public website |
| US-CONTACT-7.2 | 🔴 Critical | View and manage contact submissions (admin) |
| US-CONTACT-7.3 | 🟠 High | Configure contact form email recipients |
| US-CONTACT-7.4 | 🟠 High | Email delivery queue and retry logic |
| US-CONTACT-7.5 | 🟠 High | Auto-reply to contact form submissions |
| US-CONTACT-7.6 | 🟡 Medium | Contact form analytics and reporting |
| US-CONTACT-7.7 | 🟡 Medium | Contact form A/B testing |
| US-CONTACT-7.8 | 🟢 Nice-to-have | Contact form integrations (Phase 2) |
| US-CONTACT-7.9 | 🟢 Nice-to-have | Multi-step contact form (Phase 2) |
| US-CONTACT-7.10 | 🟢 Nice-to-have | Contact form chatbot integration (Phase 2) |

**Key Features:**
- Comprehensive form validation (frontend and backend)
- Rate limiting (5 submissions per minute per IP)
- SMTP email delivery to multiple recipients
- Email queue with retry logic
- Auto-reply functionality
- Spam protection (honeypot, time tracking, IP blocking)
- Bilingual support (Arabic and English)
- Responsive design for all devices
- Accessibility compliance (WCAG 2.1 AA)
- Analytics and reporting
- A/B testing capabilities
- Third-party integrations (CRM, marketing, support tools)
- Multi-step form option
- Chatbot integration

**Security Priorities:**
- XSS prevention (input sanitization)
- SQL injection prevention (parameterized queries)
- CSRF protection
- Rate limiting to prevent spam
- Honeypot field for bot detection
- IP-based blocking
- Email validation to prevent header injection
- Encrypted SMTP credentials
- Audit logging for all actions

**Phase 1 Scope:**
- Stories 7.1 through 7.5 (core contact form functionality)
- Basic analytics (7.6) if time permits

**Phase 2 Scope:**
- Advanced analytics and A/B testing (7.6, 7.7)
- Third-party integrations (7.8)
- Multi-step form (7.9)
- Chatbot integration (7.10)

**Technical Requirements:**
- Laravel backend with Eloquent ORM
- Next.js frontend with SSR
- SMTP email delivery (Gmail, SendGrid, AWS SES, Mailgun)
- Email queue with Laravel Queue
- Redis for rate limiting and caching
- MySQL database for submissions and logs
- Real-time validation with JavaScript
- Responsive CSS with mobile-first approach
- Accessibility features (ARIA labels, keyboard navigation)
- Analytics tracking (Google Analytics, custom events)

**Testing Requirements:**
- Unit tests for validation logic
- Integration tests for email delivery
- Feature tests for form submission
- Security tests for XSS and SQL injection
- Rate limiting tests
- Spam protection tests
- Email queue tests
- Auto-reply tests
- Analytics tests
- A/B testing tests

**Performance Targets:**
- Form load: < 500ms
- Form submission: < 2 seconds
- Email delivery: < 30 seconds
- Admin dashboard load: < 1 second
- Analytics dashboard load: < 2 seconds
- Real-time validation: < 100ms

**Accessibility Requirements:**
- WCAG 2.1 AA compliance
- Keyboard navigation support
- Screen reader compatibility
- Proper ARIA labels and roles
- Focus management
- Error announcements
- Color contrast ratio ≥ 4.5:1
- Touch target size ≥ 44px

**Monitoring and Alerts:**
- Email delivery success rate
- Form submission rate
- Spam detection rate
- Queue health
- SMTP connection status
- Failed email alerts
- High spam rate alerts
- Queue size alerts
- Response time alerts

**Documentation Requirements:**
- User guide for filling contact form
- Admin guide for managing submissions
- Configuration guide for email settings
- Integration guide for third-party tools
- API documentation for webhooks
- Troubleshooting guide for common issues
- Security best practices guide

This comprehensive contact form system provides a robust foundation for capturing and managing customer inquiries, with extensive features for spam protection, email delivery, analytics, and future enhancements.
