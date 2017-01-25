<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Students;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct()
    {

    }

    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Students::with('course')->get();
        return view('view_students', compact(['students']));
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV(Request $request)
    {
        /**
         * Parse student ids
         */
        $studentIds = explode(',', $request->input('ids'));

        /**
         * Format CSV data
         */
        $students = Students::findMany($studentIds);
        $headers = [
            'firstname' => 'forename',
            'surname' => 'surname',
            'email' => 'email',
            'universityName' => 'university',
            'courseName' => 'course'
        ];
        $data = $this->formatCSVData($students, $headers);

        /**
         * Generate CSV
         */
        $this->generateCsv($data, 'students.csv');

        /**
         * Die
         */
        die;
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendanceToCSV()
    {
        /**
         * Format CSV data
         */
        $courses = Course::all();
        $headers = [
            'course_name' => 'course name',
            'university' => 'university',
            'totalStudentsAmount' => 'total students',
        ];
        $data = $this->formatCSVData($courses, $headers);

        /**
         * Generate CSV
         */
        $this->generateCsv($data, 'students.csv');

        /**
         * Die
         */
        die;
    }

    private function formatCSVData($rows, $headers)
    {
        $formattedStudentData = [];

        // headers
        foreach ($headers as $header) {
            $formattedStudentData[0][] = ucwords($header);
        }

        // rows
        foreach ($rows as $index => $row) {
            foreach ($headers as $key => $header) {
                $formattedStudentData[$index + 1][$key] = $row->{$key};
            }
        }

        return $formattedStudentData;
    }

    /**
     * Generate .csv
     */
    private function generateCsv($data, $filename)
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    }
}
