#include <iostream>
#include "StudentSystem.h"

int main() {

Student* listStudents = getStudents();
int count = getStudentCount();

int choice;
string numInput;
string lnameInput;
string fnameInput;

do {
    cout << "\n====BSIT 12 M1 STUDENT LIBRARY SYSTEM ====\n";
    cout << "1. Search by Last Name and First Name\n";
    cout << "2. Search by Student Number\n";
    cout << "3. Open Student Master List\n";
    cout << "4. Exit\n";
    cout << "Enter a number: ";
    cin >> choice;

    //Outputs search by last name and first name
    if (choice == 1) {
        cout << "Enter Last Name: ";
        cin >> lnameInput;

        cout << "Enter First Name: ";
        cin >> fnameInput;

        bool Found = false;

        for (int i = 0; i < count; i++) {
            //finds last name and first name from student list array
            if (listStudents[i].getLastName() == lnameInput &&
                listStudents[i].getFirstName() == fnameInput) {
                Found = true;
                //if found, it displays the output
                listStudents[i].display();
            }
        }

        //error if last name or first name was misinput
        if (Found == false){
            cout << "No student found.\n";
        }
    }

    //Outputs search by student number
    else if (choice == 2) {
        cout << "Enter Student Number: ";
        cin  >> numInput;

        bool found = false;

        //finds student number from student list array
        for (int i = 0; i < count; i++) {
            if (listStudents[i].getStudentNumber() == numInput) {
                listStudents[i].display();
                found = true;
            }
        }

        //error if student number was misinput
        if (!found){
            cout << "No student found.\n";
        }
    }
//---------------------------------------------------------------
    //Outputs student master list
    else if (choice == 3) {
        cout << "\n=== ALL STUDENTS ===\n";

        for (int i = 0; i < count; i++) {
            listStudents[i].masterlistDisplay();
        }
    }

//clears the console if looping errors are occured
cin.clear();
cin.ignore(1000, '\n');

} while (choice != 4);

//Outputs exit screen when number 4 is chosen
cout << "\nExiting program...\n";

    return 0;
}








































//FOR MY Bud Brook, V. Veron
//BY Christ, D. Haino
//#SupBro XD, is the code good?
//Heather was here
//Finished finalizing ts around 4:10 am, it was worth it :3
