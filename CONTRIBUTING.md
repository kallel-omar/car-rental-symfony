# Contributing to Car Rental Management System

Thank you for your interest in contributing to the Car Rental Management System! This document provides guidelines and instructions for contributing to the project.

## 🤝 Code of Conduct

Please be respectful and constructive in all interactions with other contributors and maintainers.

## 🐛 Reporting Bugs

Before creating bug reports, please check the issue list to avoid duplicates.

### When submitting a bug report, include:
- **Clear title and description** of the issue
- **Steps to reproduce** the problem
- **Expected behavior** vs. actual behavior
- **Screenshots or error messages** if applicable
- **Environment details** (PHP version, Symfony version, OS, browser)
- **Additional context** that might help

## 🚀 Suggesting Enhancements

Enhancement suggestions are welcome! When submitting an enhancement:
- Use a **clear and descriptive title**
- Provide a **detailed description** of the suggested feature
- Explain **why this feature would be useful**
- List some **examples** of how the feature would work
- Include **mockups or screenshots** if relevant

## 📝 Pull Request Process

### Before You Start
1. Fork the repository
2. Create a new branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes in small, logical commits
4. Write clear, descriptive commit messages

### Making Changes
- Follow the existing code style and conventions
- Ensure your code is well-commented
- Test your changes thoroughly
- Update documentation if needed
- Keep changes focused and avoid mixing unrelated features

### Submitting a Pull Request
1. Push your branch to your fork
2. Submit a pull request to the `main` branch
3. Fill out the pull request template with:
   - Description of changes
   - Related issues (use `#issue-number`)
   - Type of change (feature, bugfix, enhancement, etc.)
   - Testing performed
   - Screenshots (if UI changes)

### PR Guidelines
- One feature/fix per pull request
- Keep commits clean and logical
- Update the README.md if needed
- Link to related issues
- Ensure all discussions are resolved before merge

## 📋 Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/kallel-omar/car-rental-symfony.git
   cd car-rental-symfony
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Update with your local database and configuration

4. **Create database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Load fixtures (optional)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Start development server**
   ```bash
   symfony server:start
   ```

## 🧪 Testing

- Write tests for new features
- Run tests before submitting a PR:
  ```bash
  php bin/phpunit
  ```
- Ensure all tests pass

## 📚 Project Structure

```
src/
├── Controller/     # Application controllers
├── Entity/        # Doctrine ORM entities
├── Repository/    # Data access layer
├── Service/       # Business logic
└── Form/          # Symfony forms
```

## 🎨 Code Style

- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Keep methods small and focused
- Add comments for complex logic
- Use type hints in PHP 8+

## 💾 Commit Messages

Write clear, descriptive commit messages:
- Use the imperative mood ("Add feature" not "Added feature")
- Start with a capital letter
- Keep the first line under 50 characters
- Add a blank line, then detailed explanation if needed
- Reference issues: "Fixes #123" or "Relates to #456"

### Examples:
```
Add user authentication system
Create vehicle reservation form
Fix bug in reservation calculation
Update documentation for API
```

## 🔄 Review Process

- A maintainer will review your PR
- Changes may be requested
- Be open to feedback and suggestions
- Once approved, your PR will be merged

## 📄 License

By contributing, you agree that your contributions will be licensed under the project's license.

## ❓ Questions?

- Open an issue for questions
- Check existing documentation first
- Search closed issues for similar questions

## 🙏 Thank You

Thank you for contributing to make this project better!

---

**Happy Contributing! 🎉**
