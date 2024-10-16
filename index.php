<?php
$host = 'localhost';
$db = 'aptavis';
$user = 'root'; 
$pass = ''; 

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$projects = [];
$result = $mysqli->query("SELECT id, name, status, completion_progress FROM projects");
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$tasks = [];
$taskResult = $mysqli->query("SELECT id, name, status, project_id, weight FROM tasks");
while ($taskRow = $taskResult->fetch_assoc()) {
    $tasks[] = $taskRow;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tracker</title>
    <script src="scripts.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Left Panel - Project List -->
    <div class="flex">
        <div class="w-1/2 p-4">
            <button id="addProjectBtn" class="bg-blue-500 text-white py-2 px-4 rounded mb-4">Add Project</button>
            <button id="addTaskBtn" class="bg-green-500 text-white py-2 px-4 rounded mb-4">Add Task</button>

            <div id="projectList">
                <?php foreach ($projects as $project) : ?>
                    <div class="bg-white shadow rounded p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <h2 class="font-bold cursor-pointer" onclick="openProjectForm(<?= $project['id'] ?>)">
                                <?= $project['name'] ?>
                            </h2>
                            <button class="bg-green-500 text-white py-1 px-2 rounded" onclick="openTaskForm(<?= $project['id'] ?>)">+</button>
                        </div>
                        <p>Status: <span class="text-blue-500"><?= $project['status'] ?></span></p>
                        <p>Completion Progress: <span class="text-blue-500"><?= $project['completion_progress'] ?>%</span></p>

                        <h3>Tasks:</h3>
                        <ul>
                            <?php foreach ($tasks as $task) : ?>
                                <?php if ($task['project_id'] == $project['id']) : ?>
                                    <li>
                                        <p class="cursor-pointer" onclick="openTaskEditForm(<?= $task['id'] ?>, <?= $project['id'] ?>)">
                                            <?= $task['name'] ?>
                                        </p>
                                        <p>Status: <span class="text-blue-500"><?= $task['status'] ?></span></p>
                                        <p>Weight: <span class="text-blue-500"><?= $task['weight'] ?></span></p>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Panel - Add/Edit Project Form -->
        <div id="projectFormContainer" class="fixed top-0 right-0 w-80 h-full bg-white p-4 shadow-lg transform translate-x-full transition-transform duration-500">
            <div class="flex justify-end">
                <button id="closeForm" class="text-red-500">X</button>
            </div>
            <h2 class="font-bold mb-4">Add Project</h2>
            <form id="projectForm">
                <div class="mb-4">
                    <label for="projectName" class="block text-gray-700">Nama</label>
                    <input type="text" id="projectName" name="projectName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="projectStatus" class="block text-gray-700">Status</label>
                    <select id="projectStatus" name="projectStatus" class="w-full p-2 border rounded" required>
                        <option value="Draft">Draft</option>
                        <option value="In Progress">In Progress </option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="completionProgress" class="block text-gray-700">Completion Progress</label>
                    <input type="number" id="completionProgress" name="completionProgress" class="w-full p-2 border rounded" max="100" min="0" value="0" readonly>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="deleteProject" class="bg-red-500 text-white py-2 px-4 rounded">Hapus</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Right Panel - Add/Edit Task Form -->
        <div id="taskFormContainer" class="fixed top-0 right-0 w-80 h-full bg-white p-4 shadow-lg transform translate-x-full transition-transform duration-500">
            <div class="flex justify-end">
                <button id="closeTaskForm" class="text-red-500">X</button>
            </div>
            <h2 class="font-bold mb-4">Add Task</h2>
            <form id="taskForm">
                <input type="hidden" name="taskId" value="<?= $task['id'] ?>">
                <div class="mb-4">
                    <label for="taskName" class="block text-gray-700">Nama</label>
                    <input type="text" id="taskName" name="taskName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="taskStatus" class="block text-gray-700">Status</label>
                    <select id="taskStatus" name="taskStatus" class="w-full p-2 border rounded" required>
                        <option value="Draft">Draft</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskProject" class="block text-gray-700">Project</label>
                    <select id="taskProject" name="taskProject" class="w-full p-2 border rounded" required>
                        <?php foreach ($projects as $project) : ?>
                            <option value="<?= $project['id'] ?>"><?= $project['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskWeight" class="block text-gray-700">Bobot</label>
                    <input type="number" id="taskWeight" name="taskWeight" class="w-full p-2 border rounded" required>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="deleteTask" class="bg-red-500 text-white py-2 px-4 rounded">Hapus</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Right Panel - Edit Project Form -->
        <div id="editProjectFormContainer" class="fixed top-0 right-0 w-80 h-full bg-white p-4 shadow-lg transform translate-x-full transition-transform duration-500">
            <div class="flex justify-end">
                <button id="closeEditProjectForm" class="text-red-500">X</button>
            </div>
            <h2 class="font-bold mb-4">Edit Project</h2>
            <form id="editProjectForm" action="update_project.php" method="POST">
                <div class="mb-4">
                    <label for="editProjectName" class="block text-gray-700">Nama</label>
                    <input type="text" id="editProjectName" name="editProjectName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editProjectStatus" class="block text-gray-700">Status</label>
                    <select id="editProjectStatus" name="editProjectStatus" class="w-full p-2 border rounded" required>
                        <option value="Draft">Draft</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editCompletionProgress" class="block text-gray-700">Completion Progress</label>
                    <input type="number" id="editCompletionProgress" name="editCompletionProgress" class="w-full p- 2 border rounded" max="100" min="0" required>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="deleteProjectBtn" class="bg-red-500 text-white py-2 px-4 rounded">Hapus</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Right Panel - Edit Task Form -->
        <div id="editTaskFormContainer" class="fixed top-0 right-0 w-80 h-full bg-white p-4 shadow-lg transform translate-x-full transition-transform duration-500">
            <div class="flex justify-end">
                <button id="closeEditTaskForm" class="text-red-500">X</button>
            </div>
            <h2 class="font-bold mb-4">Edit Task</h2>
            <form id="editTaskForm" action="update_task.php" method="POST">
                <div class="mb-4">
                    <label for="editTaskName" class="block text-gray-700">Nama</label>
                    <input type="text" id="editTaskName" name="editTaskName" data-task-id="<?= $task['id'] ?>">
                </div>
                <div class="mb-4">
                    <label for="editTaskStatus" class="block text-gray-700">Status</label>
                    <select id="editTaskStatus" name="editTaskStatus" class="w-full p-2 border rounded" required>
                        <option value="Draft">Draft</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editTaskProject" class="block text-gray-700">Project</label>
                    <select id="editTaskProject" name="editTaskProject" class="w-full p-2 border rounded" disabled>
                        <?php foreach ($projects as $project) : ?>
                            <option value="<?= $project['id'] ?>"><?= $project['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editTaskWeight" class="block text-gray-700">Bobot</label>
                    <input type="number" id="editTaskWeight" name="editTaskWeight" class="w-full p-2 border rounded" required>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="deleteTaskBtn" class="bg-red-500 text-white py-2 px-4 rounded">Hapus</button>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>