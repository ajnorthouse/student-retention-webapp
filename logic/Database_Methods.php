<?php
	class DatabaseMethods {

	    // default method layout:
        /*

        // [description of what the method does]
        public static function asdf(asdf $asdf): asdf
        {
            /*
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "";
                $sql = $conn->prepare($query);
                $sql->execute();
                $class = $sql->fetchAll();

                if ()
                    return FALSE;
            } catch (PDOException $e) {

            }
           /*
        }
        */



        // ==================================================================================================== //
        // ==================================================================================================== //
        // Universally used methods

        // Creates the conn variable for the below methods to use.
        public static function setConnVariable()
        {
            require(dirname(__FILE__, 2) . "\database\DatabaseVariables.php");
            $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }

        // Returns an array of courses a user is enrolled in; returns empty array if there are none.
        public static function getEnrolledCourses(string $userID): array
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT c.ID, c.crseID, c.sectNum, c.crseName FROM "
                    . "courses c, coursesusersroster cur WHERE c.ID=cur.crseID AND cur.userID=$userID";
                $sql = $conn->prepare($query);
                $sql->execute();
                return $sql->fetchAll();
            } catch (PDOException $e) {
                return array();
            }
        }

        // Returns an array of enrolled courses with their professors' names; returns empty array if there are none.
        public static function getEnrolledCoursesAndProfessorNames($userID): array
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT c.ID, c.crseID, c.sectNum, c.crseName, p.fname, p.lname "
                    . "FROM courses c, coursesusersroster cur1, coursesusersroster cur2, users p "
                    . "WHERE (c.ID=cur1.crseID AND cur1.userID=$userID) AND "
                    . "(cur1.crseID=cur2.crseID AND cur2.userID=p.ID AND p.isProf=1)";
                $sql = $conn->prepare($query);
                $sql->execute();
                return $sql->fetchAll();
            } catch (PDOException $e) {
                return array();
            }
        }

        // Attempts to insert row into "coursesusersroster"; returns TRUE for success, FALSE otherwise.
        public static function attemptCourseUserRelationshipInsertion(int $courseID, string $userID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO coursesusersroster VALUES ($courseID, $userID)";
                $sql = $conn->prepare($query);
                $sql->execute();
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }



        // ==================================================================================================== //
        // ==================================================================================================== //
        // Login methods

        // Attempts to log the user in; returns array of user data on success, empty array otherwise.
        public static function attemptLogin(string $username, string $password): array
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT * FROM users WHERE username='$username'";
                $sql = $conn->prepare($query);
                $sql->execute();
                $user = $sql->fetchAll();

                if (!empty($user)) {
                    if (password_verify($password, $user[0]['password'])) {
                        return $user[0];
                    }
                }
                return array();
            } catch (PDOException $e) {
                return array();
            }
        }




        // Register methods

        // Checks database for university / username combo; returns TRUE if exists, FALSE otherwise.
        public static function duplicateUsernameCheck(string $username, string $uniID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT * FROM users WHERE uniID=$uniID AND username='$username'";
                $sql = $conn->prepare($query);
                $sql->execute();

                if (empty($sql->fetchALL())) return FALSE;
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }

        // Attempts to insert the user into the database; returns TRUE for success, FALSE for failure.
        public static function attemptUserInsertion(string $username, string $password, string $uniID,
            string $fName, string $lName, string $isProf): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO users VALUES ".
                    "(NULL, $uniID, '$username', '$password', '$fName', '$lName', '$isProf')";
                $conn->exec($query);

                echo ($conn->lastInsertID());
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }



        // ==================================================================================================== //
        // ==================================================================================================== //
        // Add_Course database methods

        // Checks the database for a provided course ID; returns TRUE if it exists, FALSE otherwise.
		public static function checkCourseExists(int $courseNumber): bool
        {
			try {
                $conn = DatabaseMethods::setConnVariable();
				$query = "SELECT ID FROM courses WHERE ID=$courseNumber";
                $sql = $conn->prepare($query);
				$sql->execute();

				if (empty($sql->fetchAll()))
					return FALSE;
				return TRUE;
			} catch (PDOException $e) {
				return FALSE;
			}
		}

        // Checks database for user / course combo; returns FALSE if not enrolled, TRUE otherwise.
        public static function checkEnrollment(int $courseNumber, int $studentID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT * FROM coursesusersroster WHERE crseID=$courseNumber AND userID=$studentID";
                $sql = $conn->prepare($query);
                $sql->execute();

                if (!empty($sql->fetchAll()))
                    return TRUE;
                return FALSE;
            } catch (PDOException $e) {
                return TRUE;
            }
        }

        // Attempts to insert row into coursesusersroster; returns TRUE for success, FALSE for failure.
        public static function attemptStudentInsertion(string $courseNumber, string $userID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO coursesusersroster VALUES ($courseNumber, $userID)";
                $sql = $conn->prepare($query);
                $sql->execute();
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }




        // Chatbot_Methods database methods

        // This is where a method would go... IF IT HAD ONE!




        // Add_Data database methods

        // Checks database for course / question combo; returns TRUE if it exists, FALSE otherwise.
        public static function duplicateQACheck(string $question, string $answer, int $selectedCourse): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT * FROM questions WHERE crseID=$selectedCourse AND qtext='$question'";
                $sql = $conn->prepare($query);
                $sql->execute();

                if (empty($sql->fetchAll()))
                    return FALSE;
                else
                    return TRUE;
            } catch (PDOException $e) {
                return TRUE;
            }
        }

        // Attempts to insert row into questions; returns TRUE for success, FALSE otherwise.
        public static function attemptQAInsertion(string $question, string $answer, int $selectedCourse): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO questions VALUES (NULL, $selectedCourse, '$question', '$answer')";
                $sql = $conn->prepare($query);
                $sql->execute();

                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }




        // View_Courses database methods

        // Attempts to delete row in coursesusersroster; returns TRUE for success, FALSE for failure.
        public static function attemptCourseWithdraw($courseNumber, $studentID)
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "DELETE FROM coursesusersroster WHERE crseID=$courseNumber AND userID=$studentID";
                $sql = $conn->prepare($query);
                $sql->execute();
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }



        // ==================================================================================================== //
        // ==================================================================================================== //
        // Create_Course database methods

        // Checks database for course number / section combo; returns TRUE if it exists, FALSE otherwise.
        public static function duplicateCrseNumSectCheck(string $courseNumber, string $courseSection,
            string $universityID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT * FROM courses WHERE uniID=$universityID AND crseID='$courseNumber' " .
                    "AND sectNum=$courseSection";
                $sql = $conn->prepare($query);
                $sql->execute();

                if (empty($sql->fetchAll()))
                    return FALSE;
                else
                    return TRUE;
            } catch (PDOException $e) {
                return TRUE;
            }
        }

        // Attempts to insert row into courses; returns course ID for success, empty return otherwise.
        public static function attemptCourseInsertion(string $courseNumber, string $courseSection, string $courseName,
            string $universityID): int
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO courses VALUES ".
                    "(NULL, $universityID, '$courseNumber', '$courseSection', '$courseName')";
                $conn->exec($query);
                return $conn->lastInsertID();
            } catch (PDOException $e) {
                return NULL;
            }
        }




        // Create_Syllabus database methods

        // Checks database for a syllabus with that course ID; returns TRUE if it exists, FALSE otherwise.
        public static function checkSyllabusExists ($courseID): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT ID FROM syllabi WHERE crseID=$courseID";
                $sql = $conn->prepare($query);
                $sql->execute();

                if (empty($sql->fetchAll()))
                    return FALSE;
                return TRUE;
            } catch (PDOException $e) {
                return TRUE;
            }
        }

        // Attempts to insert row into syllabi; returns TRUE for success, FALSE otherwise
        public static function attemptSyllabusInsertion(int $courseID, string $courseTitle, string $contactInfo,
            string $officeHours, string $courseDesc, string $courseGoals, string $reqMaterials, string $grading,
            string $attendance, string $uniPolicies, string $stuResources): bool
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "INSERT INTO syllabi VALUES (NULL, $courseID, '$courseTitle', '$contactInfo', '$officeHours',"
                    . " '$courseDesc', '$courseGoals', '$reqMaterials', '$grading', '$attendance',"
                    . " '$uniPolicies', '$stuResources')";
                $sql = $conn->prepare($query);
                $sql->execute();
                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }




        // View_Data database methods

        // Attempts to pull a courses' questions; returns array of questions if they exist, empty array otherwise.
        public static function attemptQuestionsPull($courseID): array
        {
            try {
                $conn = DatabaseMethods::setConnVariable();
                $query = "SELECT q.ID, q.qtext, q.atext FROM questions q WHERE q.crseID=$courseID";
                $sql = $conn->prepare($query);
                $sql->execute();
                return $sql->fetchAll();
            } catch (PDOException $e) {
                return array();
            }
        }
    }
?>