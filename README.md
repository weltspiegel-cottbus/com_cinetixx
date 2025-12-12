# Cinetixx Component

Joomla 5/6 component for integrating with the Cinetixx API to display movies and showtimes.

## Description

This component provides movie and showtime management by fetching data from the Cinetixx API. It includes models, views, and layouts for displaying cinema events on your Joomla site.

## Requirements

- Joomla 5.0 or higher (tested with Joomla 6)
- PHP 8.1 or higher
- Cinetixx API access (mandator ID required)

## Installation

### Manual Installation

1. Build the package:
   ```bash
   pnpm build
   ```

2. Upload the generated ZIP file via Joomla Administrator:
   - System → Extensions → Install
   - Upload `com_cinetixx-*.zip`

3. Configure the component:
   - Components → Cinetixx
   - Set your Mandator ID
   - Configure booking link type (new tab, same tab, or modal)

### Via GitHub Releases

Download the latest release ZIP from the GitHub releases page and install via Joomla Administrator.

### Automatic Updates

Once installed, the component can be updated automatically through Joomla's update system:
- System → Update → Extensions
- Joomla checks the update server configured in the component manifest
- New versions are automatically detected and can be installed with one click

The update manifest is hosted at:
`https://raw.githubusercontent.com/weltspiegel-cottbus/com_cinetixx/main/update-manifest.xml`

## Development

### Build Package

```bash
pnpm build
```

This creates a `com_cinetixx-{version}.zip` file ready for Joomla installation.

### Creating Releases

This project uses [changelogen](https://github.com/unjs/changelogen) for automated changelog generation and releases based on conventional commits.

**Prerequisites:** This project uses pnpm for package management. Install it with `npm install -g pnpm` if you don't have it.

#### Commit Message Format

Follow conventional commits format:

```
<type>: <description>

[optional body]
```

**Types:**
- `feat:` New feature (bumps minor version)
- `fix:` Bug fix (bumps patch version)
- `perf:` Performance improvement (bumps patch version)
- `refactor:` Code refactoring (bumps patch version)
- `docs:` Documentation changes
- `build:` Build system changes
- `chore:` Maintenance tasks
- `ci:` CI/CD changes
- `style:` Code style changes
- `test:` Test changes

**Examples:**
```bash
git commit -m "feat: add booking modal option"
git commit -m "fix: correct event filtering logic"
git commit -m "docs: update installation instructions"
```

#### Release Commands

Install dependencies first:
```bash
pnpm install
```

**Before releasing**, update version numbers and create SQL migration:

1. **`cinetixx.xml`**:
   - Update the `<version>` tag to match the new version

2. **`update-manifest.xml`**:
   - Update the `<version>` tag to match the new version
   - Update the download URL to match the new version tag and filename

3. **`media/com_cinetixx/joomla.asset.json`**:
   - Update the `version` field to match the new version

4. **`administrator/components/com_cinetixx/sql/updates/{version}.sql`**:
   - Create a new SQL migration file named with the new version (e.g., `0.5.0.sql`)
   - For releases without database changes, use: `# Empty - No database updates`
   - This ensures Joomla's version tracking works correctly

5. Commit these changes

Then create a release:

```bash
# Patch release (0.4.0 -> 0.4.1) - for bug fixes
pnpm release:patch

# Minor release (0.4.0 -> 0.5.0) - for new features
pnpm release:minor

# Major release (0.4.0 -> 1.0.0) - for breaking changes
pnpm release:major
```

This will:
1. Generate/update `CHANGELOG.md`
2. Bump version in `package.json`
3. Create a git commit with the changes
4. Create a git tag (e.g., `v0.5.0`)
5. Push to GitHub
6. Create a GitHub release with changelog
7. Trigger GitHub Actions to build and attach the ZIP file

#### Manual Changelog Generation

To generate changelog without releasing:

```bash
pnpm changelog
```

### What Gets Packaged

The build script includes only the necessary files:
- `cinetixx.xml` - Component manifest
- `administrator/` - Backend component files
- `components/` - Frontend component files
- `media/` - JavaScript and asset files
- `changelog.xml` - Joomla changelog format

Excluded from package:
- `.idea/` - IDE files
- `.git/` - Git repository
- `node_modules/` - Dependencies
- `build.js`, `package.json` - Build files
- `update-manifest.xml` - Update manifest (hosted separately)
- `*.zip` - Previous builds

## Continuous Integration

### GitHub Actions

The component includes a GitHub Actions workflow (`.github/workflows/release.yml`) that automatically builds and attaches the ZIP file to releases.

#### How It Works

When you run `pnpm release:minor` (or patch/major):
1. **Changelogen** creates the GitHub release with changelog
2. **GitHub Actions** triggers automatically on release creation
3. **Workflow** builds the ZIP and attaches it to the release

The workflow:
1. Checks out the code
2. Installs dependencies with pnpm
3. Builds the installable ZIP package
4. Uploads the ZIP to the release created by changelogen

No manual intervention needed - just run the release command!

## Features

- **Event Management**: Fetches current movies from Cinetixx API
- **Showtime Display**: Shows upcoming showtimes for each movie
- **Booking Integration**: Configurable booking links (new tab, same tab, or modal)
- **Responsive Design**: Mobile-friendly layouts
- **Template Overrides**: Supports Joomla template overrides
- **Automatic Updates**: Self-updating via Joomla's update system

## License

MIT License - see LICENSE file for details

## Credits

Developed for Weltspiegel Cottbus cinema
