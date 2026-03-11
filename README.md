Survey System (local_survey)
Overview

local_survey is a Moodle local plugin that allows administrators to create surveys and collect responses from students within the Moodle platform. The plugin provides a simple way to create surveys with multiple questions and gather feedback directly inside the LMS.

Features

Create, edit, and delete surveys

Add multiple questions to a survey

Students can participate in active surveys

Teachers and managers can view survey responses

Capability-based access control for managing surveys and viewing results

Integrated with Moodle navigation and user interface

Project Background

This plugin was developed as part of an internal school LMS project built on Moodle. The goal was to provide a simple tool for collecting feedback from students without relying on external survey systems.

Only the parts of the project that I personally worked on are included here as a code sample.

My Role in the Project

During the development of this plugin, I worked on:

Designing the plugin structure following Moodle plugin development standards

Implementing survey creation and question management

Developing the student interface for submitting survey responses

Handling database operations using Moodle’s $DB API

Adding capability checks to control access to survey management and results

Debugging and testing the plugin functionality

Installation
Install using ZIP

Log in to Moodle as an administrator.

Go to Site administration → Plugins → Install plugins.

Upload the plugin ZIP file.

Follow the instructions to complete the installation.

Manual Installation

Copy the plugin folder into:

{moodle}/local/survey

Log in as an administrator and visit:

Site administration → Notifications

Moodle will detect the plugin and complete the installation.

Alternatively, run:

php admin/cli/upgrade.php
Technologies Used

PHP

Moodle Plugin APIs

Moodle Database API

MySQL / MariaDB

JavaScript / jQuery

HTML / CSS

AI Usage

AI tools were used only to help with documentation and formatting before publishing the code. The plugin structure and functionality were implemented by me.

Author

Sinsila Ansar
PHP / Moodle Developer
