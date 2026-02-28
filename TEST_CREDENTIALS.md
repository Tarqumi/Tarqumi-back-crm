# Test Credentials Reference

This file contains all seeded user accounts and their credentials for testing the Tarqumi CRM system.

## User Accounts

### Super Admin
- **Email**: `admin@tarqumi.com`
- **Password**: `password`
- **Role**: Super Admin
- **Permissions**: Full control over entire system, can delete other Admins, can edit landing page

### CTO Founder
- **Email**: `cto@tarqumi.com`
- **Password**: `password`
- **Role**: Founder (CTO)
- **Permissions**: Can view CRM data, **CAN edit landing page** (only founder with this permission)

### CEO Founder
- **Email**: `ceo@tarqumi.com`
- **Password**: `password`
- **Role**: Founder (CEO)
- **Permissions**: Can view CRM data, CANNOT edit landing page

---

## Default Client

### Tarqumi (Default Client)
- **Name**: Tarqumi
- **Company**: Tarqumi
- **Email**: info@tarqumi.com
- **Phone**: +966-XX-XXX-XXXX
- **Website**: https://tarqumi.com
- **Industry**: Technology
- **Status**: Active
- **Special Note**: This client CANNOT be deleted (protected by business rules)

---

## Testing Notes

1. All user accounts are active and email-verified
2. All passwords are set to `password` for testing purposes
3. The default "Tarqumi" client is protected and cannot be deleted
4. Super Admin can perform all operations
5. CTO is the only founder role that can edit the landing page
6. CEO can view CRM data but cannot edit landing page

## Security Reminder

⚠️ **IMPORTANT**: These are test credentials for local development only. 
- Change all passwords before deploying to production
- Use strong, unique passwords for production accounts
- Never commit production credentials to version control
