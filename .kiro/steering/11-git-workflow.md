---
inclusion: always
priority: 12
---

# Git Workflow & Commit Standards

## Branch Strategy
- `main` — production-ready code
- `develop` — integration branch
- `feature/*` — new features
- `fix/*` — bug fixes
- `hotfix/*` — urgent production fixes

## Commit Message Format
Use semantic commit messages:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types
- `feat` — New feature
- `fix` — Bug fix
- `refactor` — Code refactoring (no behavior change)
- `style` — Formatting, missing semicolons, etc.
- `test` — Adding or updating tests
- `docs` — Documentation changes
- `chore` — Maintenance tasks, dependency updates
- `perf` — Performance improvements
- `security` — Security fixes

### Examples
```
feat(auth): add password reset functionality

Implement password reset flow with email verification.
Users can now request password reset via email.

Closes #123

---

fix(projects): resolve SQL injection in search

Replace string concatenation with parameterized query.
All search inputs now properly sanitized.

Security: Critical

---

refactor(services): extract email service from controller

Move email logic to dedicated EmailService class.
Improves testability and follows SRP.

---

test(client): add CRUD tests for client management

Cover create, read, update, delete operations.
Include validation and authorization tests.

---

style(frontend): fix RTL layout for services page

Adjust margins and text alignment for Arabic.
No functional changes.
```

## Commit Rules
- Commit often (after each working feature)
- One logical change per commit
- Write clear, descriptive messages
- Reference issue numbers when applicable
- Run tests before committing

## .gitignore
MUST include:
```
# Environment
.env
.env.*

# Dependencies
node_modules/
vendor/

# Build
dist/
build/
.next/

# Storage
storage/app/public/uploads/
storage/logs/

# IDE
.vscode/
.idea/
*.swp

# OS
.DS_Store
Thumbs.db

# Testing
coverage/
.phpunit.result.cache
```

## Pre-Commit Checklist
Before EVERY commit:
- [ ] All tests pass
- [ ] No TypeScript errors
- [ ] No ESLint warnings
- [ ] No hardcoded strings
- [ ] No console.log/dd()
- [ ] No hardcoded colors
- [ ] Files < 300 lines
- [ ] .env not staged

## Pull Request Guidelines
- Clear title and description
- Reference related issues
- Include screenshots for UI changes
- List breaking changes
- Update documentation if needed
- Ensure CI passes
- Request review from team

## Code Review Checklist
Reviewers check:
- [ ] Follows coding standards
- [ ] Tests included
- [ ] No security vulnerabilities
- [ ] No performance issues
- [ ] Documentation updated
- [ ] i18n complete (AR + EN)
- [ ] No hardcoded values
- [ ] Proper error handling

## Merge Strategy
- Squash and merge for feature branches
- Keep commit history clean
- Delete branch after merge
- Update local branches regularly

## Never Commit
- `.env` files
- Secrets or API keys
- Large binary files
- Generated files (build artifacts)
- IDE-specific files
- Database dumps
- Log files
- Uploaded user content
