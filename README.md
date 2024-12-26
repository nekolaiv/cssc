# CSS Student Standing Calculator

## Overview

This project allows users to input their college grades and calculates whether they qualify for Dean's Lister award.

## Features

- Grade input and calculation
- Honor roll qualification checks
- Admin and instructor management interfaces

## Installation - For Users

1. **Clone the repository:**

   ```bash
   git clone https://github.com/nekolaiv/cssc.git
   ```

2. **Move the project directory to XAMPP's `htdocs`:**

   ```bash
   mv student-standing /path/to/xampp/htdocs/
   ```

   Replace `/path/to/xampp/htdocs/` with your actual XAMPP `htdocs` directory path (usually something like `C:/xampp/htdocs/` on Windows).

3. **Navigate to the project directory:**
   ```bash
   cd /path/to/xampp/htdocs/cssc
   ```

4. **Import the SQL database:**

   - Open your web browser and go to `http://localhost/phpmyadmin`.
   - Create a new database (e.g., `css_system`).
   - Import the provided SQL file (`database.sql`) into the created database. You can find `database.sql` in the project folder.

5. **Ensure Internet Connectivity:**

   The system requires an active internet connection as it utilizes online resources for certain features and updates.

## Usage

- Start the XAMPP server.
- Open your web browser and go to `http://localhost/cssc`.

## Contributing - For Developers

We welcome contributions from the community! Here’s how you can help improve the project:

### Steps to Contribute

1. **Fork the Repository:**

   - Go to the [cssc repository](https://github.com/nekolaiv/cssc).
   - Click on the "Fork" button in the upper right corner of the page to create your own copy of the repository.

2. **Clone Your Fork:**

   - After forking, clone your copy of the repository to your local machine:
     ```bash
     git clone https://github.com/your-username/cssc.git
     ```
   - Replace `your-username` with your actual GitHub username.
   - Move the project directory to XAMPP or LAMPP folder (Refer to installation above on how to move the project).

3. **Create a New Branch:**

   - Navigate to the project directory:
     ```bash
     cd cssc
     ```
   - Create a new branch for your changes:
     ```bash
     git checkout -b your-feature-branch
     ```
   - Replace `your-feature-branch` with a descriptive name for your branch (e.g., `wip-admin`, `wip-staff`).

4. **Make Changes:**

   - Make your desired changes in the codebase. Ensure that your changes are well-documented and follow the existing coding style. (Refer to `conventions.txt` in the `z_text_files` folder from the root directory.)

5. **Commit Your Changes:**

   - Stage your changes:
     ```bash
     git add .
     ```
   - Commit your changes with a descriptive message:

     ```bash
     git commit -m "Add a brief description of your changes"
     ```

   - Make sure to follow commit message conventions in `conventions.txt`.

6. **Push to Your Fork:**

   - Push your changes back to your forked repository:
     ```bash
     git push origin your-feature-branch
     ```

7. **Create a Pull Request:**
   - Go to your forked repository on GitHub.
   - Click the "New Pull Request" button.
   - Select your feature branch from the dropdown, then click "Create Pull Request."
   - Add a title and description for your pull request, explaining your changes and why they should be merged.
   - Wait for code review and approval from the admin.

### Guidelines for Contributions

- **Issues:** Before you start working on a feature or bug, please check the [issues page](https://github.com/nekolaiv/cssc/issues) to see if someone is already working on it.
- **Code Quality:** Ensure that your code is clean and follows best practices.
- **Testing:** If applicable, add tests for your changes.
- **Documentation:** Update the documentation if your changes affect the way users interact with the project.

## Updating Your Fork from the Upstream Repository

This guide provides step-by-step instructions on how to update your forked repository with the latest changes from the original (upstream) repository.

### Prerequisites

- You have a forked repository on GitHub.
- You have Git installed on your local machine.
- You have cloned your forked repository to your local machine.

### Steps to Update Your Fork

1. Open Your Terminal

Navigate to your local repository using the terminal:

```bash
cd path/to/your/local/repo
```

2. Add Upstream Remote: Do this once only

If you haven't already added the upstream repository as a remote, run the following command:

```bash
git remote add upstream https://github.com/nekolaiv/cssc
```

`<URL-of-upstream-repo>` should be the URL of the main repository. You can find this URL on the original repo's GitHub page.

3. Fetch the Changes from Upstream

To retrieve the latest changes from the upstream repository, execute:

```bash
git fetch upstream
```

4. Merge Changes into Your Local Branch

Switch to your main branch (usually `main` or `master`):

```bash
git checkout main  # or master
```

Now, merge the upstream changes:

```bash
git merge upstream/main  # or upstream/master
```

5. Resolve Conflicts (if any)

If there are any conflicts, Git will indicate which files are conflicting. Open those files and manually resolve the conflicts. Look for conflict markers (`<<<<<<`, `======`, `>>>>>>`) and edit the code as needed.

After resolving conflicts, mark the files as resolved:

```bash
git add <specific-file-with-conflict> # or git add .
```

Then, complete the merge:

```bash
git commit -m "commit message"
```

6. Push Updates to Your Fork

Finally, push the updated changes to your fork on GitHub:

```bash
git push origin main  # or master
```

## Collaborations

All contributions must be made from branches other than the `main` branch. This ensures that the main codebase remains stable while we review contributions.

Thank you for considering contributing to our project! We appreciate your help in making this project better for everyone.

## License

This project is licensed under the MIT [LICENSE](LICENSE).

