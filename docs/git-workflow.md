# Git Workflow and Branching Strategy

This document outlines the Git workflow and branching strategy for the TFN Artist project. Following these guidelines will help maintain a clean and organized repository, facilitate collaboration, and ensure code quality.

## Branching Strategy

We follow a modified version of the [GitFlow](https://nvie.com/posts/a-successful-git-branching-model/) branching model, adapted for our specific needs.

### Main Branches

- **main** - The production branch. This branch always reflects the current production state.
- **develop** - The development branch. This is where all feature branches are merged into and serves as the integration branch.

### Supporting Branches

- **feature/** - For developing new features
- **bugfix/** - For fixing bugs
- **hotfix/** - For critical fixes that need to be applied directly to production
- **release/** - For preparing releases
- **docs/** - For documentation changes only

## Branch Naming Conventions

Branches should be named using the following convention:

```
<type>/<issue-number>-<short-description>
```

Where:
- `<type>` is one of: feature, bugfix, hotfix, release, docs
- `<issue-number>` is the ticket/issue number (if applicable)
- `<short-description>` is a brief, hyphenated description of the change

Examples:
- `feature/123-add-user-authentication`
- `bugfix/456-fix-login-validation`
- `hotfix/789-security-vulnerability`
- `release/v1.2.0`
- `docs/update-api-documentation`

## Workflow for Feature Development

1. **Create a new branch**
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/123-add-user-authentication
   ```

2. **Make changes and commit regularly**
   ```bash
   git add .
   git commit -m "Add user authentication controller"
   ```

3. **Keep your branch up to date with develop**
   ```bash
   git checkout develop
   git pull origin develop
   git checkout feature/123-add-user-authentication
   git merge develop
   ```

4. **Push your branch to the remote repository**
   ```bash
   git push origin feature/123-add-user-authentication
   ```

5. **Create a Pull Request (PR)**
   - Create a PR from your feature branch to the develop branch
   - Fill in the PR template with details about your changes
   - Request reviews from team members

6. **Address review feedback**
   - Make necessary changes based on review feedback
   - Push additional commits to your branch

7. **Merge the PR**
   - Once approved, merge your PR into the develop branch
   - Delete the feature branch after merging

## Pull Request Requirements

All PRs must meet the following requirements before being merged:

1. **Code Review**: At least one approval from a team member
2. **Tests**: All tests must pass
3. **Code Style**: Code must adhere to the project's style guidelines
4. **Documentation**: Changes must be documented appropriately
5. **No Conflicts**: PR must be up to date with the target branch and have no conflicts

## Release Process

1. **Create a release branch**
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b release/v1.2.0
   ```

2. **Prepare the release**
   - Update version numbers in relevant files
   - Update CHANGELOG.md
   - Make any final adjustments

3. **Test the release**
   - Ensure all tests pass
   - Perform any necessary manual testing

4. **Merge to main and develop**
   ```bash
   # Merge to main
   git checkout main
   git pull origin main
   git merge release/v1.2.0
   git push origin main
   
   # Create a tag for the release
   git tag -a v1.2.0 -m "Version 1.2.0"
   git push origin v1.2.0
   
   # Merge back to develop
   git checkout develop
   git pull origin develop
   git merge release/v1.2.0
   git push origin develop
   
   # Delete the release branch
   git branch -d release/v1.2.0
   git push origin --delete release/v1.2.0
   ```

## Hotfix Process

For critical issues that need to be fixed in production:

1. **Create a hotfix branch from main**
   ```bash
   git checkout main
   git pull origin main
   git checkout -b hotfix/789-security-vulnerability
   ```

2. **Fix the issue and commit**
   ```bash
   git add .
   git commit -m "Fix security vulnerability"
   ```

3. **Merge to main and develop**
   ```bash
   # Merge to main
   git checkout main
   git pull origin main
   git merge hotfix/789-security-vulnerability
   git push origin main
   
   # Create a tag for the hotfix
   git tag -a v1.2.1 -m "Version 1.2.1"
   git push origin v1.2.1
   
   # Merge to develop
   git checkout develop
   git pull origin develop
   git merge hotfix/789-security-vulnerability
   git push origin develop
   
   # Delete the hotfix branch
   git branch -d hotfix/789-security-vulnerability
   git push origin --delete hotfix/789-security-vulnerability
   ```

## Best Practices

1. **Commit Messages**: Write clear, concise commit messages that explain what changes were made and why.
   ```
   # Good
   Add user authentication controller
   
   - Implement login and registration endpoints
   - Add validation for user input
   - Create authentication middleware
   
   # Bad
   Fixed stuff
   ```

2. **Commit Frequency**: Commit small, logical chunks of work rather than large changes.

3. **Keep Branches Updated**: Regularly merge changes from the develop branch into your feature branch.

4. **Clean Up Branches**: Delete branches after they've been merged.

5. **Code Review**: Be thorough but constructive in code reviews.

6. **Documentation**: Update documentation as part of your changes, not as an afterthought.

7. **Testing**: Write tests for new features and ensure all tests pass before submitting a PR.

## Git Hooks

Consider using Git hooks to automate certain tasks:

- **pre-commit**: Run linters and formatters
- **pre-push**: Run tests

You can use tools like [Husky](https://github.com/typicode/husky) to manage Git hooks.

## Continuous Integration

We use GitHub Actions for continuous integration. The CI pipeline runs on every push and PR, and performs the following checks:

- Linting
- Static analysis
- Unit tests
- Feature tests

PRs cannot be merged if the CI pipeline fails.