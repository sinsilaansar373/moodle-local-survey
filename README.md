# Moodle Local Plugin – Survey (local_survey)

## Overview
`local_survey` is a Moodle local plugin that allows administrators to create surveys and collect responses from students within the Moodle platform. It provides a simple way to create surveys with multiple questions and gather feedback directly inside the LMS.

## Features
- Create, edit, and delete surveys
- Add multiple questions to a survey
- Students can participate in active surveys
- Teachers and managers can view survey responses
- Capability-based access control for managing surveys and viewing results
- Integrated with Moodle navigation and interface

## Project Background
This plugin was developed as part of an internal school LMS project built on Moodle. The goal was to provide a simple way to collect feedback from students without using external survey tools.

Only the components that I personally worked on are included here as a code sample.

## My Contribution
My work on this plugin included:

- Designing the plugin structure following Moodle plugin development standards
- Implementing survey creation and question management
- Developing the student interface for submitting survey responses
- Handling database operations using Moodle’s `$DB` API
- Implementing capability checks for proper access control
- Debugging and testing the plugin functionality

## Installation

### Install using ZIP
1. Log in to Moodle as an administrator.
2. Go to **Site administration → Plugins → Install plugins**.
3. Upload the plugin ZIP file.
4. Follow the on-screen instructions to complete the installation.

### Manual Installation
1. Copy the plugin folder into:

```
{your/moodle}/local/survey
```

2. Log in as an administrator and visit:

**Site administration → Notifications**

3. Moodle will automatically detect and install the plugin.

Alternatively, run:

```
php admin/cli/upgrade.php
```

## Technologies Used
- PHP
- Moodle Plugin APIs
- Moodle Database API
- MySQL / MariaDB
- JavaScript / jQuery
- HTML / CSS

## AI Usage Disclosure
AI tools were used only for improving documentation and formatting before publishing the code. The plugin structure and functionality were developed by me.

## Author
Sinsila Ansar  
PHP / Moodle Developer
