# Contributing to Twenty Nineteen
Howdy, it’s really great you want to contribute to the new default theme for the WordPress 5.0 release! Before you dive in, here are a few pointers on how to contribute.

## How it works
For early development, Twenty Nineteen will remain on GitHub. Once it reaches a usable and stable state, the theme will be moved into WordPress Core and all development will happen in SVN and Trac. Until then, follow this document for guidance.

## Reporting an issue
Twenty Nineteen should have all issues reported on GitHub at https://github.com/WordPress/twentynineteen/. We are not using Trac for issue reporting until the theme is moved into WordPress Core.

## Testing a Pull Request
If you're using Git locally, you can test a pull request by pulling down the associated branch, creating a zip file of the contents, and uploading to your site. This repository includes all compiled files, so it should install just like any other uploaded theme. 

If you're not already using Git, you may benefit from installing the [GitHub desktop application](https://desktop.github.com). This will allow you to [download the repository in  one click](https://help.github.com/desktop/guides/contributing-to-projects/cloning-a-repository-from-github-to-github-desktop/), keep it in sync, and easily [switch between different pull requests](https://help.github.com/desktop/guides/contributing-to-projects/accessing-a-pull-request-locally/). Once a pull request is selected in the application, create a zip file of the whole repository, and upload it to your site to test.

## Submitting Fixes
To submit a fix, please [fork the repository](https://help.github.com/articles/fork-a-repo/) and submit a [pull request](https://help.github.com/articles/creating-a-pull-request/). In your pull request's  description, please explain your update and reference the associated issue you're fixing. 

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
