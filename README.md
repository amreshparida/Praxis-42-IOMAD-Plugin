# Praxis 42 - IOMAD Plugin
Author:  [Amaresh Parida](https://amareshparida.com)
Date: 11-07-2022
## Task Requirements
- Create a new plugin with 2 forms. The plugin is only available for logged in users, no access for guest users.

- Form 1 will accept the following inputs: First name, last name, username, email, password and once it is submitted it will create a new user. The user created can log in using their username and password.

- Form 2 will list all the courses assigned to the company (dropdown) and all the active users in the company (dropdown), course score and course completion date. Once the form is submitted it will records a course completion for the user with the selected course, selected user, completion date and score.

## Functionalities
- Form 1 will accept the following inputs: First name, last name, username, email, password and once it is submitted it will create a new user. 
- The user created can log in using their username and password.
- Form 2 will list all companies and courses, on selection of company and course, users enrolled to the selected course and selected company will list down, select user and fill final grade and completion date and submit the form. On submit form course will be marked for the specidied user with completion date time and final grades. 

## Tech
- PHP
- jQuery

## System Requirement
- Moodle IOMAD version 2020061514 (release 3.9) or later

## Installation of Plugin
```sh
Copy praxis_42 into moodle-IOMAD root directory/local/
$ cd {moodle-IOMAD root directory}
$ php admin/cli/upgrade.php
```

## Consideration
- Once the IOMAD framework has been installed, please create 2 new test companies and 2 new courses (they can be any format and do not require any content)
- Assign the course/enroll the user we are creating to a course.
- Course completion tracking should be enabled for created courses.
- Course is complete when ANY of the conditions are met should have set on Course Completion configuarton page.
- Course completion criteria should be enabled with Grade on Course Completion configuarton page. 
- - Praxis 42 local plugin is only available for site admin and company managers context. Not available for other users.
- Form 1: User registration is done as per the company selected on dashboard for site admin roel. For company managers user registration is to their company only.
- Form 1: Email address is not set as their usernames.
- Form 2: Company select will list all companies for site admin role. For company mangers it will list only their company.
- Form 2: Course select will list all companies for site admin role. For company managers it will list only course assigned to that company.


## Flow Diagram Form 1 User Registration

[![N](https://i.postimg.cc/yNX5rL4L/form1-moodle-drawio.png)]()

## Flow Diagram Form 2 Mark course completion 

[![N](https://i.postimg.cc/RhXSTZLn/form2-moodle-drawio-1.png)]()

## Praxis 42 Test usage
```sh
- Install and configure IOMAD 3.9 or later

- Create two companies i.e., Company One and Company Two

- Create two company mangers i.e., manager@companyone.com and manager@companytwo.com for Company One and Company Two respectively.

- Create two course Eg. Course 1 and Course 2 with enrollment type license and Enable course completion tracking.

- On couse completion configuration page set course completion criteria condition to ANY and enbale course completion by Grade citeria.

- Create a license for the course created above i.e., Course 1 and Course 2

- Under IOMAD course settings set is licensed and is shared for both of the above created courses, So that it could be shared with other company.

- Install Praxis 42 plugin

- Praxis 42 plugin will be visible on navigation.

- Create user on form 1 by filling first name, last name, username, email and password.

- A form 1 submit, a new user will be created for slected company.User registration is done as per the company selected on dashboard for site admin roel. For company managers user registration is to their company only.

- Now go to IOMAD Allocate license section and allocate the course access license to the created user.

- Now go to Praxis 42 plugin Form 2.Select company, course, user and fill final grade and course completion date and time.on submit it will mark course completion for the user with final grade and completion date and time. 
```

## Video Demo
<figure class="video_container">
  <video controls="true" allowfullscreen="true" poster="path/to/poster_image.png">
    <source src="https://about.gitlab.com/handbook/markdown-guide/html5-demo.mp4" type="video/mp4">
  </video>
 </figure>
 