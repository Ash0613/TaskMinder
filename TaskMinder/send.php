<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task and Goal Processor</title>
</head>
<body>
    <h1>Task and Goal Processor</h1>
  
    <script>
        // Runs the function every 5 minutes
        setInterval(function() {
            // Make an AJAX call to trigger the processing script
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "process_tasks_and_goals.php", true);
            xhr.send();
        }, 5 * 60 * 1000); // 5 minutes in milliseconds
    </script>
</body>
</html>
