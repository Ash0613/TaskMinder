# TaskMinder

## Description
TaskMinder is a simple task management website designed to help you stay organized and manage your time effectively. With TaskMinder, you can add tasks, set goals, and receive reminders through SMS. The website also features a calendar where you can see your tasks and goal dates, marked with pointers and colored circles. Additional features include a journal and notes section for additional organization.

## Technologies Used
- HTML
- CSS
- JavaScript
- PHP
- MySQL
- SMS Gateway Service

## Features
- Home page contains this weeek's tasks and goal's list. And a calendar.
- Can add goals and tasks.
- Can view completed tasks and no.of achieved goals.
- Journaling and notes option.
- SMS is used for reminders.

  
## Database Setup
This project uses a MySQL database running on XAMPP. **Please note that the MySQL port number is set to 3308 in the code.** If your MySQL server is running on a different port, you will need to update the port number in the database connection code.

Here are the steps to set up your database:

1. Install XAMPP on your machine if you haven't already. You can download it from the official XAMPP website.
2. Start the XAMPP control panel and start the Apache and MySQL services.
3. Click on admin, for MySQL which will lead to a phpmyadmin site.
4. Create a new MySQL database for this project.
5. In the project code, update the database connection details. The servername should be "localhost" or if yor using different port number-"localhost:(portno)", and the username and password should be your MySQL username and password.

## Usage
Once you have the TaskMinder installed and the database set up, you can start using the application. Here's how:

1. **Start the XAMPP servers**: Open the XAMPP control panel and start the Apache and MySQL servers.
2. **SMS using twilio**: To set up, Video referrred: https://www.youtube.com/watch?v=obolAwbx388&t=496s
3. **Open the project in a web browser**: Navigate to `http://localhost/your_project_directory/getstarted.html` in your web browser. Replace `your_project_directory` with the name of the directory where you installed the Pet Adoption System. And if you are using XAMPP store the file inside htdocs.
   Here are some of the things you can do:
    - **Home page**: View the list of this weeks tasks and goals and also a calendar in the home page.
    -  **Add tasks**: Click on the 'Add Task' to  add tasks, view all your tasks and also view completed tasks.
    - **Add goals**: Click on the 'Add Goal' to  add goals, view all your goals and also view how many goals are active and how many no.of goals you've achieved.
    - **Get reminders**: send.php should be running continuosly, separately, since the schedulin is done by it.
    - **Write in journal**: Click on the 'Journal' to write your journal, for that particular day, and view it in "previous journals".
    - **Take notes**: Click on the 'Notes' to take notes, where you can edit or delete.
      
## Some Images
![Screenshot (210)](https://github.com/Ash0613/TaskMinder/assets/159044952/1abc5f69-2a62-4ea4-8802-2c02d7be1d14)
![Screenshot (212)](https://github.com/Ash0613/TaskMinder/assets/159044952/0d932ddf-4df5-4228-b505-7ed69cae14de)
![Screenshot (218)](https://github.com/Ash0613/TaskMinder/assets/159044952/f9c5adb7-ae76-407c-a3a8-97040d35030e)


## Contact
For more information or queries, please contact me at ashlin21122003@gmail.com.

