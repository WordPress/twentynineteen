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

## Compling SCSS
Currently team is finalising the approach to which extent the SCSS will be used. So as of now use plain node-sass to compile the SCSS files to CSS. It can be installed from https://sass-lang.com/install.
Style.scss or style-editor.scss with following commands to watch and compile.

`sass --sourcemap=none --watch --style=expanded style.scss:style.css`

`sass --sourcemap=none --watch --style=expanded style-editor.scss:style-editor.css`

## Commit Process
All changes happen through a pull request made by contributors, ideally associated with an issue. After you send your proposed changes, one of the committers will review and test. After that, we can merge the changes.

When you add a pull request, please also add in the description your WordPress.org username. We will then add it to CONTRIBUTORS.md. This is a running list of all contributors and essential to giving everyone that has helped make this project props in Core.
