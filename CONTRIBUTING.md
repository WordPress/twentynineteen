# Contributing to Twenty Nineteen
Howdy, it’s really great you want to contribute to the new default theme for the WordPress 5.0 release! Before you dive in, here are a few pointers on how to contribute.

## How it works
For early development, Twenty Nineteen will remain on GitHub. Once it reaches a usable and stable state, the theme will be moved into WordPress Core and all development will happen in SVN and Trac. Until then, follow this document for guidance.

## Reporting an issue
Twenty Nineteen should have all issues reported on GitHub at https://github.com/WordPress/twentynineteen/. We are not using Trac for issue reporting until the theme is moved into WordPress Core.

## Best Practices
Whatever you add, make sure you follow the theme review handbook requirements here: https://make.wordpress.org/themes/handbook/review/required/.

### Commit Messages
Here are some good ideas for commit messages:
- Keep them to a one line summary.
- Keep them short (50 chars or less).
- Make them relevant to the commit.

## Commit Process
All changes happen through a pull request made by contributors, ideally associated with an issue. After you send your proposed changes, one of the committers will review and test. After that, we can merge the changes.

When you add a pull request, please also add in the description your WordPress.org username. We will then add it to CONTRIBUTORS.md. This is a running list of all contributors and essential to giving everyone that has helped make this project props in Core.

## Compling SCSS
Twenty Nineteen relies on [Sass](https://sass-lang.com/guide) which allows us to more easily share code between multiple stylesheets (`style.css`, `style-editor.css`, etc.).
To compile Sass files (`.scss`) use the built-in `npm` build tool. The build tool will make sure that your compiled CSS code stays in sync and has the correct formatting.

### Installation instructions
1. Using a command line interface, go to the “twentynineteen” directory `cd /my-compter/local-wordpress-install/wp-content/themes/twentynineteen`. 
2. Type `npm install` into the command line and press [return], to install all Node.js dependencies.
3. The dependencies may take a few minutes to download but once it completes, you’re done.

### Usage instructions
1. After making a change to a `.scss` file, run `npm run build` from within the theme directory to build the CSS files with your new changes.
2. You can also “watch” the theme directory for Sass changes and rebuild the CSS anytime a change occurs by running: `npm run watch`.
