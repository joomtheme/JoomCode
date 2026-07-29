# JoomCode

JoomCode is a Joomla content plugin that enhances standard `<pre><code>` blocks with GitHub-style syntax highlighting, line numbers, line wrapping, height limits, language labels, and an accessible copy button.

The plugin uses Joomla's namespaced plugin architecture, dependency injection service provider, typed content event, language files, Web Asset Manager, and the existing Joomla/Bootstrap administration UI. It does not replace Joomla layouts or load a separate UI framework.

## Requirements

- Joomla 5.x or 6.x
- PHP 8.1 or later
- A modern browser with JavaScript enabled

## Installation

Install `releases/plg_content_joomcode-1.1.0.zip` through **System → Install → Extensions**, then enable **Content - JoomCode** in the Plugins manager.

## Editor usage

Use the editor's code sample feature and paste the raw source code. JoomCode recognises common `language-*`, `lang-*`, `data-language`, and SyntaxHighlighter-style `brush:` language declarations.

Supported bundled syntax definitions:

- HTML/XML/markup
- PHP
- CSS
- Plain text

## Repository layout

- `package/` — installable Joomla plugin source
- `updates/` — Joomla update server and changelog XML files
- `docs/JED-CHECKLIST.md` — JED preparation notes

## Third-party code

PrismJS 1.30.0 is bundled under the MIT License. Its license is included at `package/media/js/vendor/prism/LICENSE`.

## License

JoomCode is licensed under the GNU General Public License version 2 or later. See `LICENSE.txt`.
