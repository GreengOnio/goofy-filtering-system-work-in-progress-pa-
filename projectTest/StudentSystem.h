#ifndef STUDENTSYSTEM_H
#define STUDENTSYSTEM_H

#include <iostream>
using namespace std;

class Student {
private:
    string firstName;
    string lastName;
    string studentNumber;
    string studentCourse;
    float  grade;
    float  gpa;

public:
    Student(string fn, string ln, string sn, string sc, float g, float gp);

    string getLastName();
    string getFirstName();
    string getStudentNumber();

    void masterlistDisplay();
    void display();
};

Student* getStudents();
int getStudentCount();

#endif
