#include "StudentSystem.h"

//deeclare variables, simplified
Student::Student(string fn, string ln, string sn, string sc, float g, float gp) {
    firstName     = fn;
    lastName      = ln;
    studentNumber = sn;
    studentCourse = sc;
    grade         = g;
    gpa           = gp;
}

//function to find last name from array
string Student::getLastName() {
    return lastName;
}

//function to first name from array
string Student::getFirstName() {
    return firstName;
}

//function to student number from array
string Student::getStudentNumber() {
    return studentNumber;
}

//function to display student's personal info and grades
void Student::display() {
    cout << "\nName: "         <<  firstName     << " "
                               <<  lastName      << endl;
    cout << "Student No: "     <<  studentNumber << endl;
    cout << "Student Course: " <<  studentCourse << endl;
    cout << "Grade: "          <<  grade         << endl;
    cout << "GPA: "            <<  gpa           << endl;
}

//functon to display all students' names, student number, and courses
void Student::masterlistDisplay(){
    cout << "\nName: "        << firstName << " "
                              << lastName      << endl;
    cout << "Student No: "    << studentNumber << endl;
    cout << "Student Course: " << studentCourse << endl;
}

//array list to add values for student function "student();"
Student listStudents[6] = {
    Student("Dexter",   "Escanilla", "2025-001", "BSIT",    90, 1.25),
    Student("Charlene", "Escanilla", "2025-002", "BS-ARCH", 98, 1.0) ,
    Student("Releena",  "Escalante", "2025-003", "BSIT",    97, 1.0) ,
    Student("Rachel",   "Arroyo",    "2025-004", "BSIT",    93, 1.25),
    Student("Xia",      "DeLeon",    "2025-005", "BSIT",    99, 1.0) ,
    Student("Lehvie",   "Bataclan",  "2025-006", "BSIT",    88, 1.75)
};

//function to display the chosen student information from array (per row)
Student* getStudents() {
    return listStudents;
}

//counter to traverse through the array of 5 values (first+last name, student number, course, grade, gpa)
int getStudentCount() {
    return 5;
}
