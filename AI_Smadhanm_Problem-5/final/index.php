<?php
//include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = processExcel($_FILES['inputFile']);
    $result = distributeMarks($data);
    $report = generateReport($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI-Powered Smart Mark Distribution</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Smart Mark Distribution System</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="inputFile">Upload Excel File:</label>
            <input type="file" name="inputFile" id="inputFile" required />
            <button type="submit">Submit</button>
        </form>

        <?php if (isset($report)): ?>
            <div class="results">
                <h2>Report</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Final Marks</th>
                            <th>SPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report as $student): ?>
                            <tr>
                                <td><?php echo $student['name']; ?></td>
                                <td><?php echo implode(', ', $student['finalMarks']); ?></td>
                                <td><?php echo $student['SPI']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php

// Sample data for students (static values)
function processExcel($file) {
    // Static data of students
    $data = [
        'students' => [
            ['name' => 'John Doe', 'scores' => [85, 92, 45], 'attendanceBonus' => 10, 'credits' => [3, 3, 2]],
            ['name' => 'Jane Smith', 'scores' => [55, 60, 40], 'attendanceBonus' => 8, 'credits' => [3, 3, 2]],
        ],
        'subjects' => [
            'subject1' => ['type' => 'theory', 'credits' => 3],
            'subject2' => ['type' => 'practical', 'credits' => 3],
            'subject3' => ['type' => 'both', 'credits' => 2],
        ],
    ];

    return $data;
}

// Function to distribute marks and calculate the SPI
function distributeMarks($data) {
    $students = $data['students'];
    $subjects = $data['subjects'];
    
    // For each student, distribute the bonus marks and calculate grades
    foreach ($students as $index => $student) {
        $students[$index]['finalMarks'] = [];
        foreach ($student['scores'] as $subjectIndex => $score) {
            // Implement bonus distribution and grading logic here
            // For example: Add attendance bonus, check for HoD bonus, Extra bonus, etc.
            $students[$index]['finalMarks'][] = $score + $student['attendanceBonus'];
        }

        // Calculate SPI after all marks are distributed
        $students[$index]['SPI'] = calculateSPI($students[$index], $subjects);
    }

    return $students;
}

// Function to calculate SPI
function calculateSPI($student, $subjects) {
    $totalGradePoints = 0;
    $totalCredits = 0;

    foreach ($student['finalMarks'] as $index => $marks) {
        $gradePoint = getGradePoint($marks);
        $credits = $subjects['subject' . ($index + 1)]['credits'];
        $totalGradePoints += $gradePoint * $credits;
        $totalCredits += $credits;
    }

    return $totalGradePoints / $totalCredits;
}

// Function to get grade point from marks
function getGradePoint($marks) {
    if ($marks >= 95) return 10;
    if ($marks >= 90) return 9.5;
    if ($marks >= 85) return 9;
    if ($marks >= 80) return 8.5;
    if ($marks >= 75) return 8;
    if ($marks >= 70) return 7.5;
    if ($marks >= 65) return 7;
    if ($marks >= 60) return 6.5;
    if ($marks >= 55) return 6;
    if ($marks >= 50) return 5.5;
    if ($marks >= 45) return 5;
    return 0;
}

// Function to generate report (without Excel, just return static data)
function generateReport($students) {
    // Just return the data in a format suitable for display in the webpage
    return $students;
}

